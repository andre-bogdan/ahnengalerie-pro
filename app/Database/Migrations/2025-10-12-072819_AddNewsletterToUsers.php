<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNewsletterToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'newsletter_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,  // Standardmäßig aktiviert
                'null' => false,
                'after' => 'is_admin'
            ]
        ];
        
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'newsletter_enabled');
    }
}