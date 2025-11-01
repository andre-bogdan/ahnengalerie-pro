<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PersonModel;
use App\Models\RelationshipModel;
use App\Models\EventModel;

/**
 * GEDCOM Export Controller
 * 
 * Exportiert Genealogie-Daten im GEDCOM 5.5.1 Format
 * GEDCOM = GEnealogical Data COMmunication
 */
class Export extends BaseController
{
    protected $personModel;
    protected $relationshipModel;
    protected $eventModel;
    protected $db;
    
    public function __construct()
    {
        $this->personModel = new PersonModel();
        $this->relationshipModel = new RelationshipModel();
        $this->eventModel = new EventModel();
        $this->db = \Config\Database::connect();
    }
    
    /**
     * Export-Übersichtsseite
     */
    public function index()
    {
        $data = [
            'title' => 'Datenexport',
            'personCount' => $this->personModel->countAll(),
            'relationshipCount' => $this->relationshipModel->countAll(),
            'eventCount' => $this->eventModel->countAll()
        ];
        
        return view('export/index', $data);
    }
    
    /**
     * GEDCOM Export - Hauptfunktion
     */
    public function gedcom()
    {
        // Alle Personen laden
        $persons = $this->personModel->findAll();
        
        // Alle Beziehungen laden
        $relationships = $this->relationshipModel->findAll();
        
        // Alle Events laden
        $events = $this->eventModel->findAll();
        
        // GEDCOM-Inhalt generieren
        $gedcomContent = $this->generateGedcom($persons, $relationships, $events);
        
        // Dateiname mit Datum
        $filename = 'ahnengalerie_export_' . date('Y-m-d_His') . '.ged';
        
        // Headers für Download setzen
        return $this->response
            ->setHeader('Content-Type', 'text/x-gedcom; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0')
            ->setBody($gedcomContent);
    }
    
    /**
     * GEDCOM-Format generieren
     */
    private function generateGedcom($persons, $relationships, $events)
    {
        $gedcom = "";
        
        // GEDCOM Header
        $gedcom .= "0 HEAD\n";
        $gedcom .= "1 SOUR Ahnengalerie Pro\n";
        $gedcom .= "2 VERS 1.3.0\n";
        $gedcom .= "2 NAME Ahnengalerie Pro - Moderne Genealogie Software\n";
        $gedcom .= "2 CORP Ahnengalerie\n";
        $gedcom .= "3 ADDR " . base_url() . "\n";
        $gedcom .= "1 DATE " . date('d M Y') . "\n";
        $gedcom .= "2 TIME " . date('H:i:s') . "\n";
        $gedcom .= "1 GEDC\n";
        $gedcom .= "2 VERS 5.5.1\n";
        $gedcom .= "2 FORM LINEAGE-LINKED\n";
        $gedcom .= "1 CHAR UTF-8\n";
        $gedcom .= "1 LANG German\n";
        $gedcom .= "1 SUBM @SUBM1@\n";
        
        // Submitter (Einreicher)
        $gedcom .= "0 @SUBM1@ SUBM\n";
        $gedcom .= "1 NAME " . session('username') . "\n";
        
        // Familien-Array erstellen (für GEDCOM FAM Records)
        $families = $this->createFamilies($relationships, $persons);
        
        // Individuen (INDI Records)
        foreach ($persons as $person) {
            $gedcom .= $this->createIndividualRecord($person, $relationships, $events);
        }
        
        // Familien (FAM Records)
        foreach ($families as $familyId => $family) {
            $gedcom .= $this->createFamilyRecord($familyId, $family);
        }
        
        // GEDCOM Trailer
        $gedcom .= "0 TRLR\n";
        
        return $gedcom;
    }
    
