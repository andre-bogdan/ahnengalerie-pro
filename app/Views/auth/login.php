<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<style>
    .password-container {
        position: relative;
        display: inline-block;
    }

    .password-container input[type="password"],
    .password-container input[type="text"] {
        padding-right: 2.5em;
        /* Platz für das Icon */
        box-sizing: border-box;
    }

    .password-container .toggle-password {
        position: absolute;
        right: 0.5em;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: none;
        cursor: pointer;
        font-size: 1em;
    }
</style>

<div class="card shadow-lg" style="max-width: 450px; margin: 100px auto;">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <h1 class="h3 mb-3">
                🌳
                <br>
                Ahnengalerie Pro
            </h1>
            <p class="text-muted">Bitte melden Sie sich an</p>
        </div>

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

        <!-- Countdown Alert (wenn geblockt) -->
        <?php if (isset($blocked_until) && $blocked_until && $remaining_seconds > 0): ?>
            <div class="alert alert-warning" id="countdown-alert">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm text-warning me-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div>
                        <strong>Account vorübergehend gesperrt</strong>
                        <div class="mt-1">
                            Verbleibende Zeit: <strong><span id="countdown-timer">--:--</span></strong>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-warning" id="countdown-progress" role="progressbar"
                                style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login/authenticate') ?>" method="post" id="login-form">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="username" class="form-label">
                    <i class="bi bi-person me-1"></i> Benutzername
                </label>
                <input type="text" class="form-control w-100" id="username" name="username" required autofocus
                    <?= isset($blocked_until) && $blocked_until ? 'disabled' : '' ?>>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="bi bi-lock me-1"></i> Passwort
                </label>

                <div class="password-container position-relative mb-3 w-100">
                    <input type="password" class="form-control w-100" id="password" name="password" required
                        <?= isset($blocked_until) && $blocked_until ? 'disabled' : '' ?>>
                    <button type="button"
                        class="toggle-password btn btn-link position-absolute end-0 top-50 translate-middle-y me-2"
                        onclick="togglePassword()" tabindex="-1">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>


            <script>
                function togglePassword() {
                    const pw = document.getElementById("password");
                    const btnIcon = document.querySelector(".toggle-password i");

                    if (pw.type === "password") {
                        pw.type = "text";
                        btnIcon.classList.remove("bi-eye");
                        btnIcon.classList.add("bi-eye-slash");
                    } else {
                        pw.type = "password";
                        btnIcon.classList.remove("bi-eye-slash");
                        btnIcon.classList.add("bi-eye");
                    }
                }
            </script>


            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary btn-lg" id="login-button" <?= isset($blocked_until) && $blocked_until ? 'disabled' : '' ?>>
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Anmelden
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="bi bi-shield-check me-1"></i>
                Sichere Anmeldung
            </small>
        </div>
    </div>
</div>

<?php if (isset($remaining_seconds) && $remaining_seconds > 0): ?>
    <script>
        // Countdown Timer
        let remainingSeconds = <?= $remaining_seconds ?>;
        const totalSeconds = 900; // 15 Minuten

        function updateCountdown() {
            if (remainingSeconds <= 0) {
                // Countdown abgelaufen - Seite neu laden
                location.reload();
                return;
            }

            // Zeit formatieren (MM:SS)
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            const timeString = minutes.toString().padStart(2, '0') + ':' +
                seconds.toString().padStart(2, '0');

            // Timer aktualisieren
            document.getElementById('countdown-timer').textContent = timeString;

            // Progress Bar aktualisieren
            const progress = (remainingSeconds / totalSeconds) * 100;
            document.getElementById('countdown-progress').style.width = progress + '%';

            // Sekunde abziehen
            remainingSeconds--;

            // Nächste Iteration in 1 Sekunde
            setTimeout(updateCountdown, 1000);
        }

        // Countdown starten
        updateCountdown();

        // Form-Submit verhindern während Countdown läuft
        document.getElementById('login-form').addEventListener('submit', function (e) {
            if (remainingSeconds > 0) {
                e.preventDefault();
                alert('Bitte warten Sie bis der Countdown abgelaufen ist.');
            }
        });
    </script>
<?php endif; ?>

<?= $this->endSection() ?>