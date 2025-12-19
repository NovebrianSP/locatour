<?php

namespace App\Controllers;

use App\Libraries\RecommendationService;
use Config\Database;

class Recommender extends BaseController
{
    protected $service;

    public function __construct()
    {
        $db = Database::connect();
        $this->service = new RecommendationService($db);
    }

    public function index()
    {
        return service('response')->setJSON([
            'status' => 'ok',
            'message' => 'LocaTour Recommendation API',
            'endpoints' => [
                '/recs/hybrid?user_id=1&place_id=101&top_n=5',
                '/recs/personalized/1?top_n=5',
                '/recs/weather?top_n=5',
            ]
        ]);
    }

    public function hybrid()
    {
        $userId = $this->request->getGet('user_id');
        $placeId = $this->request->getGet('place_id');
        $category = $this->request->getGet('category');
        $pref = $this->request->getGet('pref'); // indoor | outdoor | mixed
        $topN = (int)($this->request->getGet('top_n') ?? 10);
        $weights = [
            'content' => (float)($this->request->getGet('w_content') ?? 0.33),
            'collaborative' => (float)($this->request->getGet('w_collab') ?? 0.34),
            'weather' => (float)($this->request->getGet('w_weather') ?? 0.33),
        ];

        $data = $this->service->getHybridRecommendations(
            $userId ? (int)$userId : null,
            $placeId ?: null,
            $weights,
            $topN,
            $category ?: null,
            true,
            $pref ?: null
        );

        return service('response')->setJSON($data);
    }

    public function personalized($userId)
    {
        $topN = (int)($this->request->getGet('top_n') ?? 10);
        $data = $this->service->getHybridRecommendations(
            (int)$userId,
            null,
            ['content' => 0.2, 'collaborative' => 0.5, 'weather' => 0.3],
            $topN,
            null,
            true
        );
        return service('response')->setJSON($data);
    }

    public function weather()
    {
        $topN = (int)($this->request->getGet('top_n') ?? 10);
        // Weather-aware by prioritizing weather weight
        $data = $this->service->getHybridRecommendations(
            null,
            null,
            ['content' => 0.2, 'collaborative' => 0.2, 'weather' => 0.6],
            $topN,
            null,
            true
        );
        return service('response')->setJSON($data);
    }

    public function search()
    {
        $keyword = $this->request->getGet('q') ?? '';
        $minPrice = $this->request->getGet('min_price');
        $maxPrice = $this->request->getGet('max_price');
        $category = $this->request->getGet('category');
        $pref = $this->request->getGet('pref');
        $topN = (int)($this->request->getGet('top_n') ?? 20);

        $minPrice = $minPrice ? (float)$minPrice : null;
        $maxPrice = $maxPrice ? (float)$maxPrice : null;

        $data = $this->service->searchPlaces(
            $keyword,
            $minPrice,
            $maxPrice,
            $category ?: null,
            $pref ?: null,
            $topN,
            true
        );

        return service('response')->setJSON($data);
    }
}
