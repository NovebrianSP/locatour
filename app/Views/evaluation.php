<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluasi Rekomendasi - LocaTour</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0f766e;
            --secondary: #f59e0b;
            --surface: #ffffff;
            --muted: #6b7280;
            --bg: #f5f7fb;
            --border: #e5e7eb;
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: #111827;
        }

        .navbar {
            background: var(--surface);
            box-shadow: 0 10px 35px rgba(15, 118, 110, 0.08);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary) !important;
            letter-spacing: -0.5px;
        }

        .hero {
            background: linear-gradient(135deg, #0f766e, #115e59);
            color: #ecfeff;
            padding: 80px 0 70px;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: -80px;
            right: -120px;
            width: 260px;
            height: 260px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .hero h1 {
            font-weight: 700;
            letter-spacing: -0.6px;
        }

        .hero p {
            color: #d1fae5;
            max-width: 640px;
        }

        .card-metric {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.06);
            height: 100%;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }

        .metric-label {
            color: var(--muted);
            font-size: 0.95rem;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 18px;
            letter-spacing: -0.4px;
        }

        .form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.05);
        }

        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ecfdf3;
            color: #047857;
            border-radius: 10px;
            padding: 8px 14px;
            font-weight: 600;
        }

        .table-stats td {
            padding: 10px 8px;
        }

        .pill {
            display: inline-block;
            background: rgba(15, 118, 110, 0.1);
            color: var(--primary);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .table-wrap {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0,0,0,0.05);
        }

        .eval-table thead th {
            background: linear-gradient(135deg, #5b6bff, #8b5cf6);
            color: #fff;
            border: none;
            padding: 14px 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .eval-table tbody td {
            padding: 12px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }

        .config-name {
            font-weight: 700;
            color: #111827;
        }

        .chart-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg py-3">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url() ?>">
            <i class="fas fa-map-marked-alt me-2"></i>LocaTour
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Evaluasi</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <p class="pill mb-3"><i class="fas fa-flask"></i> Uji Akurasi Rekomendasi</p>
                <h1 class="mb-3">Evaluasi MAE & RMSE</h1>
                <p>Mengukur seberapa dekat prediksi rating sistem rekomendasi dengan rating sebenarnya menggunakan Mean Absolute Error (MAE) dan Root Mean Squared Error (RMSE).</p>
            </div>
        </div>
    </div>
</section>

<main class="container" style="margin-top: 40px;">
    <div class="d-flex justify-content-end mb-3">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold">Tampilan</span>
            <select id="viewSwitcher" class="form-select" style="width: 220px;">
                <option value="detail" selected>Evaluasi detail</option>
                <option value="comparison">Perbandingan bobot</option>
            </select>
        </div>
    </div>

    <div id="view-detail">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="form-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="mb-0">Pengaturan</h5>
                    <span class="badge text-bg-light">Item-based CF</span>
                </div>
                <form method="get" class="mt-3">
                    <label for="k" class="form-label fw-semibold">k nearest neighbors</label>
                    <input type="number" min="1" max="50" class="form-control" id="k" name="k" value="<?= $k ?>" required>
                    <div class="form-text">Use 1-50 nearest neighbors when predicting ratings.</div>
                    <button class="btn btn-success w-100 mt-3" type="submit">
                        <i class="fas fa-sync-alt"></i> Hitung ulang
                    </button>
                </form>
                <div class="mt-4">
                    <div class="stat-badge">
                        <i class="fas fa-database"></i>
                        <?= number_format($stats['total_ratings'] ?? 0) ?> rating | <?= number_format($stats['total_users'] ?? 0) ?> pengguna
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card-metric">
                        <div class="metric-label">MAE</div>
                        <div class="metric-value mb-1">
                            <?= $metrics['mae'] !== null ? number_format($metrics['mae'], 4) : 'N/A' ?>
                        </div>
                        <div class="metric-label">Rata-rata |prediksi - aktual|</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-metric">
                        <div class="metric-label">RMSE</div>
                        <div class="metric-value mb-1">
                            <?= $metrics['rmse'] !== null ? number_format($metrics['rmse'], 4) : 'N/A' ?>
                        </div>
                        <div class="metric-label">Akar dari rata-rata error kuadrat</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-metric">
                        <div class="metric-label">Cakupan Prediksi</div>
                        <div class="metric-value mb-1">
                            <?= isset($metrics['coverage']) ? number_format($metrics['coverage'] * 100, 2) . '%' : '0%' ?>
                        </div>
                        <div class="metric-label">Proporsi rating yang bisa diprediksi</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-metric">
                        <div class="metric-label">Jumlah Dievaluasi</div>
                        <div class="metric-value mb-1">
                            <?= number_format($metrics['tested_count'] ?? 0) ?>
                        </div>
                        <div class="metric-label">Rating yang berhasil diprediksi</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-metric">
                        <div class="metric-label">Dilewati</div>
                        <div class="metric-value mb-1 text-danger">
                            <?= number_format($metrics['skipped_count'] ?? 0) ?>
                        </div>
                        <div class="metric-label">Tidak ada tetangga atau bobot nol</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-lg-6">
            <div class="card-metric h-100">
                <h5 class="section-title">Ringkasan Dataset</h5>
                <table class="table table-borderless table-stats mb-0">
                    <tr>
                        <td><i class="fas fa-users text-primary"></i> Pengguna unik</td>
                        <td class="text-end fw-semibold"><?= number_format($stats['total_users'] ?? 0) ?></td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-map-pin text-primary"></i> Destinasi unik</td>
                        <td class="text-end fw-semibold"><?= number_format($stats['total_items'] ?? 0) ?></td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-star text-warning"></i> Total rating</td>
                        <td class="text-end fw-semibold"><?= number_format($stats['total_ratings'] ?? 0) ?></td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-th-large text-success"></i> k neighbors</td>
                        <td class="text-end fw-semibold"><?= number_format($metrics['k_neighbors'] ?? $k) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-metric h-100">
                <h5 class="section-title">Catatan</h5>
                <ul class="mb-0" style="color: var(--muted);">
                    <li>Evaluation uses item-based collaborative filtering with k nearest neighbors.</li>
                    <li>Each rating is predicted from the top-k similar items already rated by the same user.</li>
                    <li>MAE is more tolerant to outliers, RMSE penalizes large errors.</li>
                    <li>If you see "N/A", ensure rating data is sufficient or adjust k to find neighbors.</li>
                </ul>
            </div>
        </div>
    </div>
    </div> <!-- end detail view -->

    <?php
        $scenarioRows = [
            [
                'name' => 'content_based',
                'content' => 1.0,
                'collaborative' => 0.0,
                'weather' => 0.0,
                'mae' => $comparison['content']['mae'] ?? null,
                'rmse' => $comparison['content']['rmse'] ?? null,
            ],
            [
                'name' => 'collaborative',
                'content' => 0.0,
                'collaborative' => 1.0,
                'weather' => 0.0,
                'mae' => $comparison['collaborative']['mae'] ?? null,
                'rmse' => $comparison['collaborative']['rmse'] ?? null,
            ],
            [
                'name' => 'weather_based',
                'content' => 0.1,
                'collaborative' => 0.1,
                'weather' => 0.8,
                'mae' => $comparison['weather']['mae'] ?? null,
                'rmse' => $comparison['weather']['rmse'] ?? null,
            ],
            [
                'name' => 'hybrid',
                'content' => 0.2,
                'collaborative' => 0.5,
                'weather' => 0.3,
                'mae' => $metrics['mae'],
                'rmse' => $metrics['rmse'],
            ],
        ];
    ?>

    <div id="view-comparison" style="display: none;">
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="table-wrap">
                    <table class="table eval-table mb-0">
                        <thead>
                            <tr>
                                <th>Konfigurasi</th>
                                <th>Content</th>
                                <th>Collaborative</th>
                                <th>Weather</th>
                                <th>MAE</th>
                                <th>RMSE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scenarioRows as $row): ?>
                                <tr>
                                    <td class="config-name"><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= is_numeric($row['content']) ? number_format($row['content'], 2) : $row['content'] ?></td>
                                    <td><?= is_numeric($row['collaborative']) ? number_format($row['collaborative'], 2) : $row['collaborative'] ?></td>
                                    <td><?= is_numeric($row['weather']) ? number_format($row['weather'], 2) : $row['weather'] ?></td>
                                    <td><?= $row['mae'] !== null ? number_format($row['mae'], 4) : 'N/A' ?></td>
                                    <td><?= $row['rmse'] !== null ? number_format($row['rmse'], 4) : 'N/A' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-12">
                <div class="chart-card">
                    <h5 class="section-title">Visualisasi Error</h5>
                    <canvas id="errorChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div> <!-- end comparison view -->
</main>

<footer class="mt-5 py-4" style="background: #0b4f49; color: #e0f2f1;">
    <div class="container d-flex justify-content-between flex-wrap gap-3">
        <div>
            <strong>LocaTour</strong><br>
            Evaluasi akurasi rekomendasi pariwisata Yogyakarta.
        </div>
        <div>
            <a href="<?= base_url() ?>" class="text-white text-decoration-none"><i class="fas fa-home"></i> Kembali ke beranda</a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // View switcher
    const switcher = document.getElementById('viewSwitcher');
    const detailView = document.getElementById('view-detail');
    const comparisonView = document.getElementById('view-comparison');

    function updateView() {
        const v = switcher.value;
        if (v === 'comparison') {
            comparisonView.style.display = 'block';
            detailView.style.display = 'none';
        } else {
            comparisonView.style.display = 'none';
            detailView.style.display = 'block';
        }
    }
    switcher?.addEventListener('change', updateView);
    updateView();

    const scenarioData = <?= json_encode($scenarioRows) ?>;
    const labels = scenarioData.map(r => r.name);
    const maeData = scenarioData.map(r => r.mae);
    const rmseData = scenarioData.map(r => r.rmse);

    const ctx = document.getElementById('errorChart');
    if (ctx && Array.isArray(maeData)) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'MAE',
                        data: maeData,
                        backgroundColor: '#f6ad55',
                    },
                    {
                        label: 'RMSE',
                        data: rmseData,
                        backgroundColor: '#f87171',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 2 }
                    }
                }
            }
        });
    }
</script>
</body>
</html>
