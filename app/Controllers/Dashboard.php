<?php

namespace App\Controllers;

use App\Models\PersonModel;
use App\Models\PhotoModel;
use App\Models\RelationshipModel;
use App\Models\UserModel;
use App\Models\EventModel;

class Dashboard extends BaseController
{
    protected $personModel;
    protected $photoModel;
    protected $relationshipModel;
    protected $userModel;
    protected $eventModel;
    protected $db;

    public function __construct()
    {
        $this->personModel = new PersonModel();
        $this->photoModel = new PhotoModel();
        $this->relationshipModel = new RelationshipModel();
        $this->userModel = new UserModel();
        $this->eventModel = new EventModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Statistics
        $stats = [
            'persons' => $this->personModel->countAll(),
            'photos' => $this->photoModel->countAll(),
            'relationships' => $this->relationshipModel->countAll(),
            'users' => $this->userModel->countAll(),
        ];

        // Recent persons (neu hinzugefügt)
        $recent_persons = $this->personModel
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->find();

        // Recently updated persons - MIT User-Info!
        $recently_updated = $this->db->table('persons p')
            ->select('p.*, u.username as updated_by_name')
            ->join('users u', 'u.id = p.updated_by', 'left')
            ->where('p.updated_at !=', null)
            ->where('p.updated_at !=', 'p.created_at', false)  // Nur wirklich geänderte
            ->orderBy('p.updated_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // Upcoming birthdays
        $upcomingBirthdays = $this->getUpcomingBirthdays();

        $data = [
            'title' => 'Dashboard',
            'stats' => $stats,
            'recent_persons' => $recent_persons,
            'recently_updated' => $recently_updated,
            'upcomingBirthdays' => $upcomingBirthdays
        ];

        return view('dashboard/index', $data);
    }

    private function getUpcomingBirthdays(int $limit = 5): array
    {
        $persons = $this->personModel
            ->where('birth_date IS NOT NULL')
            ->findAll();

        $birthdays = [];
        $tz = new \DateTimeZone(date_default_timezone_get());
        $today = new \DateTimeImmutable('today', $tz); // 00:00:00
        $currentYear = (int) $today->format('Y');

        foreach ($persons as $person) {
            if (empty($person['birth_date'])) {
                continue;
            }

            $birthDate = new \DateTimeImmutable($person['birth_date'], $tz);
            $month = (int) $birthDate->format('m');
            $day = (int) $birthDate->format('d');

            // Nächster Geburtstag (dieses Jahr) auf 00:00 setzen
            // Achtung: 29.02. kann in Nicht-Schaltjahren invalid sein -> try/catch
            try {
                $nextBirthday = (new \DateTimeImmutable('00:00:00', $tz))
                    ->setDate($currentYear, $month, $day);
            } catch (\Exception $e) {
                // Fallback für 29.02.: auf 28.02. feiern (oder 01.03., je nach Wunsch)
                if ($month === 2 && $day === 29) {
                    $nextBirthday = (new \DateTimeImmutable('00:00:00', $tz))
                        ->setDate($currentYear, 2, 28);
                } else {
                    continue; // ungewöhnliches Datum -> überspringen
                }
            }

            if ($nextBirthday < $today) {
                // Geburtstag in diesem Jahr war schon -> nächstes Jahr
                $nextBirthday = $nextBirthday->modify('+1 year');
            }

            // Exakte Tage-Differenz (nur ganze Tage, da beide 00:00 Uhr)
            $daysUntil = (int) $today->diff($nextBirthday)->days;

            if ($daysUntil <= 30) { // inkl. heute (0 Tage) bis 30 Tage
                // Alter am kommenden Geburtstag
                $age = $nextBirthday->diff($birthDate)->y;

                $birthdays[] = [
                    'person' => $person,
                    'next_birthday' => $nextBirthday,
                    'days_until' => $daysUntil,
                    'age' => $age,
                    'is_deceased' => !empty($person['death_date']),
                ];
            }
        }

        usort($birthdays, fn($a, $b) => $a['days_until'] <=> $b['days_until']);

        return array_slice($birthdays, 0, $limit);
    }
}