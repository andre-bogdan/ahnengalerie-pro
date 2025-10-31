<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('favicon.svg') ?>">
    <title><?= $title ?? 'Dashboard' ?> - Ahnengalerie Pro</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
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
        }

        /* Navbar Links */
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            margin: 0 0.2rem;
            transition: all 0.3s;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
        }

        .navbar-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.25);
            color: white !important;
            font-weight: 600;
        }

        .navbar-nav .nav-link i {
            margin-right: 0.4rem;
        }

        /* Mobile Navbar */
        @media (max-width: 991px) {
            .navbar-nav .nav-link {
                padding: 0.75rem 1rem;
                margin: 0.2rem 0;
            }

            .navbar-collapse {
                background: rgba(0, 0, 0, 0.1);
                padding: 1rem;
                border-radius: 8px;
                margin-top: 1rem;
            }
        }

        .content-wrapper {
            padding: 2rem;
            min-height: calc(100vh - 56px);
            max-width: 1400px;
            margin: 0 auto;
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        /* Global Search Styles */
        #global-search {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            transition: all 0.3s;
        }

        #global-search:focus {
            background: white;
            color: #333;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        #global-search::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        #global-search:focus::placeholder {
            color: #999;
        }

        .search-dropdown {
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            max-height: 400px;
            overflow-y: auto;
            z-index: 1050;
        }

        .search-results-list {
            padding: 8px 0;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            transition: background 0.2s;
        }

        .search-result-item:hover {
            background: #f8f9fa;
        }

        .search-result-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
            border: 2px solid #e0e0e0;
        }

        .search-result-info {
            flex: 1;
        }

        .search-result-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 2px;
        }

        .search-result-extra {
            font-size: 0.85rem;
            color: #666;
        }

        .search-no-results {
            padding: 20px;
            text-align: center;
            color: #999;
            font-style: italic;
        }

        /* Scrollbar für Dropdown */
        .search-dropdown::-webkit-scrollbar {
            width: 6px;
        }

        .search-dropdown::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 3px;
        }

        .search-dropdown::-webkit-scrollbar-thumb:hover {
            background: #bbb;
        }

        /* Admin Divider */
        .navbar-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin: 0.5rem 0;
        }

        @media (min-width: 992px) {
            .navbar-divider {
                display: none;
            }
        }

        /*Animationen*/
        /* ✨ MODERNE EFFEKTE */

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Card Hover mit 3D-Lift */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        }

        /* Button Ripple */
        .btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:active::before {
            width: 300px;
            height: 300px;
        }

        /* Page Fade-in */
        .content-wrapper {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Alert Slide-in */
        .alert {
            animation: slideInRight 0.5s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Navbar Link Underline */
        .navbar-nav .nav-link {
            position: relative;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: white;
            transition: width 0.3s ease, left 0.3s ease;
        }

        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 100%;
            left: 0;
        }

        /* Icon Hover Animation */
        .nav-link i,
        .btn i {
            transition: transform 0.3s ease;
        }

        .nav-link:hover i,
        .btn:hover i {
            transform: rotate(10deg) scale(1.1);
        }

        /* Animierter Navbar-Gradient */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%) !important;
            background-size: 200% 200%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Navbar Shadow on Scroll */
        .navbar.scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2) !important;
        }
    </style>

    <?= $this->renderSection('styles') ?>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
                🌳 Ahnengalerie Pro
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Main Navigation -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() == 'dashboard' ? 'active' : '' ?>"
                            href="<?= base_url('dashboard') ?>">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'persons') !== false && !strpos(uri_string(), 'tree') ? 'active' : '' ?>"
                            href="<?= base_url('persons') ?>">
                            <i class="bi bi-people"></i>
                            Personen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'persons/tree') !== false ? 'active' : '' ?>"
                            href="<?= base_url('persons/tree') ?>">
                            <i class="bi bi-diagram-3"></i>
                            Stammbaum
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'photos') !== false ? 'active' : '' ?>"
                            href="<?= base_url('photos') ?>">
                            <i class="bi bi-images"></i>
                            Galerie
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'statistics') !== false ? 'active' : '' ?>"
                            href="<?= base_url('statistics') ?>">
                            <i class="bi bi-graph-up"></i>
                            Statistiken
                        </a>
                    </li>

                    <?php if (session('is_admin')): ?>
                        <!-- Admin Divider (nur mobile) -->
                        <li class="d-lg-none">
                            <div class="navbar-divider"></div>
                            <div class="text-white-50 px-3 py-1">
                                <small>ADMINISTRATION</small>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos(uri_string(), 'admin/users') !== false ? 'active' : '' ?>"
                                href="<?= base_url('admin/users') ?>">
                                <i class="bi bi-person-gear"></i>
                                Benutzer
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Right Side: Search & User -->
                <ul class="navbar-nav">
                    <!-- Help -->
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'help') !== false ? 'active' : '' ?>"
                            href="<?= base_url('help') ?>">
                            <i class="bi bi-question-circle"></i>
                            Hilfe
                        </a>
                    </li>
                    <!-- Global Search -->
                    <li class="nav-item">
                        <div class="position-relative mt-1" style="min-width: 250px;">
                            <input type="text" class="form-control form-control-sm" id="global-search"
                                placeholder="🔍 Personen suchen..." autocomplete="off">

                            <!-- Search Results Dropdown -->
                            <div id="search-results" class="search-dropdown" style="display: none;">
                                <div class="search-results-list"></div>
                            </div>
                        </div>
                    </li>

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <?= esc(session('username')) ?>
                            <?php if (session('is_admin')): ?>
                                <span class="badge bg-warning text-dark ms-1 d-none d-lg-inline">Admin</span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('profile') ?>">
                                    <i class="bi bi-person me-2"></i> Profil
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i> Abmelden
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content (Full Width) -->
    <div class="container-fluid">
        <main class="content-wrapper">
            <!-- Flash Messages -->
            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('info')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <?= session('info') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <?= $this->renderSection('scripts') ?>
    <script>
        // Global Search Funktionalität
        (function () {
            const searchInput = document.getElementById('global-search');
            const resultsContainer = document.getElementById('search-results');
            const resultsList = resultsContainer.querySelector('.search-results-list');
            let searchTimeout = null;

            // Event Listener für Eingabe
            searchInput.addEventListener('input', function (e) {
                const query = e.target.value.trim();

                // Clear timeout
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }

                // Mindestens 2 Zeichen
                if (query.length < 2) {
                    hideResults();
                    return;
                }

                // Debounce: Warte 300ms nach letzter Eingabe
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });

            // Suche durchführen
            function performSearch(query) {
                fetch(`<?= base_url('persons/search') ?>?q=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displayResults(data.results);
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
            }

            // Ergebnisse anzeigen
            function displayResults(results) {
                if (results.length === 0) {
                    resultsList.innerHTML = '<div class="search-no-results">Keine Personen gefunden</div>';
                    showResults();
                    return;
                }

                let html = '';
                results.forEach(person => {
                    html += `
                <a href="${person.url}" class="search-result-item">
                    <img src="${person.photo}" alt="${person.display}" class="search-result-photo">
                    <div class="search-result-info">
                        <div class="search-result-name">${person.display}</div>
                        ${person.extra ? `<div class="search-result-extra">${person.extra}</div>` : ''}
                    </div>
                </a>
            `;
                });

                resultsList.innerHTML = html;
                showResults();
            }

            // Ergebnisse anzeigen
            function showResults() {
                resultsContainer.style.display = 'block';
            }

            // Ergebnisse verstecken
            function hideResults() {
                resultsContainer.style.display = 'none';
            }

            // Schließen bei Klick außerhalb
            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                    hideResults();
                }
            });

            // Focus-Event
            searchInput.addEventListener('focus', function () {
                if (resultsList.children.length > 0) {
                    showResults();
                }
            });

            // ESC zum Schließen
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    hideResults();
                    searchInput.blur();
                }
            });
        })();
    </script>
    <script>
        // Navbar Shadow beim Scrollen
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Dashboard-Zahlen hochzählen
        document.addEventListener('DOMContentLoaded', function () {
            const statNumbers = document.querySelectorAll('.card-body h2');
            statNumbers.forEach(num => {
                const finalValue = parseInt(num.textContent);
                if (!isNaN(finalValue)) {
                    let current = 0;
                    const increment = finalValue / 50;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= finalValue) {
                            num.textContent = finalValue;
                            clearInterval(timer);
                        } else {
                            num.textContent = Math.floor(current);
                        }
                    }, 20);
                }
            });
        });
    </script>

</body>

</html>