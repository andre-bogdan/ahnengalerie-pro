<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Header -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>
                        <i class="bi bi-person-plus"></i>
                        <?= $title ?>
                    </h2>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Zurück
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
    
            <!-- Validation Errors -->
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <h6><i class="bi bi-exclamation-triangle"></i> Bitte beheben Sie folgende Fehler:</h6>
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Formular -->
            <div class="card">
                <div class="card-body">
                    <form method="post"
                        action="<?= $user ? base_url('admin/users/update/' . $user['id']) : base_url('admin/users/store') ?>">

                        <?= csrf_field() ?>

                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                Username <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= session()->getFlashdata('errors')['username'] ?? false ? 'is-invalid' : '' ?>"
                                id="username" name="username" value="<?= old('username', $user['username'] ?? '') ?>"
                                required minlength="3" maxlength="50">
                            <small class="text-muted">Mindestens 3 Zeichen</small>
                        </div>

                        <!-- E-Mail -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                E-Mail-Adresse <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                class="form-control <?= session()->getFlashdata('errors')['email'] ?? false ? 'is-invalid' : '' ?>"
                                id="email" name="email" value="<?= old('email', $user['email'] ?? '') ?>" required>
                        </div>

                        <!-- Passwort -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Passwort
                                <?php if (!$user): ?>
                                    <span class="text-danger">*</span>
                                <?php else: ?>
                                    <small class="text-muted">(leer lassen = nicht ändern)</small>
                                <?php endif; ?>
                            </label>
                            <input type="password"
                                class="form-control <?= session()->getFlashdata('errors')['password'] ?? false ? 'is-invalid' : '' ?>"
                                id="password" name="password" <?= $user ? '' : 'required' ?> minlength="6">
                            <small class="text-muted">Mindestens 6 Zeichen</small>
                        </div>

                        <!-- Passwort bestätigen -->
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">
                                Passwort bestätigen
                                <?php if (!$user): ?>
                                    <span class="text-danger">*</span>
                                <?php endif; ?>
                            </label>
                            <input type="password"
                                class="form-control <?= session()->getFlashdata('errors')['password_confirm'] ?? false ? 'is-invalid' : '' ?>"
                                id="password_confirm" name="password_confirm" <?= $user ? '' : 'required' ?>>
                        </div>

                        <!-- Admin-Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1"
                                    <?= old('is_admin', $user['is_admin'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_admin">
                                    <i class="bi bi-shield-check text-success"></i>
                                    <strong>Administrator-Rechte</strong>
                                    <br>
                                    <small class="text-muted">
                                        Administratoren haben vollen Zugriff auf alle Funktionen inkl.
                                        Benutzerverwaltung.
                                    </small>
                                </label>
                            </div>
                        </div>

                        <!-- NEU: Email-Versand Checkbox -->
                        <?php if (!isset($user)): ?>
                            <div class="mb-4">
                                <div class="card border-info">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="send_email"
                                                name="send_email" value="1" checked>
                                            <label class="form-check-label" for="send_email">
                                                <strong>📧 Zugangsdaten per E-Mail versenden</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            Der Benutzer erhält eine E-Mail mit Benutzername und Passwort.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <hr>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Abbrechen
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i>
                                <?= $user ? 'Änderungen speichern' : 'Benutzer anlegen' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info-Box für Edit -->
            <?php if ($user): ?>
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-info-circle"></i> Benutzer-Informationen
                        </h6>
                        <ul class="list-unstyled mb-0 small">
                            <li><strong>ID:</strong> <?= $user['id'] ?></li>
                            <li><strong>Erstellt am:</strong> <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?> Uhr
                            </li>
                            <li><strong>Letzte Änderung:</strong> <?= date('d.m.Y H:i', strtotime($user['updated_at'])) ?>
                                Uhr</li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>