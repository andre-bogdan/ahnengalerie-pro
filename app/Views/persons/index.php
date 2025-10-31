<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-people me-2"></i>
            Personen
        </h1>
        <p class="text-muted mb-0">Alle Personen im Stammbaum</p>
    </div>
    <div>
        <a href="<?= base_url('persons/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Person hinzufügen
        </a>
    </div>
</div>

<!-- Search & Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="<?= base_url('persons') ?>" method="get" class="row g-3">
            <div class="col-md-5">
                <label for="search" class="form-label">Suche</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="search" 
                        name="search" 
                        placeholder="Vorname, Nachname, Geburtsname..."
                        value="<?= esc($search ?? '') ?>"
                    >
                </div>
            </div>
            
            <div class="col-md-3">
                <label for="gender" class="form-label">Geschlecht</label>
                <select class="form-select" id="gender" name="gender">
                    <option value="">Alle</option>
                    <option value="m" <?= ($gender ?? '') === 'm' ? 'selected' : '' ?>>Männlich</option>
                    <option value="f" <?= ($gender ?? '') === 'f' ? 'selected' : '' ?>>Weiblich</option>
                    <option value="x" <?= ($gender ?? '') === 'x' ? 'selected' : '' ?>>Divers</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="sort" class="form-label">Sortierung</label>
                <select class="form-select" id="sort" name="sort">
                    <option value="last_name" <?= ($sortBy ?? 'last_name') === 'last_name' ? 'selected' : '' ?>>Nachname</option>
                    <option value="first_name" <?= ($sortBy ?? '') === 'first_name' ? 'selected' : '' ?>>Vorname</option>
                    <option value="birth_date" <?= ($sortBy ?? '') === 'birth_date' ? 'selected' : '' ?>>Geburtsdatum</option>
                    <option value="created_at" <?= ($sortBy ?? '') === 'created_at' ? 'selected' : '' ?>>Hinzugefügt</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label d-block">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-2"></i>
                    Filtern
                </button>
            </div>
        </form>
        
        <?php if ($search || $gender): ?>
            <div class="mt-3">
                <a href="<?= base_url('persons') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>
                    Filter zurücksetzen
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Persons Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>
            <?= count($persons) ?> Person(en) gefunden
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($persons)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="text-muted mt-3 mb-0">
                    <?php if ($search || $gender): ?>
                        Keine Personen mit diesen Kriterien gefunden.
                    <?php else: ?>
                        Noch keine Personen vorhanden.
                    <?php endif; ?>
                </p>
                <?php if (!$search && !$gender): ?>
                    <a href="<?= base_url('persons/create') ?>" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle me-2"></i>
                        Erste Person hinzufügen
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;"></th>
                            <th>Name</th>
                            <th>Geburtsname</th>
                            <th>Geschlecht</th>
                            <th>Geburtsdatum</th>
                            <th>Geburtsort</th>
                            <th class="text-end">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($persons as $person): ?>
                            <tr>
                                <td>
                                    <?php if ($person['primary_photo_id']): ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person-circle text-muted fs-4"></i>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('persons/view/' . $person['id']) ?>" class="text-decoration-none">
                                        <strong><?= esc($person['first_name'] . ' ' . $person['last_name']) ?></strong>
                                    </a>
                                    <?php if ($person['death_date']): ?>
                                        <span class="badge bg-secondary ms-2">†</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($person['maiden_name'] ?? '-') ?></td>
                                <td>
                                    <?php if ($person['gender'] === 'm'): ?>
                                        <i class="bi bi-gender-male text-primary"></i> Männlich
                                    <?php elseif ($person['gender'] === 'f'): ?>
                                        <i class="bi bi-gender-female text-danger"></i> Weiblich
                                    <?php elseif ($person['gender'] === 'x'): ?>
                                        <i class="bi bi-gender-trans text-info"></i> Divers
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $person['birth_date'] ? date('d.m.Y', strtotime($person['birth_date'])) : '-' ?>
                                </td>
                                <td><?= esc($person['birth_place'] ?? '-') ?></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('persons/view/' . $person['id']) ?>" 
                                           class="btn btn-outline-primary" 
                                           title="Ansehen">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url('persons/edit/' . $person['id']) ?>" 
                                           class="btn btn-outline-secondary" 
                                           title="Bearbeiten">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('persons/delete/' . $person['id']) ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Löschen"
                                           onclick="return confirm('Person wirklich löschen?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>