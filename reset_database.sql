-- ================================================
-- RESET DATABASE LOCATOUR
-- Jalankan ini sebelum run seeder ulang
-- ================================================

USE locatour;

-- Disable foreign key checks untuk menghapus data
SET FOREIGN_KEY_CHECKS = 0;

-- Hapus semua data dari tabel (TRUNCATE lebih cepat dari DELETE)
TRUNCATE TABLE tourism_ratings;
TRUNCATE TABLE wisata_merged;
TRUNCATE TABLE tourism_places;
TRUNCATE TABLE category_summary;
TRUNCATE TABLE users;

-- Enable kembali foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Verifikasi semua tabel kosong
SELECT 'users' as tabel, COUNT(*) as jumlah FROM users
UNION ALL
SELECT 'category_summary', COUNT(*) FROM category_summary
UNION ALL
SELECT 'tourism_places', COUNT(*) FROM tourism_places
UNION ALL
SELECT 'tourism_ratings', COUNT(*) FROM tourism_ratings
UNION ALL
SELECT 'wisata_merged', COUNT(*) FROM wisata_merged;

-- Setelah ini, jalankan: php spark db:seed DatabaseSeeder
