<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MenuModel extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Return all menu items ordered by date_created desc
     */
    public function get_all() {
        // Join with inventory table to include stock_quantity when available
        $this->db->select('m.*, COALESCE(tmi.stock_quantity, 0) as stock_quantity');
        $this->db->from('menu_tbl m');
        // Only return items marked Available
        $this->db->where('m.status', 'Available');
        $this->db->join('tbl_menu_items tmi', 'tmi.menu_id = m.menu_id', 'left');
        $this->db->order_by('m.date_created', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Return items with stock less than or equal to threshold (default 5)
     */
    public function get_low_stock($threshold = 5)
    {
        $this->db->select('m.*, COALESCE(tmi.stock_quantity, 0) as stock_quantity');
        $this->db->from('menu_tbl m');
        $this->db->join('tbl_menu_items tmi', 'tmi.menu_id = m.menu_id', 'left');
        $this->db->where('COALESCE(tmi.stock_quantity, 0) <=', (int)$threshold);
    // Use a raw ORDER BY expression so CI doesn't escape the function call incorrectly
    // Pass an empty string (not NULL) for the second parameter to avoid trim(NULL) deprecation warnings
    $this->db->order_by('COALESCE(tmi.stock_quantity, 0) ASC', '', FALSE);
        $query = $this->db->get();
        return $query->result_array();
    }
}
