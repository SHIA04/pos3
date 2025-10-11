<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SettingsModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ✅ Get admin info for current session
    public function get_admin($admin_id) {
        return $this->db->get_where('tbl_signup', ['admin_id' => $admin_id])->row_array();
    }

    // ✅ Update username and/or password
    public function update_admin($admin_id, $data) {
        $this->db->where('admin_id', $admin_id);
        return $this->db->update('tbl_signup', $data);
    }
}
