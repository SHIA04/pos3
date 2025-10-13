<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SignupModel extends CI_Model
{
    // Target table - use the signup table that matches the form fields
    protected $table = 'tbl_signup';

    // Whitelisted columns that can be inserted (match `tbl_signup` schema)
    protected $allowed = [
        'fullname',
        'username',
        'age',
        'sex',
        'birthday',
        'role',
        'phone_number',
        'email',
        'password',
        'profile_image',
        'created_at',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
    * Insert a new user row into users_tbl.
    * - Whitelists fields
    * - Drops confirm_password if present
    * - Adds created_at timestamp if missing
     */
    public function insert_user($data)
    {
        // Keep only allowed columns
        $filtered = array_intersect_key($data, array_flip($this->allowed));

        // Never store confirm_password (in case controller passed it)
        unset($filtered['confirm_password']);

        // Timestamps (only created_at for this model)
        $now = date('Y-m-d H:i:s');
        if (empty($filtered['created_at'])) {
            $filtered['created_at'] = $now;
        }

        $ok = $this->db->insert($this->table, $filtered);
        if (!$ok) {
            log_message('error', 'SignupModel insert_user failed: ' . json_encode($this->db->error()));
        }
        return $ok;
    }

    /**
     * Check if email already exists.
     * If your DB collation is case-insensitive (utf8mb4_unicode_ci), this is sufficient.
     */
    public function email_exists($email)
    {
        return $this->db->where('email', $email)
                        ->limit(1)
                        ->count_all_results($this->table) > 0;
    }

    /**
     * Check if username already exists.
     */
    public function username_exists($username)
    {
        return $this->db->where('username', $username)
                        ->limit(1)
                        ->count_all_results($this->table) > 0;
    }
}