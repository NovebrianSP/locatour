<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - LocaTour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
            --accent-color: #f39c12;
            --light-bg: #ecf0f1;
            --dark-text: #2c3e50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-text);
            background: var(--light-bg);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--secondary-color) !important;
        }

        .navbar-brand i {
            color: var(--accent-color);
        }

        .search-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 0;
            color: white;
            text-align: center;
            margin-bottom: 50px;
        }

        .search-hero h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .search-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }

        .meta-badge {
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .results-section {
            padding: 60px 0;
        }

        .results-header {
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .results-count {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .destination-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            margin-bottom: 30px;
        }

        .destination-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .card-img-wrapper {
            position: relative;
            height: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
        }

        .card-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--secondary-color);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .destination-card .card-body {
            padding: 25px;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .card-category {
            display: inline-block;
            background: var(--accent-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-bottom: 15px;
        }

        .card-text {
            color: #7f8c8d;
            font-size: 0.95rem;
            margin-bottom: 15px;
            display: -webkit-box;
            line-clamp: 3;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .score-badge {
            display: inline-block;
            background: #e8f4f8;
            padding: 8px 12px;
            border-radius: 8px;
            margin: 5px 5px 5px 0;
            font-size: 0.85rem;
            color: #2c5aa0;
        }

        .score-badge i {
            margin-right: 5px;
        }

        .card-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #ecf0f1;
        }

        .card-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--secondary-color);
        }

        .card-rating {
            color: var(--accent-color);
            font-size: 1rem;
        }

        .no-results {
            text-align: center;
            padding: 80px 20px;
        }

        .no-results i {
            font-size: 5rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .no-results h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .no-results p {
            color: #7f8c8d;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 30px;
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            color: #c0392b;
        }

        .footer {
            background: var(--primary-color);
            color: white;
            padding: 50px 0 20px;
            margin-top: 60px;
        }

        .footer h5 {
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .footer ul {
            list-style: none;
            padding: 0;
        }

        .footer ul li {
            margin-bottom: 10px;
        }

        .footer a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .footer a:hover {
            opacity: 1;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-map-marked-alt"></i> LocaTour
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Search Hero -->
    <section class="search-hero">
        <div class="container">
            <h1>Hasil Pencarian</h1>
            <p>Destinasi wisata yang sesuai dengan preferensi Anda</p>
            <div class="search-meta">
                <?php if (!empty($keyword)): ?>
                    <div class="meta-badge"><i class="fas fa-search"></i> Kata kunci: "<strong><?= htmlspecialchars($keyword) ?></strong>"</div>
                <?php endif; ?>
                <?php if (!empty($category)): ?>
                    <div class="meta-badge"><i class="fas fa-tag"></i> Kategori: <strong><?= htmlspecialchars($category) ?></strong></div>
                <?php endif; ?>
                <?php if ($minPrice || $maxPrice): ?>
                    <div class="meta-badge"><i class="fas fa-tag"></i> Harga: <strong><?= $minPrice ? 'Rp ' . number_format($minPrice, 0, ',', '.') : '0' ?> - <?= $maxPrice ? 'Rp ' . number_format($maxPrice, 0, ',', '.') : 'unlimited' ?></strong></div>
                <?php endif; ?>
                <?php if (!empty($preference)): ?>
                    <div class="meta-badge"><i class="fas fa-cloud"></i> Preferensi: <strong><?= ucfirst($preference) ?></strong></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Results Section -->
    <section class="results-section">
        <div class="container">
            <a href="<?= base_url() ?>" class="back-link">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>

            <?php if (count($results) > 0): ?>
                <div class="results-header">
                    <h3>Ditemukan <?= count($results) ?> destinasi</h3>
                </div>

                <div class="row">
                    <?php foreach ($results as $place): ?>
                    <div class="col-md-4">
                        <div class="destination-card">
                            <div class="card-img-wrapper">
                                <span class="card-badge">
                                    <i class="fas fa-star"></i> <?= $place['rating'] ?>/5
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($place['place_name']) ?></h5>
                                <span class="card-category">
                                    <i class="fas fa-tag"></i> <?= htmlspecialchars($place['category']) ?>
                                </span>
                                <p class="card-text">
                                    <?= htmlspecialchars(substr($place['description'] ?? '', 0, 150)) ?>...
                                </p>

                                <!-- Scoring breakdown -->
                                <div style="margin: 10px 0; padding: 10px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">
                                    <div class="score-badge">
                                        <i class="fas fa-search"></i> Keyword: <?= number_format($place['content_score'] * 100, 1) ?>%
                                    </div>
                                    <div class="score-badge">
                                        <i class="fas fa-star"></i> Rating: <?= number_format($place['rating_score'] * 100, 1) ?>%
                                    </div>
                                    <div class="score-badge">
                                        <i class="fas fa-cloud-sun"></i> Cuaca: <?= number_format($place['weather_score'] * 100, 1) ?>%
                                    </div>
                                </div>

                                <div class="card-info">
                                    <div class="card-price">
                                        <?php if (($place['price'] ?? 0) == 0): ?>
                                            <i class="fas fa-ticket-alt"></i> Gratis
                                        <?php else: ?>
                                            <i class="fas fa-ticket-alt"></i> Rp <?= number_format($place['price'], 0, ',', '.') ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-rating">
                                        <i class="fas fa-heart"></i> Skor: <?= number_format($place['hybrid_score'], 2) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <h3>Tidak Ada Hasil</h3>
                    <p>Tidak ada destinasi wisata yang sesuai dengan kriteria pencarian Anda. Coba ubah filter atau kata kunci.</p>
                    <a href="<?= base_url() ?>" class="btn btn-danger mt-3">
                        <i class="fas fa-home"></i> Kembali ke Home
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-map-marked-alt"></i> LocaTour</h5>
                    <p>Platform wisata terlengkap untuk menjelajahi keindahan Yogyakarta.</p>
                </div>
                <div class="col-md-4">
                    <h5>Link Cepat</h5>
                    <ul>
                        <li><a href="<?= base_url() ?>"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="<?= base_url() ?>#search"><i class="fas fa-chevron-right"></i> Cari</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Yogyakarta, Indonesia</li>
                        <li><i class="fas fa-envelope"></i> info@locatour.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 LocaTour. Dibuat dengan <i class="fas fa-heart" style="color: #e74c3c;"></i> untuk Yogyakarta</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animate cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.destination-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>
