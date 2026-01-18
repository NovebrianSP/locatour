# LocaTour - Dokumentasi Database & Aplikasi

## 📊 Struktur Database

### Tabel-Tabel Utama

#### 1. **users**
```sql
- user_id (INT, PRIMARY KEY)
- location (VARCHAR)
- age (INT)
- location_clean, location_norm, location_tokens (TEXT)
- age_normalized (DECIMAL)
```

#### 2. **category_summary**
```sql
- category_id (INT, PRIMARY KEY)
- category_standardized (VARCHAR, UNIQUE)
- jumlah (INT) - total tempat wisata per kategori
- rata_rata_rating (DECIMAL)
- rata_rata_price (DECIMAL)
- total_vote (INT)
```
**Data:** 7 kategori (cagar alam, budaya, taman hiburan, bahari, wisata air, museum, lainnya)

#### 3. **tourism_places**
```sql
- place_id (VARCHAR, PRIMARY KEY)
- place_name (VARCHAR)
- description (TEXT)
- category (VARCHAR)
- city (VARCHAR)
- price (INT)
- rating (DECIMAL)
- time_minutes (INT)
- latitude, longitude (DECIMAL)
```
**Data:** 143 tempat wisata Yogyakarta

#### 4. **tourism_ratings**
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY → users)
- place_id (VARCHAR, FOREIGN KEY → tourism_places)
- place_ratings (INT, 1-5)
- place_ratings_normalized (DECIMAL)
```
**Data:** 10,000+ ratings dari users

#### 5. **wisata_merged**
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- source (VARCHAR)
- source_id (INT)
- name, description (TEXT)
- category, category_standardized (VARCHAR)
- category_id (INT, FOREIGN KEY → category_summary)
- price, unified_price (DECIMAL)
- rating (DECIMAL)
- time_minutes (INT)
- latitude, longitude (DECIMAL)
- vote_count (INT)
- indoor, outdoor (TINYINT)
- [+ banyak field normalisasi/preprocessing]
```
**Data:** 228 wisata merged dari berbagai sumber

---

## 🏗️ Arsitektur Aplikasi

### Models

#### **TourismModel** ([app/Models/TourismModel.php](app/Models/TourismModel.php))
- Table: `tourism_places`
- Methods:
  - `getFeaturedPlaces($limit)` - Rating ≥ 4.5
  - `getByCategory($category, $limit)`
  - `getPopularDestinations($limit)` - Rating ≥ 4.3, Price ≤ 50000
  - `searchPlaces($keyword)`
  - `getStatistics()` - Total, avg rating, total categories

#### **CategorySummaryModel** ([app/Models/CategorySummaryModel.php](app/Models/CategorySummaryModel.php))
- Table: `category_summary`
- Methods:
  - `getAllSummaries()` - Semua kategori ordered by jumlah

#### **WisataMergedModel** ([app/Models/WisataMergedModel.php](app/Models/WisataMergedModel.php))
- Table: `wisata_merged`
- Methods:
  - `getPopularPlaces($limit)` - Rating ≥ 4.3
  - `getByCategory($category, $limit)`

### Controllers

#### **Home** ([app/Controllers/Home.php](app/Controllers/Home.php))

**Method: `index()`** - Landing Page
- Featured Places (6 items, rating ≥ 4.5)
- Categories (7 categories)
- Popular Places (9 items dari wisata_merged)
- Statistics (total, avg rating, categories count)

**Method: `category($categoryName)`** - Category Page
- Filter places by category dari tourism_places
- Category statistics dari category_summary
- URL: `/category/{category_name}`

### Views

#### **landing_page.php** ([app/Views/landing_page.php](app/Views/landing_page.php))
- Hero section dengan background slider (3 images)
- Statistics cards (4 cards)
- Featured destinations (6 cards, rating ≥ 4.5)
- Categories (7 cards dengan icon)
- Popular destinations (9 cards)
- CTA section
- Footer

**Data yang dibutuhkan:**
```php
$data = [
    'featured_places' => [['id', 'name', 'description', 'category', 'city', 'price', 'rating', 'time']],
    'categories' => [['name', 'total', 'avg_rating', 'avg_price']],
    'popular_places' => [['id', 'name', 'description', 'category', 'city', 'price', 'rating', 'time']],
    'statistics' => ['total_places', 'avg_rating', 'total_categories', 'total_visitors']
];
```

#### **category_page.php** ([app/Views/category_page.php](app/Views/category_page.php))
- Category hero dengan breadcrumb
- Category statistics (3 cards: total, avg rating, avg price)
- Filters & sorting (rating, price low/high)
- Places grid (semua tempat dalam kategori)

**Data yang dibutuhkan:**
```php
$data = [
    'category_name' => 'budaya',
    'places' => [['id', 'name', 'description', 'category', 'city', 'price', 'rating', 'time']],
    'total_places' => 36,
    'avg_rating' => 4.5,
    'avg_price' => 19763.89
];
```

---

## 🛠️ Setup & Installation

### 1. Database Setup

**Opsi A: Via phpMyAdmin**
1. Import [database_locatour.sql](database_locatour.sql)
2. Jalankan seeder:
```bash
php spark db:seed DatabaseSeeder
```

**Opsi B: Manual Reset**
1. Jalankan [reset_database.sql](reset_database.sql) di phpMyAdmin
2. Jalankan seeder:
```bash
php spark db:seed DatabaseSeeder
```

### 2. File CSV yang Digunakan

Dataset directory: [`dataset/`](dataset/)

