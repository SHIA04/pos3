<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_password_resets extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'signup_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
            ],
            'token_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => FALSE,
            ],
            'used' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => FALSE,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => FALSE,
            ],
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('password_resets', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('password_resets', TRUE);
    }
}
