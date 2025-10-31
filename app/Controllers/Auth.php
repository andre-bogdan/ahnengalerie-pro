<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    /**
     * Show login form
     */
    // In app/Controllers/Auth.php
// Ersetze die login() Methode:

    public function login()
    {
        // Bereits eingeloggt? -> Dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        // Prüfe ob IP geblockt ist
        $cache = \Config\Services::cache();
        $ipAddress = $this->request->getIPAddress();
        $blockKey = 'login_blocked_' . $ipAddress;
        $blockedUntil = $cache->get($blockKey);

        $data = [
            'title' => 'Login',
            'blocked_until' => null,
            'remaining_seconds' => 0
        ];

        // Wenn geblockt: Blockzeit übergeben
        if ($blockedUntil !== null && time() < $blockedUntil) {
            $data['blocked_until'] = $blockedUntil;
            $data['remaining_seconds'] = $blockedUntil - time();
        }

        return view('auth/login', $data);
    }

    /**
     * Process login (authenticate user)
     */
    // In app/Controllers/Auth.php
// Ersetze die authenticate() Methode:

    public function authenticate()
    {
        $cache = \Config\Services::cache();
        $ipAddress = $this->request->getIPAddress();
        $attemptKey = 'login_attempts_' . $ipAddress;
        $blockKey = 'login_blocked_' . $ipAddress;

        // Hole Username und Password
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Validierung
        if (empty($username) || empty($password)) {
            return redirect()->to('/login')
                ->with('error', 'Bitte füllen Sie alle Felder aus.');
        }

        // User aus Datenbank holen
        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('username', $username)->first();

        // Prüfe Credentials
        if (!$user || !password_verify($password, $user['password'])) {
            // FEHLGESCHLAGEN - Counter erhöhen
            $attempts = (int) $cache->get($attemptKey);
            $attempts++;

            // Speichere neue Anzahl (15 Minuten TTL)
            $cache->save($attemptKey, $attempts, 900);

            // Nach 5 Versuchen: IP blocken
            if ($attempts >= 5) {
                $blockUntil = time() + 900; // 15 Minuten
                $cache->save($blockKey, $blockUntil, 900);

                log_message('warning', 'IP {ip} nach 5 fehlgeschlagenen Login-Versuchen geblockt', [
                    'ip' => $ipAddress
                ]);

                return redirect()->to('/login')
                    ->with('error', 'Zu viele fehlgeschlagene Login-Versuche. Ihr Zugang wurde für 15 Minuten gesperrt.');
            }

            // Fehlermeldung mit verbleibenden Versuchen
            $remaining = 5 - $attempts;
            return redirect()->to('/login')
                ->with('error', "Ungültige Anmeldedaten. Noch {$remaining} Versuch(e) übrig.");
        }

        // LOGIN ERFOLGREICH - Counter zurücksetzen
        $cache->delete($attemptKey);
        $cache->delete($blockKey);

        // Session setzen
        session()->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'is_admin' => $user['is_admin'],
            'logged_in' => true,
        ]);

        // Session regenerieren (Sicherheit)
        session()->regenerate();

        log_message('info', 'Benutzer {username} erfolgreich eingeloggt', [
            'username' => $user['username'],
            'ip' => $ipAddress
        ]);

        // ✨ NEU: Prüfung ob Passwort geändert werden muss
        if ($user['created_at'] === $user['updated_at']) {
            // Passwort wurde noch nie geändert
            session()->setFlashdata('password_change_required', true);
            session()->setFlashdata(
                'warning',
                'Willkommen! Bitte ändern Sie aus Sicherheitsgründen Ihr initiales Passwort.'
            );
            return redirect()->to('/profile');
        }

        return redirect()->to('/dashboard');
    }

    /**
     * Logout
     */
    public function logout()
    {
        // Destroy session
        session()->destroy();

        // Success message
        session()->setFlashdata('success', 'Sie wurden erfolgreich abgemeldet.');

        return redirect()->to('/login');
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Benutzer nicht gefunden.');
        }

        return view('auth/profile', ['user' => $user]);
    }

    /**
     * Update user profile (email and/or password)
     */
    public function updateProfile()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Benutzer nicht gefunden.');
        }

        // Bestimme welches Formular abgeschickt wurde
        $formType = $this->request->getPost('form_type');

        if ($formType === 'email') {
            return $this->updateEmail($userModel, $userId, $user);
        } elseif ($formType === 'password') {
            return $this->updatePassword($userModel, $userId, $user);
        } elseif ($formType === 'newsletter') { 
            return $this->updateNewsletter($userModel, $userId);
        }

        return redirect()->back()->with('error', 'Ungültige Anfrage.');
    }

    /**
     * Update email address
     */
    private function updateEmail($userModel, $userId, $user)
    {
        // Validierung
        $rules = [
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('email_errors', $this->validator->getErrors());
        }

        $newEmail = $this->request->getPost('email');

        // E-Mail aktualisieren
        if ($userModel->update($userId, ['email' => $newEmail])) {
            // Session aktualisieren
            session()->set('email', $newEmail);

            return redirect()->to('/profile')->with('success', 'E-Mail-Adresse erfolgreich geändert.');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Fehler beim Aktualisieren der E-Mail-Adresse.');
        }
    }

    /**
     * Update password
     */
    private function updatePassword($userModel, $userId, $user)
    {
        // Validierung
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'new_password_confirm' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('password_errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Altes Passwort überprüfen
        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->back()
                ->with('error', 'Das aktuelle Passwort ist falsch.');
        }

        // Neues Passwort speichern (wird automatisch vom Model gehasht)
        if ($userModel->update($userId, ['password' => $newPassword])) {
            return redirect()->to('/profile')->with('success', 'Passwort erfolgreich geändert.');
        } else {
            return redirect()->back()
                ->with('error', 'Fehler beim Ändern des Passworts.');
        }
    }

    /**
     * Update newsletter preference
     */
    private function updateNewsletter($userModel, $userId)
    {
        $newsletterEnabled = $this->request->getPost('newsletter_enabled') ? 1 : 0;

        if ($userModel->update($userId, ['newsletter_enabled' => $newsletterEnabled])) {
            $message = $newsletterEnabled
                ? 'Newsletter erfolgreich aktiviert.'
                : 'Newsletter erfolgreich deaktiviert.';
            return redirect()->to('/profile')->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'Fehler beim Aktualisieren der Newsletter-Einstellungen.');
        }
    }
}