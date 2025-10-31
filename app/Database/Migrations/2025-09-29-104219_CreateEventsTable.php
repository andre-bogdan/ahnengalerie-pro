<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            
            // Zuordnung
            'person_id' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
            ],
            
            // Ereignis-Details
            'event_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment'    => 'birth, death, baptism, marriage, divorce, education, employment, military, residence, immigration',
            ],
            'event_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'event_place' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            
            // Optional: Verknüpfung mit anderen Personen
            'related_person_id' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
                'null'     => true,
                'comment'  => 'z.B. bei Hochzeit: Partner',
            ],
            
            // Metadaten
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
        $this->forge->createTable('events');

        // Indizes
        $this->db->query('CREATE INDEX idx_events_person ON events(person_id)');
        $this->db->query('CREATE INDEX idx_events_type ON events(event_type)');
        $this->db->query('CREATE INDEX idx_events_date ON events(event_date)');

        echo "\033[32m✓\033[0m events Tabelle erstellt\n";
        echo "\033[33mℹ\033[0m Event-Typen: birth, death, baptism, marriage, divorce, education, employment, military, residence, immigration\n";
    }

    public function down()
    {
        $this->forge->dropTable('events');
        echo "\033[31m✗\033[0m events Tabelle gelöscht\n";
    }
}