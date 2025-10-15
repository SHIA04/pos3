<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_remember_tokens extends CI_Migration {
    public function up() {
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE],
            'signup_id' => ['type' => 'INT', 'constraint' => 11],
            'token_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'expires_at' => ['type' => 'DATETIME'],
            'used' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP']
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('remember_tokens', TRUE);
    }

    public function down() {
        $this->dbforge->drop_table('remember_tokens', TRUE);
    }
}
