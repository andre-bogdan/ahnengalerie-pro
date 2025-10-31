<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePersonsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            
            // Basis-Informationen
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'maiden_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'comment'    => 'Geburtsname (falls abweichend)',
            ],
            'gender' => [
                'type'       => 'VARCHAR',
                'constraint' => '1',
                'null'       => true,
                'comment'    => 'm = männlich, f = weiblich, x = divers',
            ],
            
            // Lebensdaten
            'birth_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'birth_place' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'death_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'death_place' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            
            // Weitere Details
            'biography' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Lebensgeschichte, Notizen',
            ],
            'occupation' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'comment'    => 'Beruf',
            ],
            
            // Medien
            'primary_photo_id' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
                'null'     => true,
                'comment'  => 'Referenz zum Hauptfoto (photos.id)',
            ],
            
            // Metadaten
            'created_by' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
                'null'     => true,
                'comment'  => 'user.id - wer hat Person angelegt',
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
        $this->forge->createTable('persons');

        // Indizes für Suche & Performance
        $this->db->query('CREATE INDEX idx_persons_last_name ON persons(last_name)');
        $this->db->query('CREATE INDEX idx_persons_first_name ON persons(first_name)');
        $this->db->query('CREATE INDEX idx_persons_birth_date ON persons(birth_date)');
        $this->db->query('CREATE INDEX idx_persons_created_by ON persons(created_by)');

        echo "\033[32m✓\033[0m persons Tabelle erstellt\n";
    }

    public function down()
    {
        $this->forge->dropTable('persons');
        echo "\033[31m✗\033[0m persons Tabelle gelöscht\n";
    }
}