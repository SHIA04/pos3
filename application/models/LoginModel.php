<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // ✅ Validate user credentials
    public function validate_user($username, $password) {
        // Sanitize inputs (XSS filtering handled by CI input class)
        $username = $this->security->xss_clean($username);
        $password = $this->security->xss_clean($password);

        $this->db->where('username', $username);
        $query = $this->db->get('tbl_login');

        if ($query->num_rows() === 1) {
            $user = $query->row();
            // ✅ Password verification
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

    public function insert_login($data) {
    return $this->db->insert('tbl_login', $data);
}

public function get_admin_by_id($admin_id) {
    return $this->db->get_where('tbl_login', ['admin_id' => $admin_id])->row_array();
}

public function update_admin($admin_id, $data) {
    $this->db->where('admin_id', $admin_id);
    return $this->db->update('tbl_login', $data);
}


}
