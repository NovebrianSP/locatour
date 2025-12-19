<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TourismRatingsSeeder extends Seeder
{
    public function run()
    {
        $file = ROOTPATH . 'dataset/tourism_rating_yogya_preprocessed.csv';
        
        if (!is_file($file)) {
            echo "❌ File tidak ditemukan: $file\n";
            return;
        }

        if (($handle = fopen($file, 'r')) === false) {
            echo "❌ Gagal membuka file\n";
            return;
        }

        echo "📁 Membaca file: tourism_rating_yogya_preprocessed.csv\n";
        echo "⚠️  PERHATIAN: Seeder ini memerlukan tabel 'users' dan 'tourism_places' sudah terisi!\n";

        $header = fgetcsv($handle, 1000, ',');
        $data = [];
        $count = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle, 10000, ',')) !== false) {
            $item = array_combine($header, $row);
            
            $userId = (int)$item['User_Id'];
            $placeId = $item['Place_Id'] ?? null;
            
            // Skip jika user_id atau place_id kosong
            if (empty($userId) || empty($placeId)) {
                $skipped++;
                continue;
            }
            
            $data[] = [
                'user_id' => $userId,
                'place_id' => $placeId,
                'place_ratings' => (int)($item['Place_Ratings'] ?? 0),
                'place_ratings_normalized' => (float)($item['Place_Ratings_normalized'] ?? 0)
            ];

            $count++;
            
            // Batch insert setiap 500 record
            if (count($data) >= 500) {
                try {
                    $this->db->table('tourism_ratings')->insertBatch($data);
                    echo "   ✓ Imported $count ratings...\n";
                } catch (\Exception $e) {
                    echo "   ⚠️  Error pada batch: " . $e->getMessage() . "\n";
                }
                $data = [];
            }
        }

        // Insert sisa data
        if (!empty($data)) {
            try {
                $this->db->table('tourism_ratings')->insertBatch($data);
            } catch (\Exception $e) {
                echo "   ⚠️  Error pada batch terakhir: " . $e->getMessage() . "\n";
            }
        }

        fclose($handle);
        echo "✅ Seeder Tourism Ratings selesai!\n";
        echo "   Total berhasil: $count ratings\n";
        if ($skipped > 0) {
            echo "   Total dilewati: $skipped (data tidak valid)\n";
        }
        echo "\n";
    }
}
