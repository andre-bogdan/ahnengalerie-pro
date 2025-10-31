<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('persons') ?>">Personen</a></li>
            <li class="breadcrumb-item active">
                <?= $person ? 'Bearbeiten' : 'Hinzufügen' ?>
            </li>
        </ol>
    </nav>

    <h1 class="h3 mb-0">
        <i class="bi bi-person-<?= $person ? 'gear' : 'plus' ?> me-2"></i>
        <?= $person ? 'Person bearbeiten' : 'Person hinzufügen' ?>
    </h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="<?= $person ? base_url('persons/update/' . $person['id']) : base_url('persons/store') ?>"
                    method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <!-- Basic Information -->
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-person-badge me-2"></i>
                        Persönliche Daten
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">
                                Vorname <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= $validation && $validation->hasError('first_name') ? 'is-invalid' : '' ?>"
                                id="first_name" name="first_name"
                                value="<?= old('first_name', $person['first_name'] ?? '') ?>" required>
                            <?php if ($validation && $validation->hasError('first_name')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('first_name') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label for="last_name" class="form-label">
                                Nachname <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= $validation && $validation->hasError('last_name') ? 'is-invalid' : '' ?>"
                                id="last_name" name="last_name"
                                value="<?= old('last_name', $person['last_name'] ?? '') ?>" required>
                            <?php if ($validation && $validation->hasError('last_name')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('last_name') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label for="maiden_name" class="form-label">
                                Geburtsname
                                <small class="text-muted">(falls abweichend)</small>
                            </label>
                            <input type="text" class="form-control" id="maiden_name" name="maiden_name"
                                value="<?= old('maiden_name', $person['maiden_name'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="gender" class="form-label">Geschlecht</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">Bitte wählen...</option>
                                <option value="m" <?= old('gender', $person['gender'] ?? '') === 'm' ? 'selected' : '' ?>>
                                    Männlich
                                </option>
                                <option value="f" <?= old('gender', $person['gender'] ?? '') === 'f' ? 'selected' : '' ?>>
                                    Weiblich
                                </option>
                                <option value="x" <?= old('gender', $person['gender'] ?? '') === 'x' ? 'selected' : '' ?>>
                                    Divers
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Birth Information -->
                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-calendar-event me-2"></i>
                        Geburt
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="birth_date" class="form-label">Geburtsdatum</label>
                            <input type="date"
                                class="form-control <?= $validation && $validation->hasError('birth_date') ? 'is-invalid' : '' ?>"
                                id="birth_date" name="birth_date"
                                value="<?= old('birth_date', $person['birth_date'] ?? '') ?>">
                            <?php if ($validation && $validation->hasError('birth_date')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('birth_date') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label for="birth_place" class="form-label">Geburtsort</label>
                            <input type="text" class="form-control" id="birth_place" name="birth_place"
                                placeholder="z.B. München, Deutschland"
                                value="<?= old('birth_place', $person['birth_place'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Death Information -->
                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-flower1 me-2"></i>
                        Tod
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="death_date" class="form-label">Todesdatum</label>
                            <input type="date"
                                class="form-control <?= $validation && $validation->hasError('death_date') ? 'is-invalid' : '' ?>"
                                id="death_date" name="death_date"
                                value="<?= old('death_date', $person['death_date'] ?? '') ?>">
                            <?php if ($validation && $validation->hasError('death_date')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('death_date') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label for="death_place" class="form-label">Todesort</label>
                            <input type="text" class="form-control" id="death_place" name="death_place"
                                placeholder="z.B. Berlin, Deutschland"
                                value="<?= old('death_place', $person['death_place'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Weitere Informationen
                    </h5>

                    <div class="mb-3">
                        <label for="occupation" class="form-label">Beruf</label>
                        <input type="text" class="form-control" id="occupation" name="occupation"
                            placeholder="z.B. Lehrer, Arzt, Kaufmann..."
                            value="<?= old('occupation', $person['occupation'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="biography" class="form-label">Biografie / Notizen</label>
                        <textarea class="form-control" id="biography" name="biography" rows="5"
                            placeholder="Lebensgeschichte, wichtige Ereignisse, besondere Eigenschaften..."><?= old('biography', $person['biography'] ?? '') ?></textarea>
                        <div class="form-text">
                            Hier können Sie ausführliche Informationen zur Person eintragen.
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-image me-2"></i>
                        Foto
                    </h5>

                    <!-- NEU: Vorhandene Fotos anzeigen (nur beim Bearbeiten) -->
                    <?php if ($person && !empty($photos)): ?>
                        <div class="mb-4">
                            <label class="form-label d-block">Vorhandene Fotos</label>
                            <div class="row g-3">
                                <?php foreach ($photos as $p): ?>
                                    <div class="col-md-4">
                                        <div class="card <?= $p['is_primary'] ? 'border-success' : '' ?>">
                                            <img src="<?= base_url('uploads/' . $p['thumbnail_path']) ?>" class="card-img-top"
                                                alt="<?= esc($p['title'] ?? 'Foto') ?>"
                                                style="height: 150px; object-fit: cover;">
                                            <div class="card-body p-2">
                                                <?php if ($p['title']): ?>
                                                    <p class="small mb-1"><strong><?= esc($p['title']) ?></strong></p>
                                                <?php endif; ?>

                                                <?php if ($p['is_primary']): ?>
                                                    <span class="badge bg-success mb-2 w-100">
                                                        <i class="bi bi-star-fill"></i> Hauptfoto
                                                    </span>
                                                <?php else: ?>
                                                    <a href="<?= base_url('persons/set-primary-photo/' . $p['id']) ?>"
                                                        class="btn btn-sm btn-outline-success w-100 mb-2">
                                                        <i class="bi bi-star"></i> Als Hauptfoto markieren
                                                    </a>
                                                <?php endif; ?>

                                                <div class="d-grid">
                                                    <a href="<?= base_url('persons/delete-photo/' . $p['id']) ?>"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Foto wirklich löschen?')">
                                                        <i class="bi bi-trash me-1"></i> Löschen
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <hr class="my-4">
                        </div>
                    <?php endif; ?>

                    <!-- Neues Foto hochladen -->
                    <div class="mb-3">
                        <label for="photo" class="form-label">
                            <?= $person ? 'Neues Foto hochladen (optional)' : 'Foto hochladen (optional)' ?>
                        </label>
                        <input type="file" class="form-control" id="photo" name="photo"
                            accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                        <div class="form-text">
                            Erlaubt: JPG, PNG, GIF, WebP. Max. 5 MB.
                        </div>
                    </div>

                    <!-- NEU: Titel für das Foto -->
                    <div class="mb-3">
                        <label for="photo_title" class="form-label">
                            Foto-Titel <small class="text-muted">(optional)</small>
                        </label>
                        <input type="text" class="form-control" id="photo_title" name="photo_title"
                            placeholder="z.B. Hochzeitsfoto 1985, Schulabschluss..." value="<?= old('photo_title') ?>">
                        <div class="form-text">
                            Geben Sie dem Foto einen aussagekräftigen Titel.
                        </div>
                    </div>

                    <!-- Relationships (only when editing) -->
                    <?php if ($person): ?>
                        <h5 class="border-bottom pb-2 mb-3 mt-4">
                            <i class="bi bi-diagram-3 me-2"></i>
                            Beziehungen
                        </h5>

                        <div class="row g-3 mb-4">
                            <!-- Parents -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                                        <h6 class="mb-0 small">
                                            <i class="bi bi-people me-1"></i>
                                            Eltern
                                        </h6>
                                        <?php if (count($parents ?? []) < 2): ?>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#addParentModal">
                                                <i class="bi bi-plus-circle"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body p-2">
                                        <?php if (!empty($parents)): ?>
                                            <ul class="list-unstyled mb-0 small">
                                                <?php foreach ($parents as $parent): ?>
                                                    <li class="d-flex justify-content-between align-items-center mb-1">
                                                        <span>
                                                            <?= esc($parent['first_name'] . ' ' . $parent['last_name']) ?>
                                                        </span>
                                                        <a href="<?= base_url('relationships/delete/' . $parent['relationship_id']) ?>"
                                                            class="btn btn-sm btn-outline-danger py-0 px-1"
                                                            onclick="return confirm('Beziehung löschen?')">
                                                            <i class="bi bi-trash" style="font-size: 0.75rem;"></i>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <p class="text-muted small mb-0">Keine Eltern</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Spouses -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                                        <h6 class="mb-0 small">
                                            <i class="bi bi-heart me-1"></i>
                                            Partner
                                        </h6>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addSpouseModal">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                    </div>
                                    <div class="card-body p-2">
                                        <?php if (!empty($spouses)): ?>
                                            <ul class="list-unstyled mb-0 small">
                                                <?php foreach ($spouses as $spouse): ?>
                                                    <li class="d-flex justify-content-between align-items-center mb-1">
                                                        <span>
                                                            <?= esc($spouse['first_name'] . ' ' . $spouse['last_name']) ?>
                                                        </span>
                                                        <a href="<?= base_url('relationships/delete/' . $spouse['relationship_id']) ?>"
                                                            class="btn btn-sm btn-outline-danger py-0 px-1"
                                                            onclick="return confirm('Beziehung löschen?')">
                                                            <i class="bi bi-trash" style="font-size: 0.75rem;"></i>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <p class="text-muted small mb-0">Keine Partner</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Children -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                                        <h6 class="mb-0 small">
                                            <i class="bi bi-person-hearts me-1"></i>
                                            Kinder
                                        </h6>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addChildModal">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                    </div>
                                    <div class="card-body p-2">
                                        <?php if (!empty($children)): ?>
                                            <ul class="list-unstyled mb-0 small">
                                                <?php foreach ($children as $child): ?>
                                                    <li class="d-flex justify-content-between align-items-center mb-1">
                                                        <span>
                                                            <?= esc($child['first_name'] . ' ' . $child['last_name']) ?>
                                                        </span>
                                                        <a href="<?= base_url('relationships/delete/' . $child['relationship_id']) ?>"
                                                            class="btn btn-sm btn-outline-danger py-0 px-1"
                                                            onclick="return confirm('Beziehung löschen?')">
                                                            <i class="bi bi-trash" style="font-size: 0.75rem;"></i>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <p class="text-muted small mb-0">Keine Kinder</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-1"></i>
                            Geschwister werden automatisch über gemeinsame Eltern erkannt.
                        </div>
                    <?php endif; ?>

                    <!-- Events/Timeline - nur beim Bearbeiten -->
                    <?php if ($person): ?>
                        <h5 class="border-bottom pb-2 mb-3 mt-4">
                            <i class="bi bi-calendar-check me-2"></i>
                            Ereignisse / Timeline
                        </h5>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="text-muted small mb-0">
                                    Dokumentieren Sie wichtige Ereignisse im Leben dieser Person.
                                </p>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addEventModal">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Ereignis hinzufügen
                                </button>
                            </div>

                            <?php if (!empty($events)): ?>
                                <div class="list-group">
                                    <?php foreach ($events as $event): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i
                                                            class="<?= \App\Models\EventModel::getEventTypeIcon($event['event_type']) ?> me-2"></i>
                                                        <strong><?= \App\Models\EventModel::getEventTypeLabel($event['event_type']) ?></strong>
                                                        <?php if ($event['event_date']): ?>
                                                            <span class="badge bg-light text-dark ms-2">
                                                                <?= date('d.m.Y', strtotime($event['event_date'])) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if ($event['event_place']): ?>
                                                        <p class="small text-muted mb-1">
                                                            <i class="bi bi-geo-alt me-1"></i>
                                                            <?= esc($event['event_place']) ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php if ($event['description']): ?>
                                                        <p class="small mb-1"><?= esc($event['description']) ?></p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($event['related_person'])): ?>
                                                        <p class="small text-muted mb-0">
                                                            <i class="bi bi-person me-1"></i>
                                                            Mit:
                                                            <?= esc($event['related_person']['first_name'] . ' ' . $event['related_person']['last_name']) ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                                <a href="<?= base_url('persons/delete-event/' . $event['id']) ?>"
                                                    class="btn btn-sm btn-outline-danger ms-2"
                                                    onclick="return confirm('Ereignis wirklich löschen?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-light text-center">
                                    <i class="bi bi-calendar-x display-6 text-muted"></i>
                                    <p class="text-muted mb-0 mt-2">Noch keine Ereignisse eingetragen.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            <?= $person ? 'Änderungen speichern' : 'Person hinzufügen' ?>
                        </button>
                        <a href="<?= $person ? base_url('persons/view/' . $person['id']) : base_url('persons') ?>"
                            class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>
                            Abbrechen
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar with help -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Hilfe
                </h6>
            </div>
            <div class="card-body">
                <p class="small mb-2"><strong>Pflichtfelder:</strong></p>
                <ul class="small mb-3">
                    <li>Vorname</li>
                    <li>Nachname</li>
                </ul>

                <p class="small mb-2"><strong>Tipps:</strong></p>
                <ul class="small mb-0">
                    <li>Verwenden Sie den <strong>Geburtsnamen</strong>, wenn dieser vom Nachnamen abweicht (z.B. bei
                        Heirat)</li>
                    <li>Datumsangaben sind optional - Sie können sie auch später ergänzen</li>
                    <li>Die <strong>Biografie</strong> eignet sich für ausführliche Informationen</li>
                    <li>Fotos können Sie auch nachträglich hinzufügen oder ändern</li>
                    <li><strong>Foto-Titel</strong> helfen beim späteren Wiederfinden</li>
                </ul>
            </div>
        </div>

        <?php if ($person): ?>
            <div class="card mt-3">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Gefahrenzone
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2">Person unwiderruflich löschen:</p>
                    <a href="<?= base_url('persons/delete/' . $person['id']) ?>" class="btn btn-danger btn-sm w-100"
                        onclick="return confirm('Person wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden!')">
                        <i class="bi bi-trash me-2"></i>
                        Person löschen
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modals for relationships (only when editing) -->
<?php if ($person): ?>

    <!-- Add Parent Modal -->
    <div class="modal fade" id="addParentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus me-2"></i>
                        Elternteil hinzufügen
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('relationships/add-parent') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="person_id" value="<?= $person['id'] ?>">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Elternteil auswählen</label>
                            <select class="form-select" id="parent_id" name="parent_id" required>
                                <option value="">Bitte wählen...</option>
                                <?php
                                $allPersons = model('PersonModel')->findAll();
                                foreach ($allPersons as $p):
                                    if ($p['id'] == $person['id'])
                                        continue;
                                    ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= esc($p['first_name'] . ' ' . $p['last_name']) ?>
                                        <?php if ($p['birth_date']): ?>
                                            (*<?= date('Y', strtotime($p['birth_date'])) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Eine Person kann maximal 2 Elternteile haben.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            Hinzufügen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Spouse Modal -->
    <div class="modal fade" id="addSpouseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-heart me-2"></i>
                        Partner/Ehepartner hinzufügen
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('relationships/add-spouse') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="person_id" value="<?= $person['id'] ?>">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="spouse_id" class="form-label">Person auswählen</label>
                            <select class="form-select" id="spouse_id" name="spouse_id" required>
                                <option value="">Bitte wählen...</option>
                                <?php
                                $allPersons = model('PersonModel')->findAll();
                                foreach ($allPersons as $p):
                                    if ($p['id'] == $person['id'])
                                        continue;
                                    ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= esc($p['first_name'] . ' ' . $p['last_name']) ?>
                                        <?php if ($p['birth_date']): ?>
                                            (*<?= date('Y', strtotime($p['birth_date'])) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="spouse_type" class="form-label">Beziehungstyp</label>
                            <select class="form-select" id="spouse_type" name="type" required>
                                <option value="spouse">Ehepartner (verheiratet)</option>
                                <option value="partner">Partner (unverheiratet)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">
                                Datum (optional)
                                <small class="text-muted">z.B. Hochzeitsdatum</small>
                            </label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            Hinzufügen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Child Modal -->
    <div class="modal fade" id="addChildModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-hearts me-2"></i>
                        Kind hinzufügen
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('relationships/add-child') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="person_id" value="<?= $person['id'] ?>">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="child_id" class="form-label">Kind auswählen</label>
                            <select class="form-select" id="child_id" name="child_id" required>
                                <option value="">Bitte wählen...</option>
                                <?php
                                $allPersons = model('PersonModel')->findAll();
                                foreach ($allPersons as $p):
                                    if ($p['id'] == $person['id'])
                                        continue;
                                    ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= esc($p['first_name'] . ' ' . $p['last_name']) ?>
                                        <?php if ($p['birth_date']): ?>
                                            (*<?= date('Y', strtotime($p['birth_date'])) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Ein Kind kann maximal 2 Elternteile haben.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            Hinzufügen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">
                        <i class="bi bi-calendar-plus me-2"></i>
                        Ereignis hinzufügen
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                </div>
                <form action="<?= base_url('persons/add-event') ?>" method="post" id="addEventForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="person_id" value="<?= $person['id'] ?>">

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="event_type" class="form-label">
                                    Ereignis-Typ <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="event_type" name="event_type" required>
                                    <option value="">Bitte wählen...</option>
                                    <option value="birth">Geburt</option>
                                    <option value="death">Tod</option>
                                    <option value="baptism">Taufe</option>
                                    <option value="marriage">Hochzeit</option>
                                    <option value="divorce">Scheidung</option>
                                    <option value="education">Ausbildung/Studium</option>
                                    <option value="employment">Beschäftigung</option>
                                    <option value="military">Militärdienst</option>
                                    <option value="residence">Umzug/Wohnort</option>
                                    <option value="immigration">Einwanderung</option>
                                    <option value="other">Sonstiges</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="event_date" class="form-label">Datum</label>
                                <input type="date" class="form-control" id="event_date" name="event_date">
                            </div>

                            <div class="col-12">
                                <label for="event_place" class="form-label">Ort</label>
                                <input type="text" class="form-control" id="event_place" name="event_place"
                                    placeholder="z.B. München, Deutschland">
                            </div>

                            <div class="col-12">
                                <label for="event_description" class="form-label">Beschreibung</label>
                                <textarea class="form-control" id="event_description" name="description" rows="3"
                                    placeholder="Zusätzliche Informationen zum Ereignis..."></textarea>
                            </div>

                            <div class="col-12">
                                <label for="related_person_id" class="form-label">
                                    Verknüpfte Person <small class="text-muted">(optional)</small>
                                </label>
                                <select class="form-select" id="related_person_id" name="related_person_id">
                                    <option value="">Keine</option>
                                    <?php
                                    $allPersons = model('PersonModel')->findAll();
                                    foreach ($allPersons as $p):
                                        if ($p['id'] == $person['id'])
                                            continue;
                                        ?>
                                        <option value="<?= $p['id'] ?>">
                                            <?= esc($p['first_name'] . ' ' . $p['last_name']) ?>
                                            <?php if ($p['birth_date']): ?>
                                                (*<?= date('Y', strtotime($p['birth_date'])) ?>)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    Z.B. bei Hochzeit: Partner auswählen
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info small mt-3 mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Ereignisse helfen dabei, die Lebensgeschichte einer Person nachzuvollziehen.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            Hinzufügen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<?php endif; ?>

<?= $this->endSection() ?>