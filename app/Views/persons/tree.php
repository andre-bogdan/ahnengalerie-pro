<!-- app/Views/persons/tree.php - Stammbaum mit Vis.js -->

<!-- Layout: Main -->
<?= $this->extend('layouts/main') ?>

<!-- Section: Content -->
<?= $this->section('content') ?>

<!-- Statistik Header -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="mb-0">
                            <i class="bi bi-diagram-3"></i> Familien-Stammbaum
                        </h2>
                        <p class="text-muted mb-0">Interaktive Visualisierung</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <div id="stats-info">
                            <span class="badge bg-primary me-2">
                                <i class="bi bi-people"></i> <span id="person-count">-</span> Personen
                            </span>
                            <span class="badge bg-success">
                                <i class="bi bi-link-45deg"></i> <span id="relationship-count">-</span> Beziehungen
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kontroll-Panel -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- Layout Auswahl -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Layout:</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="layout" id="layout-hierarchical" value="hierarchical" checked>
                            <label class="btn btn-outline-primary" for="layout-hierarchical">
                                <i class="bi bi-diagram-3"></i> Hierarchisch
                            </label>
                            <input type="radio" class="btn-check" name="layout" id="layout-network" value="network">
                            <label class="btn btn-outline-primary" for="layout-network">
                                <i class="bi bi-circle"></i> Netzwerk
                            </label>
                        </div>
                    </div>

                    <!-- Richtung -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Richtung:</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="direction" id="dir-vertical" value="UD" checked>
                            <label class="btn btn-outline-secondary" for="dir-vertical">
                                <i class="bi bi-arrow-down"></i> Vertikal
                            </label>
                            <input type="radio" class="btn-check" name="direction" id="dir-horizontal" value="LR">
                            <label class="btn btn-outline-secondary" for="dir-horizontal">
                                <i class="bi bi-arrow-right"></i> Horizontal
                            </label>
                        </div>
                    </div>

                    <!-- Zoom Controls -->
                    <div class="col-md-4 text-end">
                        <label class="form-label fw-bold d-block">Zoom:</label>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary" id="zoom-in">
                                <i class="bi bi-zoom-in"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="zoom-out">
                                <i class="bi bi-zoom-out"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="zoom-fit">
                                <i class="bi bi-arrows-angle-contract"></i> Fit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stammbaum Visualisierung -->
<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0 position-relative">
                <!-- Loading Spinner -->
                <div id="loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Lädt...</span>
                    </div>
                    <p class="mt-3 text-muted">Stammbaum wird geladen...</p>
                </div>

                <!-- Vis.js Network Container -->
                <div id="network-container" style="height: 700px; display: none;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Legende -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-info-circle"></i> Legende</h6>
                <div class="row">
                    <div class="col-md-3">
                        <strong>Personen:</strong><br>
                        <span class="badge bg-primary">Männlich</span>
                        <span class="badge bg-danger">Weiblich</span>
                        <span class="badge bg-secondary">Unbekannt</span>
                    </div>
                    <div class="col-md-9">
                        <strong>Beziehungen:</strong><br>
                        <span style="color: green;">━━▶</span> Elternteil → Kind &nbsp;|&nbsp;
                        <span style="color: red;">━━━</span> Ehe &nbsp;|&nbsp;
                        <span style="color: red;">┄┄┄</span> Partnerschaft &nbsp;|&nbsp;
                        <span style="color: orange;">┄┄┄</span> Geschwister
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vis.js Network CDN -->
<script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>

<!-- Custom Script -->
<script>
let network = null;
let treeData = null;

// Stammbaum-Daten laden
fetch('<?= base_url('persons/tree-data') ?>')
    .then(response => response.json())
    .then(data => {
        treeData = data;
        
        console.log('Original data loaded:', data.nodes.length, 'nodes,', data.edges.length, 'edges');
        
        // Geschwister-Beziehungen hinzufügen
        addSiblingEdges(treeData);
        
        // Generationen berechnen und setzen
        calculateGenerations(treeData);
        
        console.log('Enhanced data:', treeData.nodes.length, 'nodes,', treeData.edges.length, 'edges');
        
        // Statistik aktualisieren
        document.getElementById('person-count').textContent = data.nodes.length;
        document.getElementById('relationship-count').textContent = treeData.edges.length;
        
        // Loading ausblenden
        document.getElementById('loading').style.display = 'none';
        
        // Stammbaum rendern
        renderNetwork();
    })
    .catch(error => {
        console.error('Fehler beim Laden:', error);
        document.getElementById('loading').innerHTML = 
            '<div class="alert alert-danger">Fehler beim Laden der Daten</div>';
    });

// Geschwister-Beziehungen hinzufügen
function addSiblingEdges(data) {
    const siblings = new Map();
    
    // Finde alle Kinder pro Elternteil
    data.edges.forEach(edge => {
        if (edge.arrows === 'to') {
            // parent -> child
            if (!siblings.has(edge.from)) {
                siblings.set(edge.from, []);
            }
            siblings.get(edge.from).push(edge.to);
        }
    });
    
    // Erstelle Geschwister-Verbindungen
    const siblingPairs = new Set();
    
    siblings.forEach((children, parent) => {
        if (children.length > 1) {
            // Alle Kombinationen von Geschwistern
            for (let i = 0; i < children.length; i++) {
                for (let j = i + 1; j < children.length; j++) {
                    const pair = [children[i], children[j]].sort().join('-');
                    if (!siblingPairs.has(pair)) {
                        siblingPairs.add(pair);
                        
                        // Geschwister-Edge hinzufügen
                        data.edges.push({
                            from: children[i],
                            to: children[j],
                            color: {
                                color: '#FF9800',
                                opacity: 0.6
                            },
                            width: 2,
                            dashes: [5, 5],
                            smooth: false
                        });
                    }
                }
            }
        }
    });
    
    console.log('Added', siblingPairs.size, 'sibling connections');
}

