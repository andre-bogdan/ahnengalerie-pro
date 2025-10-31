<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'person_id',
        'event_type',
        'event_date',
        'event_place',
        'description',
        'related_person_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'person_id' => 'required|integer',
        'event_type' => 'required|string|max_length[50]',
        'event_date' => 'permit_empty|valid_date',
        'event_place' => 'permit_empty|string|max_length[255]',
        'description' => 'permit_empty|string',
        'related_person_id' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'person_id' => [
            'required' => 'Person-ID ist erforderlich.',
            'integer' => 'Person-ID muss eine Zahl sein.'
        ],
        'event_type' => [
            'required' => 'Event-Typ ist erforderlich.',
            'max_length' => 'Event-Typ darf maximal 50 Zeichen lang sein.'
        ],
        'event_date' => [
            'valid_date' => 'Bitte geben Sie ein gültiges Datum ein.'
        ]
    ];

    /**
     * Alle Events einer Person abrufen (chronologisch sortiert)
     */
    public function getEventsByPerson(int $personId): array
    {
        return $this->where('person_id', $personId)
                    ->orderBy('event_date', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Event mit verknüpfter Person abrufen
     */
    public function getEventWithRelatedPerson(int $eventId): ?array
    {
        $event = $this->find($eventId);
        
        if (!$event) {
            return null;
        }

        // Verknüpfte Person laden (falls vorhanden)
        if ($event['related_person_id']) {
            $personModel = new PersonModel();
            $relatedPerson = $personModel->find($event['related_person_id']);
            $event['related_person'] = $relatedPerson;
        }

        return $event;
    }

    /**
     * Event-Typ-Label abrufen
     */
    public static function getEventTypeLabel(string $type): string
    {
        $labels = [
            'birth' => 'Geburt',
            'death' => 'Tod',
            'baptism' => 'Taufe',
            'marriage' => 'Hochzeit',
            'divorce' => 'Scheidung',
            'education' => 'Ausbildung/Studium',
            'employment' => 'Beschäftigung',
            'military' => 'Militärdienst',
            'residence' => 'Umzug/Wohnort',
            'immigration' => 'Einwanderung',
            'other' => 'Sonstiges'
        ];

        return $labels[$type] ?? $type;
    }

    /**
     * Event-Typ-Icon abrufen
     */
    public static function getEventTypeIcon(string $type): string
    {
        $icons = [
            'birth' => 'bi-calendar-event',
            'death' => 'bi-flower1',
            'baptism' => 'bi-droplet',
            'marriage' => 'bi-heart-fill',
            'divorce' => 'bi-heart-break',
            'education' => 'bi-book',
            'employment' => 'bi-briefcase',
            'military' => 'bi-shield',
            'residence' => 'bi-house',
            'immigration' => 'bi-globe',
            'other' => 'bi-calendar-check'
        ];

        return $icons[$type] ?? 'bi-circle';
    }
}