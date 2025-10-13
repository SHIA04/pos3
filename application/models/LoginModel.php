<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginModel extends CI_Model
{
    /**
     * Validate a user by username and password against tbl_signup.
     *
     * Expects tbl_signup columns:
     * - signup_id (PK)
     * - username
     * - role
     * - password (preferably a password_hash() value)
     *
     * @param string $username
     * @param string $password
     * @return object|false stdClass user row on success, false otherwise
     */
    public function validate_user($username, $password)
    {
        if (!is_string($username) || $username === '' || !is_string($password)) {
            return false;
        }

        $query = $this->db
            ->select('signup_id, username, role, password')
            ->from('tbl_signup')
            ->where('username', $username)
            ->limit(1)
            ->get();

        $row = $query->row();
        if (!$row) {
            return false;
        }

        $stored = (string) $row->password;

        // Prefer secure verify if stored is a hash
        $isHash = (strpos($stored, '$2y$') === 0) || (strpos($stored, '$argon2') === 0);
        if ($isHash) {
            if (password_verify($password, $stored)) {
                // Optionally rehash if needed (algorithm changes/cost)
                if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
                    // Safe best-effort rehash update (ignore errors)
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $this->db->where('signup_id', $row->signup_id)
                             ->update('tbl_signup', ['password' => $newHash]);
                }
                return $row;
            }
            return false;
        }

        // Fallback: plain-text compare if DB still stores plain passwords (not recommended)
        if (hash_equals($stored, $password)) {
            return $row;
        }

        return false;
    }
}