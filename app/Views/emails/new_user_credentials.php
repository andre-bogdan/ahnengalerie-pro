<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                  color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border: 1px solid #e0e0e0; 
                   border-top: none; border-radius: 0 0 10px 10px; }
        .credentials { background: white; padding: 20px; border-radius: 8px; 
                      margin: 20px 0; border-left: 4px solid #667eea; }
        .credentials strong { display: block; margin-bottom: 5px; color: #667eea; }
        .credentials code { background: #f0f0f0; padding: 8px 12px; border-radius: 4px; 
                           display: inline-block; font-size: 16px; }
        .button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                 color: white; text-decoration: none; padding: 12px 30px; border-radius: 5px; margin: 20px 0; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; 
                  margin: 20px 0; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌳 Ahnengalerie Pro</h1>
        <p>Willkommen!</p>
    </div>
    <div class="content">
        <h2>Ihr Zugang wurde erstellt</h2>
        <p>Hallo,</p>
        <p>für Sie wurde ein Benutzerkonto erstellt:</p>
        <div class="credentials">
            <strong>🔐 Benutzername:</strong>
            <code><?= esc($username) ?></code>
            <br><br>
            <strong>🔑 Passwort:</strong>
            <code><?= esc($password) ?></code>
        </div>
        <div class="warning">
            <strong>⚠️ Wichtig:</strong><br>
            Bitte ändern Sie Ihr Passwort nach der ersten Anmeldung.
        </div>
        <p style="text-align: center;">
            <a href="<?= $login_url ?>" class="button">🔓 Jetzt anmelden</a>
        </p>
    </div>
</body>
</html>