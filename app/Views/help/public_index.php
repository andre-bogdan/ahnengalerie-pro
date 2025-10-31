<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-question-circle me-2"></i> Hilfe & Dokumentation</h1>
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Willkommen im Hilfe-Bereich!</strong><br>
            Hier finden Sie Anleitungen zur Nutzung von Ahnengalerie Pro.
        </div>
        
        <div class="alert alert-primary">
            <i class="bi bi-star me-2"></i>
            Sie sind noch nicht angemeldet. 
            <a href="<?= base_url('login') ?>" class="alert-link">Jetzt einloggen</a> 
            um Ahnengalerie Pro zu nutzen!
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
                                        <a href="<?= base_url('hilfe/' . esc($article['slug'])) ?>" class="text-decoration-none">
                                            <?= esc($article['title']) ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted">
                                        <?= esc($article['description']) ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="badge bg-<?= $article['difficulty'] === 'beginner' ? 'success' : ($article['difficulty'] === 'intermediate' ? 'warning' : 'danger') ?>">
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
                                    <a href="<?= base_url('hilfe/' . esc($article['slug'])) ?>" class="btn btn-sm btn-primary w-100">
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
</style>

<?= $this->endSection() ?>