<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Help as HelpConfig;

class Help extends BaseController
{
    protected HelpConfig $helpConfig;
    protected string $helpContentPath;
    protected string $indexPath;
    protected string $currentLanguage;

    public function __construct()
    {
        // Help-Config laden
        $this->helpConfig = config('Help');

        // Aktuelle Sprache (später aus Session/User-Präferenz)
        $this->currentLanguage = $this->helpConfig->defaultLanguage;

        // Pfade setzen
        $this->helpContentPath = $this->helpConfig->getContentPath($this->currentLanguage);
        $this->indexPath = $this->helpConfig->getIndexPath($this->currentLanguage);
    }

    /**
     * Hilfe-Übersicht (Index)
     */
    public function index()
    {
        // Validiere Pfad existiert
        if (!is_dir($this->helpContentPath)) {
            log_message('error', 'Help content path not found: ' . $this->helpContentPath);
            return redirect()->to('/dashboard')->with('error', 'Hilfe-System nicht verfügbar');
        }

        if (!file_exists($this->indexPath)) {
            log_message('error', 'Help index.json not found: ' . $this->indexPath);
            return redirect()->to('/dashboard')->with('error', 'Hilfe-Index nicht gefunden');
        }

        $indexData = json_decode(file_get_contents($this->indexPath), true);

        $fromEmail = env('email.fromEmail');

        $data = [
            'title' => 'Hilfe & Dokumentation',
            'helpIndex' => $indexData,
            'fromEmail' => $fromEmail
        ];

        return view('help/index', $data);
    }

    /**
     * Einzelnen Hilfe-Artikel anzeigen
     */
    public function show($slug = null)
    {
        if (empty($slug)) {
            return redirect()->to('/help');
        }

        // Sanitize slug (Sicherheit!)
        $slug = $this->sanitizeSlug($slug);

        // Pfad zur Markdown-Datei
        $filepath = $this->helpContentPath . $slug . '.md';

        if (!file_exists($filepath)) {
            log_message('warning', 'Help article not found: ' . $slug);
            return redirect()->to('/help')->with('error', 'Artikel nicht gefunden');
        }

        // Markdown lesen
        $markdown = file_get_contents($filepath);

        // Markdown zu HTML konvertieren
        $html = $this->parseMarkdown($markdown);

        // Artikel-Informationen aus index.json holen
        $articleInfo = $this->getArticleInfo($slug);

        $data = [
            'title' => $articleInfo['title'] ?? ucfirst(str_replace('-', ' ', $slug)),
            'content' => $html,
            'slug' => $slug,
            'articleInfo' => $articleInfo
        ];

        return view('help/show', $data);
    }

