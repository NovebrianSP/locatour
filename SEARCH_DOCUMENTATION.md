# LocaTour Search Feature

Fitur pencarian advanced yang mengintegrasikan rekomendasi berbasis TF-IDF, filter harga/kategori, dan weather-aware scoring.

## Komponen

### 1. Backend - RecommendationService (`searchPlaces()`)
- **File:** `app/Libraries/RecommendationService.php`
- **Method:** `searchPlaces($keyword, $minPrice, $maxPrice, $category, $preferenceOverride, $topN, $useWeather)`
- **Algoritma:**
  - Keyword matching: TF-IDF cosine similarity
  - Filter: harga range + kategori
  - Weather scoring: suitabilitas cuaca outdoor/indoor
  - Hybrid scoring: 40% keyword + 30% rating + 30% weather

### 2. API Endpoint - `/recs/search`
- **Controller:** `app/Controllers/Recommender.php`
- **Query Params:**
  - `q` - keyword
  - `min_price` - harga minimum
  - `max_price` - harga maksimum
  - `category` - nama kategori
  - `pref` - override preferensi (indoor/outdoor/mixed)
  - `top_n` - jumlah hasil (default: 20)
- **Response:** JSON dengan results + meta (weather, preference, filters)

### 3. Search Results Page - `/search`
- **Controller:** `app/Controllers/Search.php`
- **View:** `app/Views/search_results.php`
- **Display:**
  - Hero section dengan filter metadata
  - Card grid dengan scoring breakdown (keyword%, rating%, weather%)
  - Hybrid score badge di setiap hasil
  - Price + rating info

### 4. Search Form UI
- **File:** `app/Views/search_form.php` (injectable)
- **Inputs:**
  - Keyword text input
  - Category dropdown (7 kategori)
  - Min/Max price inputs
  - Weather preference selector
  - Submit button (redirect ke `/search`)

## Cara Penggunaan

### Web UI
1. Buka landing page: `http://localhost/locatour/`
2. Scroll ke section "Cari Destinasi Wisata"
3. Isi form:
   - Keyword (opsional): "candi", "pantai", "museum"
   - Kategori (opsional): pilih dari dropdown
   - Harga: rentang min-max (opsional)
   - Preferensi cuaca (opsional)
4. Klik "Cari Destinasi"
5. Lihat hasil di page `/search?q=...&category=...&min_price=...&max_price=...&pref=...`

### API Direct
```bash
# Basic search
curl "http://localhost/locatour/recs/search?q=candi"

# With filters
curl "http://localhost/locatour/recs/search?q=candi&category=budaya&min_price=0&max_price=100000&pref=outdoor&top_n=10"

# Indoor preference
curl "http://localhost/locatour/recs/search?q=museum&pref=indoor&top_n=5"
```

## Scoring Breakdown
Setiap hasil menampilkan:
- **Keyword Score:** Relevansi keyword match (0-100%)
- **Rating Score:** Rating place (0-100% = 0-5 bintang)
- **Weather Score:** Suitabilitas cuaca (0-100%)
- **Hybrid Score:** Kombinasi ketiga (0-1.0)

## Contoh Hasil
```json
{
  "meta": {
    "keyword": "candi",
    "filters": {
      "minPrice": 0,
      "maxPrice": 100000,
      "category": "budaya"
    },
    "weather": {
      "temperature": 28,
      "humidity": 70,
      "preference": "mixed"
    },
    "results_count": 5
  },
  "results": [
    {
      "place_id": "101",
      "place_name": "Borobudur Temple",
      "category": "budaya",
      "price": 50000,
      "rating": 4.8,
      "content_score": 0.85,
      "rating_score": 0.96,
      "weather_score": 0.65,
      "hybrid_score": 0.82
    }
  ]
}
```

## Integration Points

### Landing Page
- Sertakan `search_form.php` antara stats section dan featured section
- atau copy HTML dari file tersebut

### Routes
- `/search` → `Search::results` (hasil page)
- `/recs/search` → `Recommender::search` (API)

### Models
- `tourism_places`: untuk data tempat wisata
- `tourism_ratings`: untuk collaborative data (opsional)

## Tech Stack
- TF-IDF untuk keyword matching
- Open-Meteo API untuk weather data
- Bootstrap 5 untuk UI
- CodeIgniter 4 untuk framework
