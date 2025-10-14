<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PasswordResetModel extends CI_Model
{
    protected $table = 'password_resets';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a password reset token for a signup_id.
     * Returns the raw token (not stored) on success.
     */
    public function create_token($signup_id, $expires_seconds = 3600)
    {
        $token = bin2hex(random_bytes(24));
        $token_hash = password_hash($token, PASSWORD_BCRYPT);
        $expires_at = date('Y-m-d H:i:s', time() + $expires_seconds);

        $insert = [
            'signup_id' => (int)$signup_id,
            'token_hash' => $token_hash,
            'expires_at' => $expires_at,
            'used' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $ok = $this->db->insert($this->table, $insert);
        if ($ok) {
            return $token;
        }
        log_message('error', 'PasswordResetModel create_token failed: ' . json_encode($this->db->error()));
        return false;
    }

    /**
     * Verify a raw token. Returns reset row object if valid, false otherwise.
     */
    public function verify_token($raw_token)
    {
        // Get candidates that are not used and not expired
        $now = date('Y-m-d H:i:s');
        $query = $this->db->where('used', 0)
                          ->where('expires_at >=', $now)
                          ->order_by('created_at', 'desc')
                          ->get($this->table);

        foreach ($query->result() as $row) {
            if (password_verify($raw_token, $row->token_hash)) {
                return $row;
            }
        }
        return false;
    }

    /**
     * Mark a reset token row as used by id
     */
    public function mark_used($id)
    {
        return $this->db->where('id', $id)->update($this->table, ['used' => 1]);
    }
}
