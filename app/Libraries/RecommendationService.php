<?php

namespace App\Libraries;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\I18n\Time;

/**
 * RecommendationService
 * PHP implementation of hybrid recommendations:
 * - Content-Based (TF-IDF + cosine)
 * - Collaborative (item-based CF)
 * - Weather-Based (Open-Meteo API)
 *
 * Data sources:
 * - tourism_places (place_id, place_name, description, category, city, price, rating, time_minutes, lat, long)
 * - tourism_ratings (user_id, place_id, place_ratings_normalized)
 */
class RecommendationService
{
    /** @var ConnectionInterface */
    protected $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /* ===================== Weather-Based ===================== */
    public function getCurrentWeather(float $latitude = -7.7956, float $longitude = 110.3695): array
    {
        $url = 'https://api.open-meteo.com/v1/forecast?latitude=' . $latitude . '&longitude=' . $longitude . '&current=temperature_2m,relative_humidity_2m,precipitation,rain,weather_code,wind_speed_10m&timezone=Asia/Jakarta';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || !$response) {
            return [
                'temperature' => 28,
                'humidity' => 70,
                'precipitation' => 0,
                'rain' => 0,
                'weather_code' => 1,
                'wind_speed' => 5,
                'time' => Time::now('Asia/Jakarta')->toDateTimeString(),
            ];
        }

