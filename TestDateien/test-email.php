<?php
/**
 * Email-Test-Suite für Ahnengalerie Pro
 * WICHTIG: Nach Test löschen oder umbenennen!
 */

// Einfaches Passwort-Schutz
$testPassword = 'test123'; // Ändere das!
if (!isset($_GET['pw']) || $_GET['pw'] !== $testPassword) {
    die('Zugriff verweigert. Nutze: ?pw=test123');
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email-Test-Suite</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 20px auto; padding: 0 20px; }
        h1 { color: #333; }
        .test-section { background: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; }
        button:hover { background: #0056b3; }
        input[type="email"], input[type="text"] { width: 100%; padding: 8px; margin: 5px 0; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .test-box { border: 1px solid #ddd; padding: 15px; margin: 10px 0; background: white; }
    </style>
</head>
<body>
    <h1>📧 Email-Test-Suite - Ahnengalerie Pro</h1>
    
    <?php
    // PHP mail() Test
    echo '<div class="test-section">';
    echo '<h2>1️⃣ PHP mail() Funktion Test</h2>';
    
    if (function_exists('mail')) {
        echo '<div class="success">✅ PHP mail() Funktion ist verfügbar</div>';
    } else {
        echo '<div class="error">❌ PHP mail() Funktion ist NICHT verfügbar!</div>';
    }
    
    // PHP Konfiguration
    echo '<h3>PHP Mail-Konfiguration:</h3>';
    echo '<pre>';
    echo 'SMTP: ' . ini_get('SMTP') . "\n";
    echo 'smtp_port: ' . ini_get('smtp_port') . "\n";
    echo 'sendmail_path: ' . ini_get('sendmail_path') . "\n";
    echo 'sendmail_from: ' . ini_get('sendmail_from') . "\n";
    echo '</pre>';
    echo '</div>';
    
    // .env Konfiguration
    echo '<div class="test-section">';
    echo '<h2>2️⃣ .env Email-Konfiguration</h2>';
    
    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
        
        // Extrahiere Email-Settings
        preg_match('/email\.fromEmail\s*=\s*["\']?([^"\'\s]+)["\']?/i', $envContent, $fromEmail);
        preg_match('/email\.fromName\s*=\s*["\']?([^"\'\n]+)["\']?/i', $envContent, $fromName);
        preg_match('/email\.protocol\s*=\s*["\']?([^"\'\s]+)["\']?/i', $envContent, $protocol);
        
        echo '<pre>';
        echo 'From Email: ' . ($fromEmail[1] ?? 'NICHT GESETZT') . "\n";
        echo 'From Name: ' . ($fromName[1] ?? 'NICHT GESETZT') . "\n";
        echo 'Protocol: ' . ($protocol[1] ?? 'mail (Standard)') . "\n";
        echo '</pre>';
        
        if (empty($fromEmail[1])) {
            echo '<div class="warning">⚠️ email.fromEmail ist nicht in .env gesetzt!</div>';
        }
    } else {
        echo '<div class="error">❌ .env Datei nicht gefunden!</div>';
    }
    echo '</div>';
    
    // DNS/SPF Check
    echo '<div class="test-section">';
    echo '<h2>3️⃣ DNS & SPF-Record Check</h2>';
    
    $domain = 'ahnengalerie-pro.de';
    
    // MX Records
    if (getmxrr($domain, $mxhosts)) {
        echo '<div class="success">✅ MX-Records gefunden:</div>';
        echo '<ul>';
        foreach ($mxhosts as $mx) {
            echo '<li>' . htmlspecialchars($mx) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<div class="error">❌ Keine MX-Records gefunden für ' . htmlspecialchars($domain) . '</div>';
    }
    
    // SPF Record (TXT)
    $txtRecords = dns_get_record($domain, DNS_TXT);
    $spfFound = false;
    
    if ($txtRecords) {
        foreach ($txtRecords as $record) {
            if (isset($record['txt']) && stripos($record['txt'], 'v=spf1') !== false) {
                echo '<div class="success">✅ SPF-Record gefunden:</div>';
                echo '<pre>' . htmlspecialchars($record['txt']) . '</pre>';
                $spfFound = true;
                break;
            }
        }
    }
    
    if (!$spfFound) {
        echo '<div class="warning">⚠️ Kein SPF-Record gefunden. Emails könnten als Spam markiert werden!</div>';
        echo '<div class="info">';
        echo '<strong>Empfohlener SPF-Record:</strong><br>';
        echo '<code>v=spf1 mx a include:_spf.dogado.de ~all</code>';
        echo '</div>';
    }
    
    echo '</div>';
    ?>
    
    <!-- Test-Email senden -->
    <div class="test-section">
        <h2>4️⃣ Test-Email senden</h2>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_test'])) {
            $to = filter_var($_POST['test_email'], FILTER_VALIDATE_EMAIL);
            
            if (!$to) {
                echo '<div class="error">❌ Ungültige Email-Adresse!</div>';
            } else {
                $subject = 'Test-Email von Ahnengalerie Pro';
                $message = "Hallo!\n\n";
                $message .= "Dies ist eine Test-Email von Ahnengalerie Pro.\n\n";
                $message .= "Wenn Sie diese Email erhalten, funktioniert der Email-Versand!\n\n";
                $message .= "Zeitstempel: " . date('d.m.Y H:i:s') . "\n";
                $message .= "Server: " . $_SERVER['SERVER_NAME'] . "\n\n";
                $message .= "Viele Grüße\n";
                $message .= "Ihr Ahnengalerie-Team";
                
                $headers = "From: mail@ahnengalerie-pro.de\r\n";
                $headers .= "Reply-To: mail@ahnengalerie-pro.de\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                
                if (mail($to, $subject, $message, $headers)) {
                    echo '<div class="success">';
                    echo '✅ Email wurde erfolgreich versendet an: ' . htmlspecialchars($to) . '<br>';
                    echo 'Bitte prüfen Sie Ihr Postfach (auch Spam-Ordner!)';
                    echo '</div>';
                } else {
                    echo '<div class="error">❌ Email-Versand fehlgeschlagen!</div>';
                    echo '<div class="info">Prüfen Sie die Server-Logs für Details.</div>';
                }
            }
        }
        ?>
        
        <form method="POST">
            <div class="test-box">
                <label><strong>Email-Adresse für Test:</strong></label>
                <input type="email" name="test_email" placeholder="ihre-email@example.com" required>
                <button type="submit" name="send_test">📧 Test-Email senden</button>
            </div>
        </form>
    </div>
    
    <!-- HTML-Email Test -->
    <div class="test-section">
        <h2>5️⃣ HTML-Email Test (mit Template)</h2>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_html_test'])) {
            $to = filter_var($_POST['html_test_email'], FILTER_VALIDATE_EMAIL);
            
            if (!$to) {
                echo '<div class="error">❌ Ungültige Email-Adresse!</div>';
            } else {
                $subject = 'HTML Test-Email von Ahnengalerie Pro';
                
                // HTML Email Template
                $htmlMessage = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Email-Test erfolgreich!</h1>
        </div>
        <div class="content">
            <p>Hallo,</p>
            <p>wenn Sie diese HTML-Email sehen, funktioniert der Email-Versand mit HTML-Templates einwandfrei!</p>
            <p><strong>Test-Details:</strong></p>
            <ul>
                <li>Zeitstempel: ' . date('d.m.Y H:i:s') . '</li>
                <li>Server: ' . htmlspecialchars($_SERVER['SERVER_NAME']) . '</li>
                <li>PHP Version: ' . phpversion() . '</li>
            </ul>
            <p style="text-align: center;">
                <a href="https://unsere.ahnengalerie-pro.de" class="button">Zur Ahnengalerie</a>
            </p>
            <p>Viele Grüße<br>Ihr Ahnengalerie-Team</p>
        </div>
        <div class="footer">
            <p>Ahnengalerie Pro - Familienstammbaum-Verwaltung</p>
        </div>
    </div>
</body>
</html>';
                
                $headers = "From: mail@ahnengalerie-pro.de\r\n";
                $headers .= "Reply-To: mail@ahnengalerie-pro.de\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                
                if (mail($to, $subject, $htmlMessage, $headers)) {
                    echo '<div class="success">';
                    echo '✅ HTML-Email wurde erfolgreich versendet an: ' . htmlspecialchars($to) . '<br>';
                    echo 'Bitte prüfen Sie Ihr Postfach!';
                    echo '</div>';
                } else {
                    echo '<div class="error">❌ Email-Versand fehlgeschlagen!</div>';
                }
            }
        }
        ?>
        
        <form method="POST">
            <div class="test-box">
                <label><strong>Email-Adresse für HTML-Test:</strong></label>
                <input type="email" name="html_test_email" placeholder="ihre-email@example.com" required>
                <button type="submit" name="send_html_test">🎨 HTML-Email senden</button>
            </div>
        </form>
    </div>
    
    <!-- CodeIgniter Email-Service Test -->
    <div class="test-section">
        <h2>6️⃣ CodeIgniter EmailService Test</h2>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_ci_test'])) {
            // Lade CodeIgniter
            require __DIR__ . '/../app/Config/Paths.php';
            
            try {
                // Bootstrap CI
                require rtrim((new Config\Paths())->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
                
                // Load EmailService
                $emailService = new \App\Libraries\EmailService();
                
                $testEmail = filter_var($_POST['ci_test_email'], FILTER_VALIDATE_EMAIL);
                $testUsername = 'testuser_' . time();
                $testPassword = 'Test' . rand(1000, 9999) . '!';
                
                if (!$testEmail) {
                    throw new Exception('Ungültige Email-Adresse');
                }
                
                $result = $emailService->sendNewUserCredentials(
                    $testEmail,
                    $testUsername,
                    $testPassword
                );
                
                if ($result) {
                    echo '<div class="success">';
                    echo '✅ Email über EmailService versendet!<br>';
                    echo 'Test-Zugangsdaten:<br>';
                    echo 'Username: ' . htmlspecialchars($testUsername) . '<br>';
                    echo 'Passwort: ' . htmlspecialchars($testPassword);
                    echo '</div>';
                } else {
                    echo '<div class="error">❌ EmailService-Versand fehlgeschlagen!</div>';
                }
                
            } catch (Exception $e) {
                echo '<div class="error">❌ Fehler: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
        
        <form method="POST">
            <div class="test-box">
                <label><strong>Email für CI-Service Test:</strong></label>
                <input type="email" name="ci_test_email" placeholder="ihre-email@example.com" required>
                <p><small>Sendet eine Test-Email mit dem echten EmailService und Template</small></p>
                <button type="submit" name="send_ci_test">🚀 Mit EmailService senden</button>
            </div>
        </form>
    </div>
    
    <!-- Troubleshooting -->
    <div class="test-section">
        <h2>🔧 Troubleshooting</h2>
        
        <div class="test-box">
            <h3>Email kommt nicht an?</h3>
            <ol>
                <li><strong>Spam-Ordner prüfen!</strong> Oft landen Test-Emails im Spam.</li>
                <li><strong>Absender-Domain prüfen:</strong> mail@ahnengalerie-pro.de muss zur Domain passen</li>
                <li><strong>SPF-Record setzen</strong> (siehe oben)</li>
                <li><strong>Server-Logs prüfen:</strong> <code>writable/logs/</code></li>
                <li><strong>SMTP verwenden</strong> statt PHP mail() (siehe unten)</li>
            </ol>
        </div>
        
        <div class="test-box">
            <h3>SMTP statt PHP mail() nutzen</h3>
            <p>Füge in <code>.env</code> hinzu:</p>
            <pre>
email.protocol = smtp
email.SMTPHost = smtp.dogado.de
email.SMTPUser = mail@ahnengalerie-pro.de
email.SMTPPass = IhrPasswort
email.SMTPPort = 587
email.SMTPCrypto = tls</pre>
        </div>
    </div>
    
    <div class="warning" style="margin-top: 30px;">
        ⚠️ <strong>WICHTIG:</strong> Löschen oder umbenennen Sie diese Test-Datei nach dem Test!<br>
        <code>rm public/test-email.php</code>
    </div>
</body>
</html>