    /**
     * INDI Record für eine Person erstellen
     */
    private function createIndividualRecord($person, $relationships, $events)
    {
        $indi = "";
        $personId = "@I" . $person['id'] . "@";
        
        // Individual Record Start
        $indi .= "0 " . $personId . " INDI\n";
        
        // Name
        $indi .= "1 NAME " . $person['first_name'] . " /" . $person['last_name'] . "/\n";
        $indi .= "2 GIVN " . $person['first_name'] . "\n";
        $indi .= "2 SURN " . $person['last_name'] . "\n";
        
        // Geburtsname (falls vorhanden)
        if (!empty($person['maiden_name'])) {
            $indi .= "2 _MARNM " . $person['first_name'] . " /" . $person['maiden_name'] . "/\n";
        }
        
        // Geschlecht
        if (!empty($person['gender'])) {
            $gender = strtoupper($person['gender']);
            if ($gender == 'M' || $gender == 'F') {
                $indi .= "1 SEX " . $gender . "\n";
            } else if ($gender == 'X') {
                $indi .= "1 SEX U\n"; // Unknown in GEDCOM
            }
        }
        
        // Geburt
        if (!empty($person['birth_date']) || !empty($person['birth_place'])) {
            $indi .= "1 BIRT\n";
            if (!empty($person['birth_date'])) {
                $indi .= "2 DATE " . $this->formatGedcomDate($person['birth_date']) . "\n";
            }
            if (!empty($person['birth_place'])) {
                $indi .= "2 PLAC " . $person['birth_place'] . "\n";
            }
        }
        
        // Tod
        if (!empty($person['death_date']) || !empty($person['death_place'])) {
            $indi .= "1 DEAT\n";
            if (!empty($person['death_date'])) {
                $indi .= "2 DATE " . $this->formatGedcomDate($person['death_date']) . "\n";
            }
            if (!empty($person['death_place'])) {
                $indi .= "2 PLAC " . $person['death_place'] . "\n";
            }
        }
        
        // Beruf
        if (!empty($person['occupation'])) {
            $indi .= "1 OCCU " . $person['occupation'] . "\n";
        }
        
        // Biografie als Notiz
        if (!empty($person['biography'])) {
            $bioLines = $this->splitLongText($person['biography'], 248);
            $indi .= "1 NOTE " . $bioLines[0] . "\n";
            for ($i = 1; $i < count($bioLines); $i++) {
                $indi .= "2 CONT " . $bioLines[$i] . "\n";
            }
        }
        
        // Events der Person hinzufügen
        $personEvents = array_filter($events, function($e) use ($person) {
            return $e['person_id'] == $person['id'];
        });
        
        foreach ($personEvents as $event) {
            // Überspringen wenn bereits als Geburt/Tod verarbeitet
            if (in_array($event['event_type'], ['birth', 'death'])) {
                continue;
            }
            
            $indi .= $this->createEventRecord($event);
        }
        
        // Familie als Ehepartner (FAMS)
        $spouseRelations = array_filter($relationships, function($r) use ($person) {
            return ($r['relationship_type'] == 'spouse' || $r['relationship_type'] == 'partner') && 
                   ($r['person1_id'] == $person['id'] || $r['person2_id'] == $person['id']);
        });
        
        foreach ($spouseRelations as $relation) {
            $famId = $this->getFamilyId($relation);
            $indi .= "1 FAMS @F" . $famId . "@\n";
        }
        
        // Familie als Kind (FAMC)
        $childRelations = array_filter($relationships, function($r) use ($person) {
            return $r['relationship_type'] == 'parent' && $r['person2_id'] == $person['id'];
        });
        
        if (count($childRelations) > 0) {
            // Gruppiere Eltern um Familie zu finden
            $parents = [];
            foreach ($childRelations as $relation) {
                $parents[] = $relation['person1_id'];
            }
            
            // Finde die Familie mit diesen Eltern
            $famId = $this->findFamilyByParents($parents, $relationships);
            if ($famId) {
                $indi .= "1 FAMC @F" . $famId . "@\n";
            }
        }
        
        // Änderungsdatum
        if (!empty($person['updated_at'])) {
            $indi .= "1 CHAN\n";
            $indi .= "2 DATE " . $this->formatGedcomDate($person['updated_at']) . "\n";
        }
        
        return $indi;
    }
    
