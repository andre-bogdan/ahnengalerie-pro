<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Mein Profil<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Header -->
            <div class="mb-4">
                <h2>
                    <i class="bi bi-person-circle"></i>
                    Mein Profil
                </h2>
                <p class="text-muted">Verwalten Sie Ihre Konto-Einstellungen</p>
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

            <!-- ✨ NEU: Passwort-Änderung erforderlich -->
            <?php if (session()->getFlashdata('password_change_required')): ?>
                <div class="alert alert-warning border-warning" role="alert">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <i class="bi bi-shield-exclamation" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="alert-heading">
                                <i class="bi bi-exclamation-triangle"></i>
                                Passwort-Änderung erforderlich
                            </h5>
                            <p class="mb-2"><?= session()->getFlashdata('warning') ?></p>
                            <hr>
                            <p class="mb-0">
                                <small>
                                    <i class="bi bi-info-circle"></i>
                                    Bitte scrollen Sie nach unten zum Bereich <strong>"Passwort ändern"</strong>
                                    und vergeben Sie ein sicheres, persönliches Passwort.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Profil-Informationen -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i>
                        Konto-Informationen
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Benutzername:</strong>
                        </div>
                        <div class="col-md-8">
                            <?= esc($user['username']) ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>E-Mail:</strong>
                        </div>
                        <div class="col-md-8">
                            <?= esc($user['email']) ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Rolle:</strong>
                        </div>
                        <div class="col-md-8">
                            <?php if ($user['is_admin']): ?>
                                <span class="badge bg-success">
                                    <i class="bi bi-shield-check"></i> Administrator
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="bi bi-person"></i> Benutzer
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Konto erstellt:</strong>
                        </div>
                        <div class="col-md-8">
                            <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?> Uhr
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Letzte Änderung:</strong>
                        </div>
                        <div class="col-md-8">
                            <?= date('d.m.Y H:i', strtotime($user['updated_at'])) ?> Uhr
                        </div>
                    </div>
                </div>
            </div>

            <!-- E-Mail ändern -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope"></i>
                        E-Mail-Adresse ändern
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('email_errors')): ?>
                        <div class="alert alert-danger">
                            <h6><i class="bi bi-exclamation-triangle"></i> Fehler:</h6>
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('email_errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= base_url('profile/update') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="form_type" value="email">

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Neue E-Mail-Adresse <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= old('email', $user['email']) ?>" required>
                            <small class="text-muted">Geben Sie Ihre neue E-Mail-Adresse ein</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> E-Mail ändern
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Newsletter-Einstellungen -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope-paper"></i>
                        Newsletter-Einstellungen
                    </h5>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('profile/update') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="form_type" value="newsletter">

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="newsletter_enabled"
                                name="newsletter_enabled" value="1" <?= $user['newsletter_enabled'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="newsletter_enabled">
                                <strong>Monatlichen Newsletter erhalten</strong>
                            </label>
                        </div>

                        <div class="alert alert-info mt-3 mb-3">
                            <i class="bi bi-info-circle"></i>
                            <small>
                                Sie erhalten jeweils am 1. des Monats eine Übersicht über alle Änderungen
                                und Neuerungen der Ahnengalerie des letzten Monats.
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Einstellungen speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Passwort ändern -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-key"></i>
                        Passwort ändern
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('password_errors')): ?>
                        <div class="alert alert-danger">
                            <h6><i class="bi bi-exclamation-triangle"></i> Fehler:</h6>
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('password_errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= base_url('profile/update') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="form_type" value="password">

                         <!-- 1. Aktuelles Passwort -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                Aktuelles Passwort <span class="text-danger">*</span>
                            </label>
                            <div class="password-container position-relative">
                                <input type="password" class="form-control" id="current_password"
                                    name="current_password" required>
                                <button type="button"
                                    class="toggle-password btn btn-link p-0 position-absolute top-50 end-0 translate-middle-y me-2"
                                    onclick="togglePassword('current_password', this)" tabindex="-1"
                                    aria-label="Passwort anzeigen/verbergen">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Zur Sicherheit: Geben Sie Ihr aktuelles Passwort ein</small>
                        </div>

                        <!-- 2. Neues Passwort -->
                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                Neues Passwort <span class="text-danger">*</span>
                            </label>
                            <div class="password-container position-relative">
                                <input type="password" class="form-control" id="new_password" name="new_password"
                                    required minlength="6">
                                <button type="button"
                                    class="toggle-password btn btn-link p-0 position-absolute top-50 end-0 translate-middle-y me-2"
                                    onclick="togglePassword('new_password', this)" tabindex="-1"
                                    aria-label="Passwort anzeigen/verbergen">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Mindestens 6 Zeichen</small>
                        </div>

                        <!-- 3. Passwort bestätigen -->
                        <div class="mb-3">
                            <label for="new_password_confirm" class="form-label">
                                Neues Passwort bestätigen <span class="text-danger">*</span>
                            </label>
                            <div class="password-container position-relative">
                                <input type="password" class="form-control" id="new_password_confirm"
                                    name="new_password_confirm" required>
                                <button type="button"
                                    class="toggle-password btn btn-link p-0 position-absolute top-50 end-0 translate-middle-y me-2"
                                    onclick="togglePassword('new_password_confirm', this)" tabindex="-1"
                                    aria-label="Passwort anzeigen/verbergen">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Wiederholen Sie das neue Passwort</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Passwort-Hinweise:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Mindestens 6 Zeichen lang</li>
                                <li>Verwenden Sie eine Kombination aus Buchstaben und Zahlen</li>
                                <li>Vermeiden Sie einfache Passwörter wie "123456"</li>
                            </ul>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Passwort ändern
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Zurück-Button -->
            <div class="text-center">
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Zurück zum Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?php if (session()->getFlashdata('password_change_required')): ?>
    <script>
        // Automatisch zur Passwort-Sektion scrollen
        document.addEventListener('DOMContentLoaded', function () {
            const passwordSection = document.getElementById('password-section');
            if (passwordSection) {
                setTimeout(() => {
                    passwordSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 500);
            }
        });
    </script>
<?php endif; ?>