1. **user_yogya_preprocessed.csv** → `users` table (300 users)
2. **category_reference.csv** → `category_summary` table (7 categories)
3. **tourism_with_id_yogya_preprocessed.csv** → `tourism_places` table (143 places)
4. **tourism_rating_yogya_preprocessed.csv** → `tourism_ratings` table (10,000+ ratings)
5. **wisata_yogyakarta_merged_with_category_id.csv** → `wisata_merged` table (228 places)

### 3. Routes

[app/Config/Routes.php](app/Config/Routes.php):
```php
$routes->get('/', 'Home::index');
$routes->get('category/(:segment)', 'Home::category/$1');
```

**Available URLs:**
- `http://localhost/locatour/public/` - Landing page
- `http://localhost/locatour/public/category/budaya` - Category: Budaya
- `http://localhost/locatour/public/category/cagar%20alam` - Category: Cagar Alam
- `http://localhost/locatour/public/category/museum` - Category: Museum
- dll.

---

## 🎨 Design Features

### Landing Page
- ✅ Responsive Bootstrap 5 layout
- ✅ Hero slider dengan 3 background images
- ✅ Gradient color scheme (primary: #2c3e50, secondary: #e74c3c, accent: #f39c12)
- ✅ Smooth scroll animations
- ✅ Card hover effects dengan transform
- ✅ Font Awesome icons

### Category Page
- ✅ Category hero dengan breadcrumb navigation
- ✅ Statistics cards (total, rating, price)
- ✅ Filter & sorting (JavaScript)
- ✅ Responsive grid layout
- ✅ Card animations on scroll

---

## 📝 Field Mapping

### tourism_places (Database) → View Display

| Database Field | View Variable | Display |
|---------------|---------------|---------|
| place_id | id | Hidden/Link |
| place_name | name | Judul Card |
| description | description | Deskripsi (150 char) |
| category | category | Badge |
| city | city | Location badge |
| price | price | Harga/Gratis |
| rating | rating | ⭐ Rating/5 |
| time_minutes | time | Durasi kunjungan |

### category_summary → Category Cards

| Database Field | Display |
|---------------|---------|
| category_standardized | Nama Kategori |
| jumlah | {X} Destinasi |
| rata_rata_rating | Rating: {X} |
| rata_rata_price | Avg Price (internal) |

---

## 🔧 Troubleshooting

### Error: "Duplicate entry for PRIMARY KEY"
**Solusi:**
```bash
# Di phpMyAdmin atau MySQL, jalankan:
mysql -u root < d:\DATA\xampp\htdocs\locatour\reset_database.sql

# Kemudian jalankan seeder lagi:
php spark db:seed DatabaseSeeder
```

### Error: "Table 'tourism' doesn't exist"
**Solusi:** Nama tabel sudah diubah ke `tourism_places`. Pastikan semua model menggunakan nama yang benar.

### Category page menampilkan 0 hasil
**Solusi:** Pastikan seeder berhasil import data tourism_places. Cek dengan:
```sql
SELECT COUNT(*) FROM tourism_places;
SELECT DISTINCT category FROM tourism_places;
```

---

## 📊 Data Summary

Setelah seeder berhasil:

```sql
-- Verifikasi data
SELECT 'users' as tabel, COUNT(*) as jumlah FROM users
UNION ALL
SELECT 'category_summary', COUNT(*) FROM category_summary
UNION ALL
SELECT 'tourism_places', COUNT(*) FROM tourism_places
UNION ALL
SELECT 'tourism_ratings', COUNT(*) FROM tourism_ratings
UNION ALL
SELECT 'wisata_merged', COUNT(*) FROM wisata_merged;
```

**Expected Results:**
| Tabel | Jumlah |
|-------|--------|
| users | 300 |
| category_summary | 7 |
| tourism_places | 143 |
| tourism_ratings | 10,000+ |
| wisata_merged | 228 |

---

## ✅ Checklist Perbaikan

### Database
- [x] Tabel `tourism_places` (bukan `tourism`)
- [x] Primary key `category_summary.category_id`
- [x] Foreign keys (tourism_ratings, wisata_merged)
- [x] All seeders updated dengan field yang benar

### Models
- [x] TourismModel → table 'tourism_places'
- [x] CategorySummaryModel → primaryKey 'category_id'
- [x] WisataMergedModel → fields lengkap

### Controllers
- [x] Home::index() → data untuk landing page
- [x] Home::category() → filter by category
- [x] Field mapping konsisten (snake_case di DB)

### Views
- [x] landing_page.php → display featured, categories, popular
- [x] category_page.php → display filtered places
- [x] Responsive design dengan Bootstrap 5
- [x] Animations dan hover effects

### Routes
- [x] `/` → Home::index()
- [x] `/category/(:segment)` → Home::category()

---

## 🚀 Next Steps

1. **Run Reset Script:**
   ```bash
   # Di phpMyAdmin, jalankan reset_database.sql
   # Atau via terminal:
   mysql -u root < d:\DATA\xampp\htdocs\locatour\reset_database.sql
   ```

2. **Run Seeder:**
   ```bash
   cd d:\DATA\xampp\htdocs\locatour
   php spark db:seed DatabaseSeeder
   ```

3. **Test Application:**
   - Buka: `http://localhost/locatour/public/`
   - Klik category card → test category page
   - Verifikasi data muncul semua

4. **Verify Data:**
   ```sql
   -- Total places
   SELECT COUNT(*) FROM tourism_places;
   
   -- Categories
   SELECT * FROM category_summary;
   
   -- Sample places
   SELECT place_name, category, rating, price FROM tourism_places LIMIT 10;
   ```

---

**Last Updated:** 2025-12-14
**Database:** locatour
**Framework:** CodeIgniter 4.6.3
**PHP:** 8.2.12 (XAMPP)
