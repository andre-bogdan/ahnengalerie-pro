<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h1 class="h3 mb-0">
        <i class="bi bi-graph-up me-2"></i>
        Datenexport
    </h1>
    <p class="text-muted">Export Ihrer Daten in verschiedenen Formaten</p>
</div>

<!-- Export Options -->
<div class="row mt-3">
    <!-- GEDCOM Export Card -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    GEDCOM Export
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Exportiere deine Familiendaten im universellen GEDCOM-Format (Version 5.5.1).
                    Dieser Standard wird von allen gängigen Genealogie-Programmen unterstützt.
                </p>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Was wird exportiert?</strong>
                    <ul class="mb-0 mt-2">
                        <li>Alle <?= number_format($personCount) ?> Personen mit vollständigen Daten</li>
                        <li>Alle <?= number_format($relationshipCount) ?> Beziehungen (Eltern, Kinder, Partner)</li>
                        <li>Alle <?= number_format($eventCount) ?> Ereignisse (Geburt, Hochzeit, Tod, etc.)</li>
                        <li>Biografien und Notizen</li>
                        <li>Berufe und weitere Details</li>
                    </ul>
                </div>

                <div class="d-grid">
                    <a href="<?= base_url('export/gedcom') ?>" class="btn btn-primary">
                        <i class="bi bi-download me-2"></i>
                        GEDCOM-Datei herunterladen
                    </a>
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-lightbulb me-1"></i>
                        <strong>Tipp:</strong> Die GEDCOM-Datei kann in Programme wie
                        Family Tree Maker, MyHeritage, Ancestry.com, Gramps,
                        Legacy Family Tree oder Webtrees importiert werden.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- CSV/Excel Export Card -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>
                    CSV/Excel Export
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Exportiere deine Daten als CSV-Dateien für Excel, LibreOffice oder Google Sheets.
                    Perfekt für eigene Analysen, Listen und Übersichten.
                </p>

                <div class="list-group list-group-flush mb-3">
                    <!-- Personen CSV -->
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">
                                    <i class="bi bi-people text-primary me-2"></i>
                                    Personen-Liste
                                </h6>
                                <p class="mb-0 small text-muted">
                                    Alle Personen mit Geburts-/Sterbedaten, Beruf und Statistiken
                                </p>
                            </div>
                            <a href="<?= base_url('export/csv') ?>" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Beziehungen CSV -->
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">
                                    <i class="bi bi-diagram-3 text-info me-2"></i>
                                    Beziehungen
                                </h6>
                                <p class="mb-0 small text-muted">
                                    Alle familiären Verbindungen in tabellarischer Form
                                </p>
                            </div>
                            <a href="<?= base_url('export/csv-relationships') ?>"
                                class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Ereignisse CSV -->
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">
                                    <i class="bi bi-calendar-event text-warning me-2"></i>
                                    Ereignisse/Timeline
                                </h6>
                                <p class="mb-0 small text-muted">
                                    Chronologische Liste aller Lebensereignisse
                                </p>
                            </div>
                            <a href="<?= base_url('export/csv-events') ?>" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Statistik CSV -->
                    <div class="list-group-item px-0 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">
                                    <i class="bi bi-graph-up text-success me-2"></i>
                                    Statistik-Übersicht
                                </h6>
                                <p class="mb-0 small text-muted">
                                    Zusammenfassung und Analysen deiner Daten
                                </p>
                            </div>
                            <a href="<?= base_url('export/csv-statistics') ?>" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="alert alert-success alert-sm">
                    <i class="bi bi-check-circle me-2"></i>
                    CSV-Dateien können direkt in Excel geöffnet werden!
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Weitere Export-Optionen (Zukunft) -->
<div class="col-lg-6 mb-4">
    <div class="card h-100 border-0 shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">
                <i class="bi bi-journal-text me-2"></i>
                Weitere Export-Formate
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted">
                Zusätzliche Export-Formate sind in Entwicklung und werden in zukünftigen
                Versionen verfügbar sein.
            </p>

            <div class="list-group list-group-flush">
                <div class="list-group-item px-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">
                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                PDF Stammbuch
                            </h6>
                            <p class="mb-0 small text-muted">
                                Druckbares Familienbuch mit Fotos und Stammbäumen
                            </p>
                        </div>
                        <span class="badge bg-warning text-dark">Geplant</span>
                    </div>
                </div>

                <div class="list-group-item px-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">
                                <i class="bi bi-file-earmark-code text-primary me-2"></i>
                                JSON/XML Export
                            </h6>
                            <p class="mb-0 small text-muted">
                                Strukturierte Daten für Entwickler und APIs
                            </p>
                        </div>
                        <span class="badge bg-warning text-dark">Geplant</span>
                    </div>
                </div>

                <div class="list-group-item px-0 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">
                                <i class="bi bi-printer text-secondary me-2"></i>
                                Druckvorlagen
                            </h6>
                            <p class="mb-0 small text-muted">
                                Ahnentafeln, Stammbaum-Poster, Personenblätter
                            </p>
                        </div>
                        <span class="badge bg-warning text-dark">Geplant</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Backup Notice -->
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning">
            <h5 class="alert-heading">
                <i class="bi bi-shield-check me-2"></i>Datensicherung
            </h5>
            <p>
                Wir empfehlen regelmäßige Exports deiner Daten zur Sicherung.
                Der GEDCOM-Export enthält alle wichtigen Informationen deines Stammbaums
                und kann bei Bedarf wieder importiert werden.
            </p>
            <hr>
            <p class="mb-0">
                <strong>Hinweis:</strong> Fotos und Mediendateien sind nicht im GEDCOM-Export enthalten.
                Sichere diese bitte separat über den Dateimanager deines Servers.
            </p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Download-Fortschritt anzeigen
    document.addEventListener('DOMContentLoaded', function () {
        // Alle Download-Links
        const downloadLinks = document.querySelectorAll('a[href*="export/"]');

        downloadLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                // Nur bei direkten Downloads
                if (this.href.includes('csv') || this.href.includes('gedcom')) {
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Erstelle...';
                    this.classList.add('disabled');

                    // Nach kurzer Zeit zurücksetzen
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.classList.remove('disabled');
                    }, 2000);
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>