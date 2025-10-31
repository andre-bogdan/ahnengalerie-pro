<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-left: 4px solid #667eea;
            margin: 20px 0;
            border-radius: 5px;
        }
        .success-icon {
            text-align: center;
            font-size: 48px;
            color: #28a745;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌳 Ahnengalerie</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Support-Team</p>
        </div>

        <div class="content">
            <div class="success-icon">✅</div>
            
            <h2 style="text-align: center; color: #333;">Ihre Anfrage wurde empfangen!</h2>
            
            <p>Hallo <strong><?= esc($name) ?></strong>,</p>
            
            <p>vielen Dank für Ihre Nachricht! Wir haben Ihre Support-Anfrage erhalten und werden uns schnellstmöglich bei Ihnen melden.</p>

            <div class="info-box">
                <p style="margin: 0;"><strong>Ihre Anfrage:</strong></p>
                <p style="margin: 5px 0 0 0;"><strong>Betreff:</strong> <?= esc($subject) ?></p>
                <p style="margin: 10px 0 0 0;"><strong>Nachricht:</strong></p>
                <p style="margin: 5px 0 0 0; padding: 10px; background: white; border-radius: 3px;">
                    <?= nl2br(esc(substr($message, 0, 200))) ?><?= strlen($message) > 200 ? '...' : '' ?>
                </p>
                <p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">
                    <strong>Gesendet am:</strong> <?= esc($timestamp) ?>
                </p>
            </div>

            <h3>Was passiert jetzt?</h3>
            <ul style="line-height: 1.8;">
                <li>✅ Wir prüfen Ihre Anfrage</li>
                <li>📧 Sie erhalten eine Antwort per Email (in der Regel innerhalb von 24 Stunden)</li>
                <li>💬 Bei dringenden Fragen können Sie uns auch direkt kontaktieren</li>
            </ul>

            <p style="text-align: center;">
                <a href="<?= base_url() ?>" class="button">Zurück zur Ahnengalerie</a>
            </p>

            <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

            <p style="font-size: 14px; color: #666;">
                <strong>Hinweis:</strong> Bitte antworten Sie nicht auf diese Email. 
                Dies ist eine automatische Bestätigung. Wir melden uns über eine separate Email bei Ihnen.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0;">
                <strong>🌳 Ahnengalerie</strong><br>
                Ihre Familiengeschichte, professionell verwaltet
            </p>
            <p style="margin: 10px 0 0 0;">
                <a href="<?= base_url() ?>" style="color: #667eea; text-decoration: none;">Website besuchen</a> • 
                <a href="<?= base_url('help') ?>" style="color: #667eea; text-decoration: none;">Hilfe-Center</a>
            </p>
        </div>
    </div>

    <p style="text-align: center; color: #999; font-size: 11px; margin-top: 20px;">
        Diese Email wurde automatisch generiert.<br>
        © <?= date('Y') ?> Ahnengalerie - Alle Rechte vorbehalten
    </p>
</body>
</html>