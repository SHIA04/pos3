<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderModel extends CI_Model {

    public function get_all_orders() {
        return $this->db->order_by('order_date', 'DESC')->get('tbl_orders')->result();
    }

    public function insert_order($data) {
        return $this->db->insert('tbl_orders', $data);
    }

    public function get_order($order_id) {
        return $this->db->get_where('tbl_orders', ['order_id' => $order_id])->row();
    }

    public function update_order($order_id, $data) {
        $this->db->where('order_id', $order_id);
        return $this->db->update('tbl_orders', $data);
    }

    public function delete_order($order_id) {
        return $this->db->delete('tbl_orders', ['order_id' => $order_id]);
    }

    // ✅ Updated get_done_orders with period filter
    public function get_done_orders($period = null) {
        $this->db->where('status', 'Done');

        if ($period == 'daily') {
            $this->db->where('DATE(order_date)', date('Y-m-d'));
        } elseif ($period == 'weekly') {
            $this->db->where('YEARWEEK(order_date, 1) = YEARWEEK(CURDATE(), 1)');
        } elseif ($period == 'monthly') {
            $this->db->where('MONTH(order_date)', date('m'));
            $this->db->where('YEAR(order_date)', date('Y'));
        }

        $this->db->order_by('order_date', 'DESC');
        $query = $this->db->get('tbl_orders');
        return $query->result();
    }

    public function get_sales_total($period) {
        $this->db->select_sum('total_amount');
        $this->db->where('status', 'Done');

        if ($period == 'daily') {
            $this->db->where('DATE(order_date)', date('Y-m-d'));
        } elseif ($period == 'weekly') {
            $this->db->where('YEARWEEK(order_date, 1) = YEARWEEK(CURDATE(), 1)');
        } elseif ($period == 'monthly') {
            $this->db->where('MONTH(order_date)', date('m'));
            $this->db->where('YEAR(order_date)', date('Y'));
        }

        $query = $this->db->get('tbl_orders');
        $result = $query->row();
        return $result->total_amount ? $result->total_amount : 0;
    }

    public function get_chart_data($period) {
        $this->db->select('DATE(order_date) as date, SUM(total_amount) as total');
        $this->db->where('status', 'Done');

        if ($period == 'daily') {
            $this->db->where('DATE(order_date)', date('Y-m-d'));
        } elseif ($period == 'weekly') {
            $this->db->where('YEARWEEK(order_date, 1) = YEARWEEK(CURDATE(), 1)');
            $this->db->group_by('DATE(order_date)');
        } elseif ($period == 'monthly') {
            $this->db->where('MONTH(order_date)', date('m'));
            $this->db->where('YEAR(order_date)', date('Y'));
            $this->db->group_by('DATE(order_date)');
        }

        $query = $this->db->get('tbl_orders');
        return $query->result_array();
    }
}
