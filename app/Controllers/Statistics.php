<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PersonModel;
use App\Models\RelationshipModel;

class Statistics extends BaseController
{
    /**
     * Statistiken Dashboard - VEREINFACHTE VERSION
     */
    public function index()
    {
        $personModel = new PersonModel();
        $relationshipModel = new RelationshipModel();

        // Nur EINFACHE Zählungen - KEINE Schleifen!
        $totalPersons = $personModel->countAll();

        $maleCount = $personModel->where('gender', 'm')->countAllResults();
        $femaleCount = $personModel->where('gender', 'f')->countAllResults();
        $diverseCount = $personModel->where('gender', 'x')->countAllResults();
        $unknownCount = $personModel->where('gender', null)->countAllResults();

        $marriages = $relationshipModel->where('relationship_type', 'spouse')->countAllResults();
        $partnerships = $relationshipModel->where('relationship_type', 'partner')->countAllResults();

        // Chart-Daten vorbereiten
        $genderChartData = [
            'labels' => ['Männlich', 'Weiblich', 'Divers', 'Unbekannt'],
            'data' => [$maleCount, $femaleCount, $diverseCount, $unknownCount],
            'colors' => ['#6495ED', '#FF69B4', '#9C27B0', '#9E9E9E']
        ];

        // Top-Listen
        $topBirthPlaces = $this->getTopBirthPlaces(5);
        $topOccupations = $this->getTopOccupations(5);

        // Neueste Personen
        $recentPersons = $this->getRecentPersons(10);

        // Altersstatistiken
        $ageStats = $this->getSimpleAgeStats();

        // Geburten pro Jahrzehnt
        $birthsByDecade = $this->getBirthsByDecade();

        $data = [
            'title' => 'Statistiken',
            'totalPersons' => $totalPersons,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'diverseCount' => $diverseCount,
            'unknownCount' => $unknownCount,
            'marriages' => $marriages,
            'partnerships' => $partnerships,
            'genderChartData' => $genderChartData,
            'topBirthPlaces' => $topBirthPlaces,
            'topOccupations' => $topOccupations,
            'recentPersons' => $recentPersons,
            'ageStats' => $ageStats,
            'birthsByDecade' => $birthsByDecade,
        ];

        return view('statistics/index', $data);
    }

    /**
     * Top Geburtsorte
     */
    private function getTopBirthPlaces(int $limit = 5): array
    {
        $db = \Config\Database::connect();

        $query = $db->query(
            "SELECT birth_place, COUNT(*) as count 
         FROM persons 
         WHERE birth_place IS NOT NULL AND birth_place != '' 
         GROUP BY birth_place 
         ORDER BY count DESC 
         LIMIT ?",
            [$limit]
        );

        return $query->getResultArray();
    }

    /**
     * Top Berufe
     */
    private function getTopOccupations(int $limit = 5): array
    {
        $db = \Config\Database::connect();

        $query = $db->query(
            "SELECT occupation, COUNT(*) as count 
         FROM persons 
         WHERE occupation IS NOT NULL AND occupation != '' 
         GROUP BY occupation 
         ORDER BY count DESC 
         LIMIT ?",
            [$limit]
        );

        return $query->getResultArray();
    }

    /**
     * Neueste Personen (OHNE Events - vermeidet Schleifen)
     */
    private function getRecentPersons(int $limit = 10): array
    {
        $personModel = new PersonModel();

        return $personModel
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Einfache Altersstatistiken (nur Zählungen)
     */
    private function getSimpleAgeStats(): array
    {
        $personModel = new PersonModel();

        // Lebende (haben Geburtsdatum aber kein Todesdatum)
        $living = $personModel
            ->where('birth_date IS NOT NULL')
            ->where('death_date IS NULL')
            ->countAllResults();

        // Verstorbene (haben Todesdatum)
        $deceased = $personModel
            ->where('death_date IS NOT NULL')
            ->countAllResults();

        // Personen mit Geburtsdatum
        $withBirthDate = $personModel
            ->where('birth_date IS NOT NULL')
            ->countAllResults();

        return [
            'living' => $living,
            'deceased' => $deceased,
            'withBirthDate' => $withBirthDate,
        ];
    }

    /**
     * Geburten pro Jahrzehnt
     */
    private function getBirthsByDecade(): array
    {
        $db = \Config\Database::connect();

        $query = $db->query(
            "SELECT 
            (CAST(strftime('%Y', birth_date) AS INTEGER) / 10) * 10 as decade,
            COUNT(*) as count
         FROM persons 
         WHERE birth_date IS NOT NULL
         GROUP BY decade
         ORDER BY decade ASC"
        );

        $results = $query->getResultArray();

        $decades = [];
        foreach ($results as $row) {
            $decades[$row['decade']] = $row['count'];
        }

        return $decades;
    }
}