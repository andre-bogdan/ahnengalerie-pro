<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Cron extends BaseController
{
    /**
     * Newsletter per URL-Aufruf versenden
     * URL: https://unsere.ahnengalerie-pro.de/cron/newsletter?secret=DEIN_GEHEIMER_SCHLÜSSEL
     */
    public function newsletter()
    {
        // 1. SECRET KEY PRÜFEN
        $secret = $this->request->getGet('secret');
        $validSecret = env('app.internalNewsletterKey');

        if ($secret !== $validSecret) {
            return $this->response->setStatusCode(403)->setBody('Unauthorized');
        }

        // 2. NUR EINMAL PRO TAG AUSFÜHREN (Spam-Schutz)
        $cache = \Config\Services::cache();
        $today = date('Y-m-d');
        $cacheKey = 'newsletter_sent_' . $today;

        if ($cache->get($cacheKey)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Newsletter wurde heute bereits versendet.',
                'date' => $today
            ]);
        }

        // 3. STATISTIKEN BERECHNEN
        $userModel = new \App\Models\UserModel();
        $personModel = new \App\Models\PersonModel();
        $photoModel = new \App\Models\PhotoModel();
        $eventModel = new \App\Models\EventModel();
        $relationshipModel = new \App\Models\RelationshipModel();

        // Letzter Monat berechnen
        $lastMonth = new \DateTime('first day of last month');
        $firstDay = $lastMonth->format('Y-m-01 00:00:00');
        $lastDay = $lastMonth->format('Y-m-t 23:59:59');

        $monthName = $this->getGermanMonthName($lastMonth->format('m'));
        $year = $lastMonth->format('Y');

        // Statistiken berechnen
        $stats = [
            'new_persons' => $personModel
                ->where('created_at >=', $firstDay)
                ->where('created_at <=', $lastDay)
                ->countAllResults(),

            'updated_persons' => $personModel
                ->where('updated_at >=', $firstDay)
                ->where('updated_at <=', $lastDay)
                ->where('created_at != updated_at')
                ->countAllResults(),

            'new_photos' => $photoModel
                ->where('created_at >=', $firstDay)
                ->where('created_at <=', $lastDay)
                ->countAllResults(),

            'new_events' => $eventModel
                ->where('created_at >=', $firstDay)
                ->where('created_at <=', $lastDay)
                ->countAllResults(),

            // ✨ NEU: Geänderte Events
            'updated_events' => $eventModel
                ->where('updated_at >=', $firstDay)
                ->where('updated_at <=', $lastDay)
                ->where('created_at != updated_at')
                ->countAllResults(),

            'new_relationships' => $relationshipModel
                ->where('created_at >=', $firstDay)
                ->where('created_at <=', $lastDay)
                ->countAllResults(),

            // ✨ NEU: Geänderte Beziehungen
            'updated_relationships' => $relationshipModel
                ->where('updated_at >=', $firstDay)
                ->where('updated_at <=', $lastDay)
                ->where('created_at != updated_at')
                ->countAllResults(),
        ];

        $hasChanges = array_sum($stats) > 0;

        // 4. BENUTZER MIT NEWSLETTER HOLEN
        $users = $userModel->where('newsletter_enabled', 1)->findAll();

        if (empty($users)) {
            // Trotzdem Admin informieren
            $this->sendAdminReport(false, "$monthName $year", 0, 0, 0, [], $stats, $hasChanges);

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Keine Benutzer mit aktiviertem Newsletter gefunden.',
                'stats' => $stats
            ]);
        }

        // 5. EMAILS VERSENDEN
        $email = \Config\Services::email();
        $sentCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($users as $user) {
            try {
                $email->clear();
                $email->setTo($user['email']);
                $email->setFrom('noreply@ahnengalerie-pro.de', 'Ahnengalerie Pro');
                $email->setSubject("Monatlicher Rückblick - $monthName $year");

                $data = [
                    'username' => $user['username'],
                    'month_name' => $monthName,
                    'year' => $year,
                    'stats' => $stats,
                    'has_changes' => $hasChanges,
                    'dashboard_url' => base_url('dashboard'),
                    'profile_url' => base_url('profile'),
                ];

                $message = view('emails/monthly_newsletter', $data);
                $email->setMessage($message);

                if ($email->send()) {
                    $sentCount++;
                } else {
                    $errorCount++;
                    $errors[] = [
                        'email' => $user['email'],
                        'error' => 'Email konnte nicht versendet werden'
                    ];
                }

            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = [
                    'email' => $user['email'],
                    'error' => $e->getMessage()
                ];
            }
        }

        // 6. CACHE SETZEN (24 Stunden gültig)
        $cache->save($cacheKey, true, 86400);

        // 7. ✨ ADMIN-REPORT SENDEN
        $adminReportSent = $this->sendAdminReport(
            $errorCount === 0,
            "$monthName $year",
            count($users),
            $sentCount,
            $errorCount,
            $errors,
            $stats,
            $hasChanges
        );

        // 8. JSON-RESPONSE (für Monitoring)
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Newsletter erfolgreich versendet',
            'period' => "$monthName $year",
            'timeframe' => "$firstDay bis $lastDay",
            'stats' => $stats,
            'has_changes' => $hasChanges,
            'recipients' => count($users),
            'sent' => $sentCount,
            'errors' => $errorCount,
            'error_details' => $errors,
            'admin_report_sent' => $adminReportSent
        ]);
    }

    /**
     * ✨ NEU: Admin-Report per Email senden
     */
    private function sendAdminReport($success, $period, $recipients, $sent, $errorCount, $errors, $stats, $hasChanges)
    {
        try {
            // Alle Admins holen
            $userModel = new \App\Models\UserModel();
            $admins = $userModel->where('is_admin', 1)->findAll();

            if (empty($admins)) {
                return false;
            }

            $email = \Config\Services::email();

            foreach ($admins as $admin) {
                $email->clear();
                $email->setTo($admin['email']);
                $email->setFrom('noreply@ahnengalerie-pro.de', 'Ahnengalerie Pro');

                $subject = $success
                    ? "✅ Newsletter erfolgreich versendet - $period"
                    : "⚠️ Newsletter-Versand Report - $period";

                $email->setSubject($subject);

                $data = [
                    'success' => $success,
                    'period' => $period,
                    'recipients' => $recipients,
                    'sent' => $sent,
                    'error_count' => $errorCount,
                    'error_details' => $errors,
                    'stats' => $stats,
                    'has_changes' => $hasChanges
                ];

                $message = view('emails/newsletter_report', $data);
                $email->setMessage($message);
                $email->send();
            }

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Admin-Report konnte nicht versendet werden: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Hilfsfunktion für deutsche Monatsnamen
     */
    private function getGermanMonthName($month)
    {
        $months = [
            '01' => 'Januar',
            '02' => 'Februar',
            '03' => 'März',
            '04' => 'April',
            '05' => 'Mai',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'August',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Dezember'
        ];

        return $months[$month] ?? 'Unbekannt';
    }
}