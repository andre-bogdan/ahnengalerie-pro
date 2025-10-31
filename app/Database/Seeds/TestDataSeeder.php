<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        echo "\033[33mℹ\033[0m Erstelle Test-Daten...\n";

        // 1. Normale User erstellen
        $this->db->table('users')->insert([
            'username'   => 'user',
            'email'      => 'user@ahnengalerie.local',
            'password'   => password_hash('user123', PASSWORD_DEFAULT),
            'is_admin'   => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        echo "\033[32m✓\033[0m Test-User erstellt (user / user123)\n";

        // 2. Beispiel-Familie erstellen (3 Generationen)
        // Generation 1: Großeltern
        
        // Heinrich (ID wird 1)
        $this->db->table('persons')->insert([
            'first_name'  => 'Heinrich',
            'last_name'   => 'Müller',
            'gender'      => 'm',
            'birth_date'  => '1920-03-15',
            'death_date'  => '1995-08-22',
            'birth_place' => 'Berlin',
            'biography'   => 'Großvater mütterlicherseits. Arbeitete als Tischler.',
            'created_by'  => 1,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        
        // Margarete (ID wird 2)
        $this->db->table('persons')->insert([
            'first_name'  => 'Margarete',
            'last_name'   => 'Müller',
            'maiden_name' => 'Schmidt',
            'gender'      => 'f',
            'birth_date'  => '1923-07-10',
            'death_date'  => '2000-12-05',
            'birth_place' => 'Hamburg',
            'biography'   => 'Großmutter mütterlicherseits. Lehrerin an der Grundschule.',
            'created_by'  => 1,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        
        // Generation 2: Eltern
        
        // Maria (ID wird 3)
        $this->db->table('persons')->insert([
            'first_name'  => 'Maria',
            'last_name'   => 'Weber',
            'maiden_name' => 'Müller',
            'gender'      => 'f',
            'birth_date'  => '1950-05-20',
            'birth_place' => 'Berlin',
            'occupation'  => 'Krankenschwester',
            'biography'   => 'Mutter. Arbeitete 30 Jahre im Krankenhaus.',
            'created_by'  => 1,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        
        // Thomas (ID wird 4)
        $this->db->table('persons')->insert([
            'first_name'  => 'Thomas',
            'last_name'   => 'Weber',
            'gender'      => 'm',
            'birth_date'  => '1948-11-08',
            'birth_place' => 'München',
            'occupation'  => 'Ingenieur',
            'biography'   => 'Vater. Maschinenbau-Ingenieur bei Siemens.',
            'created_by'  => 1,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        
        // Generation 3: Kinder
        
        // Anna (ID wird 5)
        $this->db->table('persons')->insert([
            'first_name'  => 'Anna',
            'last_name'   => 'Weber',
            'gender'      => 'f',
            'birth_date'  => '1975-08-14',
            'birth_place' => 'Berlin',
            'occupation'  => 'Ärztin',
            'biography'   => 'Älteste Tochter. Fachärztin für Innere Medizin.',
            'created_by'  => 1,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        
        // Michael (ID wird 6)
        $this->db->table('persons')->insert([
            'first_name'  => 'Michael',
            'last_name'   => 'Weber',
            'gender'      => 'm',
            'birth_date'  => '1978-02-25',
            'birth_place' => 'Berlin',
            'occupation'  => 'Software-Entwickler',
            'biography'   => 'Jüngster Sohn. Arbeitet in der IT-Branche.',
            'created_by'  => 1,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        
        echo "\033[32m✓\033[0m 6 Test-Personen erstellt (3 Generationen)\n";

        // 3. Beziehungen erstellen
        
        // Heinrich ⚭ Margarete (Großeltern)
        $this->db->table('relationships')->insert([
            'person1_id'        => 1,
            'person2_id'        => 2,
            'relationship_type' => 'spouse',
            'start_date'        => '1945-06-12',
            'notes'             => 'Kirchliche Hochzeit in Berlin',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
        
        // Heinrich → Maria (Vater-Tochter)
        $this->db->table('relationships')->insert([
            'person1_id'        => 1,
            'person2_id'        => 3,
            'relationship_type' => 'parent',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
        
        // Margarete → Maria (Mutter-Tochter)
        $this->db->table('relationships')->insert([
            'person1_id'        => 2,
            'person2_id'        => 3,
            'relationship_type' => 'parent',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
        
        // Thomas ⚭ Maria (Eltern)
        $this->db->table('relationships')->insert([
            'person1_id'        => 4,
            'person2_id'        => 3,
            'relationship_type' => 'spouse',
            'start_date'        => '1973-09-15',
            'notes'             => 'Standesamtliche Hochzeit in München',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
        
        // Maria → Anna (Mutter-Tochter)
        $this->db->table('relationships')->insert([
            'person1_id'        => 3,
            'person2_id'        => 5,
            'relationship_type' => 'parent',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
        
        // Thomas → Anna (Vater-Tochter)
        $this->db->table('relationships')->insert([
            'person1_id'        => 4,
            'person2_id'        => 5,
            'relationship_type' => 'parent',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
        
        // Maria → Michael (Mutter-Sohn)
        $this->db->table('relationships')->insert([
            'person1_id'        => 3,
            'person2_id'        => 6,
            'relationship_type' => 'parent',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
        
        // Thomas → Michael (Vater-Sohn)
        $this->db->table('relationships')->insert([
            'person1_id'        => 4,
            'person2_id'        => 6,
            'relationship_type' => 'parent',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
        
        // Anna ↔ Michael (Geschwister)
        $this->db->table('relationships')->insert([
            'person1_id'        => 5,
            'person2_id'        => 6,
            'relationship_type' => 'sibling',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
        
        echo "\033[32m✓\033[0m 9 Beziehungen erstellt\n";

        // 4. Beispiel-Events
        
        $this->db->table('events')->insert([
            'person_id'   => 1,
            'event_type'  => 'birth',
            'event_date'  => '1920-03-15',
            'event_place' => 'Berlin',
            'description' => 'Geboren in Berlin-Mitte',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        
        $this->db->table('events')->insert([
            'person_id'         => 1,
            'event_type'        => 'marriage',
            'event_date'        => '1945-06-12',
            'event_place'       => 'Berlin',
            'description'       => 'Heirat mit Margarete Schmidt',
            'related_person_id' => 2,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
        
        $this->db->table('events')->insert([
            'person_id'   => 3,
            'event_type'  => 'education',
            'event_date'  => '1970-06-30',
            'event_place' => 'Berlin',
            'description' => 'Abschluss der Krankenpflegeschule',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        
        echo "\033[32m✓\033[0m 3 Events erstellt\n";

        echo "\033[32m✓\033[0m Alle Test-Daten erfolgreich erstellt!\n";
        echo "\033[33mℹ\033[0m Test-Familie: Müller-Weber (3 Generationen, 6 Personen)\n";
    }
}