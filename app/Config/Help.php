<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Help System Configuration
 * 
 * HINWEIS FÜR USER:
 * Du musst normalerweise NICHT diese Datei bearbeiten!
 * Überschreibe stattdessen die Werte in deiner .env Datei:
 * 
 * help.contentPath = ../shared/help-content
 * help.defaultLanguage = de
 * 
 * Die Werte hier sind nur FALLBACK-Defaults, falls .env nicht gesetzt ist.
 */

class Help extends BaseConfig
{
    /**
     * Pfad zum Hilfe-Content Ordner
     * Relativ zu ROOTPATH oder absoluter Pfad
     * 
     * Standard: ../shared/help-content (außerhalb von public/)
     * 
     * @var string
     */
    public string $contentPath = '../shared/help-content';

    /**
     * Standard-Sprache für Hilfe-Artikel
     * 
     * @var string
     */
    public string $defaultLanguage = 'de';

    /**
     * Unterstützte Sprachen
     * Später für Mehrsprachigkeit
     * 
     * @var array
     */
    public array $supportedLanguages = ['de', 'en'];

    /**
     * Cache-Dauer für index.json (in Sekunden)
     * 0 = kein Cache
     * 
     * @var int
     */
    public int $indexCacheDuration = 3600; // 1 Stunde

    /**
     * Cache-Dauer für Artikel (in Sekunden)
     * 
     * @var int
     */
    public int $articleCacheDuration = 3600;

    public function __construct()
    {
        parent::__construct();

        // .env Werte überschreiben Config-Defaults
        if (getenv('help.contentPath')) {
            $this->contentPath = getenv('help.contentPath');
        }

        if (getenv('help.defaultLanguage')) {
            $this->defaultLanguage = getenv('help.defaultLanguage');
        }
    }

    /**
     * Gibt den vollen Pfad zum Content-Ordner zurück
     * 
     * @param string|null $language Sprache (optional, default aus Config)
     * @return string
     */
    public function getContentPath(?string $language = null): string
    {
        $lang = $language ?? $this->defaultLanguage;
        
        // Wenn relativer Pfad, von ROOTPATH aus
        if (!str_starts_with($this->contentPath, '/')) {
            return ROOTPATH . $this->contentPath . '/' . $lang . '/';
        }
        
        // Absoluter Pfad
        return rtrim($this->contentPath, '/') . '/' . $lang . '/';
    }

    /**
     * Gibt Pfad zur index.json zurück
     * 
     * @param string|null $language
     * @return string
     */
    public function getIndexPath(?string $language = null): string
    {
        return $this->getContentPath($language) . 'index.json';
    }
}