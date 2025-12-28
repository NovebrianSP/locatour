<?php

namespace App\Models;

use CodeIgniter\Model;

class TourismModel extends Model
{
    protected $table = 'tourism_places';
    protected $primaryKey = 'place_id';
    protected $allowedFields = [
        'place_id', 'place_name', 'description', 'category', 'city', 
        'price', 'rating', 'time_minutes', 'latitude', 'longitude'
    ];

    /**
     * Get featured tourism places with high ratings
     */
    public function getFeaturedPlaces($limit = 6)
    {
        return $this->where('rating >=', 4.5)
                    ->orderBy('rating', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get tourism places by category
     */
    public function getByCategory($category, $limit = 10)
    {
        return $this->where('category', $category)
                    ->orderBy('rating', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get all categories with count
     */
    public function getCategorySummary()
    {
        return $this->select('category, COUNT(*) as total, AVG(rating) as avg_rating, AVG(price) as avg_price')
                    ->groupBy('category')
                    ->orderBy('total', 'DESC')
                    ->findAll();
    }

    /**
     * Get popular destinations (high rating and affordable price)
     */
    public function getPopularDestinations($limit = 9)
    {
        return $this->where('rating >=', 4.3)
                    ->where('price <=', 50000)
                    ->orderBy('rating', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get free tourist attractions
     */
    public function getFreeAttractions($limit = 6)
    {
        return $this->where('price', 0)
                    ->orderBy('rating', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Search tourism places
     */
    public function searchPlaces($keyword)
    {
        return $this->like('place_name', $keyword)
                    ->orLike('description', $keyword)
                    ->orLike('category', $keyword)
                    ->orderBy('rating', 'DESC')
                    ->findAll();
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        $avgRating = $this->selectAvg('rating', 'avg_rating')->first();
        
        $stats = [
            'total_places' => $this->countAll(),
            'avg_rating' => round($avgRating['avg_rating'], 2),
            'total_categories' => $this->select('category')->distinct()->countAllResults(),
            'highest_rated' => $this->orderBy('rating', 'DESC')->first()
        ];
        
        return $stats;
    }
}
