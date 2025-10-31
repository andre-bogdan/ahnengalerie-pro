<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="https://ahnengalerie-pro.de">Startseite</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('hilfe') ?>">Hilfe</a></li>
                <li class="breadcrumb-item active"><?= esc($title) ?></li>
            </ol>
        </nav>

        <div class="card help-article-content">
            <div class="card-body p-4 p-md-5">
                <?= $content ?>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4 mb-5">
            <a href="<?= base_url('hilfe') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Zurück zur Übersicht
            </a>
        </div>
    </div>
</div>

<!-- Gleiche Styles wie show.php -->
<style>
.help-article-content h1 {
    color: #333;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 3px solid #667eea;
}

.help-article-content h2 {
    color: #444;
    font-size: 1.8rem;
    font-weight: 600;
    margin-top: 3rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e0e0e0;
}

.help-article-content h3 {
    color: #555;
    font-size: 1.4rem;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.help-article-content p {
    margin-bottom: 1.2rem;
    color: #555;
    line-height: 1.8;
}

.help-article-content code {
    background: #f5f5f5;
    padding: 2px 6px;
    border-radius: 3px;
    color: #d63384;
}

.help-article-content pre {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 1rem;
    border-radius: 5px;
    overflow-x: auto;
}

.help-article-content ul, .help-article-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.help-article-content li {
    margin-bottom: 0.5rem;
}
</style>

<?= $this->endSection() ?>