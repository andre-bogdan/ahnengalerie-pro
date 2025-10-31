<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Benutzerverwaltung<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>
                        <i class="bi bi-people"></i>
                        Benutzerverwaltung
                    </h2>
                    <p class="text-muted mb-0">Benutzerkonten verwalten</p>
                </div>
                <div>
                    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Neuer Benutzer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistik-Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0"><?= $stats['total'] ?></h3>
                            <p class="text-muted mb-0">Gesamt</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0"><?= $stats['admins'] ?></h3>
                            <p class="text-muted mb-0">Administratoren</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-person text-info" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0"><?= $stats['users'] ?></h3>
                            <p class="text-muted mb-0">Normale Benutzer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suche & Filter -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="get" class="row g-3">
                        <div class="col-md-6">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Suche nach Username oder E-Mail..."
                                   value="<?= esc($search ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <select name="filter" class="form-select">
                                <option value="">Alle Benutzer</option>
                                <option value="admins" <?= ($filter ?? '') === 'admins' ? 'selected' : '' ?>>Nur Admins</option>
                                <option value="users" <?= ($filter ?? '') === 'users' ? 'selected' : '' ?>>Nur normale Benutzer</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Suchen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- User-Tabelle -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?php if (empty($users)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Keine Benutzer gefunden.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>E-Mail</th>
                                        <th>Rolle</th>
                                        <th>Erstellt am</th>
                                        <th class="text-end">Aktionen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?= $user['id'] ?></td>
                                            <td>
                                                <strong><?= esc($user['username']) ?></strong>
                                                <?php if ($user['id'] == session()->get('user_id')): ?>
                                                    <span class="badge bg-info">Sie</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($user['email']) ?></td>
                                            <td>
                                                <?php if ($user['is_admin']): ?>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-shield-check"></i> Admin
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-person"></i> User
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" 
                                                       class="btn btn-outline-primary"
                                                       title="Bearbeiten">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    
                                                    <?php if ($user['id'] != session()->get('user_id')): ?>
                                                        <button type="button"
                                                                class="btn btn-outline-danger"
                                                                onclick="deleteUser(<?= $user['id'] ?>, '<?= esc($user['username']) ?>')"
                                                                title="Löschen">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
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
        </div>
    </div>
</div>

<!-- Lösch-Bestätigung Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Benutzer löschen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Möchten Sie den Benutzer <strong id="deleteUsername"></strong> wirklich löschen?</p>
                <p class="text-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    Diese Aktion kann nicht rückgängig gemacht werden!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                <form id="deleteForm" method="post" style="display: inline;">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Löschen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteUser(userId, username) {
    // Modal füllen
    document.getElementById('deleteUsername').textContent = username;
    document.getElementById('deleteForm').action = '<?= base_url('admin/users/delete') ?>/' + userId;
    
    // Modal anzeigen
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>

<?= $this->endSection() ?>