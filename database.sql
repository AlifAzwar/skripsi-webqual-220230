-- Inisialisasi Database Hasil Evaluasi WebQual 4.0 InfoSumbar (Sesuai Struktur Skripsi Alif)

CREATE DATABASE IF NOT EXISTS `db_webqual_infosumbar`;
USE `db_webqual_infosumbar`;

-- Nonaktifkan Foreign Key Check sementara saat reset/re-import
SET FOREIGN_KEY_CHECKS = 0;

-- Drop tabel berelasi terlebih dahulu untuk mencegah FK constraint error di phpMyAdmin
DROP TABLE IF EXISTS `laporan`;
DROP TABLE IF EXISTS `hasil_analisis`;
DROP TABLE IF EXISTS `admin`;

-- 1. Tabel Admin
CREATE TABLE `admin` (
  `id_admin` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_admin` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Admin (Password: admin123)
INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_admin`) VALUES
(1, 'admin', 'admin123', 'Administrator');

-- 2. Tabel Hasil Analisis
CREATE TABLE `hasil_analisis` (
  `id_hasil` INT AUTO_INCREMENT PRIMARY KEY,
  `kode_indikator` VARCHAR(10) NOT NULL,
  `nama_dimensi` VARCHAR(100) NOT NULL,
  `nama_indikator` VARCHAR(255) NOT NULL,
  `nilai_persepsi` DECIMAL(4,3) NOT NULL,
  `nilai_harapan` DECIMAL(4,3) NOT NULL,
  `nilai_gap` DECIMAL(4,3) NOT NULL,
  `kategori` VARCHAR(50),
  `tanggal_input` DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data Hasil Analisis 14 Indikator WebQual 4.0 InfoSumbar
INSERT INTO `hasil_analisis` (`id_hasil`, `kode_indikator`, `nama_dimensi`, `nama_indikator`, `nilai_persepsi`, `nilai_harapan`, `nilai_gap`, `kategori`, `tanggal_input`) VALUES

-- Usability
(1, 'US.1', 'Usability (Kemudahan Penggunaan)', 'Website mudah dipelajari untuk pertama kali digunakan.', 4.12, 4.19, -0.07, 'Baik', '2026-07-27'),
(2, 'US.2', 'Usability (Kemudahan Penggunaan)', 'Interaksi pada website mudah dipahami dengan jelas.', 3.69, 4.37, -0.68, 'Baik', '2026-07-27'),
(3, 'US.3', 'Usability (Kemudahan Penggunaan)', 'Website mudah untuk dinavigasikan.', 4.02, 4.37, -0.35, 'Baik', '2026-07-27'),
(4, 'US.4', 'Usability (Kemudahan Penggunaan)', 'Website mudah digunakan oleh pengunjung.', 3.90, 4.51, -0.61, 'Baik', '2026-07-27'),
(5, 'US.5', 'Usability (Kemudahan Penggunaan)', 'Tampilan website memiliki desain yang menarik.', 4.00, 4.41, -0.41, 'Baik', '2026-07-27'),

-- Information Quality
(6, 'IQ.1', 'Information Quality (Kualitas Informasi)', 'Website menyajikan informasi yang akurat.', 4.10, 4.46, -0.36, 'Sangat Baik', '2026-07-27'),
(7, 'IQ.2', 'Information Quality (Kualitas Informasi)', 'Informasi pada website dapat dipercaya.', 4.24, 4.51, -0.27, 'Sangat Baik', '2026-07-27'),
(8, 'IQ.3', 'Information Quality (Kualitas Informasi)', 'Informasi pada website selalu up to date.', 4.10, 4.29, -0.19, 'Sangat Baik', '2026-07-27'),
(9, 'IQ.4', 'Information Quality (Kualitas Informasi)', 'Informasi pada website relevan dengan kebutuhan pengguna.', 4.12, 4.58, -0.46, 'Sangat Baik', '2026-07-27'),
(10, 'IQ.5', 'Information Quality (Kualitas Informasi)', 'Informasi pada website mudah dipahami.', 4.17, 4.54, -0.37, 'Sangat Baik', '2026-07-27'),

-- Service Interaction Quality
(11, 'SQ.1', 'Service Interaction Quality (Kualitas Interaksi Layanan)', 'Website memberikan rasa aman ketika diakses.', 3.88, 4.42, -0.54, 'Baik', '2026-07-27'),
(12, 'SQ.2', 'Service Interaction Quality (Kualitas Interaksi Layanan)', 'Website memiliki reputasi yang baik dan dapat dipercaya.', 4.10, 4.53, -0.43, 'Sangat Baik', '2026-07-27'),
(13, 'SQ.3', 'Service Interaction Quality (Kualitas Interaksi Layanan)', 'Website memudahkan pengguna untuk berinteraksi dengan pengelola.', 4.05, 4.20, -0.15, 'Sangat Baik', '2026-07-27'),
(14, 'SQ.4', 'Service Interaction Quality (Kualitas Interaksi Layanan)', 'Website mampu memberikan keyakinan kepada pengguna atas layanan yang diberikan.', 3.86, 4.44, -0.58, 'Baik', '2026-07-27');

-- 3. Tabel Laporan
CREATE TABLE `laporan` (
  `id_laporan` INT AUTO_INCREMENT PRIMARY KEY,
  `judul_laporan` VARCHAR(150) NOT NULL,
  `periode` VARCHAR(50),
  `tanggal_laporan` DATE,
  `ringkasan` TEXT,
  `file_pdf` VARCHAR(255),
  `id_admin` INT,
  FOREIGN KEY (`id_admin`) REFERENCES `admin`(`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Dummy Laporan
INSERT INTO `laporan` (`id_laporan`, `judul_laporan`, `periode`, `tanggal_laporan`, `ringkasan`, `file_pdf`, `id_admin`) VALUES
(1, 'Laporan Evaluasi Kualitas Layanan InfoSumbar Q3 2026', 'Juli 2026', '2026-07-25', 'Laporan komprehensif mengenai hasil evaluasi kualitas layanan website InfoSumbar menggunakan metode WebQual 4.0 dan analisis GAP.', 'laporan_infosumbar_q3_2026.pdf', 1);

-- Aktifkan kembali Foreign Key Check
SET FOREIGN_KEY_CHECKS = 1;
