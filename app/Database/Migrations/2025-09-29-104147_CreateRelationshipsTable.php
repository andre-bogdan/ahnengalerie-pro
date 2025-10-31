<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRelationshipsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            
            // Beteiligte Personen
            'person1_id' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
            ],
            'person2_id' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
            ],
            
            // Art der Beziehung
            'relationship_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'comment'    => 'parent, spouse, partner, sibling',
            ],
            
            // Zeitliche Details
            'start_date' => [
                'type'    => 'DATE',
                'null'    => true,
                'comment' => 'z.B. Hochzeitsdatum bei spouse',
            ],
            'end_date' => [
                'type'    => 'DATE',
                'null'    => true,
                'comment' => 'z.B. Scheidung oder Tod',
            ],
            
            // Zusätzliche Informationen
            'notes' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Zusatzinfos zur Beziehung',
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
        $this->forge->createTable('relationships');

        // Foreign Key Constraints (SQLite Style)
        // Hinweis: SQLite unterstützt Foreign Keys, aber anders als MySQL
        
        // Indizes für Performance
        $this->db->query('CREATE INDEX idx_relationships_person1 ON relationships(person1_id)');
        $this->db->query('CREATE INDEX idx_relationships_person2 ON relationships(person2_id)');
        $this->db->query('CREATE INDEX idx_relationships_type ON relationships(relationship_type)');
        
        // Unique Constraint: Verhindert doppelte Beziehungen
        $this->db->query('CREATE UNIQUE INDEX idx_relationships_unique 
            ON relationships(person1_id, person2_id, relationship_type)');

        echo "\033[32m✓\033[0m relationships Tabelle erstellt\n";
        echo "\033[33mℹ\033[0m Beziehungstypen: parent, spouse, partner, sibling\n";
    }

    public function down()
    {
        $this->forge->dropTable('relationships');
        echo "\033[31m✗\033[0m relationships Tabelle gelöscht\n";
    }
}