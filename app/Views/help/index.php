<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-question-circle me-2"></i> Hilfe & Dokumentation</h1>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Zurück
            </a>
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Willkommen im Hilfe-Bereich!</strong><br>
            Hier finden Sie Anleitungen und Tipps zur Nutzung von Ahnengalerie Pro.
        </div>
    </div>
</div>

<?php if (isset($helpIndex['categories']) && !empty($helpIndex['categories'])): ?>
    <?php foreach ($helpIndex['categories'] as $category): ?>
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-3">
                    <i class="bi <?= esc($category['icon']) ?> me-2"></i>
                    <?= esc($category['title']) ?>
                </h2>
                <p class="text-muted mb-4"><?= esc($category['description']) ?></p>

                <div class="row g-3">
                    <?php foreach ($category['articles'] as $article): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 help-card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="<?= base_url('help/' . esc($article['slug'])) ?>" class="text-decoration-none">
                                            <?= esc($article['title']) ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted">
                                        <?= esc($article['description']) ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span
                                            class="badge bg-<?= $article['difficulty'] === 'beginner' ? 'success' : ($article['difficulty'] === 'intermediate' ? 'warning' : 'danger') ?>">
                                            <?php
                                            $difficultyLabels = [
                                                'beginner' => 'Einsteiger',
                                                'intermediate' => 'Fortgeschritten',
                                                'advanced' => 'Experte'
                                            ];
                                            echo $difficultyLabels[$article['difficulty']] ?? 'Einsteiger';
                                            ?>
                                        </span>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            <?= esc($article['readTime']) ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="card-footer bg-light">
                                    <a href="<?= base_url('help/' . esc($article['slug'])) ?>" class="btn btn-sm btn-primary w-100">
                                        <i class="bi bi-book me-2"></i>Artikel lesen
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Keine Hilfe-Artikel verfügbar.
    </div>
<?php endif; ?>