    /**
     * API-Endpoint: Gibt index.json als JSON zurück
     */
    public function apiIndex()
    {
        if (!file_exists($this->indexPath)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Index nicht gefunden'
            ])->setStatusCode(404);
        }

        $indexData = json_decode(file_get_contents($this->indexPath), true);

        return $this->response->setJSON([
            'success' => true,
            'data' => $indexData
        ]);
    }

    /**
     * API-Endpoint: Gibt einzelnen Artikel als HTML zurück
     */
    public function apiArticle($slug = null)
    {
        if (empty($slug)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Slug fehlt'
            ])->setStatusCode(400);
        }

        $slug = $this->sanitizeSlug($slug);
        $filepath = $this->helpContentPath . $slug . '.md';

        if (!file_exists($filepath)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Artikel nicht gefunden'
            ])->setStatusCode(404);
        }

        $markdown = file_get_contents($filepath);
        $html = $this->parseMarkdown($markdown);
        $articleInfo = $this->getArticleInfo($slug);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'slug' => $slug,
                'html' => $html,
                'info' => $articleInfo
            ]
        ]);
    }

    /**
     * Öffentliche Hilfe-Übersicht
     */
    public function publicIndex()
    {
        if (!file_exists($this->indexPath)) {
            return view('errors/html/error_404');
        }

        $indexData = json_decode(file_get_contents($this->indexPath), true);

        $data = [
            'title' => 'Hilfe & Dokumentation',
            'helpIndex' => $indexData
        ];

        return view('help/public_index', $data);
    }

    /**
     * Öffentlicher Hilfe-Artikel
     */
    public function publicShow($slug = null)
    {
        if (empty($slug)) {
            return redirect()->to('/hilfe');
        }

        $slug = $this->sanitizeSlug($slug);
        $filepath = $this->helpContentPath . $slug . '.md';

        if (!file_exists($filepath)) {
            return redirect()->to('/hilfe')->with('error', 'Artikel nicht gefunden');
        }

        $markdown = file_get_contents($filepath);
        $html = $this->parseMarkdown($markdown);
        $articleInfo = $this->getArticleInfo($slug);

        $data = [
            'title' => $articleInfo['title'] ?? ucfirst(str_replace('-', ' ', $slug)),
            'content' => $html,
            'slug' => $slug,
            'articleInfo' => $articleInfo
        ];

        return view('help/public_show', $data);
    }

    // ===================================================================
    // PRIVATE HELPER METHODS
    // ===================================================================

    /**
     * Sanitize slug für Sicherheit
     * Verhindert Directory Traversal Attacks
     * 
     * @param string $slug
     * @return string
     */
    private function sanitizeSlug(string $slug): string
    {
        // Nur alphanumerisch, Bindestriche und Unterstriche erlauben
        $slug = preg_replace('/[^a-z0-9\-_]/i', '', $slug);

        // Keine '..' erlauben (Directory Traversal)
        $slug = str_replace('..', '', $slug);

        return $slug;
    }

    /**
     * Simple Markdown zu HTML Konvertierung
     * 
     * @param string $markdown
     * @return string
     */
    private function parseMarkdown(string $markdown): string
    {
        $html = $markdown;

        // Headers (h4-h1 in dieser Reihenfolge wichtig!)
        $html = preg_replace('/^#### (.+)$/m', '<h4>$1</h4>', $html);
        $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $html);

        // Bold
        $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);

        // Italic
        $html = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $html);

        // Links
        $html = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2">$1</a>', $html);

        // Lists
        $html = preg_replace('/^- (.+)$/m', '<li>$1</li>', $html);
        $html = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $html);

        // Code blocks
        $html = preg_replace('/```(.+?)```/s', '<pre><code>$1</code></pre>', $html);
        $html = preg_replace('/`(.+?)`/', '<code>$1</code>', $html);

        // Paragraphs
        $html = preg_replace('/^(?!<[hul]|<pre)(.+)$/m', '<p>$1</p>', $html);

        // Emojis mit Farbe
        $html = str_replace('✅', '<span class="text-success">✅</span>', $html);
        $html = str_replace('❌', '<span class="text-danger">❌</span>', $html);
        $html = str_replace('⚠️', '<span class="text-warning">⚠️</span>', $html);

        return $html;
    }

    /**
     * Holt Artikel-Informationen aus index.json
     * 
     * @param string $slug
     * @return array
     */
    private function getArticleInfo(string $slug): array
    {
        if (!file_exists($this->indexPath)) {
            return [];
        }

        $indexData = json_decode(file_get_contents($this->indexPath), true);

        if (!isset($indexData['categories'])) {
            return [];
        }

        foreach ($indexData['categories'] as $category) {
            if (!isset($category['articles'])) {
                continue;
            }

            foreach ($category['articles'] as $article) {
                if ($article['slug'] === $slug) {
                    return array_merge($article, [
                        'category' => $category['title'],
                        'categoryIcon' => $category['icon']
                    ]);
                }
            }
        }

        return [];
    }

    /**
     * Support-Anfrage per Email versenden
     */
    public function sendSupport()
    {
        // Nur AJAX erlauben
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ungültige Anfrage'
            ])->setStatusCode(400);
        }

        // Rate Limiting (max. 3 Anfragen pro Stunde pro IP)
        $cache = \Config\Services::cache();
        $ip = $this->request->getIPAddress();
        $cacheKey = 'support_limit_' . md5($ip);

        $attempts = (int) $cache->get($cacheKey);
        if ($attempts >= 3) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Zu viele Anfragen. Bitte versuchen Sie es in einer Stunde erneut.'
            ])->setStatusCode(429);
        }

        // Captcha-Validierung
        $captchaValid = $this->validateCaptcha(
            $this->request->getPost('captcha_answer'),
            $this->request->getPost('captcha_token')
        );

        if (!$captchaValid) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sicherheitsfrage falsch beantwortet. Bitte versuchen Sie es erneut.'
            ])->setStatusCode(400);
        }

        // Validierung
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => [
                'label' => 'Name',
                'rules' => 'required|min_length[2]|max_length[100]'
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email'
            ],
            'subject' => [
                'label' => 'Betreff',
                'rules' => 'required'
            ],
            'message' => [
                'label' => 'Nachricht',
                'rules' => 'required|min_length[20]|max_length[5000]'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode('<br>', $validation->getErrors())
            ])->setStatusCode(400);
        }

        // Daten sammeln
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        $currentPage = $this->request->getPost('current_page');

        // Email-Service laden
        $emailService = \Config\Services::email();

        // Admin-Email aus Config
        $supportEmail = env('email.supportEmail', env('email.fromEmail', 'support@example.com'));

        // 1. EMAIL AN ADMIN: Support-Anfrage
        $adminEmailBody = view('emails/support_request', [
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
            'currentPage' => $currentPage,
            'ip' => $ip,
            'userAgent' => $this->request->getUserAgent()->getAgentString(),
            'timestamp' => date('d.m.Y H:i:s')
        ]);

        $emailService->clear();
        $emailService->setFrom(env('email.fromEmail'), env('email.fromName', 'Ahnengalerie'));
        $emailService->setTo($supportEmail);
        $emailService->setReplyTo($email, $name);
        $emailService->setSubject('Support-Anfrage: ' . $subject);
        $emailService->setMessage($adminEmailBody);

        $adminSent = $emailService->send();

        if (!$adminSent) {
            log_message('error', 'Support email to admin failed: ' . $emailService->printDebugger(['headers']));
        }

        // 2. AUTO-REPLY AN USER: Bestätigung
        $userEmailBody = view('emails/support_autoreply', [
            'name' => $name,
            'subject' => $subject,
            'message' => $message,
            'timestamp' => date('d.m.Y H:i:s')
        ]);

        $emailService->clear();
        $emailService->setFrom(env('email.fromEmail'), env('email.fromName', 'Ahnengalerie Support'));
        $emailService->setTo($email);
        $emailService->setSubject('Ihre Anfrage wurde empfangen: ' . $subject);
        $emailService->setMessage($userEmailBody);

        $userSent = $emailService->send();

        if (!$userSent) {
            log_message('error', 'Auto-reply to user failed: ' . $emailService->printDebugger(['headers']));
        }

        // Wenn mindestens Admin-Email gesendet wurde = Erfolg
        if ($adminSent) {
            // Rate Limiting erhöhen
            $cache->save($cacheKey, $attempts + 1, 3600); // 1 Stunde

            // Optional: Support-Anfrage in Datenbank loggen
            $this->logSupportRequest([
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'ip' => $ip,
                'page' => $currentPage
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Ihre Nachricht wurde erfolgreich versendet! ' .
                    ($userSent ? 'Sie erhalten gleich eine Bestätigungs-Email.' : 'Wir melden uns bald bei Ihnen.')
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email konnte nicht versendet werden. Bitte versuchen Sie es später erneut.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Validiert das Math-Captcha
     */
    private function validateCaptcha($userAnswer, $token)
    {
        if (empty($userAnswer) || empty($token)) {
            return false;
        }

        try {
            // Token dekodieren
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);

            if (count($parts) !== 2) {
                return false;
            }

            $correctAnswer = (int) $parts[0];
            $timestamp = (int) $parts[1];

            // Token-Gültigkeit prüfen (max. 10 Minuten alt)
            $maxAge = 600; // 10 Minuten in Sekunden
            if ((time() - ($timestamp / 1000)) > $maxAge) {
                return false;
            }

            // Antwort prüfen
            return (int) $userAnswer === $correctAnswer;

        } catch (\Exception $e) {
            log_message('error', 'Captcha validation error: ' . $e->getMessage());
            return false;
        }
    }

    
    /**
     * Support-Anfrage loggen (optional)
     */
    private function logSupportRequest(array $data)
    {
        // Optional: In Datenbank speichern für Statistik
        // Oder einfach in Log-Datei
        log_message('info', 'Support request from: ' . $data['email'] . ' - Subject: ' . $data['subject']);
    }
}