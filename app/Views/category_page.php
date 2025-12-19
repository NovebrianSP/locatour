<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category_name) ?> - LocaTour</title>
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

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--secondary-color) !important;
        }

        .navbar-brand i {
            color: var(--accent-color);
        }

        .nav-link {
            color: var(--dark-text) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--secondary-color) !important;
        }

        /* Navbar search icon hover */
        .nav-link .fa-search {
            transition: color 0.2s ease, transform 0.2s ease, text-shadow 0.2s ease;
        }
        .nav-link:hover .fa-search {
            color: var(--secondary-color);
            transform: translateY(-1px);
            text-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        /* Hero Header */
        .category-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 120px 0 80px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .category-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,144C960,149,1056,139,1152,128C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            opacity: 0.3;
        }

        .category-hero-content {
            position: relative;
            z-index: 1;
        }

        .category-hero h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-transform: capitalize;
        }

        .category-hero .breadcrumb {
            background: rgba(255,255,255,0.1);
            padding: 10px 20px;
            border-radius: 30px;
            display: inline-flex;
            margin-top: 1rem;
        }

        .category-hero .breadcrumb-item {
            color: white;
        }

        .category-hero .breadcrumb-item a {
            color: white;
            text-decoration: none;
        }

        .category-hero .breadcrumb-item.active {
            color: var(--accent-color);
        }

        .category-hero .breadcrumb-item + .breadcrumb-item::before {
            color: white;
        }

        /* Stats Cards */
        .category-stats {
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border-radius: 15px;
            padding: 30px;
            margin-top: -50px;
            position: relative;
            z-index: 2;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
        }

        .stat-item i {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }

        .stat-item .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            display: block;
        }

        .stat-item .stat-label {
            color: #7f8c8d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Filters */
        .filters-section {
            padding: 40px 0 20px;
        }

        .filter-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .filter-btn {
            padding: 10px 20px;
            border: 2px solid #ddd;
            background: white;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        /* Places Grid */
        .places-section {
            padding: 20px 0 80px;
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
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
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

        .card-meta {
            display: flex;
            gap: 15px;
            margin-top: 10px;
            font-size: 0.85rem;
            color: #7f8c8d;
        }

        .card-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* No Results */
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

        /* Footer */
        .footer {
            background: var(--primary-color);
            color: white;
            padding: 50px 0 20px;
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

        @media (max-width: 768px) {
            .category-hero h1 {
                font-size: 2rem;
            }

            .filter-buttons {
                justify-content: center;
            }
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
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>#destinations">Destinasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>#categories">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>#contact">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#searchModal" title="Cari">
                            <i class="fas fa-search" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cari"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Category Hero -->
    <section class="category-hero">
        <div class="category-hero-content">
            <div class="container">
                <h1><i class="fas fa-tag"></i> <?= htmlspecialchars($category_name) ?></h1>
                <p class="lead">Jelajahi <?= $total_places ?> destinasi wisata terbaik</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>#categories">Kategori</a></li>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($category_name) ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

        <!-- Search Modal (triggered from navbar icon) -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="searchModalLabel"><i class="fas fa-search"></i> Pencarian Destinasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="modalSearchKeyword" class="form-label">Kata Kunci</label>
                                    <input type="text" id="modalSearchKeyword" class="form-control" placeholder="Contoh: candi, pantai, air terjun...">
                                </div>
                                <div class="col-md-6">
                                    <label for="modalSearchCategory" class="form-label">Kategori</label>
                                    <select id="modalSearchCategory" class="form-select">
                                        <option value="">-- Semua Kategori --</option>
                                        <option value="cagar alam">Cagar Alam</option>
                                        <option value="budaya">Budaya</option>
                                        <option value="taman hiburan">Taman Hiburan</option>
                                        <option value="bahari">Bahari</option>
                                        <option value="wisata air">Wisata Air</option>
                                        <option value="museum">Museum</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Rentang Harga</label>
                                    <div class="d-flex gap-2">
                                        <input type="number" id="modalSearchMinPrice" class="form-control" placeholder="Min (Rp)" min="0" step="5000">
                                        <input type="number" id="modalSearchMaxPrice" class="form-control" placeholder="Max (Rp)" min="0" step="5000">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="modalSearchPref" class="form-label">Preferensi Cuaca</label>
                                    <select id="modalSearchPref" class="form-select">
                                        <option value="">-- Sesuai Cuaca Terkini --</option>
                                        <option value="indoor">Indoor</option>
                                        <option value="outdoor">Outdoor</option>
                                        <option value="mixed">Campuran</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-danger" onclick="performSearchModal()"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </div>
            </div>
        </div>
    <!-- Recommended for Category -->
    <section class="places-section">
        <div class="container">
            <h2 class="section-title">Rekomendasi di Kategori Ini</h2>
            <p class="section-subtitle">
                Preferensi cuaca: 
                <span class="badge bg-warning text-dark" style="font-size: 0.95rem;">
                    <?= isset($recommendation_meta['preference']) ? ucfirst($recommendation_meta['preference']) : 'Mixed' ?>
                </span>
            </p>

            <div class="row">
                <?php foreach (($recommended_places ?? []) as $place): ?>
                <div class="col-md-4">
                    <div class="destination-card">
                        <div class="card-img-wrapper">
                            <span class="card-badge">
                                <i class="fas fa-sun"></i> Skor: <?= number_format($place['hybrid_score'] ?? 0, 2) ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($place['name']) ?></h5>
                            <span class="card-category">
                                <i class="fas fa-tag"></i> <?= htmlspecialchars($place['category']) ?>
                            </span>
                            <p class="card-text">
                                <?= htmlspecialchars(substr($place['description'] ?? 'Direkomendasikan sesuai kondisi cuaca.', 0, 150)) ?>...
                            </p>
                            <div class="card-info">
                                <div class="card-price">
                                    <?php if (($place['price'] ?? 0) == 0): ?>
                                        <i class="fas fa-ticket-alt"></i> Gratis
                                    <?php else: ?>
                                        <i class="fas fa-ticket-alt"></i> Rp <?= number_format($place['price'], 0, ',', '.') ?>
                                    <?php endif; ?>
                                </div>
                                <div class="card-rating">
                                    <i class="fas fa-star"></i> <?= $place['rating'] ?>/5
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Category Stats -->
    <section class="container">
        <div class="category-stats">
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="stat-value"><?= $total_places ?></span>
                        <span class="stat-label">Total Destinasi</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <i class="fas fa-star"></i>
                        <span class="stat-value"><?= number_format($avg_rating, 1) ?></span>
                        <span class="stat-label">Rating Rata-Rata</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <i class="fas fa-ticket-alt"></i>
                        <span class="stat-value">Rp <?= number_format($avg_price, 0, ',', '.') ?></span>
                        <span class="stat-label">Harga Rata-Rata</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters -->
    <section class="filters-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="mb-3"><i class="fas fa-filter"></i> Filter & Urutkan</h4>
                <div class="filter-buttons">
                    <button class="filter-btn active" onclick="filterPlaces('all')">
                        <i class="fas fa-th"></i> Semua
                    </button>
                    <button class="filter-btn" onclick="sortPlaces('rating')">
                        <i class="fas fa-star"></i> Rating Tertinggi
                    </button>
                    <button class="filter-btn" onclick="sortPlaces('price-low')">
                        <i class="fas fa-arrow-up"></i> Harga Terendah
                    </button>
                    <button class="filter-btn" onclick="sortPlaces('price-high')">
                        <i class="fas fa-arrow-down"></i> Harga Tertinggi
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Places Grid -->
    <section class="places-section">
        <div class="container">
            <?php if (count($places) > 0): ?>
                <div class="row" id="placesGrid">
                    <?php foreach ($places as $place): ?>
                    <div class="col-md-4 place-item" 
                         data-rating="<?= $place['rating'] ?>" 
                         data-price="<?= $place['price'] ?>">
                        <div class="destination-card">
                            <div class="card-img-wrapper">
                                <span class="card-badge">
                                    <i class="fas fa-star"></i> <?= $place['rating'] ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($place['name']) ?></h5>
                                <span class="card-category">
                                    <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($place['city']) ?>
                                </span>
                                <p class="card-text">
                                    <?= htmlspecialchars(substr($place['description'], 0, 150)) ?>...
                                </p>
                                <div class="card-meta">
                                    <span>
                                        <i class="fas fa-clock"></i> 
                                        <?= $place['time'] > 0 ? $place['time'] . ' menit' : 'Fleksibel' ?>
                                    </span>
                                </div>
                                <div class="card-info">
                                    <div class="card-price">
                                        <?php if ($place['price'] == 0): ?>
                                            <i class="fas fa-ticket-alt"></i> Gratis
                                        <?php else: ?>
                                            <i class="fas fa-ticket-alt"></i> Rp <?= number_format($place['price'], 0, ',', '.') ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-rating">
                                        <i class="fas fa-star"></i> <?= $place['rating'] ?>/5
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
                    <p>Tidak ada destinasi wisata untuk kategori ini saat ini.</p>
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
                        <li><a href="<?= base_url() ?>#destinations"><i class="fas fa-chevron-right"></i> Destinasi</a></li>
                        <li><a href="<?= base_url() ?>#categories"><i class="fas fa-chevron-right"></i> Kategori</a></li>
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
        let originalOrder = [];
        const placesGrid = document.getElementById('placesGrid');
        const placeItems = document.querySelectorAll('.place-item');
        
        // Store original order
        placeItems.forEach((item, index) => {
            originalOrder.push(item);
        });

        function filterPlaces(type) {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.closest('.filter-btn').classList.add('active');

            // Reset to original order
            placesGrid.innerHTML = '';
            originalOrder.forEach(item => {
                placesGrid.appendChild(item);
            });
        }

        function sortPlaces(sortType) {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.closest('.filter-btn').classList.add('active');

            const items = Array.from(placeItems);
            
            items.sort((a, b) => {
                if (sortType === 'rating') {
                    return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                } else if (sortType === 'price-low') {
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                } else if (sortType === 'price-high') {
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                }
            });

            // Clear and re-append sorted items
            placesGrid.innerHTML = '';
            items.forEach(item => {
                placesGrid.appendChild(item);
            });
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

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

        // Init tooltips (for search icon)
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Disable category when keyword filled, and disable keyword when category selected (modal)
        (function keywordCategoryMutualDisable(){
            const kw = document.getElementById('modalSearchKeyword');
            const cat = document.getElementById('modalSearchCategory');
            const modal = document.getElementById('searchModal');
            if (!kw || !cat) return;
            function sync() {
                const hasKw = kw.value.trim().length > 0;
                const hasCat = !!cat.value;
                cat.disabled = hasKw;
                kw.disabled = hasCat;
            }
            kw.addEventListener('input', sync);
            cat.addEventListener('change', sync);
            if (modal) {
                modal.addEventListener('shown.bs.modal', sync);
            }
            sync();
        })();

        // Modal search submission (redirect to /search)
        function performSearchModal() {
            const keyword = document.getElementById('modalSearchKeyword').value;
            const category = document.getElementById('modalSearchCategory').value;
            const minPrice = document.getElementById('modalSearchMinPrice').value;
            const maxPrice = document.getElementById('modalSearchMaxPrice').value;
            const pref = document.getElementById('modalSearchPref').value;

            const params = new URLSearchParams();
            if (keyword) params.append('q', keyword);
            if (category) params.append('category', category);
            if (minPrice) params.append('min_price', minPrice);
            if (maxPrice) params.append('max_price', maxPrice);
            if (pref) params.append('pref', pref);

            window.location.href = '<?= base_url('search') ?>?' + params.toString();
        }
    </script>
</body>
</html>
