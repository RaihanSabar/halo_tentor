-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Des 2024 pada 07.59
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_halotentor`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_tlp` varchar(15) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `regist_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token_created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `nama`, `email`, `no_tlp`, `image`, `password`, `regist_date`, `reset_token_created_at`) VALUES
(2, 'admin01', 'Admin User', 'raihansabarudin@gmail.com', '6283813919579', '4x6.jpg', '$2y$10$4xm7xfHHrKcrpaXeHLXpb.du2Rt9hAh/IdF4wopd0qQsMbS3vPi/.', '2024-11-13 03:10:33', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `biaya`
--

CREATE TABLE `biaya` (
  `id` int(11) NOT NULL,
  `nama_layanan` varchar(255) NOT NULL,
  `biaya` decimal(10,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_diupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `biaya`
--

INSERT INTO `biaya` (`id`, `nama_layanan`, `biaya`, `deskripsi`, `tanggal_dibuat`, `tanggal_diupdate`) VALUES
(1, 'Calistung, Montessori - Tingkat TK', '300000.00', 'Biaya untuk bimbingan belajar Calistung dan Montessori untuk anak-anak tingkat TK.', '2024-11-18 02:06:09', '2024-11-18 02:06:09'),
(2, 'Matematika, Bahasa Indonesia, Bahasa Inggris, IPA, IPS, PAI - Tingkat SD', '500000.00', 'Biaya untuk bimbingan belajar untuk semua mata pelajaran tingkat SD.', '2024-11-18 02:06:09', '2024-11-18 02:06:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_biaya`
--

CREATE TABLE `daftar_biaya` (
  `id` int(11) NOT NULL,
  `jenjang` varchar(255) DEFAULT NULL,
  `kurikulum` varchar(255) DEFAULT NULL,
  `pembelajaran` varchar(255) DEFAULT NULL,
  `1_bulan` decimal(10,2) DEFAULT NULL,
  `3_bulan` decimal(10,2) DEFAULT NULL,
  `6_bulan` decimal(10,2) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `urutan` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `daftar_biaya`
--

INSERT INTO `daftar_biaya` (`id`, `jenjang`, `kurikulum`, `pembelajaran`, `1_bulan`, `3_bulan`, `6_bulan`, `last_updated`, `urutan`) VALUES
(1, 'SD', 'KURNAS', 'OFFLINE', '1440000.00', '2015000.00', '3770000.00', '2024-11-25 04:25:19', 3),
(2, 'SD', 'KURNAS', 'ONLINE', '1305000.00', '1885000.00', '3770000.00', '2024-11-25 04:25:19', 4),
(3, 'SD', 'BILINGUAL', 'OFFLINE', '1620000.00', '2275000.00', '4290000.00', '2024-11-25 04:25:19', 5),
(4, 'SD', 'BILINGUAL', 'ONLINE', '1480000.00', '2145000.00', '4290000.00', '2024-11-25 04:25:19', 6),
(5, 'SD', 'INTERNASIONAL', 'OFFLINE', '1800000.00', '2535000.00', '4810000.00', '2024-11-25 04:25:19', 7),
(6, 'SD', 'INTERNASIONAL', 'ONLINE', '1655000.00', '4225000.00', '4810000.00', '2024-11-25 04:25:19', 8),
(9, 'TK', 'KURNAS', 'OFFLINE', '50000.00', '50000.00', '50000.00', '2024-12-05 01:59:17', 2),
(10, 'TK', 'KURNAS', 'ONLINE', '500000.00', '500000.00', '500000.00', '2024-12-05 01:59:17', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hero_content`
--

CREATE TABLE `hero_content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `hero_content`
--

INSERT INTO `hero_content` (`id`, `title`, `description`, `created_at`) VALUES
(1, 'SELAMAT DATANG DI HALO TENTOR', 'Halo Tentor adalah layanan bimbingan belajar privat untuk segala usia, mulai TK, SD, SMP, SMA/SMK, UMUM, dan KHUSUS.', '2024-11-15 02:44:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hero_images`
--

CREATE TABLE `hero_images` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `hero_images`
--

INSERT INTO `hero_images` (`id`, `title`, `image`, `created_at`) VALUES
(1, 'Hero Utama', 'image 12.png', '2024-11-20 02:56:43'),
(2, 'Hero Promo', 'WhatsApp Image 2024-10-22 at 10.21.54_a3eecf80.jpg', '2024-11-25 02:38:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `materi_pelajaran`
--

CREATE TABLE `materi_pelajaran` (
  `id` int(11) NOT NULL,
  `tingkat` varchar(50) NOT NULL,
  `materi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `materi_pelajaran`
--

INSERT INTO `materi_pelajaran` (`id`, `tingkat`, `materi`) VALUES
(1, 'Tingkat TK', 'Calistung\r\n<br>Montessori'),
(2, 'Tingkat SD', 'Matematika<br>\r\nBahasa Indonesia<br>\r\nBahasa Inggris<br>\r\nIPA<br>\r\nIPS<br>\r\nPAI'),
(3, 'Tingkat SMP', 'Matematika<br>\r\nBahasa Indonesia<br>\r\nBahasa Inggris<br>\r\nIPA<br>\r\nIPS<br>\r\nPAI'),
(4, 'Tingkat SMA/SMK', 'Matematika<br>\r\nBahasa Indonesia<br>\r\nBahasa Inggris<br>\r\nBiologi, Kimia, Fisika<br>\r\nEkonomi, Akuntansi<br>Biografi<br>\r\nPAI'),
(5, 'UMUM', 'TOEFL<br>\r\nTes Kedinasan<br>\r\nTes CPNS'),
(6, 'KHUSUS', 'Mengaji<br>\r\nBahasa Arab<br>\r\nBahasa Mandarin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `testimonial`
--

CREATE TABLE `testimonial` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `testimonial`
--

INSERT INTO `testimonial` (`id`, `title`, `image`) VALUES
(1, 'Testimoni 1', 'Desain X-Banner Proyek SI.png'),
(2, 'Testimoni 2', 'JAK48A-20240816.jpg'),
(3, 'Testimoni 3', 'WhatsApp Image 2024-11-15 at 15.14.46_6352b661.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tutors`
--

CREATE TABLE `tutors` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `university` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tutors`
--

INSERT INTO `tutors` (`id`, `nama`, `university`, `photo`) VALUES
(1, 'Tutor 01', 'Universitas AB', 'IMG_20240826_211448.jpg'),
(2, 'Saya Tutor', 'Universitas BAC', 'buat gambar anak pre school_paud tersenyum sambil membawa tas sekolah di punggungnya hadap depan den... (2).png'),
(3, 'Tutor 3', 'Universitas C', 'https://storage.googleapis.com/a1aa/image/66TsCbdcLgLec6BAlZeA7P1LttS5CKmOgX9YDohbFCUH8gtTA.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `biaya`
--
ALTER TABLE `biaya`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `daftar_biaya`
--
ALTER TABLE `daftar_biaya`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `hero_content`
--
ALTER TABLE `hero_content`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `hero_images`
--
ALTER TABLE `hero_images`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `materi_pelajaran`
--
ALTER TABLE `materi_pelajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `testimonial`
--
ALTER TABLE `testimonial`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `biaya`
--
ALTER TABLE `biaya`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `daftar_biaya`
--
ALTER TABLE `daftar_biaya`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `hero_content`
--
ALTER TABLE `hero_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `hero_images`
--
ALTER TABLE `hero_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `materi_pelajaran`
--
ALTER TABLE `materi_pelajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `testimonial`
--
ALTER TABLE `testimonial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tutors`
--
ALTER TABLE `tutors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
