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
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info-box {
            background: white;
            padding: 15px;
            border-left: 4px solid #667eea;
            margin: 10px 0;
        }
        .meta {
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            margin-top: 20px;
        }
        strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0;">🌳 Ahnengalerie - Support-Anfrage</h2>
    </div>

    <div class="content">
        <div class="info-box">
            <p><strong>Von:</strong> <?= esc($name) ?></p>
            <p><strong>Email:</strong> <a href="mailto:<?= esc($email) ?>"><?= esc($email) ?></a></p>
            <p><strong>Betreff:</strong> <?= esc($subject) ?></p>
            <p><strong>Seite:</strong> <?= esc($currentPage) ?></p>
        </div>

        <h3>Nachricht:</h3>
        <div class="info-box">
            <?= nl2br(esc($message)) ?>
        </div>
    </div>

    <div class="meta">
        <p><strong>Technische Details:</strong></p>
        <p>
            <strong>Zeitstempel:</strong> <?= esc($timestamp) ?><br>
            <strong>IP-Adresse:</strong> <?= esc($ip) ?><br>
            <strong>User-Agent:</strong> <?= esc($userAgent) ?>
        </p>
    </div>

    <p style="text-align: center; color: #999; font-size: 12px;">
        Diese Email wurde automatisch von Ahnengalerie generiert.
    </p>
</body>
</html>