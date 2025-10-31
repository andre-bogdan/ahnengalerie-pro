<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PersonModel;
use App\Models\PhotoModel;
use App\Models\RelationshipModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Get statistics
        $personModel = new PersonModel();
        $photoModel = new PhotoModel();
        $relationshipModel = new RelationshipModel();
        $userModel = new UserModel();

        // Nächste Geburtstage
        $upcomingBirthdays = $this->getUpcomingBirthdays(5);

        $data = [
            'title' => 'Dashboard',
            'stats' => [
                'persons' => $personModel->countAll(),
                'photos' => $photoModel->countAll(),
                'relationships' => $relationshipModel->countAll(),
                'users' => $userModel->countAll(),
            ],
            'recent_persons' => $personModel->orderBy('created_at', 'DESC')->limit(5)->find(),
            'recently_updated' => $personModel
                ->where('created_at != updated_at')  // Nur Personen die wirklich geändert wurden
                ->orderBy('updated_at', 'DESC')
                ->limit(5)
                ->find(),
            'upcomingBirthdays' => $upcomingBirthdays,
        ];

        return view('dashboard/index', $data);
    }

    /**
     * Nächste Geburtstage berechnen
     */
    private function getUpcomingBirthdays(int $limit = 5): array
    {
        $personModel = new \App\Models\PersonModel();

        $persons = $personModel
            ->select('id, first_name, last_name, birth_date, death_date')
            ->where('birth_date IS NOT NULL')
            // ENTFERNT: ->where('death_date IS NULL')
            ->findAll();

        $today = new \DateTime();
        $currentYear = (int) $today->format('Y');

        $birthdays = [];

        foreach ($persons as $person) {
            $birthDate = new \DateTime($person['birth_date']);

            // Geburtstag auf aktuelles Jahr setzen
            $nextBirthday = new \DateTime($currentYear . '-' . $birthDate->format('m-d'));

            // Wenn Geburtstag dieses Jahr schon vorbei, nächstes Jahr nehmen
            if ($nextBirthday < $today) {
                $nextBirthday->modify('+1 year');
            }

            // Tage bis Geburtstag
            $daysUntil = (int) $today->diff($nextBirthday)->days;

            // Alter berechnen
            $age = (int) $nextBirthday->format('Y') - (int) $birthDate->format('Y');

            $birthdays[] = [
                'person' => $person,
                'next_birthday' => $nextBirthday,
                'days_until' => $daysUntil,
                'age' => $age,
                'is_deceased' => !empty($person['death_date']),  // NEU
            ];
        }

        // Nach Tagen sortieren
        usort($birthdays, function ($a, $b) {
            return $a['days_until'] - $b['days_until'];
        });

        return array_slice($birthdays, 0, $limit);
    }
}