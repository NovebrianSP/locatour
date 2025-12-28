<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WisataMergedSeeder extends Seeder
{
    public function run()
    {
        $file = ROOTPATH . 'dataset/wisata_yogyakarta_merged_with_category_id.csv';
        
        if (!is_file($file)) {
            echo "❌ File tidak ditemukan: $file\n";
            return;
        }

        if (($handle = fopen($file, 'r')) === false) {
            echo "❌ Gagal membuka file\n";
            return;
        }

        echo "📁 Membaca file: wisata_yogyakarta_merged_with_category_id.csv\n";

        $header = fgetcsv($handle, 1000, ',');
        $data = [];
        $count = 0;

        while (($row = fgetcsv($handle, 10000, ',')) !== false) {
            $item = array_combine($header, $row);
            
            $data[] = [
                'source' => $item['source'] ?? null,
                'source_id' => !empty($item['id']) ? (int)$item['id'] : null,
                'name' => $item['name'] ?? null,
                'name_clean' => $item['name_clean'] ?? null,
                'name_norm' => $item['name_norm'] ?? null,
                'name_tokens' => $item['name_tokens'] ?? null,
                'description' => $item['description'] ?? null,
                'description_clean' => $item['description_clean'] ?? null,
                'description_norm' => $item['description_norm'] ?? null,
                'description_tokens' => $item['description_tokens'] ?? null,
                'category' => $item['category'] ?? null,
                'category_clean' => $item['category_clean'] ?? null,
                'category_norm' => $item['category_norm'] ?? null,
                'category_tokens' => $item['category_tokens'] ?? null,
                'price' => !empty($item['price']) ? (float)$item['price'] : 0,
                'rating' => !empty($item['rating']) ? (float)$item['rating'] : 0,
                'time_minutes' => !empty($item['time_minutes']) ? (int)$item['time_minutes'] : null,
                'latitude' => !empty($item['latitude']) ? (float)$item['latitude'] : null,
                'longitude' => !empty($item['longitude']) ? (float)$item['longitude'] : null,
                'vote_count' => !empty($item['vote_count']) ? (int)$item['vote_count'] : 0,
                'htm_weekday' => !empty($item['htm_weekday']) ? (float)$item['htm_weekday'] : null,
                'htm_weekend' => !empty($item['htm_weekend']) ? (float)$item['htm_weekend'] : null,
                'rating_normalized' => !empty($item['rating_normalized']) ? (float)$item['rating_normalized'] : null,
                'price_normalized' => !empty($item['price_normalized']) ? (float)$item['price_normalized'] : null,
                'category_standardized' => $item['category_standardized'] ?? null,
                'category_id' => !empty($item['category_id']) ? (int)$item['category_id'] : null,
                'unified_price' => !empty($item['unified_price']) ? (float)$item['unified_price'] : null,
                'merged_id' => !empty($item['merged_id']) ? (int)$item['merged_id'] : null,
                'indoor' => !empty($item['indoor']) ? (int)$item['indoor'] : 0,
                'outdoor' => !empty($item['outdoor']) ? (int)$item['outdoor'] : 0
            ];

            $count++;
            
            // Batch insert setiap 200 record
            if (count($data) >= 200) {
                $this->db->table('wisata_merged')->insertBatch($data);
                $data = [];
                echo "   ✓ Imported $count wisata...\n";
            }
        }

        // Insert sisa data
        if (!empty($data)) {
            $this->db->table('wisata_merged')->insertBatch($data);
        }

        fclose($handle);
        echo "✅ Seeder Wisata Merged selesai! Total: $count wisata\n\n";
    }
}
