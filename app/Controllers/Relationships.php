<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PersonModel;
use App\Models\RelationshipModel;

class Relationships extends BaseController
{
    protected $personModel;
    protected $relationshipModel;

    public function __construct()
    {
        $this->personModel = new PersonModel();
        $this->relationshipModel = new RelationshipModel();
    }

    /**
     * Add parent relationship
     * POST: person_id, parent_id
     */
    public function addParent()
    {
        $personId = $this->request->getPost('person_id');
        $parentId = $this->request->getPost('parent_id');

        // Validation
        if (!$personId || !$parentId) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Person und Elternteil müssen angegeben werden.');
        }

        // Check if persons exist
        $person = $this->personModel->find($personId);
        $parent = $this->personModel->find($parentId);

        if (!$person || !$parent) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Person oder Elternteil nicht gefunden.');
        }

        // Check if person tries to be their own parent
        if ($personId == $parentId) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Eine Person kann nicht ihr eigener Elternteil sein.');
        }

        // Check if relationship already exists
        $existing = $this->relationshipModel
            ->where('person1_id', $parentId)
            ->where('person2_id', $personId)
            ->where('relationship_type', 'parent')
            ->first();

        if ($existing) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Diese Eltern-Kind Beziehung existiert bereits.');
        }

        // Check if person already has 2 parents
        $parentCount = $this->relationshipModel
            ->where('person2_id', $personId)
            ->where('relationship_type', 'parent')
            ->countAllResults();

        if ($parentCount >= 2) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Eine Person kann maximal 2 Elternteile haben.');
        }

        // Create relationship (parent -> child)
        $data = [
            'person1_id' => $parentId,
            'person2_id' => $personId,
            'relationship_type' => 'parent',
        ];

        if ($this->relationshipModel->insert($data)) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('success', 'Elternteil erfolgreich hinzugefügt!');
        }

        return redirect()->to('/persons/edit/' . $personId)
            ->with('error', 'Fehler beim Hinzufügen des Elternteils.');
    }


    /**
     * Add spouse/partner relationship
     * POST: person_id, spouse_id, type (spouse/partner), start_date
     */
    public function addSpouse()
    {
        $personId = $this->request->getPost('person_id');
        $spouseId = $this->request->getPost('spouse_id');
        $type = $this->request->getPost('type') ?? 'spouse';
        $startDate = $this->request->getPost('start_date');

        // Validation
        if (!$personId || !$spouseId) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Beide Personen müssen angegeben werden.');
        }

        if (!in_array($type, ['spouse', 'partner'])) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Ungültiger Beziehungstyp.');
        }

        // Check if persons exist
        $person = $this->personModel->find($personId);
        $spouse = $this->personModel->find($spouseId);

        if (!$person || !$spouse) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Eine oder beide Personen nicht gefunden.');
        }

        // Check if person tries to marry themselves
        if ($personId == $spouseId) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Eine Person kann nicht mit sich selbst verheiratet sein.');
        }

        // Check if relationship already exists (both directions)
        $existing = $this->relationshipModel
            ->groupStart()
            ->groupStart()
            ->where('person1_id', $personId)
            ->where('person2_id', $spouseId)
            ->groupEnd()
            ->orGroupStart()
            ->where('person1_id', $spouseId)
            ->where('person2_id', $personId)
            ->groupEnd()
            ->groupEnd()
            ->whereIn('relationship_type', ['spouse', 'partner'])
            ->first();

        if ($existing) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Diese Beziehung existiert bereits.');
        }

        // Create relationship (symmetric)
        $data = [
            'person1_id' => $personId,
            'person2_id' => $spouseId,
            'relationship_type' => $type,
            'start_date' => $startDate ?: null,
        ];

        if ($this->relationshipModel->insert($data)) {
            $typeName = $type === 'spouse' ? 'Ehepartner' : 'Partner';
            return redirect()->to('/persons/edit/' . $personId)
                ->with('success', $typeName . ' erfolgreich hinzugefügt!');
        }

        return redirect()->to('/persons/edit/' . $personId)
            ->with('error', 'Fehler beim Hinzufügen der Beziehung.');
    }

    /**
     * Add child relationship
     * POST: person_id, child_id
     */
    public function addChild()
    {
        $personId = $this->request->getPost('person_id');
        $childId = $this->request->getPost('child_id');

        // Validation
        if (!$personId || !$childId) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Elternteil und Kind müssen angegeben werden.');
        }

        // Check if persons exist
        $person = $this->personModel->find($personId);
        $child = $this->personModel->find($childId);

        if (!$person || !$child) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Elternteil oder Kind nicht gefunden.');
        }

        // Check if person tries to be their own child
        if ($personId == $childId) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Eine Person kann nicht ihr eigenes Kind sein.');
        }

        // Check if relationship already exists
        $existing = $this->relationshipModel
            ->where('person1_id', $personId)
            ->where('person2_id', $childId)
            ->where('relationship_type', 'parent')
            ->first();

        if ($existing) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Diese Eltern-Kind Beziehung existiert bereits.');
        }

        // Check if child already has 2 parents
        $parentCount = $this->relationshipModel
            ->where('person2_id', $childId)
            ->where('relationship_type', 'parent')
            ->countAllResults();

        if ($parentCount >= 2) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('error', 'Dieses Kind hat bereits 2 Elternteile.');
        }

        // Create relationship (parent -> child)
        $data = [
            'person1_id' => $personId,
            'person2_id' => $childId,
            'relationship_type' => 'parent',
        ];

        if ($this->relationshipModel->insert($data)) {
            return redirect()->to('/persons/edit/' . $personId)
                ->with('success', 'Kind erfolgreich hinzugefügt!');
        }

        return redirect()->to('/persons/edit/' . $personId)
            ->with('error', 'Fehler beim Hinzufügen des Kindes.');
    }
    /**
     * Delete a relationship
     * GET: /relationships/delete/{id}
     */
    public function delete($id)
    {
        $relationship = $this->relationshipModel->find($id);

        if (!$relationship) {
            return redirect()->back()->with('error', 'Beziehung nicht gefunden.');
        }

        if ($this->relationshipModel->delete($id)) {
            return redirect()->back()->with('success', 'Beziehung erfolgreich gelöscht!');
        }

        return redirect()->back()->with('error', 'Fehler beim Löschen der Beziehung.');
    }

    /**
     * Get available persons for selection (AJAX)
     * Returns JSON
     */
    public function getAvailablePersons()
    {
        $personId = $this->request->getGet('person_id');
        $type = $this->request->getGet('type'); // 'parent', 'spouse', 'child'

        $persons = $this->personModel->findAll();

        // Filter out the current person
        $persons = array_filter($persons, function ($p) use ($personId) {
            return $p['id'] != $personId;
        });

        // For parents: exclude descendants (optional - simplified version)
        // For children: exclude ancestors (optional - simplified version)

        return $this->response->setJSON(array_values($persons));
    }
}