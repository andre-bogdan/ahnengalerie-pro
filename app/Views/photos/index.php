<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Filter Panel */
    .filter-panel {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .filter-panel .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    /* Stats Cards */
    .stat-card {
        background: var(--primary-gradient);
        color: white;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
    }

    .stat-card .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    /* Photo Grid - Masonry Layout */
    .photo-grid {
        max-width: 1400px;
        margin: 0 auto;
    }

    .photo-card {
        width: calc(25% - 15px); /* 4 Spalten auf Desktop */
        margin-bottom: 20px;
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        background: white;
        cursor: pointer;
        break-inside: avoid;
    }

    @media (max-width: 1200px) {
        .photo-card {
            width: calc(33.333% - 14px); /* 3 Spalten auf Tablet */
        }
    }

    @media (max-width: 768px) {
        .photo-card {
            width: calc(50% - 10px); /* 2 Spalten auf kleinen Tablets */
        }
    }

    @media (max-width: 480px) {
        .photo-card {
            width: 100%; /* 1 Spalte auf Mobile */
        }
    }

    .photo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .photo-card-image {
        width: 100%;
        height: auto;
        display: block;
    }

    .photo-card-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 100%);
        color: white;
        padding: 1rem;
        transform: translateY(100%);
        transition: transform 0.3s;
    }

    .photo-card:hover .photo-card-overlay {
        transform: translateY(0);
    }

    .photo-card-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .photo-card-meta {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    /* Lightbox */
    .lightbox {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        padding: 2rem;
    }

    .lightbox.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lightbox-content {
        max-width: 90vw;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .lightbox-image {
        max-width: 100%;
        max-height: 70vh;
        object-fit: contain;
        border-radius: 10px;
    }

    .lightbox-info {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 1rem;
        max-width: 600px;
        width: 100%;
    }

    .lightbox-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
    }

    .lightbox-close:hover {
        background: #f8f9fa;
    }

    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: white;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
    }

    .lightbox-nav:hover {
        background: #f8f9fa;
    }

    .lightbox-nav.prev {
        left: 1rem;
    }

    .lightbox-nav.next {
        right: 1rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }

    /* Loading Indicator */
    .loading-indicator {
        text-align: center;
        padding: 2rem;
        display: none;
    }

    .loading-indicator.active {
        display: block;
    }

    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #667eea;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">
        <i class="bi bi-images me-2"></i>
        Foto-Galerie
    </h1>
</div>

<!-- Statistiken -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-value"><?= number_format($stats['total']) ?></div>
            <div class="stat-label">Fotos gesamt</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-value"><?= number_format($stats['filtered']) ?></div>
            <div class="stat-label">Angezeigt</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-value"><?= number_format($stats['persons_with_photos']) ?></div>
            <div class="stat-label">Personen mit Fotos</div>
        </div>
    </div>
</div>

