<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('help') ?>">Hilfe</a></li>
                <?php if (!empty($articleInfo['category'])): ?>
                    <li class="breadcrumb-item"><?= esc($articleInfo['category']) ?></li>
                <?php endif; ?>
                <li class="breadcrumb-item active" aria-current="page"><?= esc($title) ?></li>
            </ol>
        </nav>

        <!-- Artikel-Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <?php if (!empty($articleInfo['categoryIcon'])): ?>
                            <i class="bi <?= esc($articleInfo['categoryIcon']) ?> text-primary fs-3"></i>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex gap-2">
                        <?php if (!empty($articleInfo['difficulty'])): ?>
                            <span class="badge bg-<?= $articleInfo['difficulty'] === 'beginner' ? 'success' : ($articleInfo['difficulty'] === 'intermediate' ? 'warning' : 'danger') ?>">
                                <?php
                                $difficultyLabels = [
                                    'beginner' => 'Einsteiger',
                                    'intermediate' => 'Fortgeschritten',
                                    'advanced' => 'Experte'
                                ];
                                echo $difficultyLabels[$articleInfo['difficulty']] ?? 'Einsteiger';
                                ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($articleInfo['readTime'])): ?>
                            <span class="badge bg-secondary">
                                <i class="bi bi-clock me-1"></i><?= esc($articleInfo['readTime']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($articleInfo['description'])): ?>
                    <p class="lead text-muted mb-0">
                        <?= esc($articleInfo['description']) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Artikel-Inhalt -->
        <div class="card help-article-content">
            <div class="card-body p-4 p-md-5">
                <?= $content ?>
            </div>
        </div>

        <!-- Navigation -->
        <div class="d-flex justify-content-between mt-4 mb-5">
            <a href="<?= base_url('help') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Zurück zur Übersicht
            </a>
            <a href="#top" class="btn btn-outline-primary" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
                <i class="bi bi-arrow-up me-2"></i>Nach oben
            </a>
        </div>

        <!-- Hilfe-Box -->
        <div class="card bg-light mb-4">
            <div class="card-body text-center">
                <h5><i class="bi bi-question-circle me-2"></i>War dieser Artikel hilfreich?</h5>
                <p class="text-muted mb-3">
                    Wenn Sie weitere Fragen haben oder Hilfe benötigen, kontaktieren Sie uns gerne!
                </p>
                <a href="mailto:info@ahnengalerie-pro.de" class="btn btn-primary">
                    <i class="bi bi-envelope me-2"></i>Kontakt aufnehmen
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.help-article-content {
    line-height: 1.8;
}

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

.help-article-content h4 {
    color: #666;
    font-size: 1.2rem;
    font-weight: 600;
    margin-top: 1.5rem;
    margin-bottom: 0.8rem;
}

.help-article-content p {
    margin-bottom: 1.2rem;
    color: #555;
}

.help-article-content ul,
.help-article-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.help-article-content li {
    margin-bottom: 0.5rem;
    color: #555;
}

.help-article-content a {
    color: #667eea;
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: all 0.3s;
}

.help-article-content a:hover {
    color: #764ba2;
    border-bottom-color: #764ba2;
}

.help-article-content code {
    background: #f5f5f5;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    color: #d63384;
    font-size: 0.9em;
}

.help-article-content pre {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 1rem;
    border-radius: 5px;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.help-article-content pre code {
    background: transparent;
    padding: 0;
    color: #333;
    font-size: 0.95em;
}

.help-article-content blockquote {
    border-left: 4px solid #ffc107;
    background: #fff9e6;
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    border-radius: 5px;
}

.help-article-content strong {
    color: #333;
    font-weight: 600;
}

.help-article-content em {
    font-style: italic;
}

.help-article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin: 1.5rem 0;
}

.help-article-content table {
    width: 100%;
    margin: 1.5rem 0;
    border-collapse: collapse;
}

.help-article-content table th,
.help-article-content table td {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
}

.help-article-content table th {
    background: #f8f9fa;
    font-weight: 600;
}

/* Highlighting für Checkboxen und Warnings aus Markdown */
.help-article-content .text-success {
    color: #28a745 !important;
    font-size: 1.2em;
}

.help-article-content .text-danger {
    color: #dc3545 !important;
    font-size: 1.2em;
}

.help-article-content .text-warning {
    color: #ffc107 !important;
    font-size: 1.2em;
}

/* Scroll-Margin für Anchor-Links */
.help-article-content h2,
.help-article-content h3,
.help-article-content h4 {
    scroll-margin-top: 100px;
}
</style>

<script>
// Automatisches Inhaltsverzeichnis (optional)
document.addEventListener('DOMContentLoaded', function() {
    // Alle H2 und H3 sammeln
    const headings = document.querySelectorAll('.help-article-content h2, .help-article-content h3');
    
    if (headings.length > 3) {
        // TOC erstellen (nur wenn mehr als 3 Überschriften)
        // Implementierung optional
    }
});
</script>

<?= $this->endSection() ?>