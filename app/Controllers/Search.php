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

        return view('search_results', $data);
    }
}
