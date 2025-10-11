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
    if($period) {
        $data['orders'] = $this->OrderModel->get_done_orders($period);
    } else {
        $data['orders'] = $this->OrderModel->get_all_orders();
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
                redirect('OrderController');
            }
        }

        $data = [
            'staff_id'      => $this->session->userdata('user_id'),
            'customer_name' => $this->input->post('customer_name', TRUE),
            'order_items'   => $this->input->post('order_items', TRUE),
            'total_amount'  => $this->input->post('total_amount', TRUE),
            'status'        => 'New',
            'image'         => $image
        ];

        if ($this->OrderModel->insert_order($data)) {
            $this->session->set_flashdata('success', 'Order added successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to add order.');
        }

        redirect('OrderController');
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
                redirect('OrderController');
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

        redirect('OrderController');
    }

    public function update_status($order_id, $status) {
        if ($this->OrderModel->update_order($order_id, ['status' => $status])) {
            $this->session->set_flashdata('success', 'Order status updated to '.$status.'.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update status.');
        }
        redirect('OrderController');
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
        redirect('OrderController');
    }

    public function mark_done($order_id) {
        $data = ['status' => 'Done', 'updated_at' => date('Y-m-d H:i:s')];
        if($this->OrderModel->update_order($order_id, $data)) {
            $this->session->set_flashdata('success', 'Order marked as Done!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update order.');
        }

        redirect('OrderController');
    }
}
