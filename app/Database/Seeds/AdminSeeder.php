<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Admin-User erstellen
        $data = [
            [
                'username'   => 'admin',
                'email'      => 'admin@ahnengalerie.local',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'is_admin'   => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // In Datenbank einfügen
        $this->db->table('users')->insertBatch($data);

        echo "\033[32m✓\033[0m Admin-User erstellt\n";
        echo "\033[33mℹ\033[0m Login: admin / admin123\n";
        echo "\033[31m⚠\033[0m WICHTIG: Passwort nach erstem Login ändern!\n";
    }
}