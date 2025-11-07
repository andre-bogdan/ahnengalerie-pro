<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('persons') ?>">Personen</a></li>
            <li class="breadcrumb-item active">
                <?= esc($person['first_name'] . ' ' . $person['last_name']) ?>
            </li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Person Header Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Photo -->
                    <div class="col-md-3 text-center">
                        <?php if ($photo): ?>
                            <img src="<?= base_url('uploads/' . $photo['thumbnail_path']) ?>"
                                alt="<?= esc($person['first_name'] . ' ' . $person['last_name']) ?>"
                                class="img-fluid rounded shadow-sm photo-clickable"
                                style="max-width: 200px; cursor: pointer;"
                                onclick="openLightbox('<?= base_url('uploads/' . str_replace('thumb-', '', $photo['thumbnail_path'])) ?>', '<?= esc($person['first_name'] . ' ' . $person['last_name']) ?>')">
                        <?php else: ?>
                            <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm"
                                style="height: 200px; width: 200px; margin: 0 auto;">
                                <i class="bi bi-person display-1 text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Person Info -->
                    <div class="col-md-9">
                        <h2 class="mb-1">
                            <?= esc($person['first_name'] . ' ' . $person['last_name']) ?>
                            <?php if ($person['death_date']): ?>
                                <span class="badge bg-secondary">† </span>
                            <?php endif; ?>
                        </h2>

                        <?php if ($person['maiden_name']): ?>
                            <p class="text-muted mb-3">
                                <small>geb. <?= esc($person['maiden_name']) ?></small>
                            </p>
                        <?php endif; ?>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Geboren
                                </h6>
                                <p class="mb-0">
                                    <?php if ($person['birth_date']): ?>
                                        <?= date('d.m.Y', strtotime($person['birth_date'])) ?>
                                        <small class="text-muted">
                                            (<?= floor((time() - strtotime($person['birth_date'])) / 31536000) ?> Jahre)
                                        </small>
                                    <?php else: ?>
                                        <span class="text-muted">Unbekannt</span>
                                    <?php endif; ?>
                                </p>
                                <?php if ($person['birth_place']): ?>
                                    <p class="mb-0 small text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <?= esc($person['birth_place']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">
                                    <i class="bi bi-flower1 me-1"></i>
                                    Gestorben
                                </h6>
                                <p class="mb-0">
                                    <?php if ($person['death_date']): ?>
                                        <?= date('d.m.Y', strtotime($person['death_date'])) ?>
                                        <?php if ($person['birth_date']): ?>
                                            <small class="text-muted">
                                                (<?= floor((strtotime($person['death_date']) - strtotime($person['birth_date'])) / 31536000) ?>
                                                Jahre)
                                            </small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </p>
                                <?php if ($person['death_place']): ?>
                                    <p class="mb-0 small text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <?= esc($person['death_place']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row g-2">
                            <?php if ($person['gender']): ?>
                                <div class="col-auto">
                                    <span class="badge bg-light text-dark">
                                        <?php if ($person['gender'] === 'm'): ?>
                                            <i class="bi bi-gender-male text-primary"></i> Männlich
                                        <?php elseif ($person['gender'] === 'f'): ?>
                                            <i class="bi bi-gender-female text-danger"></i> Weiblich
                                        <?php else: ?>
                                            <i class="bi bi-gender-trans text-info"></i> Divers
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if ($person['occupation']): ?>
                                <div class="col-auto">
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-briefcase me-1"></i>
                                        <?= esc($person['occupation']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-3">
                            <a href="<?= base_url('persons/edit/' . $person['id']) ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i>
                                Bearbeiten
                            </a>
                            <a href="<?= base_url('persons/delete/' . $person['id']) ?>"
                                class="btn btn-outline-danger btn-sm"
                                onclick="return confirm('Person wirklich löschen?')">
                                <i class="bi bi-trash me-1"></i>
                                Löschen
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biography -->
        <?php if ($person['biography']): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-book me-2"></i>
                        Biografie
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-line;"><?= esc($person['biography']) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Timeline / Events -->
        <?php if (!empty($events)): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Lebensgeschichte / Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($events as $event): ?>
                            <div class="timeline-item mb-4">
                                <div class="timeline-marker">
                                    <i class="<?= \App\Models\EventModel::getEventTypeIcon($event['event_type']) ?>"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">
                                                <?= \App\Models\EventModel::getEventTypeLabel($event['event_type']) ?>
                                            </h6>
                                            <?php if ($event['event_date']): ?>
                                                <p class="text-muted small mb-0">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    <?= date('d.m.Y', strtotime($event['event_date'])) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($event['event_date']): ?>
                                            <span class="badge bg-light text-dark">
                                                <?= date('Y', strtotime($event['event_date'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($event['event_place']): ?>
                                        <p class="small mb-2">
                                            <i class="bi bi-geo-alt me-1 text-primary"></i>
                                            <?= esc($event['event_place']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if ($event['description']): ?>
                                        <p class="small mb-2"><?= nl2br(esc($event['description'])) ?></p>
                                    <?php endif; ?>

                                    <?php if (!empty($event['related_person'])): ?>
                                        <p class="small text-muted mb-0">
                                            <i class="bi bi-person me-1"></i>
                                            Mit:
                                            <a href="<?= base_url('persons/view/' . $event['related_person']['id']) ?>">
                                                <?= esc($event['related_person']['first_name'] . ' ' . $event['related_person']['last_name']) ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Photos Gallery -->
        <?php if (!empty($photos)): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-images me-2"></i>
                        Fotos (<?= count($photos) ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ($photos as $p): ?>
                            <div class="col-md-4">
                                <div class="card photo-card">
                                    <img src="<?= base_url('uploads/' . $p['thumbnail_path']) ?>"
                                        class="card-img-top photo-clickable" alt="<?= esc($p['title'] ?? 'Foto') ?>"
                                        style="cursor: pointer; height: 200px; object-fit: cover;"
                                        onclick="openLightbox('<?= base_url('uploads/' . str_replace('thumb-', '', $p['thumbnail_path'])) ?>', '<?= esc($p['title'] ?? 'Foto') ?>', '<?= $p['date_taken'] ? date('d.m.Y', strtotime($p['date_taken'])) : '' ?>')">
                                    <?php if ($p['title'] || $p['date_taken']): ?>
                                        <div class="card-body p-2">
                                            <?php if ($p['title']): ?>
                                                <p class="small mb-0"><strong><?= esc($p['title']) ?></strong></p>
                                            <?php endif; ?>
                                            <?php if ($p['date_taken']): ?>
                                                <p class="small text-muted mb-0">
                                                    <?= date('d.m.Y', strtotime($p['date_taken'])) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar: Relationships -->
    <div class="col-lg-4">
        <!-- Parents -->
        <?php if (!empty($parents)): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-people me-2"></i>
                        Eltern
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($parents as $parent): ?>
                        <a href="<?= base_url('persons/view/' . $parent['id']) ?>"
                            class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= esc($parent['first_name'] . ' ' . $parent['last_name']) ?></strong>
                                    <?php if ($parent['death_date']): ?>
                                        <span class="badge bg-secondary ms-1">† </span>
                                    <?php endif; ?>
                                    <br>
                                    <small class="text-muted">
                                        <?php if ($parent['gender'] === 'm'): ?>
                                            <i class="bi bi-gender-male text-primary"></i> Vater
                                        <?php elseif ($parent['gender'] === 'f'): ?>
                                            <i class="bi bi-gender-female text-danger"></i> Mutter
                                        <?php else: ?>
                                            Elternteil
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Spouses/Partners -->
        <?php if (!empty($spouses)): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-heart me-2"></i>
                        Partner/Ehepartner
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($spouses as $spouse): ?>
                        <a href="<?= base_url('persons/view/' . $spouse['id']) ?>"
                            class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= esc($spouse['first_name'] . ' ' . $spouse['last_name']) ?></strong>
                                    <?php if ($spouse['death_date']): ?>
                                        <span class="badge bg-secondary ms-1">† </span>
                                    <?php endif; ?>
                                    <br>
                                    <small class="text-muted">
                                        <?php if ($spouse['relationship_type'] === 'spouse'): ?>
                                            <i class="bi bi-heart-fill text-danger"></i> Ehepartner
                                        <?php else: ?>
                                            <i class="bi bi-heart text-danger"></i> Partner
                                        <?php endif; ?>
                                        <?php if ($spouse['start_date']): ?>
                                            (seit <?= date('Y', strtotime($spouse['start_date'])) ?>)
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Children -->
        <?php if (!empty($children)): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-person-hearts me-2"></i>
                        Kinder
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($children as $child): ?>
                        <a href="<?= base_url('persons/view/' . $child['id']) ?>"
                            class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= esc($child['first_name'] . ' ' . $child['last_name']) ?></strong>
                                    <?php if ($child['death_date']): ?>
                                        <span class="badge bg-secondary ms-1">† </span>
                                    <?php endif; ?>
                                    <br>
                                    <small class="text-muted">
                                        <?php if ($child['birth_date']): ?>
                                            *<?= date('Y', strtotime($child['birth_date'])) ?>
                                        <?php endif; ?>
                                    </small>
                                    &nbsp;&nbsp;&nbsp;
                                    <small class="text-muted">
                                        <?php if ($child['death_date']): ?>
                                            &#8224;<?= date('Y', strtotime($child['death_date'])) ?>
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Siblings -->
        <?php if (!empty($siblings)): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-people-fill me-2"></i>
                        Geschwister
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($siblings as $sibling): ?>
                        <a href="<?= base_url('persons/view/' . $sibling['id']) ?>"
                            class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= esc($sibling['first_name'] . ' ' . $sibling['last_name']) ?></strong>
                                    <?php if ($sibling['death_date']): ?>
                                        <span class="badge bg-secondary ms-1">† </span>
                                    <?php endif; ?>
                                    <br>
                                    <small class="text-muted">
                                        <?php if ($sibling['gender'] === 'm'): ?>
                                            <i class="bi bi-gender-male text-primary"></i> Bruder
                                        <?php elseif ($sibling['gender'] === 'f'): ?>
                                            <i class="bi bi-gender-female text-danger"></i> Schwester
                                        <?php else: ?>
                                            Geschwister
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- No relationships message -->
        <?php if (empty($parents) && empty($spouses) && empty($children) && empty($siblings)): ?>
            <div class="card">
                <div class="card-body text-center py-4">
                    <i class="bi bi-diagram-3 display-4 text-muted"></i>
                    <p class="text-muted mt-3 mb-2">
                        Noch keine Beziehungen angelegt.
                    </p>
                    <a href="<?= base_url('persons/edit/' . $person['id']) ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil me-1"></i>
                        Beziehungen hinzufügen
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Metadata -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Informationen
                </h6>
            </div>
            <div class="card-body">
                <p class="small mb-2">
                    <strong>Erstellt:</strong><br>
                    <?= date('d.m.Y H:i', strtotime($person['created_at'])) ?> Uhr
                </p>
                <p class="small mb-0">
                    <strong>Zuletzt geändert:</strong><br>
                    <?= date('d.m.Y H:i', strtotime($person['updated_at'])) ?> Uhr
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lightboxTitle">Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="lightboxImage" src="" alt="" class="img-fluid" style="max-height: 80vh; width: auto;">
            </div>
            <div class="modal-footer" id="lightboxFooter">
                <!-- Wird dynamisch befüllt -->
            </div>
        </div>
    </div>
</div>

<style>
    .photo-clickable {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .photo-clickable:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .photo-card {
        transition: transform 0.2s ease;
    }

    .photo-card:hover {
        transform: translateY(-5px);
    }

    /* Timeline Styles */
    .timeline {
        position: relative;
        padding-left: 40px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
    }

    .timeline-item {
        position: relative;
    }

    .timeline-marker {
        position: absolute;
        left: -40px;
        top: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
        z-index: 1;
    }

    .timeline-content {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        border-left: 3px solid #667eea;
    }

    .timeline-item:last-child .timeline-content {
        margin-bottom: 0;
    }
</style>

<script>
    function openLightbox(imageSrc, title, date = '') {
        const lightboxModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxTitle = document.getElementById('lightboxTitle');
        const lightboxFooter = document.getElementById('lightboxFooter');

        lightboxImage.src = imageSrc;
        lightboxTitle.textContent = title;

        if (date) {
            lightboxFooter.innerHTML = '<small class="text-muted"><i class="bi bi-calendar me-1"></i>' + date + '</small>';
        } else {
            lightboxFooter.innerHTML = '';
        }

        lightboxModal.show();
    }
</script>

<?= $this->endSection() ?>