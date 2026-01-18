<?php

namespace App\Controllers;

use App\Libraries\RecommendationService;
use Config\Database;

class Search extends BaseController
{
    protected $recommender;

    public function __construct()
    {
        $this->recommender = new RecommendationService(Database::connect());
    }

    /**
     * Display search results page
     */
    public function results()
    {
        $keyword = $this->request->getGet('q') ?? '';
        $minPrice = $this->request->getGet('min_price');
        $maxPrice = $this->request->getGet('max_price');
        $category = $this->request->getGet('category');
        $pref = $this->request->getGet('pref');
        $userId = session()->get('user_id');

        $minPrice = $minPrice ? (float)$minPrice : null;
        $maxPrice = $maxPrice ? (float)$maxPrice : null;

        // Call search API
        $searchResults = $this->recommender->searchPlaces(
            $keyword,
            $minPrice,
            $maxPrice,
            $category ?: null,
            $pref ?: null,
            20,
            true
        );

        $data = [
            'keyword' => $keyword,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'category' => $category,
            'preference' => $searchResults['meta']['preference'] ?? 'mixed',
            'results' => $searchResults['results'] ?? [],
            'weather' => $searchResults['meta']['weather'] ?? []
        ];

        // Persist latest preference to session for personalized landing recommendations
        session()->set('user_pref', $data['preference']);

        // Persist search signals to user_preferences table when user is logged in
        if ($userId) {
            $this->storeSearchPreference((int)$userId, $keyword, $category, $pref, $minPrice, $maxPrice);
        }

        return view('search_results', $data);
    }

    /**
     * Upsert user search preference signals into user_preferences table
     */
    private function storeSearchPreference(int $userId, string $keyword, ?string $category, ?string $pref, ?float $minPrice, ?float $maxPrice): void
    {
        try {
            $db = Database::connect();
            $builder = $db->table('user_preferences');

            $existing = $builder->where('user_id', $userId)->get()->getRowArray();

            $now = date('Y-m-d H:i:s');
            $searchHistory = [];
            $preferredCategories = [];

            if ($existing) {
                $searchHistory = json_decode($existing['search_history'] ?? '[]', true) ?: [];
                $preferredCategories = json_decode($existing['preferred_categories'] ?? '[]', true) ?: [];
            }

            // Add new signals
            if (trim($keyword) !== '') {
                array_unshift($searchHistory, trim($keyword));
            } elseif ($category) {
                array_unshift($searchHistory, $category);
            }
            if ($category) {
                array_unshift($preferredCategories, $category);
            }

            // Keep unique and limit length
            $searchHistory = array_slice(array_values(array_unique($searchHistory)), 0, 10);
            $preferredCategories = array_slice(array_values(array_unique($preferredCategories)), 0, 5);

            $payload = [
                'preferred_categories' => json_encode($preferredCategories),
                'indoor_outdoor_pref' => $pref ?: ($existing['indoor_outdoor_pref'] ?? 'mixed'),
                'min_price' => $minPrice ?? ($existing['min_price'] ?? 0),
                'max_price' => $maxPrice ?? ($existing['max_price'] ?? 0),
                'min_rating' => $existing['min_rating'] ?? 0,
                'search_history' => json_encode($searchHistory),
                'updated_at' => $now,
            ];

            if ($existing) {
                $builder->where('user_id', $userId)->update($payload);
            } else {
                $payload['user_id'] = $userId;
                $payload['created_at'] = $now;
                $builder->insert($payload);
            }
        } catch (\Throwable $e) {
            // Silent fail to avoid breaking search flow
            log_message('error', 'Failed to store user preference: {message}', ['message' => $e->getMessage()]);
        }
    }
}
