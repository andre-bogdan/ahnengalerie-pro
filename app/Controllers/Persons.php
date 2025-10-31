<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PersonModel;
use App\Models\PhotoModel;
use App\Models\RelationshipModel;
use App\Models\EventModel;

class Persons extends BaseController
{
    protected $personModel;
    protected $photoModel;
    protected $relationshipModel;

    public function __construct()
    {
        $this->personModel = new PersonModel();
        $this->photoModel = new PhotoModel();
        $this->relationshipModel = new RelationshipModel();
    }

    /**
     * Display list of all persons
     */
    public function index()
    {
        // Get search parameters
        $search = $this->request->getGet('search');
        $gender = $this->request->getGet('gender');
        $sortBy = $this->request->getGet('sort') ?? 'last_name';
        $sortOrder = $this->request->getGet('order') ?? 'ASC';

        // Build query
        $builder = $this->personModel;

        // Search filter
        if ($search) {
            $builder = $builder->groupStart()
                ->like('first_name', $search)
                ->orLike('last_name', $search)
                ->orLike('maiden_name', $search)
                ->groupEnd();
        }

        // Gender filter
        if ($gender && in_array($gender, ['m', 'f', 'x'])) {
            $builder = $builder->where('gender', $gender);
        }

        // Sorting
        $allowedSort = ['first_name', 'last_name', 'birth_date', 'created_at'];
        if (in_array($sortBy, $allowedSort)) {
            $builder = $builder->orderBy($sortBy, $sortOrder);
        }

        // Get persons
        $persons = $builder->findAll();

        $data = [
            'title' => 'Personen',
            'persons' => $persons,
            'search' => $search,
            'gender' => $gender,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ];

        return view('persons/index', $data);
    }

    /**
     * Display single person details
     */
    /**
     * Display single person details
     */
    /**
     * Display single person details
     */
    public function view($id)
    {
        $person = $this->personModel->find($id);

        if (!$person) {
            return redirect()->to('/persons')->with('error', 'Person nicht gefunden.');
        }

        // Get primary photo
        $photo = null;
        if ($person['primary_photo_id']) {
            $photo = $this->photoModel->find($person['primary_photo_id']);
        }

        // Get all relationships (simplified - no relationship_id needed in view)
        $parents = $this->getParentsForView($id);
        $children = $this->getChildrenForView($id);
        $spouses = $this->getSpousesForView($id);
        $siblings = $this->getSiblings($id);

        // Get all photos
        $photos = $this->photoModel->where('person_id', $id)
            ->orderBy('display_order', 'ASC')
            ->findAll();

        // NEU: Events laden
        $eventModel = new EventModel();
        $events = $eventModel->getEventsByPerson($id);

        // Events mit verknüpften Personen anreichern
        foreach ($events as &$event) {
            if ($event['related_person_id']) {
                $relatedPerson = $this->personModel->find($event['related_person_id']);
                $event['related_person'] = $relatedPerson;
            }
        }

        $data = [
            'title' => $person['first_name'] . ' ' . $person['last_name'],
            'person' => $person,
            'photo' => $photo,
            'parents' => $parents,
            'children' => $children,
            'spouses' => $spouses,
            'siblings' => $siblings,
            'photos' => $photos,
            'events' => $events,  // NEU
        ];

        return view('persons/view', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'title' => 'Person hinzufügen',
            'person' => null,
            'validation' => null,
        ];

        return view('persons/form', $data);
    }

    // Ersetze die handlePhotoUpload() Methode in app/Controllers/Persons.php (ca. Zeile 422):

    /**
     * Handle photo upload
     * KORRIGIERT: Prüft ob bereits Hauptfoto existiert
     */
    private function handlePhotoUpload($photo, $personId, $title = null)
    {
        // Validate file
        if (!$photo->isValid()) {
            return false;
        }

        // WICHTIG: MIME-Type und Size VOR dem Move holen!
        $mimeType = $photo->getMimeType();
        $fileSize = $photo->getSize();
        $originalName = $photo->getClientName();

        // Check file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowedTypes)) {
            return false;
        }