    /**
     * FAM Record für eine Familie erstellen
     */
    private function createFamilyRecord($familyId, $family)
    {
        $fam = "";
        $fam .= "0 @F" . $familyId . "@ FAM\n";
        
        // Ehemann
        if (isset($family['husband'])) {
            $fam .= "1 HUSB @I" . $family['husband'] . "@\n";
        }
        
        // Ehefrau
        if (isset($family['wife'])) {
            $fam .= "1 WIFE @I" . $family['wife'] . "@\n";
        }
        
        // Kinder
        if (isset($family['children']) && count($family['children']) > 0) {
            foreach ($family['children'] as $childId) {
                $fam .= "1 CHIL @I" . $childId . "@\n";
            }
        }
        
        // Hochzeit (falls vorhanden)
        if (isset($family['marriage_date']) || isset($family['marriage_place'])) {
            $fam .= "1 MARR\n";
            if (isset($family['marriage_date'])) {
                $fam .= "2 DATE " . $this->formatGedcomDate($family['marriage_date']) . "\n";
            }
            if (isset($family['marriage_place'])) {
                $fam .= "2 PLAC " . $family['marriage_place'] . "\n";
            }
        }
        
        // Scheidung (falls vorhanden)
        if (isset($family['divorce_date'])) {
            $fam .= "1 DIV\n";
            $fam .= "2 DATE " . $this->formatGedcomDate($family['divorce_date']) . "\n";
        }
        
        return $fam;
    }
    
    /**
     * Event Record erstellen
     */
    private function createEventRecord($event)
    {
        $eventRecord = "";
        
        // Event-Typ zu GEDCOM Tag mappen
        $gedcomTag = $this->mapEventTypeToGedcom($event['event_type']);
        
        if ($gedcomTag) {
            $eventRecord .= "1 " . $gedcomTag . "\n";
            
            if (!empty($event['event_date'])) {
                $eventRecord .= "2 DATE " . $this->formatGedcomDate($event['event_date']) . "\n";
            }
            
            if (!empty($event['event_place'])) {
                $eventRecord .= "2 PLAC " . $event['event_place'] . "\n";
            }
            
            if (!empty($event['description'])) {
                $noteLines = $this->splitLongText($event['description'], 248);
                $eventRecord .= "2 NOTE " . $noteLines[0] . "\n";
                for ($i = 1; $i < count($noteLines); $i++) {
                    $eventRecord .= "3 CONT " . $noteLines[$i] . "\n";
                }
            }
        }
        
        return $eventRecord;
    }
    
