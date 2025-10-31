<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h1 class="h3 mb-0">
        <i class="bi bi-graph-up me-2"></i>
        Statistiken & Auswertungen
    </h1>
    <p class="text-muted">Übersicht über Ihren Stammbaum</p>
</div>

<!-- Zusammenfassung Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body text-center">
                <i class="bi bi-people display-4 text-primary"></i>
                <h3 class="mt-2 mb-0"><?= $totalPersons ?></h3>
                <p class="text-muted small mb-0">Personen gesamt</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <i class="bi bi-heart-pulse display-4 text-success"></i>
                <h3 class="mt-2 mb-0"><?= $ageStats['living'] ?></h3>
                <p class="text-muted small mb-0">Lebende Personen</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-secondary">
            <div class="card-body text-center">
                <i class="bi bi-flower1 display-4 text-secondary"></i>
                <h3 class="mt-2 mb-0"><?= $ageStats['deceased'] ?></h3>
                <p class="text-muted small mb-0">Verstorbene</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body text-center">
                <i class="bi bi-question-circle display-4 text-info"></i>
                <h3 class="mt-2 mb-0"><?= $unknownCount ?></h3>
                <p class="text-muted small mb-0">Geschlecht unbekannt</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-4 mb-4">
    <!-- Geschlechterverteilung -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>
                    Geschlechterverteilung
                </h5>
            </div>
            <div class="card-body">
                <canvas id="genderChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Geburten pro Jahrzehnt -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>
                    Geburten pro Jahrzehnt
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($birthsByDecade)): ?>
                    <canvas id="birthsChart" height="300"></canvas>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">Keine Geburtsdaten erfasst</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Beziehungen & Top Listen -->
<div class="row g-4 mb-4">
    <!-- Beziehungen -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-heart me-2"></i>
                    Beziehungen
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-heart-fill text-danger me-2"></i>Ehen</span>
                            <strong><?= $marriages ?></strong>
                        </div>
                    </li>
                    <li>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-heart text-danger me-2"></i>Partnerschaften</span>
                            <strong><?= $partnerships ?></strong>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Top Geburtsorte -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-geo-alt me-2"></i>
                    Häufigste Geburtsorte
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($topBirthPlaces)): ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($topBirthPlaces as $index => $place): ?>
                            <li class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span>
                                        <span class="badge bg-primary me-2"><?= $index + 1 ?></span>
                                        <?= esc($place['birth_place']) ?>
                                    </span>
                                    <strong><?= $place['count'] ?></strong>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: <?= ($place['count'] / $topBirthPlaces[0]['count'] * 100) ?>%"></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">Keine Geburtsorte erfasst</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top Berufe (kompakt) -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-briefcase me-2"></i>
                    Häufigste Berufe
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($topOccupations)): ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($topOccupations as $index => $occupation): ?>
                            <li class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span>
                                        <span class="badge bg-success me-2"><?= $index + 1 ?></span>
                                        <?= esc($occupation['occupation']) ?>
                                    </span>
                                    <strong><?= $occupation['count'] ?></strong>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: <?= ($occupation['count'] / $topOccupations[0]['count'] * 100) ?>%"></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">Keine Berufe erfasst</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Neueste Personen -->
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Zuletzt hinzugefügte Personen
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recentPersons)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Geburtsdatum</th>
                                    <th>Geburtsort</th>
                                    <th>Hinzugefügt am</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentPersons as $person): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($person['first_name'] . ' ' . $person['last_name']) ?></strong>
                                            <?php if ($person['death_date']): ?>
                                                <span class="badge bg-secondary ms-1">†</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($person['birth_date']): ?>
                                                <?= date('d.m.Y', strtotime($person['birth_date'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($person['birth_place']): ?>
                                                <?= esc($person['birth_place']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('d.m.Y H:i', strtotime($person['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('persons/view/' . $person['id']) ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">Noch keine Personen erfasst</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Geschlechterverteilung Pie Chart
    const genderCtx = document.getElementById('genderChart');
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($genderChartData['labels']) ?>,
            datasets: [{
                data: <?= json_encode($genderChartData['data']) ?>,
                backgroundColor: <?= json_encode($genderChartData['colors']) ?>,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    <?php if (!empty($birthsByDecade)): ?>
        // Geburten pro Jahrzehnt Bar Chart
        const birthsCtx = document.getElementById('birthsChart');
        new Chart(birthsCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($birthsByDecade)) ?>,
                datasets: [{
                    label: 'Geburten',
                    data: <?= json_encode(array_values($birthsByDecade)) ?>,
                    backgroundColor: 'rgba(102, 126, 234, 0.7)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    <?php endif; ?>
</script>

<?= $this->endSection() ?>