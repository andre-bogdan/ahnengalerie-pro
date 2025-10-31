<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'is_admin' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 0,
                'comment'    => '0 = normaler User, 1 = Administrator',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('users');

        // Indizes für Performance
        $this->db->query('CREATE INDEX idx_users_username ON users(username)');
        $this->db->query('CREATE INDEX idx_users_email ON users(email)');

        echo "\033[32m✓\033[0m users Tabelle erstellt\n";
    }

    public function down()
    {
        $this->forge->dropTable('users');
        echo "\033[31m✗\033[0m users Tabelle gelöscht\n";
    }
}