    /**
     * Familien aus Beziehungen erstellen
     */
    private function createFamilies($relationships, $persons)
    {
        $families = [];
        $familyCounter = 1;
        
        // Ehepartner-Beziehungen durchgehen
        $spouseRelations = array_filter($relationships, function($r) {
            return $r['relationship_type'] == 'spouse' || $r['relationship_type'] == 'partner';
        });
        
        $processedPairs = [];
        
        foreach ($spouseRelations as $relation) {
            // Vermeide doppelte Familien (A-B ist gleich B-A)
            $pairKey = min($relation['person1_id'], $relation['person2_id']) . '-' . 
                      max($relation['person1_id'], $relation['person2_id']);
            
            if (in_array($pairKey, $processedPairs)) {
                continue;
            }
            $processedPairs[] = $pairKey;
            
            $family = [];
            
            // Geschlecht der Personen bestimmen für HUSB/WIFE
            $person1 = $this->findPersonById($persons, $relation['person1_id']);
            $person2 = $this->findPersonById($persons, $relation['person2_id']);
            
            if ($person1 && $person2) {
                if (strtoupper($person1['gender'] ?? '') == 'M') {
                    $family['husband'] = $person1['id'];
                    $family['wife'] = $person2['id'];
                } else if (strtoupper($person2['gender'] ?? '') == 'M') {
                    $family['husband'] = $person2['id'];
                    $family['wife'] = $person1['id'];
                } else {
                    // Bei gleichem oder unbekanntem Geschlecht: erste Person = HUSB
                    $family['husband'] = $person1['id'];
                    $family['wife'] = $person2['id'];
                }
            }
            
            // Hochzeitsdatum
            if (!empty($relation['start_date'])) {
                $family['marriage_date'] = $relation['start_date'];
            }
            
            // Scheidung
            if (!empty($relation['end_date'])) {
                $family['divorce_date'] = $relation['end_date'];
            }
            
            // Kinder dieser Familie finden
            $family['children'] = $this->findChildrenOfFamily($family, $relationships);
            
            $families[$familyCounter] = $family;
            $familyCounter++;
        }
        
        // Alleinerziehende mit Kindern
        $parentRelations = array_filter($relationships, function($r) {
            return $r['relationship_type'] == 'parent';
        });
        
        $singleParents = [];
        foreach ($parentRelations as $relation) {
            $parentId = $relation['person1_id'];
            
            // Prüfe ob dieser Elternteil bereits in einer Familie ist
            $isInFamily = false;
            foreach ($families as $family) {
                if ((isset($family['husband']) && $family['husband'] == $parentId) ||
                    (isset($family['wife']) && $family['wife'] == $parentId)) {
                    $isInFamily = true;
                    break;
                }
            }
            
            if (!$isInFamily && !in_array($parentId, $singleParents)) {
                $singleParents[] = $parentId;
                
                $family = [];
                $parent = $this->findPersonById($persons, $parentId);
                
                if ($parent) {
                    if (strtoupper($parent['gender'] ?? '') == 'M') {
                        $family['husband'] = $parent['id'];
                    } else {
                        $family['wife'] = $parent['id'];
                    }
                    
                    // Alle Kinder dieses Elternteils finden
                    $family['children'] = array_map(function($r) {
                        return $r['person2_id'];
                    }, array_filter($parentRelations, function($r) use ($parentId) {
                        return $r['person1_id'] == $parentId;
                    }));
                    
                    if (count($family['children']) > 0) {
                        $families[$familyCounter] = $family;
                        $familyCounter++;
                    }
                }
            }
        }
        
        return $families;
    }
    
    /**
     * Kinder einer Familie finden
     */
    private function findChildrenOfFamily($family, $relationships)
    {
        $children = [];
        
        // Finde alle Kinder die beide Eltern haben
        $parentRelations = array_filter($relationships, function($r) {
            return $r['relationship_type'] == 'parent';
        });
        
        foreach ($parentRelations as $relation) {
            $childId = $relation['person2_id'];
            $parentId = $relation['person1_id'];
            
            // Prüfe ob das Kind zu dieser Familie gehört
            if ((isset($family['husband']) && $parentId == $family['husband']) ||
                (isset($family['wife']) && $parentId == $family['wife'])) {
                if (!in_array($childId, $children)) {
                    $children[] = $childId;
                }
            }
        }
        
        return $children;
    }
    
    /**
     * Familie anhand der Eltern finden
     */
    private function findFamilyByParents($parentIds, $relationships)
    {
        static $familyCache = null;
        
        if ($familyCache === null) {
            // Cache erstellen bei erstem Aufruf
            $familyCache = [];
            $familyCounter = 1;
            
            // Ähnliche Logik wie createFamilies, aber vereinfacht
            $spouseRelations = array_filter($relationships, function($r) {
                return $r['relationship_type'] == 'spouse' || $r['relationship_type'] == 'partner';
            });
            
            foreach ($spouseRelations as $relation) {
                $familyCache[$familyCounter] = [
                    $relation['person1_id'],
                    $relation['person2_id']
                ];
                $familyCounter++;
            }
        }
        
        // Suche Familie mit diesen Eltern
        foreach ($familyCache as $famId => $parents) {
            if (count(array_intersect($parentIds, $parents)) == count($parentIds)) {
                return $famId;
            }
        }
        
        return null;
    }
    
