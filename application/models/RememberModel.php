<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RememberModel extends CI_Model {
    protected $table = 'remember_tokens';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Create token record
    public function create($signup_id, $token_hash, $expires_at) {
        $data = [
            'signup_id' => $signup_id,
            'token_hash' => $token_hash,
            'expires_at' => $expires_at,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->insert($this->table, $data);
    }

    // Find by token hash
    public function find_by_hash($token_hash) {
        return $this->db->from($this->table)
                        ->where('token_hash', $token_hash)
                        ->where('expires_at >=', date('Y-m-d H:i:s'))
                        ->where('used', 0)
                        ->limit(1)
                        ->get()
                        ->row();
    }

    // Mark token used/revoked
    public function mark_used($id) {
        return $this->db->where('id', $id)->update($this->table, ['used' => 1]);
    }
}
