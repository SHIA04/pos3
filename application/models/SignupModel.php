<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SignupModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Insert new user record
    public function insert_user($data) {
        return $this->db->insert('tbl_signup', $data);
    }

    // Check if email already exists
    public function email_exists($email) {
        $query = $this->db->get_where('tbl_signup', ['email' => $email]);
        return $query->num_rows() > 0;
    }
}
 