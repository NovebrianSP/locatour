<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySummarySeeder extends Seeder
{
    public function run()
    {
        $file = ROOTPATH . 'dataset/category_reference.csv';
        
        if (!is_file($file)) {
            echo "❌ File tidak ditemukan: $file\n";
            return;
        }

        if (($handle = fopen($file, 'r')) === false) {
            echo "❌ Gagal membuka file\n";
            return;
        }

        echo "📁 Membaca file: category_reference.csv\n";

        $header = fgetcsv($handle, 1000, ',');
        $data = [];

        while (($row = fgetcsv($handle, 10000, ',')) !== false) {
            $item = array_combine($header, $row);
            
            $data[] = [
                'category_id' => !empty($item['category_id']) ? (int)$item['category_id'] : null,
                'category_standardized' => $item['category_standardized'] ?? null,
                'jumlah' => (int)($item['Jumlah'] ?? 0),
                'rata_rata_rating' => (float)($item['Rata-rata Rating'] ?? 0),
                'rata_rata_price' => (float)($item['Rata-rata Price'] ?? 0),
                'total_vote' => (int)($item['Total Vote'] ?? 0)
            ];
        }

        if (!empty($data)) {
            $this->db->table('category_summary')->insertBatch($data);
        }

        fclose($handle);
        echo "✅ Seeder Category Summary selesai! Total: " . count($data) . " kategori\n\n";
    }
}
