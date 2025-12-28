<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Master Seeder - Menjalankan semua seeder sekaligus
 * 
 * Urutan penting:
 * 1. Users & Tourism dulu (karena ada foreign key di tourism_ratings)
 * 2. TourismRatings (depends on users & tourism)
 * 3. CategorySummary & WisataMerged (independent)
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "\n";
        echo "╔════════════════════════════════════════════════════╗\n";
        echo "║     LOCATOUR - Database Seeder Master             ║\n";
        echo "╚════════════════════════════════════════════════════╝\n";
        echo "\n";

        // Step 1: Seed Users (diperlukan untuk foreign key)
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "STEP 1/5: Seeding Users\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $this->call('UsersSeeder');

        // Step 2: Seed Tourism Places (diperlukan untuk foreign key)
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "STEP 2/5: Seeding Tourism Places\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $this->call('TourismSeeder');

        // Step 3: Seed Tourism Ratings (memerlukan users & tourism)
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "STEP 3/5: Seeding Tourism Ratings\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $this->call('TourismRatingsSeeder');

        // Step 4: Seed Category Summary
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "STEP 4/5: Seeding Category Summary\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $this->call('CategorySummarySeeder');

        // Step 5: Seed Wisata Merged
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "STEP 5/5: Seeding Wisata Merged\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $this->call('WisataMergedSeeder');

        echo "\n";
        echo "╔════════════════════════════════════════════════════╗\n";
        echo "║              ✅ SEEDING SELESAI!                   ║\n";
        echo "║     Semua data berhasil diimport ke database      ║\n";
        echo "╚════════════════════════════════════════════════════╝\n";
        echo "\n";
    }
}
