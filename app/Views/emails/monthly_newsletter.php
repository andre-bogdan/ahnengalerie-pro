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

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #667eea;
        }

        .stat-box h3 {
            color: #667eea;
            margin: 0 0 10px 0;
            font-size: 18px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #999;
            font-size: 12px;
        }

        .divider {
            border-top: 2px solid #e0e0e0;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🌳 Ahnengalerie Pro</h1>
        <p>Monatlicher Rückblick - <?= $month_name ?> <?= $year ?></p>
    </div>
    <div class="content">
        <h2>Hallo <?= esc($username) ?>,</h2>

        <?php if ($has_changes): ?>
            <p>im <?= $month_name ?> gab es folgende Aktivitäten in Ihrer Ahnengalerie:</p>

            <?php if ($stats['new_persons'] > 0): ?>
                <div class="stat-box">
                    <h3>👥 Neue Personen</h3>
                    <div class="stat-number"><?= $stats['new_persons'] ?></div>
                    <div class="stat-label">
                        <?= $stats['new_persons'] === 1 ? 'Person wurde' : 'Personen wurden' ?> hinzugefügt
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($stats['updated_persons'] > 0): ?>
                <div class="stat-box">
                    <h3>✏️ Aktualisierte Personen</h3>
                    <div class="stat-number"><?= $stats['updated_persons'] ?></div>
                    <div class="stat-label">
                        <?= $stats['updated_persons'] === 1 ? 'Person wurde' : 'Personen wurden' ?> bearbeitet
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($stats['new_photos'] > 0): ?>
                <div class="stat-box">
                    <h3>📸 Neue Fotos</h3>
                    <div class="stat-number"><?= $stats['new_photos'] ?></div>
                    <div class="stat-label">
                        <?= $stats['new_photos'] === 1 ? 'Foto wurde' : 'Fotos wurden' ?> hochgeladen
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($stats['new_events'] > 0): ?>
                <div class="stat-box">
                    <h3>📅 Neue Ereignisse</h3>
                    <div class="stat-number"><?= $stats['new_events'] ?></div>
                    <div class="stat-label">
                        <?= $stats['new_events'] === 1 ? 'Ereignis wurde' : 'Ereignisse wurden' ?> hinzugefügt
                    </div>
                </div>
            <?php endif; ?>

            <!-- ✨ NEU: Geänderte Events -->
            <?php if ($stats['updated_events'] > 0): ?>
                <div class="stat-box">
                    <h3>📝 Aktualisierte Ereignisse</h3>
                    <div class="stat-number"><?= $stats['updated_events'] ?></div>
                    <div class="stat-label">
                        <?= $stats['updated_events'] === 1 ? 'Ereignis wurde' : 'Ereignisse wurden' ?> bearbeitet
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($stats['new_relationships'] > 0): ?>
                <div class="stat-box">
                    <h3>🔗 Neue Beziehungen</h3>
                    <div class="stat-number"><?= $stats['new_relationships'] ?></div>
                    <div class="stat-label">
                        <?= $stats['new_relationships'] === 1 ? 'Beziehung wurde' : 'Beziehungen wurden' ?> erfasst
                    </div>
                </div>
            <?php endif; ?>

            <!-- ✨ NEU: Geänderte Relationships -->
            <?php if ($stats['updated_relationships'] > 0): ?>
                <div class="stat-box">
                    <h3>🔄 Aktualisierte Beziehungen</h3>
                    <div class="stat-number"><?= $stats['updated_relationships'] ?></div>
                    <div class="stat-label">
                        <?= $stats['updated_relationships'] === 1 ? 'Beziehung wurde' : 'Beziehungen wurden' ?> bearbeitet
                    </div>
                </div>
            <?php endif; ?>

            <div class="divider"></div>
            <p style="text-align: center;">
                <a href="<?= $dashboard_url ?>" class="button">🔍 Änderungen ansehen</a>
            </p>
        <?php else: ?>
            <p>Im <?= $month_name ?> gab es keine Aktivitäten in Ihrer Ahnengalerie.</p>
            <div class="stat-box" style="text-align: center; border-left-color: #ffc107;">
                <h3>💡 Zeit für neue Entdeckungen!</h3>
                <p style="color: #666; margin: 15px 0;">
                    Haben Sie vielleicht alte Fotos oder Dokumente, die Sie hochladen möchten?<br>
                    Oder fehlen noch Details bei bestehenden Personen?
                </p>
                <a href="<?= $dashboard_url ?>" class="button">🌳 Jetzt vorbeischauen</a>
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>
                Sie erhalten diese E-Mail, weil Sie den monatlichen Newsletter abonniert haben.<br>
                <a href="<?= $profile_url ?>" style="color: #667eea;">Newsletter-Einstellungen ändern</a>
            </p>
        </div>
    </div>
</body>

</html>