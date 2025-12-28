<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $file = ROOTPATH . 'dataset/user_yogya_preprocessed.csv';
        
        if (!is_file($file)) {
            echo "❌ File tidak ditemukan: $file\n";
            return;
        }

        if (($handle = fopen($file, 'r')) === false) {
            echo "❌ Gagal membuka file\n";
            return;
        }

        echo "📁 Membaca file: user_yogya_preprocessed.csv\n";
        
        $header = fgetcsv($handle, 1000, ',');
        $data = [];
        $count = 0;

        while (($row = fgetcsv($handle, 10000, ',')) !== false) {
            $item = array_combine($header, $row);
            
            $data[] = [
                'user_id' => (int)$item['User_Id'],
                'location' => $item['Location'] ?? null,
                'age' => (int)($item['Age'] ?? 0),
                'location_clean' => $item['Location_clean'] ?? null,
                'location_norm' => $item['Location_norm'] ?? null,
                'location_tokens' => $item['Location_tokens'] ?? null,
                'age_normalized' => (float)($item['Age_normalized'] ?? 0)
            ];

            $count++;
            
            // Batch insert setiap 100 record untuk performa
            if (count($data) >= 100) {
                $this->db->table('users')->insertBatch($data);
                $data = [];
                echo "   ✓ Imported $count users...\n";
            }
        }

        // Insert sisa data
        if (!empty($data)) {
            $this->db->table('users')->insertBatch($data);
        }

        fclose($handle);
        echo "✅ Seeder Users selesai! Total: $count users\n\n";
    }
}
