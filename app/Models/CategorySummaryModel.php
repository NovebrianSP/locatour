<?php

namespace App\Models;

use CodeIgniter\Model;

class CategorySummaryModel extends Model
{
    protected $table = 'category_summary';
    protected $primaryKey = 'category_id';
    protected $allowedFields = [
        'category_id', 'category_standardized', 'jumlah', 'rata_rata_rating', 
        'rata_rata_price', 'total_vote'
    ];

    /**
     * Get all category summaries ordered by total
     */
    public function getAllSummaries()
    {
        return $this->orderBy('jumlah', 'DESC')->findAll();
    }
}
