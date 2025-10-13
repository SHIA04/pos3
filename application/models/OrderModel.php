<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderModel extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_orders() {
        return $this->db->order_by('created_at', 'DESC')->get('tbl_orders')->result();
    }

    /**
     * Get orders filtered by period (daily, weekly, monthly). Does NOT filter by status.
     */
    public function get_orders_by_period($period = null) {
        if ($period == 'daily') {
            $this->db->where('DATE(created_at)', date('Y-m-d'));
        } elseif ($period == 'weekly') {
            $this->db->where('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)');
        } elseif ($period == 'monthly') {
            $this->db->where('MONTH(created_at)', date('m'));
            $this->db->where('YEAR(created_at)', date('Y'));
        }

        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('tbl_orders');
        return $query->result();
    }

    public function insert_order($data) {
        return $this->db->insert('tbl_orders', $data);
    }

    public function insert_order_details_batch($rows) {
        if (empty($rows)) return false;
        return $this->db->insert_batch('tbl_order_details', $rows);
    }

    /**
     * Get order_details rows for a given order_id
     */
    public function get_order_details($order_id) {
        return $this->db->order_by('created_at', 'ASC')->get_where('tbl_order_details', ['order_id' => $order_id])->result_array();
    }

    /**
     * Delete order_details rows for a given order_id
     */
    public function delete_order_details($order_id) {
        return $this->db->delete('tbl_order_details', ['order_id' => $order_id]);
    }

    /**
     * Replace order details for an order: delete existing and insert new batch.
     * Useful when editing an order's items.
     */
    public function update_order_details_batch($order_id, $rows) {
        // Start transaction to ensure atomicity
        $this->db->trans_start();
        $this->delete_order_details($order_id);
        if (!empty($rows)) {
            $this->db->insert_batch('tbl_order_details', $rows);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_order($order_id) {
        return $this->db->get_where('tbl_orders', ['order_id' => $order_id])->row();
    }

    public function update_order($order_id, $data) {
        $this->db->where('order_id', $order_id);
        return $this->db->update('tbl_orders', $data);
    }

    public function delete_order($order_id) {
        // Remove order details first to avoid orphans
        $this->delete_order_details($order_id);
        return $this->db->delete('tbl_orders', ['order_id' => $order_id]);
    }

    // ✅ Done orders with optional period filter (using created_at)
    public function get_done_orders($period = null) {
        $this->db->where('status', 'Done');

        if ($period == 'daily') {
            $this->db->where('DATE(created_at)', date('Y-m-d'));
        } elseif ($period == 'weekly') {
            $this->db->where('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)');
        } elseif ($period == 'monthly') {
            $this->db->where('MONTH(created_at)', date('m'));
            $this->db->where('YEAR(created_at)', date('Y'));
        }

        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('tbl_orders');
        return $query->result();
    }

    public function get_sales_total($period) {
        $this->db->select_sum('total_amount');
        $this->db->where('status', 'Done');

        if ($period == 'daily') {
            $this->db->where('DATE(created_at)', date('Y-m-d'));
        } elseif ($period == 'weekly') {
            $this->db->where('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)');
        } elseif ($period == 'monthly') {
            $this->db->where('MONTH(created_at)', date('m'));
            $this->db->where('YEAR(created_at)', date('Y'));
        }

        $query = $this->db->get('tbl_orders');
        $result = $query->row();
        return $result->total_amount ? $result->total_amount : 0;
    }

    public function get_chart_data($period) {
        $this->db->select('DATE(created_at) as date, SUM(total_amount) as total');
        $this->db->where('status', 'Done');
        // apply period filters
        if ($period == 'daily') {
            $this->db->where('DATE(created_at)', date('Y-m-d'));
        } elseif ($period == 'weekly') {
            $this->db->where('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)');
        } elseif ($period == 'monthly') {
            $this->db->where('MONTH(created_at)', date('m'));
            $this->db->where('YEAR(created_at)', date('Y'));
        }

        // group by date to produce daily totals across the selected range
        $this->db->group_by('DATE(created_at)');
        $query = $this->db->get('tbl_orders');
        return $query->result_array();
    }

    public function count_by_status($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results('tbl_orders');
    }

    /**
     * Count number of completed (Done) orders for a given user for today.
     */
    public function count_user_completed_today($user_id)
    {
        $this->db->where('status', 'Done');
        $this->db->where('staff_id', $user_id);
        $this->db->where('DATE(updated_at)', date('Y-m-d'));
        return $this->db->count_all_results('tbl_orders');
    }

    /**
     * Get recent completed orders for a specific user.
     * Returns array of order objects.
     */
    public function get_recent_completed_by_user($user_id, $limit = 5)
    {
        $this->db->where('status', 'Done');
        $this->db->where('staff_id', $user_id);
        $this->db->order_by('updated_at', 'DESC');
        $this->db->limit((int)$limit);
        return $this->db->get('tbl_orders')->result();
    }
}
