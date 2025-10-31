<?php
namespace App\Libraries;

class EmailService
{
    protected $email;

    public function __construct()
    {
        $this->email = \Config\Services::email();
    }

    public function sendNewUserCredentials($recipientEmail, $username, $password)
    {
        $this->email->setTo($recipientEmail);
        $this->email->setSubject('Ihre Zugangsdaten für Ahnengalerie Pro');

        $message = view('emails/new_user_credentials', [
            'username' => $username,
            'password' => $password,
            'login_url' => base_url('login')
        ]);

        $this->email->setMessage($message);

        if ($this->email->send()) {
            log_message('info', 'Email gesendet an: ' . $recipientEmail);
            return true;
        } else {
            log_message('error', 'Email fehlgeschlagen: ' . $this->email->printDebugger());
            return false;
        }
    }

    public function sendTestEmail($recipientEmail)
    {
        $this->email->setTo($recipientEmail);
        $this->email->setSubject('Test-Email von Ahnengalerie Pro');
        $this->email->setMessage('<h1>Test!</h1><p>Funktioniert.</p>');
        return $this->email->send();
    }
}