<!-- Filter Panel -->
<div class="filter-panel">
    <form method="get" action="<?= base_url('photos') ?>" class="row g-3">
        <!-- Suche -->
        <div class="col-md-3">
            <label for="search" class="form-label">
                <i class="bi bi-search me-1"></i> Suche
            </label>
            <input type="text" class="form-control" id="search" name="search"
                placeholder="Titel, Beschreibung..." value="<?= esc($filters['search']) ?>">
        </div>

        <!-- Person Filter -->
        <div class="col-md-3">
            <label for="person" class="form-label">
                <i class="bi bi-person me-1"></i> Person
            </label>
            <select class="form-select" id="person" name="person">
                <option value="">Alle Personen</option>
                <?php foreach ($persons as $person): ?>
                    <option value="<?= $person['id'] ?>" <?= $filters['person_id'] == $person['id'] ? 'selected' : '' ?>>
                        <?= esc($person['first_name'] . ' ' . $person['last_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Jahr Filter -->
        <div class="col-md-2">
            <label for="year" class="form-label">
                <i class="bi bi-calendar me-1"></i> Jahr
            </label>
            <select class="form-select" id="year" name="year">
                <option value="">Alle Jahre</option>
                <?php foreach ($years as $yearRow): ?>
                    <?php if (!empty($yearRow['year'])): ?>
                        <option value="<?= $yearRow['year'] ?>" <?= $filters['year'] == $yearRow['year'] ? 'selected' : '' ?>>
                            <?= $yearRow['year'] ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Ort Filter -->
        <div class="col-md-2">
            <label for="location" class="form-label">
                <i class="bi bi-geo-alt me-1"></i> Ort
            </label>
            <select class="form-select" id="location" name="location">
                <option value="">Alle Orte</option>
                <?php foreach ($locations as $loc): ?>
                    <option value="<?= esc($loc['location']) ?>" <?= $filters['location'] == $loc['location'] ? 'selected' : '' ?>>
                        <?= esc($loc['location']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Sortierung -->
        <div class="col-md-2">
            <label for="sort" class="form-label">
                <i class="bi bi-sort-down me-1"></i> Sortierung
            </label>
            <select class="form-select" id="sort" name="sort">
                <option value="newest" <?= $filters['sort'] == 'newest' ? 'selected' : '' ?>>Neueste zuerst</option>
                <option value="oldest" <?= $filters['sort'] == 'oldest' ? 'selected' : '' ?>>Älteste zuerst</option>
                <option value="date_taken_desc" <?= $filters['sort'] == 'date_taken_desc' ? 'selected' : '' ?>>Aufnahmedatum ↓</option>
                <option value="date_taken_asc" <?= $filters['sort'] == 'date_taken_asc' ? 'selected' : '' ?>>Aufnahmedatum ↑</option>
                <option value="person" <?= $filters['sort'] == 'person' ? 'selected' : '' ?>>Nach Person</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel me-1"></i> Filtern
            </button>
            <a href="<?= base_url('photos') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Zurücksetzen
            </a>
        </div>
    </form>
</div>

<!-- Photo Grid -->
<?php if (empty($photos)): ?>
    <div class="empty-state">
        <i class="bi bi-images"></i>
        <h3>Keine Fotos gefunden</h3>
        <p class="text-muted">Versuchen Sie andere Filter oder laden Sie neue Fotos hoch.</p>
    </div>
<?php else: ?>
    <div class="photo-grid">
        <?php foreach ($photos as $photo): ?>
            <div class="photo-card" data-photo-id="<?= $photo['id'] ?>">
                <img src="<?= base_url('uploads/' . ($photo['thumbnail_path'] ?? $photo['file_path'])) ?>"
                    alt="<?= esc($photo['title'] ?? 'Foto') ?>" class="photo-card-image">

                <div class="photo-card-overlay">
                    <div class="photo-card-title">
                        <?= esc($photo['title'] ?? 'Ohne Titel') ?>
                    </div>
                    <div class="photo-card-meta">
                        <i class="bi bi-person me-1"></i>
                        <?= esc($photo['first_name'] . ' ' . $photo['last_name']) ?>
                        <?php if ($photo['date_taken']): ?>
                            <br>
                            <i class="bi bi-calendar3 me-1"></i>
                            <?= date('d.m.Y', strtotime($photo['date_taken'])) ?>
                        <?php endif; ?>
                        <?php if ($photo['location']): ?>
                            <br>
                            <i class="bi bi-geo-alt me-1"></i>
                            <?= esc($photo['location']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Loading Indicator für Infinite Scroll -->
<div class="loading-indicator" id="loadingIndicator">
    <div class="loading-spinner"></div>
    <p class="text-muted mt-2">Lade weitere Fotos...</p>
</div>

<!-- Sentinel für Intersection Observer -->
<div id="sentinel" style="height: 1px;"></div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <button class="lightbox-close" onclick="closeLightbox()">×</button>
    <button class="lightbox-nav prev" onclick="navigateLightbox(-1)">‹</button>
    <button class="lightbox-nav next" onclick="navigateLightbox(1)">›</button>

    <div class="lightbox-content">
        <img src="" alt="" class="lightbox-image" id="lightbox-image">
        <div class="lightbox-info" id="lightbox-info">
            <!-- Wird dynamisch gefüllt -->
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Masonry.js -->
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>

<script>
    // Photo Data für Lightbox (wird dynamisch erweitert)
    let photos = <?= json_encode(array_values($photos)) ?>;
    let currentPhotoIndex = 0;

    // Infinite Scroll State
    let currentPage = 1;
    let isLoading = false;
    let hasMorePhotos = <?= $hasMore ? 'true' : 'false' ?>;

    // Current Filters (für AJAX)
    const currentFilters = {
        person: '<?= $filters['person_id'] ?? '' ?>',
        year: '<?= $filters['year'] ?? '' ?>',
        location: '<?= $filters['location'] ?? '' ?>',
        search: '<?= $filters['search'] ?? '' ?>',
        sort: '<?= $filters['sort'] ?? 'newest' ?>'
    };

    // Masonry initialisieren
    let masonryGrid;
    function initMasonry() {
        const grid = document.querySelector('.photo-grid');
        if (!grid) return;

        masonryGrid = new Masonry(grid, {
            itemSelector: '.photo-card',
            columnWidth: '.photo-card',
            percentPosition: true,
            gutter: 20
        });

        // Masonry neu berechnen nach Bildladen
        imagesLoaded(grid, function() {
            masonryGrid.layout();
        });
    }

    // Photo Card Click Events
    function attachPhotoCardEvents() {
        document.querySelectorAll('.photo-card').forEach((card, index) => {
            // Remove old event listeners
            const newCard = card.cloneNode(true);
            card.parentNode.replaceChild(newCard, card);
            
            newCard.addEventListener('click', () => {
                currentPhotoIndex = index;
                openLightbox();
            });
        });
    }

    // Lightbox öffnen
    function openLightbox() {
        const photo = photos[currentPhotoIndex];
        const lightbox = document.getElementById('lightbox');
        const image = document.getElementById('lightbox-image');
        const info = document.getElementById('lightbox-info');

        // Bild setzen
        image.src = '<?= base_url() ?>uploads/' + photo.file_path;
        image.alt = photo.title || 'Foto';

        // Info setzen
        let infoHtml = `
            <h4>${photo.title || 'Ohne Titel'}</h4>
            <p class="text-muted mb-2">
                <i class="bi bi-person me-1"></i>
                <a href="<?= base_url('persons/view') ?>/${photo.person_id}">
                    ${photo.first_name} ${photo.last_name}
                </a>
            </p>
        `;

        if (photo.date_taken) {
            const dateTaken = new Date(photo.date_taken);
            infoHtml += `
                <p class="mb-2">
                    <i class="bi bi-calendar3 me-1"></i>
                    <strong>Aufnahmedatum:</strong> ${dateTaken.toLocaleDateString('de-DE')}
                </p>
            `;
        }

        if (photo.location) {
            infoHtml += `
                <p class="mb-2">
                    <i class="bi bi-geo-alt me-1"></i>
                    <strong>Ort:</strong> ${photo.location}
                </p>
            `;
        }

        if (photo.description) {
            infoHtml += `<p class="mt-3">${photo.description}</p>`;
        }

        info.innerHTML = infoHtml;

        // Lightbox anzeigen
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Lightbox schließen
    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Navigation
    function navigateLightbox(direction) {
        currentPhotoIndex += direction;

        // Wrap around
        if (currentPhotoIndex < 0) {
            currentPhotoIndex = photos.length - 1;
        } else if (currentPhotoIndex >= photos.length) {
            currentPhotoIndex = 0;
        }

        openLightbox();
    }

    // Weitere Fotos laden (AJAX)
    function loadMorePhotos() {
        if (isLoading || !hasMorePhotos) return;

        isLoading = true;
        document.getElementById('loadingIndicator').classList.add('active');

        // Build URL with filters
        const params = new URLSearchParams({
            page: currentPage + 1,
            ...currentFilters
        });

        fetch(`<?= base_url('photos') ?>?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.photos && data.photos.length > 0) {
                // Photos zum Array hinzufügen
                photos = photos.concat(data.photos);

                // HTML erstellen
                const grid = document.querySelector('.photo-grid');
                data.photos.forEach(photo => {
                    const photoCard = createPhotoCard(photo);
                    grid.appendChild(photoCard);
                    
                    // Masonry neu berechnen wenn Bild geladen
                    const img = photoCard.querySelector('img');
                    img.onload = () => {
                        if (masonryGrid) {
                            masonryGrid.appended(photoCard);
                            masonryGrid.layout();
                        }
                    };
                });

                // Event Listeners aktualisieren
                attachPhotoCardEvents();

                currentPage = data.nextPage;
                hasMorePhotos = data.hasMore;
            } else {
                hasMorePhotos = false;
            }

            isLoading = false;
            document.getElementById('loadingIndicator').classList.remove('active');
        })
        .catch(error => {
            console.error('Error loading photos:', error);
            isLoading = false;
            document.getElementById('loadingIndicator').classList.remove('active');
        });
    }

    // Photo Card HTML erstellen
    function createPhotoCard(photo) {
        const div = document.createElement('div');
        div.className = 'photo-card';
        div.dataset.photoId = photo.id;

        const img = document.createElement('img');
        img.src = '<?= base_url() ?>uploads/' + (photo.thumbnail_path || photo.file_path);
        img.alt = photo.title || 'Foto';
        img.className = 'photo-card-image';

        const overlay = document.createElement('div');
        overlay.className = 'photo-card-overlay';

        let overlayHtml = `
            <div class="photo-card-title">${photo.title || 'Ohne Titel'}</div>
            <div class="photo-card-meta">
                <i class="bi bi-person me-1"></i>
                ${photo.first_name} ${photo.last_name}
        `;

        if (photo.date_taken) {
            const date = new Date(photo.date_taken);
            overlayHtml += `
                <br>
                <i class="bi bi-calendar3 me-1"></i>
                ${date.toLocaleDateString('de-DE')}
            `;
        }

        if (photo.location) {
            overlayHtml += `
                <br>
                <i class="bi bi-geo-alt me-1"></i>
                ${photo.location}
            `;
        }

        overlayHtml += `</div>`;
        overlay.innerHTML = overlayHtml;

        div.appendChild(img);
        div.appendChild(overlay);

        return div;
    }

    // Intersection Observer für Infinite Scroll
    const sentinel = document.getElementById('sentinel');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && hasMorePhotos && !isLoading) {
                loadMorePhotos();
            }
        });
    }, {
        rootMargin: '200px' // Lade 200px bevor Sentinel sichtbar wird
    });

    observer.observe(sentinel);

    // Keyboard Navigation
    document.addEventListener('keydown', (e) => {
        const lightbox = document.getElementById('lightbox');
        if (!lightbox.classList.contains('active')) return;

        switch (e.key) {
            case 'Escape':
                closeLightbox();
                break;
            case 'ArrowLeft':
                navigateLightbox(-1);
                break;
            case 'ArrowRight':
                navigateLightbox(1);
                break;
        }
    });

    // Click outside to close
    document.getElementById('lightbox').addEventListener('click', (e) => {
        if (e.target.id === 'lightbox') {
            closeLightbox();
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initMasonry();
        attachPhotoCardEvents();
    });
</script>
<?= $this->endSection() ?>