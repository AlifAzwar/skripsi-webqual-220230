-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 30, 2026 at 06:34 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_webqual_infosumbar`
--

-- --------------------------------------------------------

--
-- Table structure for table `hasil_analisis`
--

CREATE TABLE `hasil_analisis` (
  `id_hasil` int NOT NULL,
  `kode_indikator` varchar(10) NOT NULL,
  `nama_dimensi` varchar(100) NOT NULL,
  `nama_indikator` varchar(255) NOT NULL,
  `nilai_persepsi` decimal(4,3) NOT NULL,
  `nilai_harapan` decimal(4,3) NOT NULL,
  `nilai_gap` decimal(4,3) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `tanggal_input` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hasil_analisis`
--

INSERT INTO `hasil_analisis` (`id_hasil`, `kode_indikator`, `nama_dimensi`, `nama_indikator`, `nilai_persepsi`, `nilai_harapan`, `nilai_gap`, `kategori`, `tanggal_input`) VALUES
(1, 'US.1', 'Usability (Kemudahan Penggunaan)', 'Website mudah dipelajari untuk pertama kali digunakan.', 4.111, 4.206, -0.095, 'Baik', '2026-07-27'),
(2, 'US.2', 'Usability (Kemudahan Penggunaan)', 'Interaksi pada website mudah dipahami dengan jelas.', 3.730, 4.365, -0.635, 'Perlu Perbaikan', '2026-07-27'),
(3, 'US.3', 'Usability (Kemudahan Penggunaan)', 'Website mudah untuk dinavigasikan.', 3.984, 4.413, -0.429, 'Baik', '2026-07-27'),
(4, 'US.4', 'Usability (Kemudahan Penggunaan)', 'Website mudah digunakan oleh pengunjung.', 3.905, 4.540, -0.635, 'Perlu Perbaikan', '2026-07-27'),
(5, 'US.5', 'Usability (Kemudahan Penggunaan)', 'Tampilan website memiliki desain yang menarik.', 4.032, 4.444, -0.412, 'Baik', '2026-07-27'),
(6, 'IQ.1', 'Information Quality (Kualitas Informasi)', 'Website menyajikan informasi yang akurat.', 4.127, 4.492, -0.365, 'Baik', '2026-07-27'),
(7, 'IQ.2', 'Information Quality (Kualitas Informasi)', 'Informasi pada website dapat dipercaya.', 4.270, 4.540, -0.270, 'Baik', '2026-07-27'),
(8, 'IQ.3', 'Information Quality (Kualitas Informasi)', 'Informasi pada website selalu up to date.', 4.111, 4.317, -0.206, 'Baik', '2026-07-27'),
(9, 'IQ.4', 'Information Quality (Kualitas Informasi)', 'Informasi pada website relevan dengan kebutuhan pengguna.', 4.143, 4.603, -0.460, 'Baik', '2026-07-27'),
(10, 'IQ.5', 'Information Quality (Kualitas Informasi)', 'Informasi pada website mudah dipahami.', 4.190, 4.540, -0.350, 'Baik', '2026-07-27'),
(11, 'SQ.1', 'Service Interaction Quality (Kualitas Interaksi Layanan)', 'Website memberikan rasa aman ketika diakses.', 3.873, 4.460, -0.587, 'Perlu Perbaikan', '2026-07-27'),
(12, 'SQ.2', 'Service Interaction Quality (Kualitas Interaksi Layanan)', 'Website memiliki reputasi yang baik dan dapat dipercaya.', 4.127, 4.556, -0.429, 'Baik', '2026-07-27'),
(13, 'SQ.3', 'Service Interaction Quality (Kualitas Interaksi Layanan)', 'Website memudahkan pengguna untuk berinteraksi dengan pengelola.', 4.079, 4.200, -0.121, 'Baik', '2026-07-27'),
(14, 'SQ.4', 'Service Interaction Quality (Kualitas Interaksi Layanan)', 'Website mampu memberikan keyakinan kepada pengguna atas layanan yang diberikan.', 3.857, 4.476, -0.619, 'Perlu Perbaikan', '2026-07-27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hasil_analisis`
--
ALTER TABLE `hasil_analisis`
  ADD PRIMARY KEY (`id_hasil`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hasil_analisis`
--
ALTER TABLE `hasil_analisis`
  MODIFY `id_hasil` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
