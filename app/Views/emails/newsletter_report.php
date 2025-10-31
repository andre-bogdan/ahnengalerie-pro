<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: #f8f9fa;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }

        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .error-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .info-box {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .stat-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            margin: 15px 0;
            border-collapse: collapse;
        }

        .stat-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .stat-table td:first-child {
            font-weight: bold;
            color: #667eea;
            width: 50%;
        }

        .stat-table tr:last-child td {
            border-bottom: none;
        }

        .error-details {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 4px;
            font-size: 12px;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🤖 Newsletter-Versand Report</h1>
        <p><?= date('d.m.Y H:i') ?> Uhr</p>
    </div>
    <div class="content">
        <h2>Hallo Administrator,</h2>

        <?php if ($success): ?>
            <div class="success-box">
                <strong>✅ Newsletter erfolgreich versendet!</strong>
            </div>
        <?php else: ?>
            <div class="error-box">
                <strong>⚠️ Newsletter-Versand mit Problemen</strong>
            </div>
        <?php endif; ?>

        <h3>📊 Zusammenfassung</h3>
        <table class="stat-table">
            <tr>
                <td>Zeitraum:</td>
                <td><?= esc($period) ?></td>
            </tr>
            <tr>
                <td>Empfänger (gesamt):</td>
                <td><?= $recipients ?></td>
            </tr>
            <tr>
                <td>Erfolgreich versendet:</td>
                <td><strong style="color: #28a745;"><?= $sent ?></strong></td>
            </tr>
            <tr>
                <td>Fehler:</td>
                <td><strong style="color: <?= $error_count > 0 ? '#dc3545' : '#28a745' ?>;">
                        <?= $error_count ?>
                    </strong></td>
            </tr>
        </table>

        <h3>📈 Aktivitäten im <?= esc($period) ?></h3>
        <table class="stat-table">
            <tr>
                <td>👥 Neue Personen:</td>
                <td><?= $stats['new_persons'] ?></td>
            </tr>
            <tr>
                <td>✏️ Aktualisierte Personen:</td>
                <td><?= $stats['updated_persons'] ?></td>
            </tr>
            <tr>
                <td>📸 Neue Fotos:</td>
                <td><?= $stats['new_photos'] ?></td>
            </tr>
            <tr>
                <td>📅 Neue Ereignisse:</td>
                <td><?= $stats['new_events'] ?></td>
            </tr>
            <!-- ✨ NEU -->
            <tr>
                <td>📝 Aktualisierte Ereignisse:</td>
                <td><?= $stats['updated_events'] ?></td>
            </tr>
            <tr>
                <td>🔗 Neue Beziehungen:</td>
                <td><?= $stats['new_relationships'] ?></td>
            </tr>
            <!-- ✨ NEU -->
            <tr>
                <td>🔄 Aktualisierte Beziehungen:</td>
                <td><?= $stats['updated_relationships'] ?></td>
            </tr>
        </table>

        <?php if (!$has_changes): ?>
            <div class="info-box">
                <strong>ℹ️ Hinweis:</strong> Es gab keine Aktivitäten im letzten Monat.
                Benutzer haben eine Motivations-Email erhalten.
            </div>
        <?php endif; ?>

        <?php if (!empty($error_details)): ?>
            <h3>⚠️ Fehler-Details</h3>
            <?php foreach ($error_details as $error): ?>
                <div class="error-details">
                    <strong>Email:</strong> <?= esc($error['email']) ?><br>
                    <strong>Fehler:</strong> <?= esc($error['error']) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="info-box" style="margin-top: 30px;">
            <small>
                <strong>💡 Tipp:</strong> Diese Email wird automatisch nach jedem Newsletter-Versand generiert.
            </small>
        </div>
    </div>
</body>

</html>