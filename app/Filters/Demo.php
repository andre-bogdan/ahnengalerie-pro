<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class DemoFilter implements FilterInterface
{
    private $templateDbPath;
    private $demoDbDir;

    public function __construct()
    {
        $this->templateDbPath = WRITEPATH . 'database/demo_template.db';
        $this->demoDbDir = WRITEPATH . 'database/demo/';
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        // Prüfe ob bereits Demo-Session existiert
        if (!session()->has('demo_user_id')) {
            $this->createDemoSession();
        }
        
        // Stelle sicher dass Demo-DB existiert
        $this->ensureDemoDatabase();
        
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nichts zu tun
    }

    /**
     * Erstellt eine neue Demo-Session
     */
    private function createDemoSession()
    {
        $demoUserId = 'demo_' . uniqid() . '_' . time();
        
        session()->set([
            'demo_user_id' => $demoUserId,
            'demo_started' => time(),
            'logged_in' => true,
            'user_id' => 1,
            'username' => 'Demo User',
            'email' => 'demo@ahnengalerie-pro.de',
            'is_admin' => false,
            'is_demo' => true
        ]);
        
        log_message('info', 'Demo session created: ' . $demoUserId);
    }

    /**
     * Stellt sicher dass Demo-Datenbank existiert
     */
    private function ensureDemoDatabase()
    {
        $demoUserId = session('demo_user_id');
        $dbPath = $this->demoDbDir . $demoUserId . '.db';
        
        // Erstelle Demo-Ordner falls nicht vorhanden
        if (!is_dir($this->demoDbDir)) {
            mkdir($this->demoDbDir, 0755, true);
        }
        
        // Wenn DB nicht existiert, erstelle sie
        if (!file_exists($dbPath)) {
            $this->createDemoDatabase($dbPath);
        }
        
        // Setze DB-Pfad für diese Session
        // WICHTIG: Dies muss VOR jedem DB-Zugriff passieren
        $this->switchToDatabase($dbPath);
    }

    /**
     * Erstellt eine neue Demo-Datenbank
     */
    private function createDemoDatabase($dbPath)
    {
        // Falls Template existiert, kopieren
        if (file_exists($this->templateDbPath)) {
            copy($this->templateDbPath, $dbPath);
            log_message('info', 'Demo DB created from template: ' . $dbPath);
        } else {
            // Erstelle neue leere DB mit Struktur
            $db = new \SQLite3($dbPath);
            $this->createTables($db);
            $this->insertDemoUser($db);
            $db->close();
            log_message('info', 'Demo DB created fresh: ' . $dbPath);
        }
        
        // Setze Berechtigungen
        chmod($dbPath, 0644);
    }

    /**
     * Erstellt alle benötigten Tabellen
     */
    private function createTables($db)
    {
        $tables = [
            // Users Table
            "CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                is_admin TINYINT(1) DEFAULT 0,
                newsletter_enabled TINYINT(1) DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )",
            
            // Persons Table
            "CREATE TABLE IF NOT EXISTS persons (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                first_name VARCHAR(100) NOT NULL,
                last_name VARCHAR(100) NOT NULL,
                maiden_name VARCHAR(100),
                gender VARCHAR(1),
                birth_date DATE,
                birth_place VARCHAR(255),
                death_date DATE,
                death_place VARCHAR(255),
                biography TEXT,
                occupation VARCHAR(100),
                primary_photo_id INTEGER,
                created_by INTEGER,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
            )",
            
            // Relationships Table
            "CREATE TABLE IF NOT EXISTS relationships (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                person1_id INTEGER NOT NULL,
                person2_id INTEGER NOT NULL,
                relationship_type VARCHAR(20) NOT NULL,
                start_date DATE,
                end_date DATE,
                notes TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (person1_id) REFERENCES persons(id) ON DELETE CASCADE,
                FOREIGN KEY (person2_id) REFERENCES persons(id) ON DELETE CASCADE,
                CHECK (person1_id != person2_id),
                UNIQUE(person1_id, person2_id, relationship_type)
            )",
            
            // Photos Table
            "CREATE TABLE IF NOT EXISTS photos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                person_id INTEGER NOT NULL,
                file_path VARCHAR(255) NOT NULL,
                thumbnail_path VARCHAR(255),
                file_size INTEGER,
                mime_type VARCHAR(50),
                title VARCHAR(255),
                description TEXT,
                date_taken DATE,
                location VARCHAR(255),
                is_primary TINYINT(1) DEFAULT 0,
                display_order INTEGER DEFAULT 0,
                uploaded_by INTEGER,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE CASCADE,
                FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
            )",
            
            // Events Table
            "CREATE TABLE IF NOT EXISTS events (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                person_id INTEGER NOT NULL,
                event_type VARCHAR(50) NOT NULL,
                event_date DATE,
                event_place VARCHAR(255),
                description TEXT,
                related_person_id INTEGER,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE CASCADE,
                FOREIGN KEY (related_person_id) REFERENCES persons(id) ON DELETE SET NULL
            )",
            
            // Indexes für Performance
            "CREATE INDEX IF NOT EXISTS idx_persons_last_name ON persons(last_name)",
            "CREATE INDEX IF NOT EXISTS idx_persons_created_by ON persons(created_by)",
            "CREATE INDEX IF NOT EXISTS idx_relationships_person1 ON relationships(person1_id)",
            "CREATE INDEX IF NOT EXISTS idx_relationships_person2 ON relationships(person2_id)",
            "CREATE INDEX IF NOT EXISTS idx_photos_person ON photos(person_id)",
            "CREATE INDEX IF NOT EXISTS idx_events_person ON events(person_id)"
        ];
        
        foreach ($tables as $sql) {
            $result = $db->exec($sql);
            if (!$result) {
                log_message('error', 'Failed to create table: ' . $db->lastErrorMsg());
            }
        }
    }

    /**
     * Fügt Demo-User ein
     */
    private function insertDemoUser($db)
    {
        $hashedPassword = password_hash('demo', PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (username, email, password, is_admin, newsletter_enabled) 
                VALUES ('Demo User', 'demo@ahnengalerie-pro.de', '{$hashedPassword}', 0, 0)";
        
        $db->exec($sql);
    }

    /**
     * Wechselt zur Demo-Datenbank
     */
    private function switchToDatabase($dbPath)
    {
        // Hole aktuelle DB-Config
        $db = \Config\Database::connect();
        
        // Schließe alte Verbindung
        $db->close();
        
        // Setze neue DB-Pfad
        $config = config('Database');
        $config->default['database'] = $dbPath;
        
        // Force neue Verbindung
        \Config\Database::connect(null, true);
    }
}