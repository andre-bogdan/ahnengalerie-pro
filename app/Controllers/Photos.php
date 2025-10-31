<?php

namespace App\Controllers;

use App\Models\PhotoModel;
use App\Models\PersonModel;

class Photos extends BaseController
{
    protected $photoModel;
    protected $personModel;

    public function __construct()
    {
        $this->photoModel = new PhotoModel();
        $this->personModel = new PersonModel();
    }

    /**
     * Galerie-Hauptseite
     */
    public function index()
    {
        // Filter aus Query-Parametern
        $filters = [
            'person_id' => $this->request->getGet('person'),
            'year' => $this->request->getGet('year'),
            'location' => $this->request->getGet('location'),
            'search' => $this->request->getGet('search'),
            'sort' => $this->request->getGet('sort') ?? 'newest'
        ];

        // Pagination
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        // AJAX Request für Infinite Scroll?
        $isAjax = $this->request->isAJAX();

        // Fotos abrufen mit Filtern
        $photos = $this->getFilteredPhotos($filters, $perPage, $offset);
        $hasMore = count($photos) === $perPage;

        // Wenn AJAX, nur JSON zurückgeben
        if ($isAjax) {
            return $this->response->setJSON([
                'photos' => $photos,
                'hasMore' => $hasMore,
                'nextPage' => $page + 1
            ]);
        }

        // Alle Personen für Filter-Dropdown
        $persons = $this->personModel
            ->select('persons.id, first_name, last_name')
            ->orderBy('last_name, first_name')
            ->findAll();

        // Jahre für Filter (aus date_taken)
        $years = $this->photoModel
            ->select('strftime("%Y", date_taken) as year')
            ->distinct()
            ->where('date_taken IS NOT NULL')
            ->orderBy('year', 'DESC')
            ->findAll();

        // Orte für Filter
        $locations = $this->photoModel
            ->select('location')
            ->distinct()
            ->where('location IS NOT NULL')
            ->where('location !=', '')
            ->orderBy('location')
            ->findAll();

        // Statistiken
        $stats = [
            'total' => $this->photoModel->countAll(),
            'filtered' => $this->getFilteredPhotosCount($filters),
            'persons_with_photos' => $this->photoModel
                ->select('person_id')
                ->distinct()
                ->countAllResults()
        ];

        return view('photos/index', [
            'title' => 'Foto-Galerie',
            'photos' => $photos,
            'persons' => $persons,
            'years' => $years,
            'locations' => $locations,
            'filters' => $filters,
            'stats' => $stats,
            'hasMore' => $hasMore
        ]);
    }

    /**
     * Einzelnes Foto anzeigen
     */
    public function view($id)
    {
        $photo = $this->photoModel
            ->select('photos.*, persons.first_name, persons.last_name, users.username as uploaded_by_name')
            ->join('persons', 'persons.id = photos.person_id')
            ->join('users', 'users.id = photos.uploaded_by', 'left')
            ->find($id);

        if (!$photo) {
            return redirect()->to('/photos')->with('error', 'Foto nicht gefunden');
        }

        // Vorheriges & nächstes Foto (Navigation)
        $prevPhoto = $this->photoModel
            ->where('id <', $id)
            ->orderBy('id', 'DESC')
            ->first();

        $nextPhoto = $this->photoModel
            ->where('id >', $id)
            ->orderBy('id', 'ASC')
            ->first();

        return view('photos/view', [
            'title' => $photo['title'] ?? 'Foto',
            'photo' => $photo,
            'prevPhoto' => $prevPhoto,
            'nextPhoto' => $nextPhoto
        ]);
    }

    /**
     * Fotos mit Filtern abrufen
     */
    private function getFilteredPhotos($filters, $limit = null, $offset = 0)
    {
        $builder = $this->photoModel
            ->select('photos.*, persons.first_name, persons.last_name')
            ->join('persons', 'persons.id = photos.person_id');

        // Filter: Person
        if (!empty($filters['person_id'])) {
            $builder->where('photos.person_id', $filters['person_id']);
        }

        // Filter: Jahr
        if (!empty($filters['year'])) {
            $builder->where('strftime("%Y", photos.date_taken)', $filters['year']);
        }

        // Filter: Ort
        if (!empty($filters['location'])) {
            $builder->where('photos.location', $filters['location']);
        }

        // Filter: Suche (Titel, Beschreibung)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $builder->groupStart()
                ->like('photos.title', $search)
                ->orLike('photos.description', $search)
                ->orLike('CONCAT(persons.first_name, " ", persons.last_name)', $search)
                ->groupEnd();
        }

        // Sortierung
        switch ($filters['sort']) {
            case 'oldest':
                $builder->orderBy('photos.created_at', 'ASC');
                break;
            case 'date_taken_asc':
                $builder->orderBy('photos.date_taken', 'ASC');
                break;
            case 'date_taken_desc':
                $builder->orderBy('photos.date_taken', 'DESC');
                break;
            case 'person':
                $builder->orderBy('persons.last_name, persons.first_name, photos.created_at DESC');
                break;
            default: // newest
                $builder->orderBy('photos.created_at', 'DESC');
        }

        // Pagination
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    // In app/Controllers/Persons.php hinzufügen:

    /**
     * Foto als Hauptfoto markieren
     */
    public function setPrimaryPhoto($photoId)
    {
        $photoModel = new \App\Models\PhotoModel();

        // Foto abrufen
        $photo = $photoModel->find($photoId);

        if (!$photo) {
            return redirect()->back()->with('error', 'Foto nicht gefunden');
        }

        // Alle Fotos dieser Person auf is_primary = 0 setzen
        $photoModel->where('person_id', $photo['person_id'])
            ->set(['is_primary' => 0])
            ->update();

        // Dieses Foto als Hauptfoto markieren
        $photoModel->update($photoId, ['is_primary' => 1]);

        // Auch in persons.primary_photo_id aktualisieren
        $personModel = new \App\Models\PersonModel();
        $personModel->update($photo['person_id'], ['primary_photo_id' => $photoId]);

        return redirect()->back()->with('success', 'Hauptfoto erfolgreich gesetzt');
    }

    /**
     * Anzahl der gefilterten Fotos
     */
    private function getFilteredPhotosCount($filters)
    {
        $builder = $this->photoModel
            ->select('photos.id')
            ->join('persons', 'persons.id = photos.person_id');

        // Filter: Person
        if (!empty($filters['person_id'])) {
            $builder->where('photos.person_id', $filters['person_id']);
        }

        // Filter: Jahr
        if (!empty($filters['year'])) {
            $builder->where('strftime("%Y", photos.date_taken)', $filters['year']);
        }

        // Filter: Ort
        if (!empty($filters['location'])) {
            $builder->where('photos.location', $filters['location']);
        }

        // Filter: Suche
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $builder->groupStart()
                ->like('photos.title', $search)
                ->orLike('photos.description', $search)
                ->orLike('CONCAT(persons.first_name, " ", persons.last_name)', $search)
                ->groupEnd();
        }

        return $builder->countAllResults();
    }
}