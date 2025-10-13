<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('OrderModel');
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->helper(['url', 'form']);

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // Prevent browser caching for back button
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        $this->output->set_header('Pragma: no-cache');
    }

  public function index($period = null) {
    // Support filtering by period: daily, weekly, monthly
    $data = [];
    if ($period === 'daily' || $period === 'weekly' || $period === 'monthly') {
        // show all orders in that period (not only Done), so owner sees incoming/completed orders within the range
        $data['orders'] = $this->OrderModel->get_orders_by_period($period);
        // sales total and chart are still computed for Done orders in that period
        $data['sales_total'] = $this->OrderModel->get_sales_total($period);
        $data['chart_data'] = $this->OrderModel->get_chart_data($period);
        $data['current_period'] = $period;
    } else {
        $data['orders'] = $this->OrderModel->get_all_orders();
        $data['sales_total'] = $this->OrderModel->get_sales_total(null);
        $data['chart_data'] = $this->OrderModel->get_chart_data(null);
        $data['current_period'] = null;
    }
    // Also load menu items so the Add Order modal can present menu choices
    $this->load->model('MenuModel');
    $data['menu_items'] = $this->MenuModel->get_all();
    
        // Attach a readable staff name for each order for display in the views
        $this->load->database();
        if (!empty($data['orders'])) {
            foreach ($data['orders'] as $o) {
                // Lookup the creator in tbl_signup (signup_id)
                $user = $this->db->get_where('tbl_signup', ['signup_id' => $o->staff_id])->row();
                if ($user && isset($user->role) && strtolower($user->role) === 'owner') {
                    $o->staff_name = 'Owner';
                    $o->staff_role = 'owner';
                } else {
                    if ($user && !empty($user->fullname)) $o->staff_name = $user->fullname;
                    else $o->staff_name = 'Staff #' . (int)$o->staff_id;
                    $o->staff_role = ($user && isset($user->role)) ? strtolower($user->role) : 'staff';
                }
            }
        }
    
        $this->load->view('orders/order_dash', $data);
}



    public function add_order() {
        $config['upload_path']   = './uploads/order_images/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = 2048;
        $this->upload->initialize($config);

        $image = null;
        if (!empty($_FILES['image']['name'])) {
            if ($this->upload->do_upload('image')) {
                $image = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                  return;
            }
        }

        $order_items_raw = $this->input->post('order_items');
        if (is_array($order_items_raw)) {
            $order_items = implode(', ', array_map('trim', $order_items_raw));
        } else {
            $order_items = $this->input->post('order_items', TRUE);
        }

        $scheduled_at = $this->input->post('scheduled_at', TRUE);

        $data = [
            'staff_id'      => $this->session->userdata('user_id'),
            'customer_name' => $this->input->post('customer_name', TRUE),
            'order_items'   => $order_items,
            'total_amount'  => $this->input->post('total_amount', TRUE),
            'status'        => 'New',
            'image'         => $image,
            'order_date'    => !empty($scheduled_at) ? date('Y-m-d H:i:s', strtotime($scheduled_at)) : date('Y-m-d H:i:s')
        ];

        if ($this->OrderModel->insert_order($data)) {
            $order_id = $this->db->insert_id();

            // Build order details from posted arrays (if provided)
            $menu_ids = $this->input->post('items_menu_id');
            $names    = $this->input->post('items_name');
            $prices   = $this->input->post('items_price');
            $qtys     = $this->input->post('items_qty');

            $detail_rows = [];
            if (is_array($names) && is_array($prices) && is_array($qtys)) {
                $count = min(count($names), count($prices), count($qtys));
                for ($i = 0; $i < $count; $i++) {
                    $menu_id  = is_array($menu_ids) && isset($menu_ids[$i]) && $menu_ids[$i] !== '' ? (int)$menu_ids[$i] : null;
                    $itemName = trim($names[$i]);
                    $price    = (float)$prices[$i];
                    $qty      = (int)$qtys[$i];
                    if ($qty <= 0 || $price < 0 || $itemName === '') continue;
                    $detail_rows[] = [
                        'order_id'  => $order_id,
                        'menu_id'   => $menu_id,
                        'item_name' => $itemName,
                        'price'     => $price,
                        'quantity'  => $qty,
                        'subtotal'  => $price * $qty,
                        'created_at'=> date('Y-m-d H:i:s')
                    ];
                }
            }

            if (!empty($detail_rows)) {
                $this->OrderModel->insert_order_details_batch($detail_rows);
            }

            // --- Decrement inventory stock and record 'out' details for mapped items ---
            $this->load->database();
            foreach ($detail_rows as $dr) {
                // only process when menu_id exists and quantity > 0
                if (empty($dr['menu_id']) || empty($dr['quantity'])) continue;
                // find inventory row that maps to this menu_id
                $inv = $this->db->get_where('tbl_menu_items', ['menu_id' => $dr['menu_id']])->row_array();
                if (!$inv) continue;
                $item_id = $inv['item_id'];
                $qty = (int)$dr['quantity'];

                // Use transaction per item: compute actual removed qty, update stock, and insert a detail row
                $this->db->trans_start();

                // Re-query current stock to avoid race with previous updates
                $row = $this->db->select('stock_quantity')->get_where('tbl_menu_items', ['item_id' => $item_id])->row_array();
                $current_stock = isset($row['stock_quantity']) ? (int)$row['stock_quantity'] : 0;

                // Determine how many we can actually remove
                $to_remove = min($current_stock, $qty);

                // Update stock to subtract the actual removed amount
                $new_stock = $current_stock - $to_remove;
                $this->db->where('item_id', $item_id);
                $this->db->update('tbl_menu_items', ['stock_quantity' => $new_stock]);

                // Record inventory detail (action 'out') with the actual removed qty
                $detail = [
                    'item_id' => $item_id,
                    'quantity' => $to_remove,
                    'action' => 'out',
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->db->insert('tbl_inventory_details', $detail);

                // If insert fails (older minimal schema), fallback to minimal insert
                $err = $this->db->error();
                if (!empty($err) && isset($err['code']) && $err['code'] != 0) {
                    $this->db->reset_query();
                    $this->db->set('item_id', $item_id);
                    $this->db->set('created_at', date('Y-m-d H:i:s'));
                    $this->db->insert('tbl_inventory_details');
                }

                $this->db->trans_complete();
            }

            $this->session->set_flashdata('success', 'Order added successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to add order.');
        }

        // Redirect back to the originating page (owner/staff orders) if available
        $ref = $this->input->server('HTTP_REFERER');
        if (!empty($ref)) {
            redirect($ref);
        } else {
            redirect('dashboard');
        }
    }

    public function edit($order_id) {
        $data['order'] = $this->OrderModel->get_order($order_id);
        $this->load->view('orders/edit_order', $data); // Make sure your view is at views/orders/edit_order.php
    }

    public function update_order() {
        $order_id = $this->input->post('order_id');
        $order = $this->OrderModel->get_order($order_id);

        $config['upload_path']   = './uploads/order_images/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = 2048;
        $this->upload->initialize($config);

        $image = $order->image;
        if (!empty($_FILES['image']['name'])) {
            if ($this->upload->do_upload('image')) {
                // Delete old image
                if ($order->image && file_exists('./uploads/order_images/' . $order->image)) {
                    unlink('./uploads/order_images/' . $order->image);
                }
                $image = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                // Redirect back to the referring page (owner/staff) or dashboard as fallback
                $ref = $this->input->server('HTTP_REFERER');
                if (!empty($ref)) redirect($ref);
                else redirect('dashboard');
            }
        }

        $data = [
            'customer_name' => $this->input->post('customer_name', TRUE),
            'order_items'   => $this->input->post('order_items', TRUE),
            'total_amount'  => $this->input->post('total_amount', TRUE),
            'status'        => $this->input->post('status', TRUE),
            'image'         => $image
        ];

        if ($this->OrderModel->update_order($order_id, $data)) {
            $this->session->set_flashdata('success', 'Order updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update order.');
        }

        // After update, redirect back to the referrer (owner or staff orders page) if available
        $ref = $this->input->server('HTTP_REFERER');
        if (!empty($ref)) {
            redirect($ref);
        } else {
            redirect('dashboard');
        }
    }

    /**
     * AJAX: return a single order as JSON for editing in a modal
     * GET param: id
     */
    public function get_order_ajax()
    {
        $id = $this->input->get('id');
        header('Content-Type: application/json');
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Missing id', 'csrf_token_name' => $this->security->get_csrf_token_name(), 'csrf_hash' => $this->security->get_csrf_hash()]);
            return;
        }

        $order = $this->OrderModel->get_order($id);
        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Order not found', 'csrf_token_name' => $this->security->get_csrf_token_name(), 'csrf_hash' => $this->security->get_csrf_hash()]);
            return;
        }

        echo json_encode(['success' => true, 'order' => $order, 'csrf_token_name' => $this->security->get_csrf_token_name(), 'csrf_hash' => $this->security->get_csrf_hash()]);
    }

    /**
     * AJAX: update an order. Accepts POST (FormData) and optional file upload 'image'.
     * Returns JSON with success and updated order data.
     */
    public function update_order_ajax()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid method']);
            return;
        }

        $order_id = $this->input->post('order_id');
        if (empty($order_id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing order_id']);
            return;
        }

        $order = $this->OrderModel->get_order($order_id);
        if (!$order) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Order not found']);
            return;
        }

        // Handle optional image upload
        $config['upload_path']   = './uploads/order_images/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = true;
        if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0755, true);
        $this->upload->initialize($config);

        $image = $order->image;
        if (!empty($_FILES) && isset($_FILES['image']) && $_FILES['image']['name'] !== '') {
            if ($this->upload->do_upload('image')) {
                // delete old file
                if ($order->image && file_exists('./uploads/order_images/' . $order->image)) {
                    @unlink('./uploads/order_images/' . $order->image);
                }
                $image = $this->upload->data('file_name');
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $this->upload->display_errors(), 'csrf_token_name' => $this->security->get_csrf_token_name(), 'csrf_hash' => $this->security->get_csrf_hash()]);
                return;
            }
        }

        $data = [
            'customer_name' => $this->input->post('customer_name', TRUE),
            'order_items'   => $this->input->post('order_items', TRUE),
            'total_amount'  => $this->input->post('total_amount', TRUE),
            'status'        => $this->input->post('status', TRUE),
            'image'         => $image,
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $ok = $this->OrderModel->update_order($order_id, $data);
        header('Content-Type: application/json');
        if ($ok) {
            $order_updated = $this->OrderModel->get_order($order_id);
            echo json_encode(['success' => true, 'order' => $order_updated, 'csrf_token_name' => $this->security->get_csrf_token_name(), 'csrf_hash' => $this->security->get_csrf_hash()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update order', 'csrf_token_name' => $this->security->get_csrf_token_name(), 'csrf_hash' => $this->security->get_csrf_hash()]);
        }
    }

    public function update_status($order_id, $status) {
        if ($this->OrderModel->update_order($order_id, ['status' => $status])) {
            $this->session->set_flashdata('success', 'Order status updated to '.$status.'.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update status.');
        }
        // Redirect back to where the request originated (owner/staff page)
        $ref = $this->input->server('HTTP_REFERER');
        if (!empty($ref)) redirect($ref);
        else redirect('dashboard');
    }

    public function delete($order_id) {
        $order = $this->OrderModel->get_order($order_id);
        if ($order->image && file_exists('./uploads/order_images/' . $order->image)) {
            unlink('./uploads/order_images/' . $order->image);
        }

        if ($this->OrderModel->delete_order($order_id)) {
            $this->session->set_flashdata('success', 'Order deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete order.');
        }
        // Redirect back to the originating page
        $ref = $this->input->server('HTTP_REFERER');
        if (!empty($ref)) redirect($ref);
        else redirect('dashboard');
    }

    /**
     * AJAX: update status of an order (expects POST: order_id, status)
     */
    public function update_status_ajax()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_404();
            return;
        }

        $order_id = $this->input->post('order_id');
        $status = $this->input->post('status');
        if (empty($order_id) || empty($status)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            return;
        }

        $data = ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')];
        $ok = $this->OrderModel->update_order($order_id, $data);
        header('Content-Type: application/json');
        echo json_encode(['success' => (bool)$ok]);
    }

    /**
     * AJAX: return count of new orders (status = 'New')
     */
    public function check_new_orders()
    {
        $count = $this->OrderModel->count_by_status('New');
        header('Content-Type: application/json');
        echo json_encode(['count' => (int)$count]);
    }

    public function mark_done($order_id) {
        $data = ['status' => 'Done', 'updated_at' => date('Y-m-d H:i:s')];
        if($this->OrderModel->update_order($order_id, $data)) {
            $this->session->set_flashdata('success', 'Order marked as Paid!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update order.');
        }
        // Redirect back to the originating page (owner/staff)
        $ref = $this->input->server('HTTP_REFERER');
        if (!empty($ref)) redirect($ref);
        else redirect('dashboard');
    }

    /**
     * AJAX: return orders for a period (daily, weekly, monthly) as JSON
     * GET param: period (daily|weekly|monthly) - if omitted returns all orders
     */
    public function orders_by_period_ajax()
    {
        header('Content-Type: application/json');
        $period = $this->input->get('period');

        if ($period && !in_array($period, ['daily','weekly','monthly'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid period', 'csrf_hash' => $this->security->get_csrf_hash()]);
            return;
        }

        if ($period) {
            $orders = $this->OrderModel->get_orders_by_period($period);
            $sales_total = $this->OrderModel->get_sales_total($period);
            $chart_data = $this->OrderModel->get_chart_data($period);
        } else {
            $orders = $this->OrderModel->get_all_orders();
            $sales_total = $this->OrderModel->get_sales_total(null);
            $chart_data = $this->OrderModel->get_chart_data(null);
        }

        // Convert objects to arrays for JSON friendliness and resolve staff_name from tbl_signup
        $orders_out = [];
        foreach ($orders as $o) {
            $user = $this->db->get_where('tbl_signup', ['signup_id' => $o->staff_id])->row();
            if ($user && isset($user->role) && strtolower($user->role) === 'owner') {
                $staff_name = 'Owner';
            } elseif ($user && isset($user->role) && strtolower($user->role) === 'staff') {
                $staff_name = 'Staff';
            } else {
                $staff_name = ($user && !empty($user->fullname)) ? $user->fullname : ('Staff #' . (int)$o->staff_id);
            }

            $orders_out[] = [
                'order_id' => $o->order_id,
                'staff_id' => $o->staff_id,
                'staff_name' => $staff_name,
                'staff_role' => ($user && isset($user->role)) ? strtolower($user->role) : 'staff',
                'customer_name' => $o->customer_name,
                'order_items' => $o->order_items,
                'total_amount' => $o->total_amount,
                'status' => $o->status,
                'order_date' => $o->order_date,
                'image' => $o->image
            ];
        }

        echo json_encode([
            'success' => true,
            'orders' => $orders_out,
            'sales_total' => $sales_total,
            'chart_data' => $chart_data,
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    }

    public function get_orders_by_period($period = null) {
        $this->db->select('*');
        $this->db->from('orders'); // Assuming your table name is 'orders'

        // Apply date filtering based on the period
        switch ($period) {
            case 'daily':
                // Select orders where the order_date is today
                $this->db->where('DATE(order_date)', 'CURDATE()', FALSE);
                break;
            case 'weekly':
                // Select orders where the week and year of order_date match the current week and year
                // The '1' in YEARWEEK sets Monday as the first day of the week
                $this->db->where('YEARWEEK(order_date, 1)', 'YEARWEEK(CURDATE(), 1)', FALSE);
                break;
            case 'monthly':
                // Select orders where the month and year of order_date match the current month and year
                $this->db->where('YEAR(order_date)', 'YEAR(CURDATE())', FALSE);
                $this->db->where('MONTH(order_date)', 'MONTH(CURDATE())', FALSE);
                break;
        }

        $this->db->order_by('order_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_sales_total($period = null) {
        $this->db->select_sum('total_amount');
        $this->db->where('status', 'Done');

        switch ($period) {
            case 'daily':
                $this->db->where('DATE(order_date)', 'CURDATE()', FALSE);
                break;
            case 'weekly':
                $this->db->where('YEARWEEK(order_date, 1)', 'YEARWEEK(CURDATE(), 1)', FALSE);
                break;
            case 'monthly':
                $this->db->where('YEAR(order_date)', 'YEAR(CURDATE())', FALSE);
                $this->db->where('MONTH(order_date)', 'MONTH(CURDATE())', FALSE);
                break;
        }

        $query = $this->db->get('orders');
        return $query->row()->total_amount;
    }
}