        // Check file size (max 5MB)
        if ($photo->getSizeByUnit('mb') > 5) {
            return false;
        }

        // Create upload directory in public/
        $uploadPath = FCPATH . 'uploads/persons/' . $personId;
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate unique filename
        $filename = uniqid() . '-' . $originalName;
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);

        // Move file
        $photo->move($uploadPath, $filename);

        $this->fixImageOrientation($uploadPath . '/' . $filename);

        // Create thumbnail
        $thumbnailName = 'thumb-' . $filename;
        $this->createThumbnail($uploadPath . '/' . $filename, $uploadPath . '/' . $thumbnailName);

        // KORRIGIERT: Prüfen ob bereits ein Hauptfoto existiert
        $existingPrimaryPhoto = $this->photoModel
            ->where('person_id', $personId)
            ->where('is_primary', 1)
            ->first();

        // Neues Foto ist nur Hauptfoto, wenn noch keines existiert
        $isPrimary = empty($existingPrimaryPhoto) ? 1 : 0;

        // Save to database
        $photoData = [
            'person_id' => $personId,
            'file_path' => 'persons/' . $personId . '/' . $filename,
            'thumbnail_path' => 'persons/' . $personId . '/' . $thumbnailName,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'title' => $title,
            'is_primary' => $isPrimary,  // KORRIGIERT: nicht mehr immer 1!
            'uploaded_by' => session()->get('user_id'),
        ];

        $photoId = $this->photoModel->insert($photoData);

        // Update person's primary_photo_id nur wenn dies das erste/Hauptfoto ist
        if ($isPrimary && $photoId) {
            $this->personModel->update($personId, ['primary_photo_id' => $photoId]);
        }

        return true;
    }


    // Füge diese NEUE Methode am Ende der Klasse hinzu (vor der letzten }):

    /**
     * Foto als Hauptfoto markieren
     */
    public function setPrimaryPhoto($photoId)
    {
        $photo = $this->photoModel->find($photoId);

        if (!$photo) {
            return redirect()->back()->with('error', 'Foto nicht gefunden');
        }

        $personId = $photo['person_id'];

        // Alle Fotos dieser Person auf is_primary = 0 setzen
        $this->photoModel->where('person_id', $personId)
            ->set(['is_primary' => 0])
            ->update();

        // Dieses Foto als Hauptfoto markieren
        $this->photoModel->update($photoId, ['is_primary' => 1]);

        // Auch in persons.primary_photo_id aktualisieren
        $this->personModel->update($personId, ['primary_photo_id' => $photoId]);

        return redirect()->back()->with('success', 'Hauptfoto erfolgreich gesetzt');
    }

    /**
     * Store new person
     * ERWEITERT: Foto-Titel übergeben
     */
    public function store()
    {
        // Validation rules
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'gender' => 'permit_empty|in_list[m,f,x]',
            'birth_date' => 'permit_empty|valid_date',
            'death_date' => 'permit_empty|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        // Prepare data
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'maiden_name' => $this->request->getPost('maiden_name'),
            'gender' => $this->request->getPost('gender'),
            'birth_date' => $this->request->getPost('birth_date') ?: null,
            'birth_place' => $this->request->getPost('birth_place'),
            'death_date' => $this->request->getPost('death_date') ?: null,
            'death_place' => $this->request->getPost('death_place'),
            'biography' => $this->request->getPost('biography'),
            'occupation' => $this->request->getPost('occupation'),
            'created_by' => session()->get('user_id'),
        ];

        // Insert person
        $personId = $this->personModel->insert($data);

        if (!$personId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Fehler beim Speichern der Person.');
        }

        // Handle photo upload (NEU: mit Titel!)
        $photo = $this->request->getFile('photo');
        $photoTitle = $this->request->getPost('photo_title');  // NEU: Titel holen

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $this->handlePhotoUpload($photo, $personId, $photoTitle);
        }

        return redirect()->to('/persons/view/' . $personId)
            ->with('success', 'Person erfolgreich hinzugefügt!');
    }


    /**
     * Show edit form
     */
    /**
     * Show edit form
     */
    public function edit($id)
    {
        $person = $this->personModel->find($id);

        if (!$person) {
            return redirect()->to('/persons')->with('error', 'Person nicht gefunden.');
        }

        // Get all relationships
        $parents = $this->getParents($id);
        $children = $this->getChildren($id);
        $spouses = $this->getSpouses($id);

        // Alle Fotos der Person laden
        $photos = $this->photoModel->where('person_id', $id)
            ->orderBy('is_primary', 'DESC')
            ->orderBy('display_order', 'ASC')
            ->findAll();

        // NEU: Events laden
        $eventModel = new EventModel();
        $events = $eventModel->getEventsByPerson($id);

        $data = [
            'title' => 'Person bearbeiten',
            'person' => $person,
            'validation' => null,
            'parents' => $parents,
            'children' => $children,
            'spouses' => $spouses,
            'photos' => $photos,
            'events' => $events,  // NEU
        ];

        return view('persons/form', $data);
    }


    /**
     * Update person
     * ERWEITERT: Foto-Titel übergeben
     */
    public function update($id)
    {
        $person = $this->personModel->find($id);

        if (!$person) {
            return redirect()->to('/persons')->with('error', 'Person nicht gefunden.');
        }

        // Validation rules
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'gender' => 'permit_empty|in_list[m,f,x]',
            'birth_date' => 'permit_empty|valid_date',
            'death_date' => 'permit_empty|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        // Prepare data
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'maiden_name' => $this->request->getPost('maiden_name'),
            'gender' => $this->request->getPost('gender'),
            'birth_date' => $this->request->getPost('birth_date') ?: null,
            'birth_place' => $this->request->getPost('birth_place'),
            'death_date' => $this->request->getPost('death_date') ?: null,
            'death_place' => $this->request->getPost('death_place'),
            'biography' => $this->request->getPost('biography'),
            'occupation' => $this->request->getPost('occupation'),
        ];

        // Update person
        if (!$this->personModel->update($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Fehler beim Aktualisieren der Person.');
        }

        // Handle photo upload (NEU: mit Titel!)
        $photo = $this->request->getFile('photo');
        $photoTitle = $this->request->getPost('photo_title');  // NEU: Titel holen

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $this->handlePhotoUpload($photo, $id, $photoTitle);
        }

        return redirect()->to('/persons/view/' . $id)
            ->with('success', 'Person erfolgreich aktualisiert!');
    }

    /**
     * Delete person
     */
    public function delete($id)
    {
        $person = $this->personModel->find($id);

        if (!$person) {
            return redirect()->to('/persons')->with('error', 'Person nicht gefunden.');
        }

        // Delete all photos first
        $photos = $this->photoModel->where('person_id', $id)->findAll();
        foreach ($photos as $photo) {
            $this->deletePhotoFiles($photo);
            $this->photoModel->delete($photo['id']);
        }

        // Delete person (CASCADE will delete relationships)
        $this->personModel->delete($id);

        return redirect()->to('/persons')
            ->with('success', 'Person erfolgreich gelöscht!');
    }

    /**
     * Delete single photo
     * NEU: Foto einzeln löschen
     */
    public function deletePhoto($photoId)
    {
        $photo = $this->photoModel->find($photoId);

        if (!$photo) {
            return redirect()->back()->with('error', 'Foto nicht gefunden.');
        }

        $personId = $photo['person_id'];

        // Dateien physisch löschen
        $uploadBase = FCPATH . 'uploads/';

        if (file_exists($uploadBase . $photo['file_path'])) {
            unlink($uploadBase . $photo['file_path']);
        }

        if ($photo['thumbnail_path'] && file_exists($uploadBase . $photo['thumbnail_path'])) {
            unlink($uploadBase . $photo['thumbnail_path']);
        }

        // War dies das Hauptfoto?
        if ($photo['is_primary']) {
            $this->personModel->update($personId, ['primary_photo_id' => null]);
        }

        // Aus Datenbank löschen
        $this->photoModel->delete($photoId);

        return redirect()->to('/persons/edit/' . $personId)
            ->with('success', 'Foto erfolgreich gelöscht!');
    }

    /**
     * API-Endpoint: Liefert Stammbaum-Daten im Vis.js Format
     * 
     * Gibt JSON zurück mit:
     * - nodes: Array von Personen (id, label, image, group)
     * - edges: Array von Beziehungen (from, to, label, arrows)
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getTreeData()
    {
        // 1. ALLE PERSONEN HOLEN
        $persons = $this->personModel->findAll();

        // 2. ALLE BEZIEHUNGEN HOLEN
        $relationships = $this->relationshipModel
            ->select('id, person1_id, person2_id, relationship_type, start_date')
            ->findAll();

        // 3. NODES ERSTELLEN (Personen)
        $nodes = [];
        foreach ($persons as $person) {
            // Foto-URL bestimmen (Thumbnail oder Platzhalter)
            $imageUrl = base_url('assets/img/no-photo.png'); // Fallback

            if ($person['primary_photo_id']) {
                $photo = $this->photoModel->find($person['primary_photo_id']);
                if ($photo && $photo['thumbnail_path']) {
                    $imageUrl = base_url($photo['thumbnail_path']);
                }
            }

            // Label erstellen (Name + Lebensdaten)
            $label = $person['first_name'] . ' ' . $person['last_name'];
            if ($person['birth_date']) {
                $birthYear = date('Y', strtotime($person['birth_date']));
                $deathYear = $person['death_date'] ? date('Y', strtotime($person['death_date'])) : '';
                $label .= "\n(" . $birthYear . ($deathYear ? ' - ' . $deathYear : '') . ")";
            }

            // Farbe nach Geschlecht
            $color = match ($person['gender']) {
                'm' => '#6495ED', // Blau für männlich
                'f' => '#FF69B4', // Pink für weiblich
                default => '#9E9E9E' // Grau für unbekannt
            };

            $nodes[] = [
                'id' => (int) $person['id'],
                'label' => $label,
                'shape' => 'circularImage',
                'image' => $imageUrl,
                'color' => [
                    'border' => $color,
                    'background' => '#FFFFFF'
                ],
                'borderWidth' => 3,
                'size' => 25,
                'font' => [
                    'size' => 14,
                    'color' => '#333333'
                ],
                // Zusätzliche Daten für Click-Event
                'title' => $person['first_name'] . ' ' . $person['last_name'], // Tooltip
                'gender' => $person['gender']
            ];
        }

        // 4. EDGES ERSTELLEN (Beziehungen)
        $edges = [];
        $edgeId = 1;

        foreach ($relationships as $rel) {
            $from = (int) $rel['person1_id'];
            $to = (int) $rel['person2_id'];
            $type = $rel['relationship_type'];

            // Edge-Eigenschaften je nach Beziehungstyp
            switch ($type) {
                case 'parent':
                    // Eltern → Kind: Pfeil zeigt zum Kind
                    $edges[] = [
                        'id' => $edgeId++,
                        'from' => $from,
                        'to' => $to,
                        'arrows' => 'to',
                        'color' => ['color' => '#2E7D32', 'opacity' => 0.8],
                        'width' => 2,
                        'smooth' => ['type' => 'cubicBezier'],
                        'label' => '',
                        'font' => ['size' => 10, 'align' => 'middle']
                    ];
                    break;

                case 'spouse':
                case 'partner':
                    // Ehe/Partner: Bidirektionale Linie (keine Pfeile)
                    $edges[] = [
                        'id' => $edgeId++,
                        'from' => $from,
                        'to' => $to,
                        'color' => ['color' => '#D32F2F', 'opacity' => 0.8],
                        'width' => 3,
                        'dashes' => ($type === 'partner'), // Gestrichelt für Partner
                        'smooth' => ['type' => 'curvedCW', 'roundness' => 0.2],
                        'label' => $rel['start_date'] ? date('Y', strtotime($rel['start_date'])) : '',
                        'font' => ['size' => 10, 'align' => 'middle', 'background' => 'white']
                    ];
                    break;

                case 'sibling':
                    // Geschwister: Gestrichelte Linie
                    $edges[] = [
                        'id' => $edgeId++,
                        'from' => $from,
                        'to' => $to,
                        'color' => ['color' => '#FFA726', 'opacity' => 0.6],
                        'width' => 1,
                        'dashes' => [5, 5],
                        'smooth' => ['type' => 'continuous']
                    ];
                    break;
            }
        }

        // 5. JSON-RESPONSE ZURÜCKGEBEN
        return $this->response->setJSON([
            'success' => true,
            'nodes' => $nodes,
            'edges' => $edges,
            'stats' => [
                'totalPersons' => count($persons),
                'totalRelationships' => count($relationships)
            ]
        ]);
    }

    public function tree()
    {
        return view('persons/tree');
    }

    // ========================================
    // HELPER METHODS
    // ========================================


    /**
     * Create thumbnail from image
     */
    private function createThumbnail($sourcePath, $destPath, $maxWidth = 300, $maxHeight = 300)
    {
        $image = \Config\Services::image()
            ->withFile($sourcePath)
            ->fit($maxWidth, $maxHeight, 'center')
            ->save($destPath);

        return $image;
    }

    /**
     * Delete photo files from disk
     */
    private function deletePhotoFiles($photo)
    {
        $uploadBase = WRITEPATH . 'uploads/';

        // Delete original
        if (file_exists($uploadBase . $photo['file_path'])) {
            unlink($uploadBase . $photo['file_path']);
        }

        // Delete thumbnail
        if ($photo['thumbnail_path'] && file_exists($uploadBase . $photo['thumbnail_path'])) {
            unlink($uploadBase . $photo['thumbnail_path']);
        }
    }

    /**
     * Get parents for view (no relationship_id needed)
     */
    private function getParentsForView($personId)
    {
        return $this->personModel
            ->select('persons.*')
            ->join('relationships', 'relationships.person1_id = persons.id')
            ->where('relationships.person2_id', $personId)
            ->where('relationships.relationship_type', 'parent')
            ->findAll();
    }

    /**
     * Get children for view (no relationship_id needed)
     */
    private function getChildrenForView($personId)
    {
        return $this->personModel
            ->select('persons.*')
            ->join('relationships', 'relationships.person2_id = persons.id')
            ->where('relationships.person1_id', $personId)
            ->where('relationships.relationship_type', 'parent')
            ->findAll();
    }

    /**
     * Get spouses for view (no relationship_id needed)
     */
    private function getSpousesForView($personId)
    {
        $db = \Config\Database::connect();

        // Get spouses where person is person1
        $spouses1 = $this->personModel
            ->select('persons.*, relationships.relationship_type, relationships.start_date, relationships.end_date')
            ->join('relationships', 'relationships.person2_id = persons.id')
            ->where('relationships.person1_id', $personId)
            ->whereIn('relationships.relationship_type', ['spouse', 'partner'])
            ->findAll();

        // Get spouses where person is person2
        $spouses2 = $this->personModel
            ->select('persons.*, relationships.relationship_type, relationships.start_date, relationships.end_date')
            ->join('relationships', 'relationships.person1_id = persons.id')
            ->where('relationships.person2_id', $personId)
            ->whereIn('relationships.relationship_type', ['spouse', 'partner'])
            ->findAll();

        // Merge both arrays
        return array_merge($spouses1, $spouses2);
    }


    /**
     * Get parents of a person (for edit)
     */
    private function getParents($personId)
    {
        $db = \Config\Database::connect();

        $query = $db->query(
            "SELECT p.*, r.id as relationship_id 
         FROM persons p 
         JOIN relationships r ON r.person1_id = p.id 
         WHERE r.person2_id = ? 
         AND r.relationship_type = 'parent'",
            [$personId]
        );

        return $query->getResultArray();
    }

    /**
     * Get children of a person (for edit)
     */
    private function getChildren($personId)
    {
        $db = \Config\Database::connect();

        $query = $db->query(
            "SELECT p.*, r.id as relationship_id 
         FROM persons p 
         JOIN relationships r ON r.person2_id = p.id 
         WHERE r.person1_id = ? 
         AND r.relationship_type = 'parent'",
            [$personId]
        );

        return $query->getResultArray();
    }

    /**
     * Get spouses/partners of a person (for edit)
     */
    private function getSpouses($personId)
    {
        $db = \Config\Database::connect();

        // Get spouses where person is person1
        $query1 = $db->query(
            "SELECT p.*, r.id as relationship_id, r.relationship_type, r.start_date, r.end_date 
         FROM persons p 
         JOIN relationships r ON r.person2_id = p.id 
         WHERE r.person1_id = ? 
         AND r.relationship_type IN ('spouse', 'partner')",
            [$personId]
        );
        $spouses1 = $query1->getResultArray();

        // Get spouses where person is person2
        $query2 = $db->query(
            "SELECT p.*, r.id as relationship_id, r.relationship_type, r.start_date, r.end_date 
         FROM persons p 
         JOIN relationships r ON r.person1_id = p.id 
         WHERE r.person2_id = ? 
         AND r.relationship_type IN ('spouse', 'partner')",
            [$personId]
        );
        $spouses2 = $query2->getResultArray();

        // Merge both arrays
        return array_merge($spouses1, $spouses2);
    }

    /**
     * Get siblings for a person
     */
    private function getSiblings($personId)
    {
        $db = \Config\Database::connect();

        // Get parent IDs directly
        $parentRelations = $db->table('relationships')
            ->select('person1_id')
            ->where('person2_id', $personId)
            ->where('relationship_type', 'parent')
            ->get()
            ->getResultArray();

        if (empty($parentRelations)) {
            return [];
        }

        $parentIds = array_column($parentRelations, 'person1_id');

        // Get all children of these parents (excluding current person)
        $siblingRelations = $db->table('relationships')
            ->select('person2_id')
            ->whereIn('person1_id', $parentIds)
            ->where('relationship_type', 'parent')
            ->where('person2_id !=', $personId)
            ->get()
            ->getResultArray();

        if (empty($siblingRelations)) {
            return [];
        }

        $siblingIds = array_unique(array_column($siblingRelations, 'person2_id'));

        // Get person details
        $siblings = $this->personModel->whereIn('id', $siblingIds)->findAll();

        return $siblings;
    }

    /**
     * Global Search API
     * Sucht über alle Personen und gibt JSON zurück
     */
    public function search()
    {
        // Nur AJAX-Requests erlauben
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Only AJAX requests allowed'
            ]);
        }

        $query = $this->request->getGet('q');

        if (empty($query) || strlen($query) < 2) {
            return $this->response->setJSON([
                'success' => true,
                'results' => []
            ]);
        }

        $personModel = new PersonModel();
        $photoModel = new PhotoModel();

        // Suche in first_name, last_name, maiden_name, birth_place, death_place
        $results = $personModel
            ->groupStart()
            ->like('first_name', $query)
            ->orLike('last_name', $query)
            ->orLike('maiden_name', $query)
            ->orLike('birth_place', $query)
            ->orLike('death_place', $query)
            ->orLike('occupation', $query)
            ->groupEnd()
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->limit(10)
            ->findAll();

        // Formatiere Ergebnisse
        $formatted = [];
        foreach ($results as $person) {
            // Hole Hauptfoto
            $photo = null;
            if ($person['primary_photo_id']) {
                $photo = $photoModel->find($person['primary_photo_id']);
            }

            // Erstelle Anzeige-String
            $display = $person['first_name'] . ' ' . $person['last_name'];

            if ($person['maiden_name']) {
                $display .= ' (geb. ' . $person['maiden_name'] . ')';
            }

            // Lebensdaten
            $dates = '';
            if ($person['birth_date']) {
                $dates = date('Y', strtotime($person['birth_date']));
            }
            if ($person['death_date']) {
                $dates .= ' - ' . date('Y', strtotime($person['death_date']));
            } elseif ($person['birth_date']) {
                $dates .= ' - ';
            }

            if ($dates) {
                $display .= ' (' . $dates . ')';
            }

            // Zusatzinfo (Geburtsort oder Beruf)
            $extra = '';
            if ($person['birth_place']) {
                $extra = '📍 ' . $person['birth_place'];
            } elseif ($person['occupation']) {
                $extra = '💼 ' . $person['occupation'];
            }

            $formatted[] = [
                'id' => $person['id'],
                'display' => $display,
                'extra' => $extra,
                'photo' => $photo ? base_url('uploads/persons/' . $person['id'] . '/' . $photo['thumbnail_path']) : base_url('assets/img/no-photo.png'),
                'url' => base_url('persons/view/' . $person['id'])
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'results' => $formatted,
            'count' => count($formatted)
        ]);
    }

    /**
     * Event hinzufügen
     */
    public function addEvent()
    {
        if (!$this->request->getPost()) {
            return redirect()->back();
        }

        $eventModel = new EventModel();

        $data = [
            'person_id' => $this->request->getPost('person_id'),
            'event_type' => $this->request->getPost('event_type'),
            'event_date' => $this->request->getPost('event_date') ?: null,
            'event_place' => $this->request->getPost('event_place'),
            'description' => $this->request->getPost('description'),
            'related_person_id' => $this->request->getPost('related_person_id') ?: null
        ];

        if ($eventModel->insert($data)) {
            return redirect()->to('persons/edit/' . $data['person_id'])
                ->with('success', 'Ereignis erfolgreich hinzugefügt.');
        } else {
            return redirect()->back()
                ->with('error', 'Fehler beim Hinzufügen des Ereignisses.');
        }
    }

    /**
     * Event löschen
     */
    public function deleteEvent($id)
    {
        $eventModel = new EventModel();
        $event = $eventModel->find($id);

        if (!$event) {
            return redirect()->back()
                ->with('error', 'Ereignis nicht gefunden.');
        }

        $personId = $event['person_id'];

        if ($eventModel->delete($id)) {
            return redirect()->to('persons/edit/' . $personId)
                ->with('success', 'Ereignis erfolgreich gelöscht.');
        } else {
            return redirect()->back()
                ->with('error', 'Fehler beim Löschen des Ereignisses.');
        }
    }

    /**
     * Fix image orientation based on EXIF data
     */
    private function fixImageOrientation($filepath)
    {
        if (!function_exists('exif_read_data')) {
            return; // EXIF not available
        }

        $exif = @exif_read_data($filepath);

        if (!isset($exif['Orientation'])) {
            return; // No orientation data
        }

        $image = imagecreatefromjpeg($filepath);

        switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, -90, 0);
                break;
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
        }

        imagejpeg($image, $filepath, 90);
        imagedestroy($image);
    }

}