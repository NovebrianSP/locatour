<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocaTour - Jelajahi Keindahan Yogyakarta</title>
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
            overflow-x: hidden;
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
            transition: all 0.3s ease;
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

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding-top: 80px;
        }

        /* Background slider behind hero content */
        .hero-slider {
            position: absolute;
            inset: 0;
            z-index: 1;
            overflow: hidden;
        }

        .hero-slide {
            position: absolute;
            inset: 0;
            background-position: center;
            background-size: cover;
            opacity: 0;
            transform: scale(1.08);
            animation: heroFade 10s infinite;
        }

        .hero-slide.slide-1 { animation-delay: 0s; }
        .hero-slide.slide-2 { animation-delay: 3.33s; }
        .hero-slide.slide-3 { animation-delay: 6.66s; }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0.45) 100%);
            z-index: 2;
        }

        .hero-section::before {
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

        .hero-content {
            position: relative;
            z-index: 3; /* above slider and overlay */
            text-align: center;
            color: white;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease;
        }

        .hero-content p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1.2s ease;
        }

        .hero-btn {
            padding: 15px 40px;
            font-size: 1.2rem;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            animation: fadeInUp 1.4s ease;
        }

        .hero-btn:hover {
            background: #c0392b;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        /* Stats Section */
        .stats-section {
            background: white;
            padding: 80px 0;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
        }

        .stat-card {
            text-align: center;
            padding: 30px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
        }

        .stat-icon {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Search Section */
        .search-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 0;
            color: white;
            position: relative;
            z-index: 5;
            margin-bottom: -40px;
        }

        .search-box {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            margin-top: 30px;
        }

        .search-box h3 {
            color: var(--primary-color);
            margin-bottom: 30px;
            font-weight: bold;
        }

        .search-form-group {
            margin-bottom: 20px;
        }

        .search-form-group label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .search-form-group input,
        .search-form-group select {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
            transition: border 0.3s ease;
            width: 100%;
        }

        .search-form-group input:focus,
        .search-form-group select:focus {
            border-color: var(--secondary-color);
            outline: none;
        }

        .price-inputs {
            display: flex;
            gap: 15px;
        }

        .price-inputs .search-form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .search-btn {
            background: var(--secondary-color);
            color: white;
            padding: 12px 40px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .search-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .search-results {
            display: none;
            margin-top: 60px;
        }

        .search-results.show {
            display: block;
        }

        .search-info {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 40px;
        }

        .no-search-results {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .no-search-results i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        /* Featured Section */
        .featured-section {
            padding: 80px 0;
            background: var(--light-bg);
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 3rem;
        }

        .destination-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
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
            /* Standard property for broader compatibility */
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

        /* Categories Section */
        .categories-section {
            padding: 80px 0;
            background: white;
        }

        .category-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }

        .category-card:hover::before {
            top: -100%;
            left: -100%;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .category-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .category-name {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: capitalize;
        }

        .category-count {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .cta-btn {
            padding: 15px 40px;
            font-size: 1.1rem;
            background: white;
            color: var(--secondary-color);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .cta-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
        }

        .cta-btn-outline {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.9);
        }

        .cta-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .cta-btn-outline:hover {
            background: rgba(255, 255, 255, 0.12);
            color: white;
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

        .social-icons {
            margin-top: 1rem;
        }

        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Keyframes for background slides */
        @keyframes heroFade {
            0% { opacity: 0; transform: scale(1.08); }
            6% { opacity: 1; transform: scale(1.04); }
            30% { opacity: 1; transform: scale(1.02); }
            36% { opacity: 0; transform: scale(1.0); }
            100% { opacity: 0; transform: scale(1.0); }
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-map-marked-alt"></i> LocaTour
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#destinations">Destinasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#categories">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#searchModal" title="Cari">
                            <i class="fas fa-search" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cari"></i>
                        </a>
                    </li>
                    <?php if (session('user_id')): ?>
                        <li class="nav-item dropdown ms-2">
                            <a class="btn btn-danger dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i> <?= esc(session('user_name')) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-2">
                            <a class="btn btn-danger" href="<?= base_url('login') ?>">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <!-- Background slider (place your images under public/images) -->
        <div class="hero-slider">
            <!-- Replace the URLs below with your uploaded image filenames -->
            <div class="hero-slide slide-1" style="background-image: url('/images/slides/hero-tugu.jpg');"></div>
            <div class="hero-slide slide-2" style="background-image: url('/images/slides/hero-prambanan.jpg');"></div>
            <div class="hero-slide slide-3" style="background-image: url('/images/slides/hero-merapi.jpg');"></div>
        </div>
        <div class="hero-overlay"></div>

        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-globe-asia"></i> Jelajahi Yogyakarta</h1>
                <p>Temukan Keindahan & Pesona Wisata Istimewa</p>
                <button class="hero-btn" onclick="document.getElementById('destinations').scrollIntoView({behavior: 'smooth'})">
                    Mulai Petualangan <i class="fas fa-arrow-right"></i>
                </button>
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

    <!-- Recommended Section -->
    <section id="recommended" class="featured-section">
        <div class="container">
            <h2 class="section-title">Rekomendasi Untuk Anda</h2>
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <?php if (!empty($is_logged_in) && ($recommendation_mode ?? 'weather') === 'personalized'): ?>
                    <p class="section-subtitle mb-0">
                        Berdasarkan aktivitas dan preferensi akun Anda.
                    </p>
                <?php else: ?>
                    <p class="section-subtitle mb-0">
                        Berdasarkan cuaca — Preferensi:
                        <span id="prefBadge" class="badge bg-warning text-dark" style="font-size: 0.95rem;">&nbsp;<?= isset($recommendation_meta['preference']) ? ucfirst($recommendation_meta['preference']) : 'Mixed' ?></span>
                    </p>
                    <div class="btn-group" role="group" aria-label="Your Preferences">
                        <button type="button" class="btn btn-outline-secondary" data-pref="mixed">Campuran</button>
                        <button type="button" class="btn btn-outline-secondary" data-pref="outdoor">Outdoor</button>
                        <button type="button" class="btn btn-outline-secondary" data-pref="indoor">Indoor</button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row" id="recommendedGrid">
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

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="stat-number"><?= $statistics['total_places'] ?></div>
                        <div class="stat-label">Destinasi Wisata</div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-number"><?= $statistics['avg_rating'] ?></div>
                        <div class="stat-label">Rating Rata-Rata</div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-list"></i>
                        </div>
                        <div class="stat-number"><?= $statistics['total_categories'] ?></div>
                        <div class="stat-label">Kategori</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Destinations Section -->
    <section id="destinations" class="featured-section">
        <div class="container">
            <h2 class="section-title">Destinasi Unggulan</h2>
            <p class="section-subtitle">Temukan tempat wisata terbaik dengan rating tertinggi di Yogyakarta</p>
            
            <div class="row">
                <?php foreach ($featured_places as $place): ?>
                <div class="col-md-4">
                    <div class="destination-card">
                        <div class="card-img-wrapper">
                            <span class="card-badge">
                                <i class="fas fa-star"></i> <?= $place['rating'] ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($place['name']) ?></h5>
                            <span class="card-category">
                                <i class="fas fa-tag"></i> <?= htmlspecialchars($place['category']) ?>
                            </span>
                            <p class="card-text">
                                <?= htmlspecialchars(substr($place['description'], 0, 150)) ?>...
                            </p>
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
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="categories-section">
        <div class="container">
            <h2 class="section-title">Kategori Wisata</h2>
            <p class="section-subtitle">Pilih kategori wisata sesuai minat Anda</p>
            
            <div class="row">
                <?php 
                $icons = [
                    'cagar alam' => 'fa-tree',
                    'lainnya' => 'fa-ellipsis-h',
                    'budaya' => 'fa-landmark',
                    'taman hiburan' => 'fa-horse-head',
                    'bahari' => 'fa-water',
                    'wisata air' => 'fa-swimmer',
                    'museum' => 'fa-university'
                ];
                
                foreach ($categories as $category): 
                    $icon = $icons[strtolower($category['name'])] ?? 'fa-map-marker-alt';
                ?>
                <div class="col-md-3 col-sm-6">
                    <a href="<?= base_url('category/' . urlencode($category['name'])) ?>" style="text-decoration: none; color: inherit;">
                        <div class="category-card">
                            <div class="category-icon">
                                <i class="fas <?= $icon ?>"></i>
                            </div>
                            <div class="category-name"><?= htmlspecialchars($category['name']) ?></div>
                            <div class="category-count">
                                <?= $category['total'] ?> Destinasi | Rating: <?= number_format($category['avg_rating'], 1) ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Popular Destinations Section -->
    <section class="featured-section">
        <div class="container">
            <h2 class="section-title">Wisata Populer</h2>
            <p class="section-subtitle">Destinasi favorit wisatawan dengan harga terjangkau</p>
            
            <div class="row">
                <?php foreach ($popular_places as $index => $place): 
                    if ($index >= 9) break;
                ?>
                <div class="col-md-4">
                    <div class="destination-card">
                        <div class="card-img-wrapper">
                            <span class="card-badge">
                                <i class="fas fa-fire"></i> Populer
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($place['name']) ?></h5>
                            <span class="card-category">
                                <i class="fas fa-tag"></i> <?= htmlspecialchars($place['category']) ?>
                            </span>
                            <p class="card-text">
                                <?= htmlspecialchars(substr($place['description'], 0, 120)) ?>...
                            </p>
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
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Siap Memulai Petualangan Anda?</h2>
            <p>Jelajahi lebih dari <?= $statistics['total_places'] ?> destinasi wisata menakjubkan di Yogyakarta</p>
            <div class="cta-actions">
                <a class="cta-btn" href="<?= base_url('#search') ?>">
                    <i class="fas fa-compass"></i> Jelajahi Sekarang
                </a>
                <a class="cta-btn cta-btn-outline" href="<?= base_url('evaluation') ?>">
                    <i class="fas fa-chart-line"></i> Lihat Evaluasi
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-map-marked-alt"></i> LocaTour</h5>
                    <p>Platform wisata terlengkap untuk menjelajahi keindahan Yogyakarta. Temukan destinasi impian Anda bersama kami.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h5>Link Cepat</h5>
                    <ul>
                        <li><a href="#home"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="#destinations"><i class="fas fa-chevron-right"></i> Destinasi</a></li>
                        <li><a href="#categories"><i class="fas fa-chevron-right"></i> Kategori</a></li>
                        <li><a href="#about"><i class="fas fa-chevron-right"></i> Tentang Kami</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Yogyakarta, Indonesia</li>
                        <li><i class="fas fa-phone"></i> +62 812-3456-7890</li>
                        <li><i class="fas fa-envelope"></i> info@locatour.com</li>
                        <li><i class="fas fa-clock"></i> Senin - Minggu: 08:00 - 20:00</li>
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
        const APP_USER_ID = <?= isset($user_id) && $user_id ? (int)$user_id : 'null' ?>;
        const RECOMMENDATION_MODE = '<?= esc($recommendation_mode ?? 'weather', 'js') ?>';
        const USER_PREF_PROFILE = <?= json_encode($user_preference_profile ?? []) ?>;

        // Smooth scroll behavior
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

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 5px 20px rgba(0,0,0,0.15)';
            } else {
                navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
            }
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

        document.querySelectorAll('.destination-card, .category-card, .stat-card').forEach(card => {
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

            window.location.href = '/search?' + params.toString();
        }

        // Enter key triggers modal search
        const modalKw = document.getElementById('modalSearchKeyword');
        if (modalKw) {
            modalKw.addEventListener('keypress', function(e) { if (e.key === 'Enter') performSearchModal(); });
        }

        // Disable category when keyword filled, and disable keyword when category selected
        (function keywordCategoryMutualDisable(){
            const kw = document.getElementById('modalSearchKeyword');
            const cat = document.getElementById('modalSearchCategory');
            const modal = document.getElementById('searchModal');
            if (!kw || !cat) return;
            function sync() {
                const hasKw = kw.value.trim().length > 0;
                const hasCat = !!cat.value;
                // Priority: if keyword typed, lock category
                cat.disabled = hasKw;
                // If category chosen, lock keyword
                kw.disabled = hasCat;
            }
            kw.addEventListener('input', sync);
            cat.addEventListener('change', sync);
            if (modal) {
                modal.addEventListener('shown.bs.modal', sync);
            }
            // Initial sync
            sync();
        })();

        // Optional: personalize recommendations (align with server-side profile)
        (function personalizeRecs(){
            if (RECOMMENDATION_MODE !== 'personalized' || !APP_USER_ID) return;
            const qs = new URLSearchParams({
                user_id: String(APP_USER_ID),
                top_n: '6',
                w_content: '0.2',
                w_collab: '0.5',
                w_weather: '0.3'
            });
            const category = USER_PREF_PROFILE?.preferred_category || '';
            const pref = USER_PREF_PROFILE?.indoor_outdoor_pref || '';
            if (category) qs.set('category', category);
            if (pref) qs.set('pref', pref);

            fetch(`/recs/hybrid?${qs.toString()}`).then(r=>r.json()).then(json=>{
                const list = (json && json.results) ? json.results : [];
                if (!Array.isArray(list) || list.length === 0) return;
                const grid = document.getElementById('recommendedGrid');
                if (!grid) return;
                grid.innerHTML = list.map(place => `
                    <div class="col-md-4">
                        <div class="destination-card">
                            <div class="card-img-wrapper">
                                <span class="card-badge">
                                    <i class="fas fa-user"></i> Skor: ${Number(place.hybrid_score||0).toFixed(2)}
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">${place.place_name || ''}</h5>
                                <span class="card-category">
                                    <i class="fas fa-tag"></i> ${place.category || ''}
                                </span>
                                <p class="card-text">Direkomendasikan untuk Anda.</p>
                                <div class="card-info">
                                    <div class="card-price">
                                        ${Number(place.price||0)===0 ? '<i class="fas fa-ticket-alt"></i> Gratis' : `<i class='fas fa-ticket-alt'></i> Rp ${Number(place.price||0).toLocaleString('id-ID')}`}
                                    </div>
                                    <div class="card-rating">
                                        <i class="fas fa-star"></i> ${Number(place.rating||0).toFixed(1)}/5
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }).catch(()=>{});
        })();

        // Preferences toggle: indoor/outdoor/mixed -> refetch hybrid
        (function preferenceToggle(){
            const group = document.querySelector('#recommended .btn-group');
            const badge = document.getElementById('prefBadge');
            const grid = document.getElementById('recommendedGrid');
            if (RECOMMENDATION_MODE !== 'weather') return;
            if (!group || !grid) return;
            group.querySelectorAll('button[data-pref]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const pref = btn.getAttribute('data-pref');
                    badge.textContent = pref.charAt(0).toUpperCase() + pref.slice(1);
                    const qs = new URLSearchParams({
                        top_n: '6',
                        w_content: '0.2',
                        w_collab: '0.2',
                        w_weather: '0.6',
                        pref
                    });
                    fetch(`/recs/hybrid?${qs.toString()}`)
                        .then(r => r.json())
                        .then(json => {
                            const list = (json && json.results) ? json.results : [];
                            grid.innerHTML = list.map(place => `
                                <div class="col-md-4">
                                    <div class="destination-card">
                                        <div class="card-img-wrapper">
                                            <span class="card-badge">
                                                <i class="fas fa-sun"></i> Skor: ${Number(place.hybrid_score||0).toFixed(2)}
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">${place.place_name || ''}</h5>
                                            <span class="card-category">
                                                <i class="fas fa-tag"></i> ${place.category || ''}
                                            </span>
                                            <p class="card-text">Direkomendasikan sesuai preferensi Anda.</p>
                                            <div class="card-info">
                                                <div class="card-price">
                                                    ${Number(place.price||0)===0 ? '<i class="fas fa-ticket-alt"></i> Gratis' : `<i class='fas fa-ticket-alt'></i> Rp ${Number(place.price||0).toLocaleString('id-ID')}`}
                                                </div>
                                                <div class="card-rating">
                                                    <i class="fas fa-star"></i> ${Number(place.rating||0).toFixed(1)}/5
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('');
                        })
                        .catch(()=>{});
                });
            });
        })();
    </script>
</body>
</html>
