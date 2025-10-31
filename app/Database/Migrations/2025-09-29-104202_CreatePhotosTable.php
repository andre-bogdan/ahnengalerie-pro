<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePhotosTable extends Migration
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
            
            // Datei-Informationen
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'comment'    => 'Pfad: uploads/persons/123/photo-xyz.jpg',
            ],
            'thumbnail_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'Thumbnail: uploads/persons/123/thumb-xyz.jpg',
            ],
            'file_size' => [
                'type'    => 'INTEGER',
                'null'    => true,
                'comment' => 'Dateigröße in Bytes',
            ],
            'mime_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'comment'    => 'image/jpeg, image/png, application/pdf',
            ],
            
            // Beschreibung
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'z.B. "Hochzeitsfoto 1985"',
            ],
            'description' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Detaillierte Beschreibung',
            ],
            'date_taken' => [
                'type'    => 'DATE',
                'null'    => true,
                'comment' => 'Wann wurde Foto aufgenommen?',
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'Wo wurde Foto aufgenommen?',
            ],
            
            // Display-Settings
            'is_primary' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 0,
                'comment'    => 'Ist dies das Hauptfoto der Person?',
            ],
            'display_order' => [
                'type'    => 'INTEGER',
                'default' => 0,
                'comment' => 'Reihenfolge in Galerie (0 = zuerst)',
            ],
            
            // Metadaten
            'uploaded_by' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
                'null'     => true,
                'comment'  => 'user.id - wer hat Foto hochgeladen',
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
        $this->forge->createTable('photos');

        // Indizes
        $this->db->query('CREATE INDEX idx_photos_person ON photos(person_id)');
        $this->db->query('CREATE INDEX idx_photos_primary ON photos(person_id, is_primary)');
        $this->db->query('CREATE INDEX idx_photos_order ON photos(person_id, display_order)');

        echo "\033[32m✓\033[0m photos Tabelle erstellt\n";
    }

    public function down()
    {
        $this->forge->dropTable('photos');
        echo "\033[31m✗\033[0m photos Tabelle gelöscht\n";
    }
}