<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_users_tbl extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'fullname' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => FALSE,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => FALSE,
            ],
            'age' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => FALSE,
            ],
            'sex' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => FALSE,
            ],
            'birthday' => [
                'type' => 'DATE',
                'null' => FALSE,
            ],
            'role' => [
                'type' => 'ENUM("owner","staff")',
                'null' => FALSE,
            ],
            'phone_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => FALSE,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
                'null' => FALSE,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ],
            'profile_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
                'default' => NULL,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('email', TRUE);
        $this->dbforge->add_key('username', TRUE);
        $this->dbforge->add_key('role');

        $this->dbforge->create_table('users_tbl', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('users_tbl', TRUE);
    }
}