// Generationen berechnen
function calculateGenerations(data) {
    const generations = new Map();
    const processed = new Set();
    
    // Finde Wurzeln (Personen ohne Eltern)
    const childIds = new Set();
    data.edges.forEach(edge => {
        if (edge.arrows === 'to') {
            childIds.add(edge.to);
        }
    });
    
    const roots = data.nodes.filter(n => !childIds.has(n.id));
    console.log('Found', roots.length, 'root persons');
    
    // Setze Wurzeln auf Generation 0
    roots.forEach(root => {
        generations.set(root.id, 0);
        processed.add(root.id);
    });
    
    // Berechne Generationen für alle anderen
    let changed = true;
    let iterations = 0;
    
    while (changed && iterations < 20) {
        changed = false;
        iterations++;
        
        data.edges.forEach(edge => {
            if (edge.arrows === 'to') {
                // parent -> child
                const parentGen = generations.get(edge.from);
                const childGen = generations.get(edge.to);
                
                if (parentGen !== undefined) {
                    const expectedChildGen = parentGen + 1;
                    
                    if (childGen === undefined || childGen < expectedChildGen) {
                        generations.set(edge.to, expectedChildGen);
                        changed = true;
                    }
                }
            }
        });
        
        // Separate Schleife für Ehen - Partner auf gleiche Generation
        data.edges.forEach(edge => {
            if (edge.arrows !== 'to' && edge.color?.color === '#D32F2F') {
                // Ehe/Partner
                const gen1 = generations.get(edge.from);
                const gen2 = generations.get(edge.to);
                
                if (gen1 !== undefined && gen2 !== undefined && gen1 !== gen2) {
                    // Setze beide auf die höhere Generation (größere Zahl = weiter unten)
                    const targetGen = Math.max(gen1, gen2);
                    generations.set(edge.from, targetGen);
                    generations.set(edge.to, targetGen);
                    changed = true;
                    console.log('Aligned partners:', edge.from, 'and', edge.to, 'to generation', targetGen);
                } else if (gen1 !== undefined && gen2 === undefined) {
                    generations.set(edge.to, gen1);
                    changed = true;
                } else if (gen2 !== undefined && gen1 === undefined) {
                    generations.set(edge.from, gen2);
                    changed = true;
                }
            }
        });
    }
    
    console.log('Generations calculated in', iterations, 'iterations');
    
    // Setze level Property für jeden Node
    data.nodes.forEach(node => {
        const gen = generations.get(node.id);
        if (gen !== undefined) {
            node.level = gen;
            console.log('Node', node.title, 'set to generation', gen);
        } else {
            node.level = 0;
            console.log('Node', node.title, 'has no generation, set to 0');
        }
    });
}

// Network rendern
function renderNetwork() {
    const container = document.getElementById('network-container');
    container.style.display = 'block';
    
    const layout = document.querySelector('input[name="layout"]:checked').value;
    const direction = document.querySelector('input[name="direction"]:checked').value;
    
    const options = {
        layout: layout === 'hierarchical' ? {
            hierarchical: {
                direction: direction,
                sortMethod: 'directed',
                levelSeparation: 150,
                nodeSpacing: 100,
                treeSpacing: 200,
                blockShifting: true,
                edgeMinimization: true,
                parentCentralization: true
            }
        } : {
            randomSeed: 42
        },
        physics: layout === 'network' ? {
            enabled: true,
            barnesHut: {
                gravitationalConstant: -8000,
                springLength: 200,
                springConstant: 0.05
            }
        } : false,
        nodes: {
            shape: 'circularImage',
            size: 30,
            font: {
                size: 14,
                face: 'Arial'
            },
            borderWidth: 3,
            shadow: true
        },
        edges: {
            width: 2,
            shadow: true,
            smooth: {
                type: 'cubicBezier',
                roundness: 0.5
            }
        },
        interaction: {
            hover: true,
            navigationButtons: false,
            keyboard: true
        }
    };
    
    network = new vis.Network(container, treeData, options);
    
    // Click-Event für Navigation
    network.on('click', function(params) {
        if (params.nodes.length > 0) {
            const personId = params.nodes[0];
            window.location.href = '<?= base_url('persons/view') ?>/' + personId;
        }
    });
}

// Event Listeners
document.querySelectorAll('input[name="layout"]').forEach(radio => {
    radio.addEventListener('change', () => {
        renderNetwork();
    });
});

document.querySelectorAll('input[name="direction"]').forEach(radio => {
    radio.addEventListener('change', () => {
        renderNetwork();
    });
});

document.getElementById('zoom-in').addEventListener('click', () => {
    if (network) {
        const scale = network.getScale();
        network.moveTo({ scale: scale * 1.2 });
    }
});

document.getElementById('zoom-out').addEventListener('click', () => {
    if (network) {
        const scale = network.getScale();
        network.moveTo({ scale: scale * 0.8 });
    }
});

document.getElementById('zoom-fit').addEventListener('click', () => {
    if (network) {
        network.fit();
    }
});
</script>

<style>
.btn-check:checked + .btn-outline-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
}

.btn-check:checked + .btn-outline-secondary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
}
</style>

<?= $this->endSection() ?>