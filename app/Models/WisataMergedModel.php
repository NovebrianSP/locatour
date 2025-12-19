<?php

namespace App\Models;

use CodeIgniter\Model;

class WisataMergedModel extends Model
{
    protected $table = 'wisata_merged';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'source', 'source_id', 'name', 'description', 'category',
        'price', 'rating', 'time_minutes', 'latitude', 'longitude',
        'category_standardized', 'category_id', 'unified_price', 'indoor', 'outdoor'
    ];

    /**
     * Get popular places with high ratings
     */
    public function getPopularPlaces($limit = 9)
    {
        return $this->where('rating >=', 4.3)
                    ->orderBy('rating', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get places by category
     */
    public function getByCategory($category, $limit = 10)
    {
        return $this->where('category_standardized', $category)
                    ->orderBy('rating', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get places by category_id
     */
    public function getByCategoryId($categoryId, $limit = null)
    {
        $builder = $this->where('category_id', $categoryId)
                         ->orderBy('rating', 'DESC');
        if ($limit !== null) {
            $builder->limit($limit);
        }
        return $builder->findAll();
    }
}
