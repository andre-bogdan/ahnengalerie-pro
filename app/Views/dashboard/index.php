<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-speedometer2 me-2"></i>
            Dashboard
        </h1>
        <p class="text-muted mb-0">Willkommen zurück, <?= esc(session('username')) ?>!</p>
    </div>
    <div>
        <span class="badge bg-success">
            <i class="bi bi-clock me-1"></i>
            <?= date('d.m.Y H:i') ?> Uhr
        </span>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Persons -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Personen</h6>
                        <h2 class="mb-0"><?= $stats['persons'] ?></h2>
                    </div>
                    <div class="display-4 text-primary opacity-25">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="<?= base_url('persons') ?>" class="btn btn-sm btn-outline-primary">
                    Alle anzeigen <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Photos -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Fotos</h6>
                        <h2 class="mb-0"><?= $stats['photos'] ?></h2>
                    </div>
                    <div class="display-4 text-success opacity-25">
                        <i class="bi bi-images"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="<?= base_url('photos') ?>" class="btn btn-sm btn-outline-success">
                    Galerie öffnen <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Relationships -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Beziehungen</h6>
                        <h2 class="mb-0"><?= $stats['relationships'] ?></h2>
                    </div>
                    <div class="display-4 text-info opacity-25">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="<?= base_url('persons/tree') ?>" class="btn btn-sm btn-outline-info">
                    Stammbaum <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>


    <!-- Nächste Geburtstage -->
    <?php if (!empty($upcomingBirthdays)): ?>
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-cake2 me-2"></i>
                            Nächste Geburtstage
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php foreach ($upcomingBirthdays as $birthday): ?>
                                <a href="<?= base_url('persons/view/' . $birthday['person']['id']) ?>"
                                    class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= esc($birthday['person']['first_name'] . ' ' . $birthday['person']['last_name']) ?></strong>
                                            <?php if ($birthday['is_deceased']): ?>
                                                <span class="badge bg-secondary ms-1">†</span>
                                            <?php endif; ?>
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                <?= $birthday['next_birthday']->format('d.m.Y') ?>
                                                (<?= $birthday['is_deceased'] ? 'würde' : 'wird' ?>         <?= $birthday['age'] ?>
                                                Jahre)
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <?php if ($birthday['days_until'] === 0): ?>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-gift-fill me-1"></i>
                                                    Heute!
                                                </span>
                                            <?php elseif ($birthday['days_until'] === 1): ?>
                                                <span class="badge bg-warning">
                                                    Morgen
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark">
                                                    in <?= $birthday['days_until'] ?> Tagen
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Recent Persons -->
    <div class="row"></div>

    <!-- Users (Admin only) -->
    <?php if (session('is_admin')): ?>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Benutzer</h6>
                            <h2 class="mb-0"><?= $stats['users'] ?></h2>
                        </div>
                        <div class="display-4 text-warning opacity-25">
                            <i class="bi bi-person-gear"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-sm btn-outline-warning">
                        Verwalten <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Recent Persons -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Zuletzt hinzugefügt
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recent_persons)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">Noch keine Personen vorhanden.</p>
                        <a href="<?= base_url('persons/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Erste Person hinzufügen
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Geburtsdatum</th>
                                    <th>Geburtsort</th>
                                    <th>Hinzugefügt am</th>
                                    <th>Aktion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_persons as $person): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($person['first_name'] . ' ' . $person['last_name']) ?></strong>
                                        </td>
                                        <td>
                                            <?= $person['birth_date'] ? date('d.m.Y', strtotime($person['birth_date'])) : '-' ?>
                                        </td>
                                        <td><?= esc($person['birth_place'] ?? '-') ?></td>
                                        <td><?= date('d.m.Y', strtotime($person['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= base_url('persons/view/' . $person['id']) ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ✨ NEU: Zuletzt geänderte Personen -->
<?php if (!empty($recently_updated)): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info bg-opacity-10">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Zuletzt bearbeitet
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Geburtsdatum</th>
                                    <th>Geburtsort</th>
                                    <th>Bearbeitet am</th>
                                    <th>Aktion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recently_updated as $person): ?>
                                    <tr>
                                        <!--
                                        <td>
                                            <strong><?= esc($person['first_name'] . ' ' . $person['last_name']) ?></strong>
                                            <span class="badge bg-info ms-2">
                                                <i class="bi bi-pencil"></i> Geändert
                                            </span>
                                        </td>
                                        -->
                                        <td>
                                            <strong><?= esc($person['first_name'] . ' ' . $person['last_name']) ?></strong>
                                            <?php if (!empty($person['updated_by_name'])): ?>
                                                <span class="badge bg-info ms-2" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Zuletzt bearbeitet von <?= esc($person['updated_by_name']) ?> am <?= date('d.m.Y H:i', strtotime($person['updated_at'])) ?>">
                                                    <i class="bi bi-pencil"></i>
                                                    von <?= esc($person['updated_by_name']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-info ms-2">
                                                    <i class="bi bi-pencil"></i> Geändert
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $person['birth_date'] ? date('d.m.Y', strtotime($person['birth_date'])) : '-' ?>
                                        </td>
                                        <td><?= esc($person['birth_place'] ?? '-') ?></td>
                                        <td>
                                            <span class="text-info">
                                                <i class="bi bi-clock"></i>
                                                <?= date('d.m.Y H:i', strtotime($person['updated_at'])) ?> Uhr
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                Erstellt: <?= date('d.m.Y', strtotime($person['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('persons/view/' . $person['id']) ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= base_url('persons/edit/' . $person['id']) ?>"
                                                class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->section('scripts') ?>
<script>
// Tooltips aktivieren
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>