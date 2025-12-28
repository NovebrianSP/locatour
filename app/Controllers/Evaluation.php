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

        return view('evaluation', [
            'k' => $k,
            'metrics' => $metrics,
            'stats' => $stats,
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
