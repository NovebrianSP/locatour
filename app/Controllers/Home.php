<?php

namespace App\Controllers;

use App\Models\TourismModel;
use App\Models\CategorySummaryModel;
use App\Models\WisataMergedModel;
use App\Libraries\RecommendationService;
use Config\Database;
use function view;

class Home extends BaseController
{
    protected $tourismModel;
    protected $categoryModel;
    protected $wisataModel;
    protected $recommender;

    public function __construct()
    {
        $this->tourismModel = new TourismModel();
        $this->categoryModel = new CategorySummaryModel();
        $this->wisataModel = new WisataMergedModel();
        $this->recommender = new RecommendationService(Database::connect());
    }

    public function index(): string
    {
        // Get data from database for landing page
        // Weather-aware recommendations (general)
        $recs = $this->recommender->getHybridRecommendations(
            null,
            null,
            ['content' => 0.2, 'collaborative' => 0.2, 'weather' => 0.6],
            6,
            null,
            true
        );

        $data = [
            'featured_places' => $this->getFeaturedPlaces(),
            'categories' => $this->getCategories(),
            'popular_places' => $this->getPopularPlaces(),
            'statistics' => $this->getStatistics(),
            'recommended_places' => $this->mapRecommendationsToCards($recs['results'] ?? []),
            'recommendation_meta' => $recs['meta'] ?? []
        ];

        return view('landing_page', $data);
    }

    /**
     * Map RecommendationService results to view card structure
     */
    private function mapRecommendationsToCards(array $results): array
    {
        if (empty($results)) return [];
        // Fetch descriptions for these place_ids
        $ids = array_map(fn($r) => $r['place_id'], $results);
        $details = [];
        if (!empty($ids)) {
            $rows = $this->tourismModel->whereIn('place_id', $ids)->findAll();
            foreach ($rows as $row) {
                $details[$row['place_id']] = $row;
            }
        }

        $cards = [];
        foreach ($results as $r) {
            $desc = $details[$r['place_id']]['description'] ?? '';
            $cards[] = [
                'id' => $r['place_id'],
                'name' => $r['place_name'] ?? '',
                'description' => $desc,
                'category' => $r['category'] ?? '',
                'city' => $r['city'] ?? 'Yogyakarta',
                'price' => $r['price'] ?? 0,
                'rating' => $r['rating'] ?? 0,
                // expose scores for badges if needed
                'content_score' => $r['content_score'] ?? 0,
                'collab_score' => $r['collaborative_score'] ?? 0,
                'weather_score' => $r['weather_score'] ?? 0,
                'hybrid_score' => $r['hybrid_score'] ?? 0,
            ];
        }
        return $cards;
    }

    /**
     * Get featured tourism places from database
     */
    private function getFeaturedPlaces()
    {
        $places = $this->tourismModel->getFeaturedPlaces(6);
        
        // Format data untuk view
        $result = [];
        foreach ($places as $place) {
            $result[] = [
                'id' => $place['place_id'] ?? '',
                'name' => $place['place_name'] ?? '',
                'description' => $place['description'] ?? '',
                'category' => $place['category'] ?? '',
                'city' => $place['city'] ?? '',
                'price' => $place['price'] ?? 0,
                'rating' => $place['rating'] ?? 0,
                'time' => $place['time_minutes'] ?? 0,
                'lat' => $place['latitude'] ?? 0,
                'long' => $place['longitude'] ?? 0
            ];
        }
        
        return $result;
    }

    /**
     * Get categories from database
     */
    private function getCategories()
    {
        $categories = $this->categoryModel->getAllSummaries();
        
        // Format data untuk view
        $result = [];
        foreach ($categories as $cat) {
            $result[] = [
                'name' => $cat['category_standardized'] ?? '',
                'total' => $cat['jumlah'] ?? 0,
                'avg_rating' => $cat['rata_rata_rating'] ?? 0,
                'avg_price' => $cat['rata_rata_price'] ?? 0
            ];
        }
        
        return $result;
    }

    /**
     * Get popular places from database
     */
    private function getPopularPlaces()
    {
        $places = $this->wisataModel->getPopularPlaces(9);
        
        // Format data untuk view
        $result = [];
        foreach ($places as $place) {
            $result[] = [
                'id' => $place['id'] ?? '',
                'name' => $place['name'] ?? '',
                'description' => $place['description_clean'] ?? $place['description'] ?? '',
                'category' => $place['category_standardized'] ?? '',
                'city' => 'Yogyakarta', // Default city
                'price' => $place['unified_price'] ?? 0,
                'rating' => $place['rating'] ?? 0,
                'time' => $place['time_minutes'] ?? 0,
                'latitude' => $place['latitude'] ?? 0,
                'longitude' => $place['longitude'] ?? 0
            ];
        }
        
        return $result;
    }

    /**
     * Get statistics from database
     */
    private function getStatistics()
    {
        $stats = $this->tourismModel->getStatistics();
        $categoryCount = $this->categoryModel->countAll();

        return [
            'total_places' => $stats['total_places'] ?? 0,
            'avg_rating' => $stats['avg_rating'] ?? 0,
            'total_categories' => $categoryCount,
            'total_visitors' => '1M+' // Static value
        ];
    }

    /**
     * Display category page with places filtered by category
     */
    public function category($categoryName = null)
    {
        if (!$categoryName) {
            return redirect()->to('/');
        }

        // Decode URL-encoded category name
        $categoryName = urldecode($categoryName);

        // Get category stats and id
        $categoryStats = $this->categoryModel
            ->where('category_standardized', $categoryName)
            ->first();

        if (!$categoryStats) {
            return redirect()->to('/');
        }

        $categoryId = $categoryStats['category_id'] ?? null;

        // Get places by category_id from wisata_merged
        $places = $this->wisataModel->getByCategoryId($categoryId);

        // Format places data from merged table
        $formattedPlaces = [];
        foreach ($places as $place) {
            $formattedPlaces[] = [
                'id' => $place['id'] ?? '',
                'name' => $place['name'] ?? '',
                'description' => $place['description_clean'] ?? $place['description'] ?? '',
                'category' => $place['category_standardized'] ?? '',
                'city' => 'Yogyakarta',
                'price' => $place['unified_price'] ?? $place['price'] ?? 0,
                'rating' => $place['rating'] ?? 0,
                'time' => $place['time_minutes'] ?? 0,
                'lat' => $place['latitude'] ?? 0,
                'long' => $place['longitude'] ?? 0
            ];
        }

        // Category-aware recommendations (weather-first)
        $recs = $this->recommender->getHybridRecommendations(
            null,
            null,
            ['content' => 0.2, 'collaborative' => 0.2, 'weather' => 0.6],
            6,
            $categoryName,
            true
        );

        $data = [
            'category_name' => $categoryName,
            'places' => $formattedPlaces,
            'total_places' => count($formattedPlaces),
            'avg_rating' => $categoryStats['rata_rata_rating'] ?? 0,
            'avg_price' => $categoryStats['rata_rata_price'] ?? 0,
            'recommended_places' => $this->mapRecommendationsToCards($recs['results'] ?? []),
            'recommendation_meta' => $recs['meta'] ?? []
        ];

        return view('category_page', $data);
    }
}