<div class="row mt-5">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h5>Weitere Hilfe benötigt? Fehler melden? Feedback geben?</h5>
                <p class="text-muted mb-3">
                    Kontaktieren Sie uns gerne!
                </p>
                <!-- GEÄNDERT: Modal-Trigger statt mailto -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#supportModal">
                    <i class="bi bi-envelope me-2"></i>Kontakt aufnehmen
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Support-Modal -->
<div class="modal fade" id="supportModal" tabindex="-1" aria-labelledby="supportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="supportModalLabel">
                    <i class="bi bi-envelope me-2"></i>Support kontaktieren
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="supportForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Probleme oder Fehler bitte möglichst genau beschreiben.
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="supportName" class="form-label">
                            Ihr Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="supportName" name="name" required
                            placeholder="Max Mustermann">
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="supportEmail" class="form-label">
                            Ihre Email-Adresse <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control" id="supportEmail" name="email" required
                            placeholder="max@example.com" value="<?= esc(session()->get('user')['email'] ?? '') ?>">
                        <small class="form-text text-muted">
                            Wir antworten an diese Adresse
                        </small>
                    </div>

                    <!-- Betreff -->
                    <div class="mb-3">
                        <label for="supportSubject" class="form-label">
                            Betreff <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="supportSubject" name="subject" required>
                            <option value="">Bitte wählen...</option>
                            <option value="Technisches Problem">Technisches Problem</option>
                            <option value="Frage zur Bedienung">Frage zur Bedienung</option>
                            <option value="Feature-Wunsch">Feature-Wunsch</option>
                            <option value="Fehler melden">Fehler melden</option>
                            <option value="Feedback">Einfach mal ein Feedback geben</option>
                            <option value="Sonstiges">Sonstiges</option>
                        </select>
                    </div>

                    <!-- Nachricht -->
                    <div class="mb-3">
                        <label for="supportMessage" class="form-label">
                            Ihre Nachricht <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="supportMessage" name="message" rows="6" required
                            placeholder="Beschreiben Sie bitte Ihr Anliegen..."></textarea>
                        <small class="form-text text-muted">
                            Mindestens 20 Zeichen
                        </small>
                    </div>

                    <!-- Captcha -->
                    <div class="mb-3">
                        <label for="supportCaptcha" class="form-label">
                            Sicherheitsfrage <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-shield-check me-2"></i>
                                Was ist <strong id="captchaQuestion" class="mx-2"></strong>?
                            </span>
                            <input type="number" class="form-control" id="supportCaptcha" name="captcha_answer" required
                                placeholder="Antwort" autocomplete="off">
                            <input type="hidden" id="captchaToken" name="captcha_token">
                        </div>
                        <small class="form-text text-muted">
                            Bitte lösen Sie die Rechenaufgabe
                        </small>
                    </div>

                    <!-- Aktueller Hilfe-Artikel (versteckt) -->
                    <input type="hidden" name="current_page" value="<?= current_url() ?>">

                    <!-- Alert für Feedback -->
                    <div id="supportAlert" class="alert d-none" role="alert"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Abbrechen
                    </button>
                    <button type="submit" class="btn btn-primary" id="supportSubmitBtn">
                        <i class="bi bi-send me-2"></i>Absenden
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<style>
    .help-card {
        transition: all 0.3s ease;
    }

    .help-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }

    .help-card .card-title a {
        color: #333;
        transition: color 0.3s;
    }

    .help-card .card-title a:hover {
        color: #667eea;
    }

    /* Modal-Animationen */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }

    #supportModal .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const supportForm = document.getElementById('supportForm');
    const submitBtn = document.getElementById('supportSubmitBtn');
    const alertBox = document.getElementById('supportAlert');
    const captchaQuestion = document.getElementById('captchaQuestion');
    const captchaTokenInput = document.getElementById('captchaToken');
    
    // Captcha generieren
    function generateCaptcha() {
        const num1 = Math.floor(Math.random() * 10) + 1; // 1-10
        const num2 = Math.floor(Math.random() * 10) + 1; // 1-10
        const operators = ['+', '-', '×'];
        const operator = operators[Math.floor(Math.random() * operators.length)];
        
        let answer;
        let questionText;
        
        switch(operator) {
            case '+':
                answer = num1 + num2;
                questionText = `${num1} + ${num2}`;
                break;
            case '-':
                // Sicherstellen, dass Ergebnis positiv ist
                const larger = Math.max(num1, num2);
                const smaller = Math.min(num1, num2);
                answer = larger - smaller;
                questionText = `${larger} - ${smaller}`;
                break;
            case '×':
                answer = num1 * num2;
                questionText = `${num1} × ${num2}`;
                break;
        }
        
        captchaQuestion.textContent = questionText;
        
        // Token erstellen (verschlüsselte Antwort)
        // Simple XOR-Verschlüsselung mit Timestamp
        const timestamp = Date.now();
        const token = btoa(answer + '|' + timestamp);
        captchaTokenInput.value = token;
    }
    
    // Captcha beim Öffnen des Modals neu generieren
    const supportModal = document.getElementById('supportModal');
    supportModal.addEventListener('show.bs.modal', function() {
        generateCaptcha();
        document.getElementById('supportCaptcha').value = '';
    });
    
    // Initial Captcha generieren
    generateCaptcha();
    
    supportForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validierung
        const message = document.getElementById('supportMessage').value;
        if (message.length < 20) {
            showAlert('danger', 'Bitte geben Sie mindestens 20 Zeichen ein.');
            return;
        }
        
        // Captcha-Validierung (Client-seitig nur zur UX-Verbesserung)
        const captchaAnswer = document.getElementById('supportCaptcha').value;
        if (!captchaAnswer || captchaAnswer.trim() === '') {
            showAlert('danger', 'Bitte beantworten Sie die Sicherheitsfrage.');
            return;
        }
        
        // Button deaktivieren
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Wird gesendet...';
        
        // FormData sammeln
        const formData = new FormData(supportForm);
        
        // AJAX-Request
        fetch('<?= base_url('help/send-support') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                supportForm.reset();
                
                // Modal nach 2 Sekunden schließen
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('supportModal'));
                    modal.hide();
                    alertBox.classList.add('d-none');
                }, 2000);
            } else {
                showAlert('danger', data.message || 'Ein Fehler ist aufgetreten.');
                // Bei Captcha-Fehler neu generieren
                if (data.message.includes('Captcha') || data.message.includes('Sicherheitsfrage')) {
                    generateCaptcha();
                }
            }
        })
        .catch(error => {
            showAlert('danger', 'Netzwerkfehler. Bitte versuchen Sie es später erneut.');
            console.error('Error:', error);
        })
        .finally(() => {
            // Button wieder aktivieren
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Absenden';
        });
    });
    
    function showAlert(type, message) {
        alertBox.className = `alert alert-${type}`;
        alertBox.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}`;
        alertBox.classList.remove('d-none');
    }
});
</script>

<?= $this->endSection() ?>