<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

/**
 * Admin User Management Controller
 * 
 * Nur für Admins: Benutzer verwalten (CRUD)
 */
class Users_copy extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    /**
     * User-Liste anzeigen
     */
    public function index()
    {
        // Nur Admins dürfen hier rein
        if (!session()->get('is_admin')) {
            return redirect()->to('/dashboard')->with('error', 'Zugriff verweigert. Nur Admins erlaubt.');
        }
        
        // Suche & Filter
        $search = $this->request->getGet('search');
        $filter = $this->request->getGet('filter'); // 'all', 'admins', 'users'
        
        $builder = $this->userModel;
        
        // Suche
        if ($search) {
            $builder->groupStart()
                    ->like('username', $search)
                    ->orLike('email', $search)
                    ->groupEnd();
        }
        
        // Filter
        if ($filter === 'admins') {
            $builder->where('is_admin', 1);
        } elseif ($filter === 'users') {
            $builder->where('is_admin', 0);
        }
        
        // Sortierung
        $builder->orderBy('created_at', 'DESC');
        
        $data = [
            'users' => $builder->findAll(),
            'search' => $search,
            'filter' => $filter,
            'stats' => [
                'total' => $this->userModel->countAll(),
                'admins' => $this->userModel->where('is_admin', 1)->countAllResults(false),
                'users' => $this->userModel->where('is_admin', 0)->countAllResults()
            ]
        ];
        
        return view('admin/users/index', $data);
    }
    
    /**
     * User-Formular anzeigen (Anlegen)
     */
    public function create()
    {
        // Nur Admins
        if (!session()->get('is_admin')) {
            return redirect()->to('/dashboard')->with('error', 'Zugriff verweigert.');
        }
        
        return view('admin/users/form', [
            'user' => null,
            'title' => 'Neuen Benutzer anlegen'
        ]);
    }
    
    /**
     * User-Formular anzeigen (Bearbeiten)
     */
    public function edit($id)
    {
        // Nur Admins
        if (!session()->get('is_admin')) {
            return redirect()->to('/dashboard')->with('error', 'Zugriff verweigert.');
        }
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Benutzer nicht gefunden.');
        }
        
        return view('admin/users/form', [
            'user' => $user,
            'title' => 'Benutzer bearbeiten'
        ]);
    }
    
    /**
     * User speichern
     */
    public function store()
    {
        // Nur Admins
        if (!session()->get('is_admin')) {
            return redirect()->to('/dashboard')->with('error', 'Zugriff verweigert.');
        }
        
        // Validierung
        $validation = \Config\Services::validation();
        
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        // User anlegen
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            //'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'password' => $this->request->getPost('password'),
            'is_admin' => $this->request->getPost('is_admin') ? 1 : 0
        ];
        
        if ($this->userModel->insert($data)) {
            return redirect()->to('/admin/users')->with('success', 'Benutzer erfolgreich angelegt.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Fehler beim Anlegen des Benutzers.');
        }
    }
    
    /**
     * User aktualisieren (Update)
     */
    public function update($id)
    {
        // Nur Admins
        if (!session()->get('is_admin')) {
            return redirect()->to('/dashboard')->with('error', 'Zugriff verweigert.');
        }
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Benutzer nicht gefunden.');
        }
        
        // Validierung
        $validation = \Config\Services::validation();
        
        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]"
        ];
        
        // Passwort nur validieren wenn eingegeben
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['password_confirm'] = 'matches[password]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        // Daten zusammenstellen
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'is_admin' => $this->request->getPost('is_admin') ? 1 : 0
        ];
        
        // Passwort nur ändern wenn eingegeben
        if ($this->request->getPost('password')) {
            //$data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            $data['password'] = $this->request->getPost('password');
        }
        
        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/admin/users')->with('success', 'Benutzer erfolgreich aktualisiert.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Fehler beim Aktualisieren des Benutzers.');
        }
    }
    
    /**
     * User löschen
     */
    public function delete($id)
    {
        // Nur Admins
        if (!session()->get('is_admin')) {
            return redirect()->to('/dashboard')->with('error', 'Zugriff verweigert.');
        }
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Benutzer nicht gefunden.');
        }
        
        // Schutz: Admin kann sich nicht selbst löschen
        if ($id == session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Sie können sich nicht selbst löschen!');
        }
        
        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'Benutzer erfolgreich gelöscht.');
        } else {
            return redirect()->to('/admin/users')->with('error', 'Fehler beim Löschen des Benutzers.');
        }
    }
    
    /**
     * Admin-Status umschalten (AJAX)
     */
    public function toggleAdmin($id)
    {
        // Nur Admins
        if (!session()->get('is_admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Zugriff verweigert.']);
        }
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Benutzer nicht gefunden.']);
        }
        
        // Schutz: Admin kann sich nicht selbst degradieren
        if ($id == session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sie können Ihre eigenen Admin-Rechte nicht entfernen!']);
        }
        
        // Toggle
        $newStatus = $user['is_admin'] ? 0 : 1;
        
        if ($this->userModel->update($id, ['is_admin' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Admin-Status erfolgreich geändert.',
                'is_admin' => $newStatus
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Fehler beim Ändern des Status.']);
        }
    }
}