<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TourismSeeder extends Seeder
{
    public function run()
    {
        $file = ROOTPATH . 'dataset/tourism_with_id_yogya_preprocessed.csv';
        
        if (!is_file($file)) {
            echo "❌ File tidak ditemukan: $file\n";
            return;
        }

        if (($handle = fopen($file, 'r')) === false) {
            echo "❌ Gagal membuka file\n";
            return;
        }

        echo "📁 Membaca file: tourism_with_id_yogya_preprocessed.csv\n";

        $header = fgetcsv($handle, 1000, ',');
        $data = [];
        $count = 0;

        while (($row = fgetcsv($handle, 10000, ',')) !== false) {
            $item = array_combine($header, $row);
            
            $data[] = [
                'place_id' => $item['Place_Id'] ?? null,
                'place_name' => $item['Place_Name'] ?? null,
                'description' => $item['Description'] ?? null,
                'category' => $item['Category'] ?? null,
                'city' => $item['City'] ?? null,
                'price' => (int)($item['Price'] ?? 0),
                'rating' => (float)($item['Rating'] ?? 0),
                'time_minutes' => (int)($item['Time_Minutes'] ?? 0),
                'latitude' => !empty($item['Lat']) ? (float)$item['Lat'] : null,
                'longitude' => !empty($item['Long']) ? (float)$item['Long'] : null,
            ];

            $count++;
            
            // Batch insert setiap 100 record
            if (count($data) >= 100) {
                $this->db->table('tourism_places')->insertBatch($data);
                $data = [];
                echo "   ✓ Imported $count places...\n";
            }
        }

        // Insert sisa data
        if (!empty($data)) {
            $this->db->table('tourism_places')->insertBatch($data);
        }

        fclose($handle);
        echo "✅ Seeder Tourism selesai! Total: $count tempat wisata\n\n";
    }
}