        $json = json_decode($response, true);
        $current = $json['current'] ?? [];
        return [
            'temperature' => $current['temperature_2m'] ?? 28,
            'humidity' => $current['relative_humidity_2m'] ?? 70,
            'precipitation' => $current['precipitation'] ?? 0,
            'rain' => $current['rain'] ?? 0,
            'weather_code' => $current['weather_code'] ?? 1,
            'wind_speed' => $current['wind_speed_10m'] ?? 5,
            'time' => $current['time'] ?? Time::now('Asia/Jakarta')->toDateTimeString(),
        ];
    }

    public function interpretWeatherCode(int $code): string
    {
        if ($code === 0) return 'clear_sky';
        if (in_array($code, [1,2,3])) return 'partly_cloudy';
        if (in_array($code, [45,48])) return 'foggy';
        if (in_array($code, [51,53,55,56,57])) return 'drizzle';
        if (in_array($code, [61,63,65,66,67])) return 'rain';
        if (in_array($code, [71,73,75,77])) return 'snow';
        if (in_array($code, [80,81,82])) return 'rain_showers';
        if (in_array($code, [85,86])) return 'snow_showers';
        if (in_array($code, [95,96,99])) return 'thunderstorm';
        return 'unknown';
    }

    public function calculateWeatherScore(array $weather): float
    {
        $score = 1.0;
        $temp = (float)($weather['temperature'] ?? 28);
        if ($temp < 20 || $temp > 35) $score -= 0.2; elseif ($temp < 22 || $temp > 33) $score -= 0.1;
        $prec = (float)($weather['precipitation'] ?? 0);
        $rain = (float)($weather['rain'] ?? 0);
        if ($prec > 5 || $rain > 5) $score -= 0.5; elseif ($prec > 2 || $rain > 2) $score -= 0.3; elseif ($prec > 0 || $rain > 0) $score -= 0.1;
        $cond = $this->interpretWeatherCode((int)($weather['weather_code'] ?? 1));
        if (in_array($cond, ['rain','thunderstorm','rain_showers'])) $score -= 0.4; elseif (in_array($cond, ['drizzle','foggy'])) $score -= 0.2; elseif ($cond === 'clear_sky') $score += 0.1;
        $wind = (float)($weather['wind_speed'] ?? 0);
        if ($wind > 30) $score -= 0.3; elseif ($wind > 20) $score -= 0.1;
        $hum = (float)($weather['humidity'] ?? 70);
        if ($hum > 90) $score -= 0.1;
        return max(0.0, min(1.0, $score));
    }

    public function getWeatherPreference(float $score): string
    {
        if ($score >= 0.7) return 'outdoor';
        if ($score <= 0.3) return 'indoor';
        return 'mixed';
    }

    /** Map category -> indoor/outdoor classification */
    protected function categoryVenueType(string $category): string
    {
        $c = strtolower(trim($category));
        $outdoor = ['cagar alam','bahari','wisata air','taman hiburan'];
        $indoor = ['museum','budaya'];
        if (in_array($c, $outdoor)) return 'outdoor';
        if (in_array($c, $indoor)) return 'indoor';
        return 'mixed';
    }

    protected function weatherSuitabilityScore(string $category, string $preference, float $weatherScore): float
    {
        $venue = $this->categoryVenueType($category);
        if ($preference === 'outdoor') {
            if ($venue === 'outdoor') return 1.0 * $weatherScore;
            if ($venue === 'mixed') return 0.5 * $weatherScore;
            return 0.2 * $weatherScore;
        }
        if ($preference === 'indoor') {
            if ($venue === 'indoor') return 1.0 * (1.0 - $weatherScore);
            if ($venue === 'mixed') return 0.5 * (1.0 - $weatherScore);
            return 0.2 * (1.0 - $weatherScore);
        }
        return 0.5; // mixed
    }

    /* ===================== Search ===================== */
    /**
     * Search places by keyword, filters (price, category), and weather
     */
    public function searchPlaces(
        string $keyword = '',
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?string $category = null,
        ?string $preferenceOverride = null,
        int $topN = 10,
        bool $useWeather = true
    ): array {
        // Get all places
        $allPlaces = $this->db->table('tourism_places')->get()->getResultArray();

        // Filter by price range
        if ($minPrice !== null || $maxPrice !== null) {
            $allPlaces = array_filter($allPlaces, function($p) use ($minPrice, $maxPrice) {
                $price = (float)($p['price'] ?? 0);
                if ($minPrice !== null && $price < $minPrice) return false;
                if ($maxPrice !== null && $price > $maxPrice) return false;
                return true;
            });
        }

        // Filter by category
        if ($category) {
            $allPlaces = array_filter($allPlaces, function($p) use ($category) {
                return strtolower($p['category'] ?? '') === strtolower($category);
            });
        }

        if (empty($allPlaces)) {
            return [
                'meta' => [
                    'keyword' => $keyword,
                    'filters' => ['minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'category' => $category],
                    'weather' => [],
                    'preference' => 'mixed',
                    'results_count' => 0,
                ],
                'results' => []
            ];
        }

        // Score by keyword match using TF-IDF
        $docs = $this->buildDocuments($allPlaces);
        $tfidf = $this->computeTfIdf($docs);
        $keywordTokens = $this->normalizeText($keyword);
        $keywordVec = [];
        foreach ($keywordTokens as $t) {
            $keywordVec[$t] = 1.0 / max(1, count($keywordTokens));
        }

        $contentScores = [];
        foreach ($tfidf as $pid => $vec) {
            $contentScores[$pid] = $this->cosine($keywordVec, $vec);
        }

        // Weather & preference
        $weatherScore = 0.5; $preference = 'mixed'; $weatherInfo = [];
        if ($useWeather) {
            $weatherInfo = $this->getCurrentWeather();
            $weatherScore = $this->calculateWeatherScore($weatherInfo);
            $preference = $this->getWeatherPreference($weatherScore);
        }
        if ($preferenceOverride) {
            $pref = strtolower($preferenceOverride);
            if (in_array($pref, ['indoor','outdoor','mixed'])) {
                $preference = $pref;
            }
        }

        // Build result list
        $indexed = [];
        foreach ($allPlaces as $p) { $indexed[$p['place_id']] = $p; }
        $results = [];
        foreach ($indexed as $pid => $p) {
            $contentScore = $contentScores[$pid] ?? 0.0;
            $weatherScore_item = $this->weatherSuitabilityScore($p['category'] ?? '', $preference, $weatherScore);
            $ratingScore = ($p['rating'] ?? 0) / 5.0;
            $results[] = [
                'place_id' => $p['place_id'],
                'place_name' => $p['place_name'],
                'category' => $p['category'],
                'city' => $p['city'],
                'price' => $p['price'],
                'rating' => $p['rating'],
                'description' => $p['description'],
                'content_score' => round($contentScore, 4),
                'weather_score' => round($weatherScore_item, 4),
                'rating_score' => round($ratingScore, 4),
                'hybrid_score' => round((0.4 * $contentScore + 0.3 * $ratingScore + 0.3 * $weatherScore_item), 4),
            ];
        }

        // Sort by hybrid score
        usort($results, fn($a, $b) => $b['hybrid_score'] <=> $a['hybrid_score']);
        $results = array_slice($results, 0, $topN);

        return [
            'meta' => [
                'keyword' => $keyword,
                'filters' => ['minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'category' => $category],
                'weather' => $weatherInfo,
                'preference' => $preference,
                'results_count' => count($results),
            ],
            'results' => $results
        ];
    }

    /* ===================== Utilities ===================== */
    protected function normalizeText(string $text): array
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z\s]/', ' ', $text);
        $tokens = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        return $tokens;
    }

    /* ===================== Content-Based ===================== */
    protected function buildDocuments(array $places): array
    {
        $docs = [];
        foreach ($places as $p) {
            $combined = ($p['place_name'] ?? '') . ' ' . ($p['description'] ?? '') . ' ' . ($p['category'] ?? '');
            $docs[$p['place_id']] = $this->normalizeText($combined);
        }
        return $docs;
    }

    protected function computeTfIdf(array $docs): array
    {
        // Document frequency
        $df = [];
        foreach ($docs as $docTokens) {
            $unique = array_unique($docTokens);
            foreach ($unique as $t) {
                $df[$t] = ($df[$t] ?? 0) + 1;
            }
        }
        $N = count($docs);
        $idf = [];
        foreach ($df as $t => $freq) {
            $idf[$t] = log(($N + 1) / ($freq + 1)) + 1.0; // smoothed idf
        }
        // TF-IDF vectors
        $vectors = [];
        foreach ($docs as $pid => $tokens) {
            $tf = [];
            foreach ($tokens as $t) { $tf[$t] = ($tf[$t] ?? 0) + 1; }
            $len = count($tokens) ?: 1;
            $vec = [];
            foreach ($tf as $t => $f) {
                $vec[$t] = ($f / $len) * ($idf[$t] ?? 0);
            }
            $vectors[$pid] = $vec;
        }
        return $vectors;
    }

    protected function cosine(array $a, array $b): float
    {
        $dot = 0.0; $na = 0.0; $nb = 0.0;
        $keys = array_unique(array_merge(array_keys($a), array_keys($b)));
        foreach ($keys as $k) {
            $va = $a[$k] ?? 0.0; $vb = $b[$k] ?? 0.0;
            $dot += $va * $vb; $na += $va * $va; $nb += $vb * $vb;
        }
        if ($na == 0.0 || $nb == 0.0) return 0.0;
        return $dot / (sqrt($na) * sqrt($nb));
    }

    public function getSimilarPlaces(string $placeId, int $topN = 10): array
    {
        $places = $this->db->table('tourism_places')->get()->getResultArray();
        $docs = $this->buildDocuments($places);
        $tfidf = $this->computeTfIdf($docs);
        if (!isset($tfidf[$placeId])) return [];
        $target = $tfidf[$placeId];
        $scores = [];
        foreach ($tfidf as $pid => $vec) {
            if ($pid === $placeId) continue;
            $scores[$pid] = $this->cosine($target, $vec);
        }
        arsort($scores);
        $topIds = array_slice(array_keys($scores), 0, $topN);
        $indexed = [];
        foreach ($places as $p) { $indexed[$p['place_id']] = $p; }
        $results = [];
        foreach ($topIds as $pid) {
            $p = $indexed[$pid] ?? null; if (!$p) continue;
            $p['content_score'] = round($scores[$pid], 4);
            $results[] = $p;
        }
        return $results;
    }

    public function getRecommendationsByFeatures(?string $category = null, float $minRating = 0.0, ?int $maxPrice = null, int $topN = 10): array
    {
        $builder = $this->db->table('tourism_places');
        if ($category) $builder->where('category', $category);
        if ($minRating > 0) $builder->where('rating >=', $minRating);
        if ($maxPrice !== null) $builder->where('price <=', $maxPrice);
        $places = $builder->orderBy('rating', 'DESC')->limit($topN)->get()->getResultArray();
        foreach ($places as &$p) { $p['content_score'] = ($p['rating'] ?? 0) / 5.0; }
        return $places;
    }

    /* ===================== Collaborative (Item-based) ===================== */
    protected function buildUserItemMatrix(): array
    {
        $rows = $this->db->table('tourism_ratings')->select('user_id, place_id, place_ratings_normalized')->get()->getResultArray();
        $users = []; $items = [];
        foreach ($rows as $r) { $users[$r['user_id']] = true; $items[$r['place_id']] = true; }
        $users = array_keys($users); $items = array_keys($items);
        $matrix = [];
        foreach ($users as $u) { $matrix[$u] = array_fill_keys($items, 0.0); }
        foreach ($rows as $r) { $matrix[$r['user_id']][$r['place_id']] = (float)$r['place_ratings_normalized']; }
        return ['matrix' => $matrix, 'users' => $users, 'items' => $items];
    }

    protected function itemSimilarity(array $matrix, array $items): array
    {
        // Compute cosine similarity between items (columns)
        $sim = [];
        foreach ($items as $i) { $sim[$i] = []; }
        foreach ($items as $i) {
            foreach ($items as $j) {
                if ($i === $j) { $sim[$i][$j] = 1.0; continue; }
                $vi = []; $vj = [];
                foreach ($matrix as $u => $ratings) { $vi[] = $ratings[$i] ?? 0.0; $vj[] = $ratings[$j] ?? 0.0; }
                // cosine
                $dot = 0.0; $ni = 0.0; $nj = 0.0;
                $cnt = count($vi);
                for ($k = 0; $k < $cnt; $k++) { $dot += $vi[$k] * $vj[$k]; $ni += $vi[$k] * $vi[$k]; $nj += $vj[$k] * $vj[$k]; }
                $sim[$i][$j] = ($ni == 0.0 || $nj == 0.0) ? 0.0 : ($dot / (sqrt($ni) * sqrt($nj)));
            }
        }
        return $sim;
    }

    public function getUserRecommendations(int $userId, int $topN = 10): array
    {
        $built = $this->buildUserItemMatrix();
        $matrix = $built['matrix']; $items = $built['items'];
        if (!isset($matrix[$userId])) return [];
        $sim = $this->itemSimilarity($matrix, $items);
        $userRatings = $matrix[$userId];
        // Predict for unrated items
        $pred = [];
        foreach ($items as $item) {
            if (($userRatings[$item] ?? 0.0) > 0.0) continue; // already rated
            // top-k similar rated items
            $sims = [];
            foreach ($items as $j) {
                if (($userRatings[$j] ?? 0.0) > 0.0) { $sims[] = [$j, $sim[$item][$j]]; }
            }
            usort($sims, function($a, $b) { return $b[1] <=> $a[1]; });
            $sims = array_slice($sims, 0, 10);
            $num = 0.0; $den = 0.0;
            foreach ($sims as [$j, $s]) { $num += $userRatings[$j] * $s; $den += abs($s); }
            if ($den > 0.0) { $pred[$item] = $num / $den; }
        }
        arsort($pred);
        $top = array_slice($pred, 0, $topN, true);
        if (empty($top)) return [];
        // Fetch item details
        $places = $this->db->table('tourism_places')->whereIn('place_id', array_keys($top))->get()->getResultArray();
        $idx = [];
        foreach ($places as $p) { $idx[$p['place_id']] = $p; }
        $results = [];
        foreach ($top as $pid => $score) {
            $p = $idx[$pid] ?? null; if (!$p) continue;
            $p['collaborative_score'] = round($score, 4);
            $results[] = $p;
        }
        return $results;
    }

    /* ===================== Hybrid ===================== */
    public function getHybridRecommendations(?int $userId = null, ?string $placeId = null, array $weights = null, int $topN = 10, ?string $categoryFilter = null, bool $useWeather = true, ?string $preferenceOverride = null): array
    {
        if ($weights === null) $weights = ['content' => 0.33, 'collaborative' => 0.34, 'weather' => 0.33];

        $content = [];
        if ($placeId) {
            $content = $this->getSimilarPlaces($placeId, $topN * 2);
        } else {
            $content = $this->getRecommendationsByFeatures($categoryFilter, 0, null, $topN * 2);
        }

        $collab = [];
        if ($userId !== null) {
            $collab = $this->getUserRecommendations($userId, $topN * 2);
        }

        // Build combined list (by place_id)
        $combined = $this->db->table('tourism_places')->get()->getResultArray();
        $byId = [];
        foreach ($combined as $p) {
            $byId[$p['place_id']] = $p + ['content_score' => 0.0, 'collaborative_score' => 0.0, 'weather_score' => 0.0];
        }
        foreach ($content as $p) {
            $pid = $p['place_id'];
            $byId[$pid]['content_score'] = $p['content_score'] ?? (($p['rating'] ?? 0) / 5.0);
        }
        foreach ($collab as $p) {
            $pid = $p['place_id'];
            $byId[$pid]['collaborative_score'] = $p['collaborative_score'] ?? 0.0;
        }

        // Weather score
        $weatherScore = 0.5; $preference = 'mixed'; $weatherInfo = [];
        if ($useWeather) {
            $weatherInfo = $this->getCurrentWeather();
            $weatherScore = $this->calculateWeatherScore($weatherInfo);
            $preference = $this->getWeatherPreference($weatherScore);
        }
        // Allow overriding preference via UI (indoor/outdoor/mixed)
        if ($preferenceOverride) {
            $pref = strtolower($preferenceOverride);
            if (in_array($pref, ['indoor','outdoor','mixed'])) {
                $preference = $pref;
            }
        }
        foreach ($byId as &$p) {
            $p['weather_score'] = $this->weatherSuitabilityScore($p['category'] ?? '', $preference, $weatherScore);
        }

        // Normalize scores
        $maxContent = max(array_map(fn($x) => $x['content_score'], $byId)) ?: 1.0;
        $maxCollab = max(array_map(fn($x) => $x['collaborative_score'], $byId)) ?: 1.0;
        $maxWeather = max(array_map(fn($x) => $x['weather_score'], $byId)) ?: 1.0;
        foreach ($byId as &$p) {
            $p['content_score'] = $p['content_score'] / $maxContent;
            $p['collaborative_score'] = $p['collaborative_score'] / $maxCollab;
            $p['weather_score'] = $p['weather_score'] / $maxWeather;
            $p['hybrid_score'] = (
                $weights['content'] * $p['content_score'] +
                $weights['collaborative'] * $p['collaborative_score'] +
                $weights['weather'] * $p['weather_score']
            );
        }

        // Filter and sort
        $list = array_values($byId);
        if ($categoryFilter) {
            $list = array_filter($list, fn($p) => strtolower($p['category'] ?? '') === strtolower($categoryFilter));
        }
        $list = array_filter($list, fn($p) => ($p['content_score'] > 0 || $p['collaborative_score'] > 0));
        usort($list, fn($a, $b) => $b['hybrid_score'] <=> $a['hybrid_score']);
        $list = array_slice($list, 0, $topN);

        // Include weather meta
        return [
            'meta' => [
                'weather' => $weatherInfo,
                'preference' => $preference,
                'weights' => $weights,
            ],
            'results' => array_map(function($p) {
                return [
                    'place_id' => $p['place_id'],
                    'place_name' => $p['place_name'],
                    'category' => $p['category'],
                    'city' => $p['city'],
                    'price' => $p['price'],
                    'rating' => $p['rating'],
                    'content_score' => round($p['content_score'], 4),
                    'collaborative_score' => round($p['collaborative_score'], 4),
                    'weather_score' => round($p['weather_score'], 4),
                    'hybrid_score' => round($p['hybrid_score'], 4),
                ];
            }, $list)
        ];
    }

    /* ===================== Evaluation ===================== */
    public function evaluateCollaborative(int $k = 10): array
    {
        $k = max(1, min($k, 50));

        $built = $this->buildUserItemMatrix();
        $matrix = $built['matrix'];
        $items = $built['items'];

        if (empty($matrix) || empty($items)) {
            return [
                'mae' => null,
                'rmse' => null,
                'tested_count' => 0,
                'skipped_count' => 0,
                'coverage' => 0.0,
                'k_neighbors' => $k,
            ];
        }

        $sim = $this->itemSimilarity($matrix, $items);

        $absSum = 0.0;
        $sqSum = 0.0;
        $tested = 0;
        $skipped = 0;

        foreach ($matrix as $ratings) {
            foreach ($ratings as $itemId => $actual) {
                if ($actual <= 0.0) {
                    continue;
                }

                $neighbors = [];
                foreach ($ratings as $otherItem => $otherRating) {
                    if ($otherItem === $itemId || $otherRating <= 0.0) {
                        continue;
                    }
                    $neighbors[] = [
                        'sim' => $sim[$itemId][$otherItem] ?? 0.0,
                        'rating' => $otherRating,
                    ];
                }

                if (empty($neighbors)) {
                    $skipped++;
                    continue;
                }

                usort($neighbors, static fn($a, $b) => $b['sim'] <=> $a['sim']);
                $neighbors = array_slice($neighbors, 0, $k);

                $num = 0.0;
                $den = 0.0;
                foreach ($neighbors as $n) {
                    $num += $n['rating'] * $n['sim'];
                    $den += abs($n['sim']);
                }

                if ($den === 0.0) {
                    $skipped++;
                    continue;
                }

                $pred = $num / $den;
                $err = $pred - $actual;
                $absSum += abs($err);
                $sqSum += $err * $err;
                $tested++;
            }
        }

        $mae = $tested > 0 ? $absSum / $tested : null;
        $rmse = $tested > 0 ? sqrt($sqSum / $tested) : null;
        $coverage = ($tested + $skipped) > 0 ? $tested / ($tested + $skipped) : 0.0;

        return [
            'mae' => $mae !== null ? round($mae, 4) : null,
            'rmse' => $rmse !== null ? round($rmse, 4) : null,
            'tested_count' => $tested,
            'skipped_count' => $skipped,
            'coverage' => round($coverage, 4),
            'k_neighbors' => $k,
        ];
    }
}