    /**
     * Familie ID für eine Beziehung ermitteln
     */
    private function getFamilyId($relation)
    {
        // Einfache Berechnung basierend auf IDs
        $id1 = min($relation['person1_id'], $relation['person2_id']);
        $id2 = max($relation['person1_id'], $relation['person2_id']);
        
        // Verwende eine einfache Hash-Funktion für konsistente IDs
        return abs(crc32($id1 . '-' . $id2)) % 10000;
    }
    
    /**
     * Person anhand ID finden
     */
    private function findPersonById($persons, $id)
    {
        foreach ($persons as $person) {
            if ($person['id'] == $id) {
                return $person;
            }
        }
        return null;
    }
    
    /**
     * Event-Typ zu GEDCOM Tag mappen
     */
    private function mapEventTypeToGedcom($eventType)
    {
        $mapping = [
            'baptism'     => 'BAPM',
            'marriage'    => 'MARR',
            'divorce'     => 'DIV',
            'education'   => 'EDUC',
            'employment'  => 'OCCU',
            'military'    => '_MILI',
            'residence'   => 'RESI',
            'immigration' => 'IMMI',
            'emigration'  => 'EMIG',
            'naturalization' => 'NATU',
            'census'      => 'CENS',
            'will'        => 'WILL',
            'probate'     => 'PROB',
            'burial'      => 'BURI',
            'cremation'   => 'CREM',
            'confirmation' => 'CONF',
            'graduation'  => 'GRAD',
            'retirement'  => 'RETI',
            'custom'      => 'EVEN'
        ];
        
        return $mapping[$eventType] ?? 'EVEN';
    }
    
    /**
     * Datum in GEDCOM Format konvertieren
     */
    private function formatGedcomDate($date)
    {
        if (empty($date)) {
            return '';
        }
        
        // Konvertiere zu Timestamp
        $timestamp = strtotime($date);
        if (!$timestamp) {
            return $date; // Fallback auf Original
        }
        
        // GEDCOM Format: DD MMM YYYY
        $months = [
            'Jan' => 'JAN', 'Feb' => 'FEB', 'Mar' => 'MAR', 
            'Apr' => 'APR', 'May' => 'MAY', 'Jun' => 'JUN',
            'Jul' => 'JUL', 'Aug' => 'AUG', 'Sep' => 'SEP', 
            'Oct' => 'OCT', 'Nov' => 'NOV', 'Dec' => 'DEC'
        ];
        
        $formatted = date('d M Y', $timestamp);
        
        // Monat in Großbuchstaben
        foreach ($months as $eng => $gedcom) {
            $formatted = str_replace($eng, $gedcom, $formatted);
        }
        
        return $formatted;
    }
    
    /**
     * Langen Text in Zeilen aufteilen
     */
    private function splitLongText($text, $maxLength = 248)
    {
        // Entferne Zeilenumbrüche und ersetze sie durch Leerzeichen
        $text = str_replace(["\r\n", "\r", "\n"], ' ', $text);
        
        // Teile in Chunks
        $lines = [];
        while (strlen($text) > 0) {
            if (strlen($text) <= $maxLength) {
                $lines[] = $text;
                break;
            }
            
            // Finde letztes Leerzeichen vor maxLength
            $lastSpace = strrpos(substr($text, 0, $maxLength), ' ');
            if ($lastSpace === false) {
                $lastSpace = $maxLength;
            }
            
            $lines[] = substr($text, 0, $lastSpace);
            $text = substr($text, $lastSpace + 1);
        }
        
        return $lines;
    }
}