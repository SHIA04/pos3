<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller
{
     public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form', 'security']);
        $this->load->library(['session', 'form_validation']);
        $this->load->model('SettingsModel');

        // ✅ Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        // Optional: ensure user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return;
        }

        // Prepare metrics for dashboard
        $this->load->model('OrderModel');
        $this->load->database();
        $data = [];

        // Total Revenue (all time) - only 'Done' orders
        $this->db->select_sum('total_amount');
        $this->db->where('status', 'Done');
        $totalRow = $this->db->get('tbl_orders')->row();
        $data['total_revenue'] = $totalRow && $totalRow->total_amount ? (float)$totalRow->total_amount : 0.0;

        // Daily sales (Done today)
        $dailySales = $this->OrderModel->get_sales_total('daily');
        $data['todays_sales'] = (float)$dailySales;

        // Today's Profit: approximate using a margin (30%) if cost not available
        $margin = 0.30; // default profit margin assumption
        $data['todays_profit'] = $data['todays_sales'] * $margin;

        // Orders Today
        $this->db->from('tbl_orders');
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        $data['orders_today'] = (int)$this->db->count_all_results();

        // Low stock: 1-5
        $this->db->from('tbl_menu_items');
        $this->db->where('stock_quantity >', 0);
        $this->db->where('stock_quantity <=', 5);
        $data['low_stock'] = (int)$this->db->count_all_results();

        // Sales Trend - last 7 days (Done orders)
        $this->db->select('DATE(created_at) as dt, SUM(total_amount) as total');
        $this->db->from('tbl_orders');
        $this->db->where('status', 'Done');
        $this->db->where('created_at >=', date('Y-m-d', strtotime('-6 days')));
        $this->db->group_by('DATE(created_at)');
        $this->db->order_by('DATE(created_at)', 'ASC');
        $rows = $this->db->get()->result_array();
        // build 7-day series
        $labels = [];$series = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime('-'.$i.' days'));
            $labels[] = date('D', strtotime($d));
            $found = 0.0;
            foreach ($rows as $r) { if ($r['dt'] === $d) { $found = (float)$r['total']; break; } }
            $series[] = $found;
        }
        $data['sales_trend'] = ['labels' => $labels, 'data' => $series];

    // Top selling items (by quantity) - top 5
    // Prefer menu_tbl.item_name when menu_id is set; fallback to order_details.item_name
    $this->db->select('od.menu_id, COALESCE(mt.item_name, od.item_name) as item_name, SUM(od.quantity) as qty', FALSE);
    $this->db->from('tbl_order_details od');
    $this->db->join('tbl_orders o', 'o.order_id = od.order_id', 'inner');
    $this->db->join('menu_tbl mt', 'mt.menu_id = od.menu_id', 'left');
    $this->db->where('o.status', 'Done');
    $this->db->group_by('od.menu_id, item_name');
    $this->db->order_by('qty', 'DESC');
    $this->db->limit(5);
    $rows = $this->db->get()->result_array();
    $topLabels = []; $topData = []; $topIds = [];
    foreach ($rows as $r) { $topLabels[] = $r['item_name']; $topData[] = (int)$r['qty']; $topIds[] = isset($r['menu_id']) ? (int)$r['menu_id'] : null; }
    $data['top_items'] = ['labels' => $topLabels, 'data' => $topData, 'ids' => $topIds];

        // Load view
        if (file_exists(APPPATH.'views/owner/dashboard.php')) {
            $this->load->view('owner/dashboard', $data);
        } else {
            $this->load->view('dashboard/index', $data);
        }
    }

    public function menu()
    {
        // Optional: ensure user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return;
        }

        // Load menu items from model and pass to view
        $this->load->model('MenuModel');
        $data = [];
        $data['menu_items'] = $this->MenuModel->get_all();

        if (file_exists(APPPATH.'views/owner/menu/menu.php')) {
            $this->load->view('owner/menu/menu', $data);
        } else {
            show_404();
        }
    }

    /**
     * AJAX endpoint to add a menu item. Expects POST: name, price, category, description
     * and optional file upload 'image'. Returns JSON with success and item data.
     */
    public function add_menu_item()
    {
        // Only allow POST
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_404();
            return;
        }

        $this->load->database();

        $name = $this->input->post('name');
        $price = $this->input->post('price');
        $category = $this->input->post('category');
        $description = $this->input->post('description');
        // Validate category (required)
        $allowed_categories = ['Waffles','Cakes','Beverages'];
        if (empty($category) || !in_array($category, $allowed_categories)) {
            $csrf_name = $this->security->get_csrf_token_name();
            $csrf_hash = $this->security->get_csrf_hash();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Category is required and must be Waffles, Cakes, or Beverages.', 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
            return;
        }
        // Basic validation
        if (empty($name) || empty($price)) {
            echo json_encode(['success' => false, 'message' => 'Name and price are required.']);
            return;
        }

        // Handle image upload if present
        $imageFileName = null;
        if (!empty($_FILES) && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $config['upload_path'] = FCPATH . 'uploads/menu_images/';
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, true);
            }
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size'] = 2048; // 2MB
            $config['encrypt_name'] = true;

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('image')) {
                $data = $this->upload->data();
                $imageFileName = 'uploads/menu_images/' . $data['file_name'];
            }
        }

        $insert = [
            'item_name' => $name,
            'description' => $description,
            'category' => !empty($category) ? $category : null,
            'price' => $price,
            'status' => 'Available',
            'image' => $imageFileName,
            'date_created' => date('Y-m-d H:i:s')
        ];

        // Use a transaction: insert into menu_tbl and also create an inventory record in tbl_menu_items
        $this->db->trans_start();
        $this->db->insert('menu_tbl', $insert);
        $insert_id = $this->db->insert_id();

        $inventory_ok = false;
        if ($insert_id) {
            // Prepare inventory row and map to newly created menu_id
            $inv = [
                'menu_id' => $insert_id,
                'item_name' => $name,
                'description' => $description,
                'price' => $price,
                'stock_quantity' => 0,
                'is_active' => ($insert['status'] === 'Available') ? 1 : 0
            ];
            $this->db->insert('tbl_menu_items', $inv);
            $inventory_ok = ($this->db->affected_rows() > 0);
        }

        $this->db->trans_complete();

        // Prepare CSRF tokens for response
        $csrf_name = $this->security->get_csrf_token_name();
        $csrf_hash = $this->security->get_csrf_hash();

        if ($this->db->trans_status() && $insert_id) {
            $insert['menu_id'] = $insert_id;
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'item' => $insert, 'inventory_created' => $inventory_ok, 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to insert item.', 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
    }

    /**
     * Return a single menu item by id (GET param ?id=)
     */
    public function get_menu_item()
    {
        $id = $this->input->get('id');
        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing id']);
            return;
        }

        $this->load->database();
        $row = $this->db->get_where('menu_tbl', ['menu_id' => $id])->row_array();
        $csrf_name = $this->security->get_csrf_token_name();
        $csrf_hash = $this->security->get_csrf_hash();

        header('Content-Type: application/json');
        if ($row) echo json_encode(['success' => true, 'item' => $row, 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
        else echo json_encode(['success' => false, 'message' => 'Not found', 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
    }

    /**
     * Update menu item (POST). Allows updating name, price, category, description, and image.
     */
    public function update_menu_item()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_404();
            return;
        }

        $id = $this->input->post('menu_id');
        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing menu_id']);
            return;
        }

        $name = $this->input->post('name');
        $price = $this->input->post('price');
        $category = $this->input->post('category');
        $description = $this->input->post('description');

        // Validate category (required)
        $allowed_categories = ['Waffles','Cakes','Beverages'];
        if (empty($category) || !in_array($category, $allowed_categories)) {
            $csrf_name = $this->security->get_csrf_token_name();
            $csrf_hash = $this->security->get_csrf_hash();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Category is required and must be Waffles, Cakes, or Beverages.', 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
            return;
        }

        $this->load->database();

        $update = [
            'item_name' => $name,
            'price' => $price,
            'category' => $category,
            'description' => $description
        ];

        // Handle image replacement
        if (!empty($_FILES) && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $config['upload_path'] = FCPATH . 'uploads/menu_images/';
            if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0755, true);
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('image')) {
                $d = $this->upload->data();
                $update['image'] = 'uploads/menu_images/' . $d['file_name'];
            }
        }

        $this->db->where('menu_id', $id)->update('menu_tbl', $update);
        // Also sync fields to inventory rows that reference this menu_id, if any
        $inv_update = [
            'item_name' => $update['item_name'],
            'price' => $update['price'],
            'description' => $update['description']
        ];
        if (!empty($update['image'])) {
            // tbl_menu_items historically doesn't have an `image` column (see pos3.sql).
            // Only include image in the inventory sync if the field actually exists to avoid SQL errors.
            if ($this->db->field_exists('image', 'tbl_menu_items')) {
                $inv_update['image'] = $update['image'];
            } else {
                // image belongs to `menu_tbl` (master). Do not attempt to write it into tbl_menu_items.
                // Optionally you can log/debug here in production environments.
            }
        }
        $this->db->where('menu_id', $id)->update('tbl_menu_items', $inv_update);
        $csrf_name = $this->security->get_csrf_token_name();
        $csrf_hash = $this->security->get_csrf_hash();

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'item' => array_merge(['menu_id' => $id], $update), 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
    }

    /**
     * Delete a menu item (POST) given menu_id
     */
    public function delete_menu_item()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_404();
            return;
        }

        $id = $this->input->post('menu_id');
        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing menu_id']);
            return;
        }

        $this->load->database();
        // Optionally remove image file
        $row = $this->db->get_where('menu_tbl', ['menu_id' => $id])->row_array();
        if ($row && !empty($row['image']) && file_exists(FCPATH . $row['image'])) {
            @unlink(FCPATH . $row['image']);
        }

        $this->db->where('menu_id', $id)->delete('menu_tbl');
        $csrf_name = $this->security->get_csrf_token_name();
        $csrf_hash = $this->security->get_csrf_hash();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
    }

    public function orders()
    {
        // Optional: ensure user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return;
        }

        // Load menu items and orders from database
        $this->load->model('MenuModel');
        $this->load->model('OrderModel');
        $data = [];
        $data['menu_items'] = $this->MenuModel->get_all();
        $data['orders'] = $this->OrderModel->get_all_orders();

        // Load your orders view.
        if (file_exists(APPPATH.'views/owner/orders/order_dash.php')) {
            $this->load->view('owner/orders/order_dash', $data);
        } else {
            show_404();
        }
    }

    public function inventory()
    {
        // Optional: ensure user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return;
        }

        // Load inventory items from the database and pass to view
    $this->load->database();
    // Left join menu_tbl to fetch category (if available) using menu_id
    $this->db->select('tmi.*, mt.category');
    $this->db->from('tbl_menu_items tmi');
    $this->db->join('menu_tbl mt', 'mt.menu_id = tmi.menu_id', 'left');
    $query = $this->db->get();
    $data = [];
    $data['inventory_items'] = $query->result_array();

    // Compute counts: In Stock (>5), Low Stock (1-5), Out of Stock (0)
    $this->db->select('COUNT(*) as cnt_in');
    $this->db->from('tbl_menu_items');
    $this->db->where('stock_quantity >', 5);
    $cntIn = (int)$this->db->get()->row()->cnt_in;

    $this->db->select('COUNT(*) as cnt_low');
    $this->db->from('tbl_menu_items');
    $this->db->where('stock_quantity >', 0);
    $this->db->where('stock_quantity <=', 5);
    $cntLow = (int)$this->db->get()->row()->cnt_low;

    $this->db->select('COUNT(*) as cnt_out');
    $this->db->from('tbl_menu_items');
    $this->db->where('stock_quantity', 0);
    $cntOut = (int)$this->db->get()->row()->cnt_out;

    $data['in_stock_count'] = $cntIn;
    $data['low_stock_count'] = $cntLow;
    $data['out_of_stock_count'] = $cntOut;

        // Load your inventory view.
        if (file_exists(APPPATH.'views/owner/inventory/inventory.php')) {
            $this->load->view('owner/inventory/inventory', $data);
        } else {
            show_404();
        }
    }

    public function sales()
    {
        // Optional: ensure user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return;
        }

        // Prepare sales & chart data from database
        $this->load->model('OrderModel');
        $data = [];
        // Totals
        $data['daily_sales'] = (float)$this->OrderModel->get_sales_total('daily');
        $data['weekly_sales'] = (float)$this->OrderModel->get_sales_total('weekly');
        $data['monthly_sales'] = (float)$this->OrderModel->get_sales_total('monthly');
        $data['total_sales'] = (float)$this->OrderModel->get_sales_total(null);

        // Charts
        // Daily: hourly totals for today (0-23)
        $this->db->select('HOUR(created_at) as hour, SUM(total_amount) as total');
        $this->db->from('tbl_orders');
        $this->db->where('status', 'Done');
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        $this->db->group_by('HOUR(created_at)');
        $this->db->order_by('hour', 'ASC');
        $rows = $this->db->get()->result_array();
        $hoursMap = array_fill(0,24,0.0);
        foreach ($rows as $r) { $h = (int)$r['hour']; $hoursMap[$h] = (float)$r['total']; }
        $data['daily_chart'] = ['labels' => array_map(function($h){ return date('gA', strtotime($h.':00')); }, array_keys($hoursMap)), 'data' => array_values($hoursMap)];

        // Weekly: totals per weekday (Mon-Sun)
        $this->db->select('DAYOFWEEK(created_at) as dow, SUM(total_amount) as total');
        $this->db->from('tbl_orders');
        $this->db->where('status', 'Done');
        $this->db->where('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)', null, false);
        $this->db->group_by('DAYOFWEEK(created_at)');
        $this->db->order_by('dow', 'ASC');
        $rows = $this->db->get()->result_array();
        // DAYOFWEEK returns 1=Sunday..7=Saturday; map to Mon..Sun order
        $weekdayOrder = [2,3,4,5,6,7,1];
        $wkMap = array_fill_keys($weekdayOrder, 0.0);
        foreach ($rows as $r) { $dow = (int)$r['dow']; $wkMap[$dow] = (float)$r['total']; }
        $labels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $data['weekly_chart'] = ['labels' => $labels, 'data' => array_values($wkMap)];

        // Monthly: totals per week number within month
        $this->db->select('WEEK(created_at,1) as wk, SUM(total_amount) as total');
        $this->db->from('tbl_orders');
        $this->db->where('status', 'Done');
        $this->db->where('MONTH(created_at)', date('m'));
        $this->db->where('YEAR(created_at)', date('Y'));
        $this->db->group_by('WEEK(created_at,1)');
        $this->db->order_by('wk', 'ASC');
        $rows = $this->db->get()->result_array();
        $monthWeeks = [];
        foreach ($rows as $r) {
            $monthWeeks[(int)$r['wk']] = (float)$r['total'];
        }
        // Build labels like Week 1..n based on present week keys
        $weekKeys = array_keys($monthWeeks);
        sort($weekKeys);
        $labels = [];
        $dataSeries = [];
        foreach ($weekKeys as $idx => $wk) { $labels[] = 'Wk '.$wk; $dataSeries[] = $monthWeeks[$wk]; }
        $data['monthly_chart'] = ['labels' => $labels, 'data' => $dataSeries];

        // Sales by category: sum of order_details(price*quantity) joined to menu_tbl.category for Done orders
        $this->db->select('COALESCE(mt.category, "Uncategorized") as category, SUM(od.price * od.quantity) as total');
        $this->db->from('tbl_order_details od');
        $this->db->join('tbl_orders o', 'o.order_id = od.order_id', 'inner');
        $this->db->join('menu_tbl mt', 'mt.menu_id = od.menu_id', 'left');
        $this->db->where('o.status', 'Done');
        $this->db->group_by('category');
        $this->db->order_by('total', 'DESC');
        $rows = $this->db->get()->result_array();
        $catLabels = []; $catData = [];
        foreach ($rows as $r) { $catLabels[] = $r['category']; $catData[] = (float)$r['total']; }
        $data['category_sales'] = ['labels' => $catLabels, 'data' => $catData];

        // Load view with computed data
        if (file_exists(APPPATH.'views/owner/sales/sales.php')) {
            $this->load->view('owner/sales/sales', $data);
        } else {
            show_404();
        }
    }

    public function settings()
    {
        // Optional: ensure user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return;
        }

        // Load your settings view.
        if (file_exists(APPPATH.'views/owner/settings/settings.php')) {
            $this->load->view('owner/settings/settings');
        } else {
            show_404();
        }
    }

    /**
     * Handle restocking an inventory item (POST)
     */
    public function restock()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_404();
            return;
        }

        $this->load->database();
        $item_id = $this->input->post('item_id');
        $qty = (int)$this->input->post('quantity_to_add');

        if (empty($item_id) || $qty <= 0) {
            $this->session->set_flashdata('error', 'Invalid item or quantity.');
            redirect('owner/inventory');
            return;
        }

        // Start transaction: update stock and insert inventory detail record
        $this->db->trans_start();

        // Increment stock_quantity safely
        $this->db->set('stock_quantity', 'stock_quantity + ' . $this->db->escape_str($qty), false);
        $this->db->where('item_id', $item_id);
        $this->db->update('tbl_menu_items');

        // Try to record detail in tbl_inventory_details. Prefer richer schema (quantity/action),
        // fall back to minimal insert (item_id only) if the richer fields/columns are not present.
        $detail = [
            'item_id' => $item_id,
            'quantity' => $qty,
            'action' => 'in',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $ins_ok = false;
        $this->db->insert('tbl_inventory_details', $detail);
        $err = $this->db->error();
        if (empty($err) || (isset($err['code']) && $err['code'] == 0)) {
            $ins_ok = true;
        } else {
            // attempt minimal insert (item_id only) if richer insert failed (e.g., older minimal schema)
            $this->db->reset_query();
            $this->db->set('item_id', $item_id);
            $this->db->set('created_at', date('Y-m-d H:i:s'));
            $this->db->insert('tbl_inventory_details');
            $err2 = $this->db->error();
            if (empty($err2) || (isset($err2['code']) && $err2['code'] == 0)) $ins_ok = true;
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_flashdata('success', 'Stock updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update stock (item may not exist).');
        }

        redirect('owner/inventory');
    }

    /**
     * Return a single inventory item as JSON (GET ?id=)
     */
    public function get_inventory_item()
    {
        $id = $this->input->get('id');
        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing id']);
            return;
        }
        $this->load->database();
        // left join to include menu_tbl.category and menu_id
        $this->db->select('tmi.*, mt.category, mt.menu_id');
        $this->db->from('tbl_menu_items tmi');
        $this->db->join('menu_tbl mt', 'mt.menu_id = tmi.menu_id', 'left');
        $this->db->where('tmi.item_id', $id);
        $row = $this->db->get()->row_array();
        $csrf_name = $this->security->get_csrf_token_name();
        $csrf_hash = $this->security->get_csrf_hash();
        header('Content-Type: application/json');
        if ($row) echo json_encode(['success' => true, 'item' => $row, 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
        else echo json_encode(['success' => false, 'message' => 'Not found', 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
    }

    /**
     * Return inventory detail records for an item (tbl_inventory_details)
     * GET param: ?item_id=
     */
    public function inventory_details()
    {
        $item_id = $this->input->get('item_id');
        if (empty($item_id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing item_id']);
            return;
        }

        $this->load->database();
        // Fetch details from tbl_inventory_details; expects columns: inventory_details_id, item_id, created_at
        $this->db->select('inventory_details_id, item_id, action, quantity, created_at');
        $this->db->from('tbl_inventory_details');
        $this->db->where('item_id', $item_id);
        $this->db->order_by('created_at', 'DESC');
        $rows = $this->db->get()->result_array();

        $csrf_name = $this->security->get_csrf_token_name();
        $csrf_hash = $this->security->get_csrf_hash();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'details' => $rows, 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
    }

    /**
     * Update an inventory item (POST)
     */
    public function update_inventory_item()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_404();
            return;
        }

        $id = $this->input->post('item_id');
        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing item_id']);
            return;
        }

    $name = $this->input->post('item_name');
    $price = $this->input->post('price');
    $desc = $this->input->post('description');
    $category = $this->input->post('category');

        $update = [
            'item_name' => $name,
            'price' => $price,
            'description' => $desc
        ];

        $this->load->database();
        // find existing row to know if there's a menu_id mapping
        $existing = $this->db->get_where('tbl_menu_items', ['item_id' => $id])->row_array();
        $menu_id = isset($existing['menu_id']) ? $existing['menu_id'] : null;

        $this->db->where('item_id', $id)->update('tbl_menu_items', $update);

        // If item maps to a menu entry, update that menu's category when provided
        if (!empty($menu_id) && !empty($category)) {
            $this->db->where('menu_id', $menu_id)->update('menu_tbl', ['category' => $category]);
        }

        // Additionally, if this inventory row links to a menu_tbl entry, sync core fields back
        if (!empty($menu_id)) {
            $menu_update = [
                'item_name' => $name,
                'price' => $price,
                'description' => $desc
            ];
            if (!empty($category)) {
                $menu_update['category'] = $category;
            }
            // Update menu_tbl to reflect edits made in inventory
            $this->db->where('menu_id', $menu_id)->update('menu_tbl', $menu_update);
        }

        // If no menu_id mapping exists, try to find a matching menu_tbl row by name+category
        if (empty($menu_id)) {
            if (!empty($name) && !empty($category)) {
                $match = $this->db->get_where('menu_tbl', ['item_name' => $name, 'category' => $category])->row_array();
                if ($match && isset($match['menu_id'])) {
                    $menu_id = $match['menu_id'];
                    // Save the mapping in tbl_menu_items
                    $this->db->where('item_id', $id)->update('tbl_menu_items', ['menu_id' => $menu_id]);
                }
            }
        } else {
            // If menu_id exists but item name or category changed to point to another menu row, consider remapping
            if (!empty($name) && !empty($category)) {
                $match = $this->db->get_where('menu_tbl', ['item_name' => $name, 'category' => $category])->row_array();
                if ($match && isset($match['menu_id']) && $match['menu_id'] != $menu_id) {
                    $menu_id = $match['menu_id'];
                    $this->db->where('item_id', $id)->update('tbl_menu_items', ['menu_id' => $menu_id]);
                }
            }
        }

        // Prepare category value for response
        $resp_category = null;
        if (!empty($menu_id)) {
            $m = $this->db->get_where('menu_tbl', ['menu_id' => $menu_id])->row_array();
            $resp_category = $m['category'] ?? $category;
        } else {
            // If no menu mapping, and category was provided, include it anyway
            $resp_category = $category;
        }

        $csrf_name = $this->security->get_csrf_token_name();
        $csrf_hash = $this->security->get_csrf_hash();
        header('Content-Type: application/json');
        if ($this->db->affected_rows() >= 0) echo json_encode(['success' => true, 'item' => array_merge(['item_id' => $id, 'category' => $resp_category], $update), 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
        else echo json_encode(['success' => false, 'message' => 'Update failed', 'csrf_token_name' => $csrf_name, 'csrf_hash' => $csrf_hash]);
    }
}