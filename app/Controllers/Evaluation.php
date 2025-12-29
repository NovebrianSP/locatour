<?php

namespace App\Controllers;

use App\Libraries\RecommendationService;
use Config\Database;

class Evaluation extends BaseController
{
    protected $service;
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->service = new RecommendationService($this->db);
    }

    public function index()
    {
        $k = (int)($this->request->getGet('k') ?? 10);
        if ($k < 1) {
            $k = 1;
        } elseif ($k > 50) {
            $k = 50;
        }

        $metrics = $this->service->evaluateCollaborative($k);

        $stats = $this->getDatasetStats();

        $weightScenarios = [
            'content' => ['content' => 1.0, 'collaborative' => 0.0, 'weather' => 0.0],
            'collaborative' => ['content' => 0.0, 'collaborative' => 1.0, 'weather' => 0.0],
            'weather' => ['content' => 0.1, 'collaborative' => 0.1, 'weather' => 0.8],
            'hybrid' => ['content' => 0.2, 'collaborative' => 0.6, 'weather' => 0.2],
        ];

        $comparison = [];
        foreach ($weightScenarios as $key => $weights) {
            $comparison[$key] = $this->service->evaluateWeighting($weights, $k);
        }

        return view('evaluation', [
            'k' => $k,
            'metrics' => $metrics,
            'stats' => $stats,
            'comparison' => $comparison,
        ]);
    }

    private function getDatasetStats(): array
    {
        $row = $this->db->table('tourism_ratings')
            ->select('COUNT(*) AS total_ratings, COUNT(DISTINCT user_id) AS total_users, COUNT(DISTINCT place_id) AS total_items')
            ->get()
            ->getRowArray();

        return [
            'total_ratings' => (int)($row['total_ratings'] ?? 0),
            'total_users' => (int)($row['total_users'] ?? 0),
            'total_items' => (int)($row['total_items'] ?? 0),
        ];
    }
}
