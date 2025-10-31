<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <title><?= $title ?? 'Hilfe' ?> - Ahnengalerie Pro</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .content-wrapper {
            padding: 2rem;
            min-height: calc(100vh - 56px);
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }
    </style>
</head>
<body>
    <!-- Einfache Navigation (nur Logo + Zurück) -->
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="https://ahnengalerie-pro.de">
                🌳 Ahnengalerie Pro
            </a>
            <div>
                <a href="https://ahnengalerie-pro.de" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-left me-2"></i>Zur Startseite
                </a>
                <a href="<?= base_url('login') ?>" class="btn btn-sm btn-outline-light ms-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <main class="content-wrapper">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>