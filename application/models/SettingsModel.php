<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SettingsModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ✅ Get admin info for current session
    public function get_admin($signup_id) {
        return $this->db->get_where('tbl_signup', ['signup_id' => $signup_id])->row_array();
    }

    // ✅ Update username and/or password
    public function update_admin($signup_id, $data) {
        $this->db->where('signup_id', $signup_id);
        return $this->db->update('tbl_signup', $data);
    }
}
