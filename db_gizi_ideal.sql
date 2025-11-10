-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Nov 2025 pada 07.43
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_gizi_ideal`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$QastnfKJcQvq0c5hFxR.D.mQOVQnyEEKeHnj7YcpgNs1eNEMzgqWC'),
(2, 'admin2', '12345');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gejala`
--

CREATE TABLE `gejala` (
  `id` int(11) NOT NULL,
  `kode_gejala` varchar(5) NOT NULL,
  `nama_gejala` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `gejala`
--

INSERT INTO `gejala` (`id`, `kode_gejala`, `nama_gejala`) VALUES
(1, 'G01', 'IMT < 18,5 (Underweight)'),
(2, 'G02', 'IMT 18,5 - 24,9 (Normal)'),
(3, 'G03', 'IMT 25,0 - 29,9 (Overweight)'),
(4, 'G04', 'IMT >= 30,0 (Obesitas)'),
(5, 'G05', 'Tidak Pernah Olahraga'),
(6, 'G06', 'Jarang Olahraga (1 kali seminggu)'),
(7, 'G07', 'Cukup Olahraga (2-3 kali seminggu)'),
(8, 'G08', 'Sering Olahraga (4-5 kali seminggu)'),
(9, 'G09', 'Rutin Olahraga (>= 6 kali seminggu)'),
(10, 'G10', 'Jenis Olahraga Ringan'),
(11, 'G11', 'Jenis Olahraga Sedang'),
(12, 'G12', 'Jenis Olahraga Berat'),
(13, 'G13', 'Tidak Melakukan Olahraga'),
(14, 'G14', 'Sering makan buah (>= 2 porsi perhari)'),
(15, 'G15', 'Jarang makan buah (<= 3 kali per minggu)'),
(16, 'G16', 'Sering makan sayur (> 3 porsi sehari)'),
(17, 'G17', 'Jarang makan sayur (< 3 kali per minggu)'),
(18, 'G18', 'Sering makan fast food (> 3 kali per minggu)'),
(19, 'G19', 'Sering konsumsi minuman manis (> 1 kali perhari)'),
(20, 'G20', 'Tidak ada riwayat penyakit kronis'),
(21, 'G21', 'Memiliki riwayat penyakit Diabetes'),
(22, 'G22', 'Memiliki riwayat penyakit Hipertensi'),
(23, 'G23', 'Memiliki riwayat Kolesterol tinggi'),
(24, 'G24', 'Memiliki riwayat penyakit pencernaan (Maag)');

-- --------------------------------------------------------

--
-- Struktur dari tabel `halaman_informasi`
--

CREATE TABLE `halaman_informasi` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `halaman_informasi`
--

INSERT INTO `halaman_informasi` (`id`, `judul`, `konten`, `urutan`) VALUES
(1, 'Tabel Indeks Massa Tubuh (IMT)', '<div class=\"table-responsive\">\r\n    <table class=\"table table-bordered text-center\">\r\n        <thead class=\"table-primary\">\r\n            <tr>\r\n                <th>Kategori</th>\r\n                <th>IMT (kg/m²)</th>\r\n            </tr>\r\n        </thead>\r\n        <tbody>\r\n            <tr><td>Kurus (Underweight)</td><td>&lt; 18,5</td></tr>\r\n            <tr><td>Normal</td><td>18,5 – 24,9</td></tr>\r\n            <tr><td>Gemuk (Overweight)</td><td>25,0 – 29,9</td></tr>\r\n            <tr><td>Obesitas</td><td>≥ 30,0</td></tr>\r\n        </tbody>\r\n    </table>\r\n</div>\r\n<small class=\"text-muted\">Sumber: World Health Organization (WHO), 2021</small>', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kondisi_tambahan`
--

CREATE TABLE `kondisi_tambahan` (
  `id` int(11) NOT NULL,
  `nama_kondisi` varchar(100) NOT NULL COMMENT 'Nama penyakit/kondisi',
  `keterangan` text DEFAULT NULL COMMENT 'Deskripsi tambahan tentang kondisi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kondisi_tambahan`
--

INSERT INTO `kondisi_tambahan` (`id`, `nama_kondisi`, `keterangan`) VALUES
(1, 'Diabetes', 'Pernah/sedang mengalami diabetes mellitus, Penyakit metabolik dengan kadar gula darah tinggi'),
(2, 'Hipertensi', 'Tekanan darah tinggi ≥140/90 mmHg'),
(3, 'Kolesterol Tinggi', 'Riwayat kolesterol melebihi batas normal, Kadar kolesterol total >200 mg/dL'),
(4, 'Penyakit Pencernaan (Maag)', 'Gangguan pada lambung/sistem pencernaan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengetahuan`
--

CREATE TABLE `pengetahuan` (
  `id` int(11) NOT NULL,
  `gejala_id` int(11) NOT NULL,
  `status_gizi_id` int(11) NOT NULL,
  `cf_pakar` decimal(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengetahuan`
--

INSERT INTO `pengetahuan` (`id`, `gejala_id`, `status_gizi_id`, `cf_pakar`) VALUES
(1, 1, 1, 1.00),
(2, 2, 2, 1.00),
(3, 3, 3, 1.00),
(4, 4, 4, 1.00),
(5, 5, 4, 0.90),
(6, 6, 4, 0.90),
(7, 9, 2, 0.90),
(8, 14, 2, 0.50),
(9, 15, 1, 0.60),
(10, 17, 1, 0.60),
(11, 18, 4, 0.90),
(12, 19, 4, 0.80),
(13, 21, 4, 1.00),
(14, 22, 4, 0.90),
(15, 24, 1, 0.40);

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekomendasi`
--

CREATE TABLE `rekomendasi` (
  `id` int(11) NOT NULL,
  `status_gizi_id` int(11) NOT NULL COMMENT 'FK ke status gizi (wajib)',
  `kondisi_tambahan_id` int(11) DEFAULT NULL COMMENT 'FK ke kondisi tambahan (opsional)',
  `saran` text NOT NULL COMMENT 'Teks saran atau rekomendasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rekomendasi`
--

INSERT INTO `rekomendasi` (`id`, `status_gizi_id`, `kondisi_tambahan_id`, `saran`) VALUES
(1, 1, NULL, 'Tingkatkan asupan bergizi: cukup protein, lemak sehat, karbohidrat seimbang, serat, vitamin, dan mineral.'),
(2, 2, NULL, 'Pertahankan asupan gizi seimbang, lakukan olahraga rutin, penuhi kebutuhan cairan, istirahat yang cukup, dan kelola stres dengan baik.'),
(3, 3, NULL, 'Batasi asupan kalori berlebih, cukupi kebutuhan protein, konsumsi makanan rendah lemak, cukup karbohidrat kompleks, serta kurangi asupan gula, garam, dan minyak.'),
(4, 4, NULL, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(5, 1, 1, 'Tingkatkan berat badan secara bertahap dengan pola makan teratur. Pilih karbohidrat kompleks (nasi merah, oatmeal), protein tanpa lemak, dan lemak sehat. Konsumsi makanan porsi kecil tapi sering (5-6 kali/hari). Hindari gula sederhana dan pantau kadar gula darah secara rutin.'),
(6, 1, 2, 'Tingkatkan asupan kalori dengan makanan bergizi tinggi namun rendah garam (maksimal 5 gram/hari). Perbanyak konsumsi buah, sayuran, protein tanpa lemak, dan lemak sehat. Hindari makanan olahan dan makanan kalengan yang tinggi natrium.'),
(7, 1, 3, 'Tingkatkan berat badan dengan memilih lemak sehat (omega-3, minyak zaitun, alpukat, kacang-kacangan). Hindari lemak trans dan lemak jenuh. Perbanyak serat larut dari oatmeal, buah-buahan, dan sayuran. Konsumsi ikan 2-3 kali seminggu.'),
(8, 1, 4, 'Makan dalam porsi kecil tapi sering (5-6 kali/hari). Pilih makanan lunak dan mudah dicerna. Hindari makanan pedas, asam, berlemak, dan bersantan. Kunyah makanan dengan baik. Hindari makan 2-3 jam sebelum tidur.'),
(9, 2, 1, 'Pertahankan berat badan ideal dengan pola makan teratur 3 kali sehari + 2-3 snack sehat. Pilih karbohidrat kompleks dengan indeks glikemik rendah. Batasi konsumsi gula dan makanan manis. Olahraga teratur 150 menit/minggu. Pantau gula darah secara berkala.'),
(10, 2, 2, 'Pertahankan berat badan dengan diet DASH (Dietary Approaches to Stop Hypertension). Batasi natrium maksimal 5 gram/hari. Perbanyak kalium dari pisang, jeruk, bayam. Kurangi kafein dan alkohol. Olahraga aerobik rutin 30 menit/hari.'),
(11, 2, 3, 'Pertahankan berat badan ideal dengan diet rendah lemak jenuh dan kolesterol. Perbanyak serat larut (minimal 25-30 gram/hari). Konsumsi ikan berlemak, kacang-kacangan, dan minyak zaitun. Hindari gorengan dan makanan berlemak tinggi. Olahraga teratur untuk meningkatkan HDL.'),
(12, 2, 4, 'Pertahankan pola makan teratur dengan porsi sedang. Hindari makanan pemicu maag (pedas, asam, kafein berlebih). Makan perlahan dan kunyah dengan baik. Hindari stres dan makan terburu-buru. Jaga jarak waktu makan dengan tidur minimal 2-3 jam.'),
(13, 3, 1, 'Turunkan berat badan 5-10% secara bertahap (0.5-1 kg/minggu). Terapkan diet rendah kalori dan karbohidrat dengan indeks glikemik rendah. Perbanyak serat dan protein. Olahraga kombinasi aerobik dan resistance 150-300 menit/minggu. Monitor gula darah secara teratur.'),
(14, 3, 2, 'Turunkan berat badan bertahap untuk menurunkan tekanan darah. Terapkan diet DASH dengan kalori terkontrol. Batasi natrium <5 gram/hari. Perbanyak kalium, magnesium, dan kalsium. Hindari lemak jenuh. Olahraga aerobik teratur minimal 150 menit/minggu.'),
(15, 3, 3, 'Turunkan berat badan untuk memperbaiki profil lipid. Diet rendah lemak jenuh (<7% total kalori) dan kolesterol (<200 mg/hari). Tingkatkan serat larut 10-25 gram/hari. Konsumsi sterol/stanol nabati 2 gram/hari. Olahraga teratur untuk meningkatkan HDL.'),
(16, 3, 4, 'Turunkan berat badan dengan porsi terkontrol namun tetap makan teratur. Hindari diet ketat yang memicu asam lambung. Pilih makanan rendah lemak dan mudah dicerna. Hindari makanan pedas, asam, dan bersantan. Makan porsi kecil tapi sering.'),
(17, 4, 1, 'Program penurunan berat badan intensif (target 7-10%). Diet rendah kalori (defisit 500-750 kkal/hari) dengan karbohidrat kompleks rendah GI. Perbanyak protein dan serat. Olahraga kombinasi aerobik dan resistance minimal 300 menit/minggu. Monitoring gula darah ketat. Konsultasi dengan dokter dan ahli gizi.'),
(18, 4, 2, 'Penurunan berat badan prioritas utama (10% dalam 6 bulan). Diet DASH dengan kalori terkontrol. Natrium maksimal 5 gram/hari. Perbanyak buah, sayur, whole grain. Hindari lemak jenuh dan trans. Olahraga teratur 300 menit/minggu. Kelola stres dengan baik.'),
(19, 4, 3, 'Penurunan berat badan untuk memperbaiki profil lipid. Diet rendah lemak jenuh dan kolesterol. Tingkatkan serat 25-35 gram/hari. Konsumsi lemak tak jenuh (omega-3, minyak zaitun). Hindari makanan tinggi kolesterol (jeroan, kuning telur berlebih). Olahraga intensitas sedang-tinggi minimal 300 menit/minggu.'),
(20, 4, 4, 'Turunkan berat badan bertahap dengan pola makan teratur. Hindari diet ekstrem yang memicu asam lambung. Makan porsi kecil tapi sering (6-7 kali/hari). Pilih makanan rendah lemak dan mudah dicerna. Hindari makanan pedas, asam, gorengan, dan bersantan. Jangan berbaring setelah makan.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_diagnosa`
--

CREATE TABLE `riwayat_diagnosa` (
  `id` int(11) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp(),
  `nama_lengkap` varchar(100) NOT NULL,
  `hasil_status_gizi_id` int(11) NOT NULL,
  `hasil_cf` decimal(5,4) NOT NULL,
  `rekomendasi_diberikan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `riwayat_diagnosa`
--

INSERT INTO `riwayat_diagnosa` (`id`, `waktu`, `nama_lengkap`, `hasil_status_gizi_id`, `hasil_cf`, `rekomendasi_diberikan`) VALUES
(2, '2025-10-17 06:34:49', '', 1, 1.0000, 'Tingkatkan asupan bergizi: cukup protein, lemak sehat, karbohidrat seimbang, serat, vitamin, dan mineral. Pilih makanan mudah cerna jika punya maag.'),
(3, '2025-10-17 06:38:30', '', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(4, '2025-10-17 06:42:24', '', 2, 1.0000, 'Pertahankan asupan gizi seimbang, lakukan olahraga rutin, penuhi kebutuhan cairan, istirahat yang cukup, dan kelola stres dengan baik.'),
(5, '2025-10-19 14:37:40', 'Fadhad Wahyu Aji', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(6, '2025-10-19 14:54:47', 'cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(7, '2025-10-19 14:55:01', 'cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(8, '2025-10-19 14:55:08', 'cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(9, '2025-10-19 14:55:19', 'cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(10, '2025-10-19 14:55:20', 'cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(11, '2025-10-19 14:55:20', 'cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(12, '2025-10-19 14:55:22', 'cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(13, '2025-10-19 14:55:27', 'cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(14, '2025-10-19 14:57:17', 'Cardiffff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(15, '2025-10-19 14:57:25', 'Cardiffff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(16, '2025-10-19 14:57:26', 'Cardiffff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(17, '2025-10-19 14:57:27', 'Cardiffff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(18, '2025-10-19 14:57:28', 'Cardiffff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(19, '2025-10-19 14:57:28', 'Cardiffff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(20, '2025-10-19 14:57:28', 'Cardiffff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(21, '2025-10-19 14:57:30', 'Cardiffff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(22, '2025-10-19 14:57:31', 'Cardiffff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(23, '2025-10-19 14:57:32', 'Cardiffff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(24, '2025-10-19 14:58:31', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(25, '2025-10-19 14:58:32', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(26, '2025-10-19 14:58:32', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(27, '2025-10-19 14:58:33', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(28, '2025-10-19 14:58:33', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(29, '2025-10-19 14:58:33', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(30, '2025-10-19 14:58:33', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(31, '2025-10-19 14:58:33', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(32, '2025-10-19 14:58:36', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(33, '2025-10-19 14:58:37', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(34, '2025-10-19 14:58:38', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(35, '2025-10-19 14:58:38', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(36, '2025-10-19 15:01:05', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(38, '2025-10-19 15:01:07', 'Cardiff', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(40, '2025-10-19 15:06:20', 'Lisa', 3, 1.0000, 'Batasi asupan kalori berlebih, cukupi kebutuhan protein, konsumsi makanan rendah lemak, cukup karbohidrat kompleks, serta kurangi asupan gula, garam, dan minyak.'),
(41, '2025-10-19 15:06:22', 'Lisa', 3, 1.0000, 'Batasi asupan kalori berlebih, cukupi kebutuhan protein, konsumsi makanan rendah lemak, cukup karbohidrat kompleks, serta kurangi asupan gula, garam, dan minyak.'),
(42, '2025-10-19 15:06:24', 'Lisa', 3, 1.0000, 'Batasi asupan kalori berlebih, cukupi kebutuhan protein, konsumsi makanan rendah lemak, cukup karbohidrat kompleks, serta kurangi asupan gula, garam, dan minyak.'),
(43, '2025-10-19 15:06:42', 'Lisa', 3, 1.0000, 'Batasi asupan kalori berlebih, cukupi kebutuhan protein, konsumsi makanan rendah lemak, cukup karbohidrat kompleks, serta kurangi asupan gula, garam, dan minyak.'),
(44, '2025-10-19 15:10:21', 'Saipul', 2, 1.0000, 'Pertahankan asupan gizi seimbang, lakukan olahraga rutin, penuhi kebutuhan cairan, istirahat yang cukup, dan kelola stres dengan baik.'),
(45, '2025-10-19 15:12:19', 'Fadhad Wahyu Aji', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(46, '2025-10-19 15:14:19', 'Fadhad Wahyu Aji', 4, 1.0000, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),
(47, '2025-10-20 13:02:29', 'Fadhad Wahyu Aji', 1, 1.0000, '=== REKOMENDASI UMUM ===\nTingkatkan asupan bergizi: cukup protein, lemak sehat, karbohidrat seimbang, serat, vitamin, dan mineral. Pilih makanan mudah cerna jika punya maag.\n\n=== REKOMENDASI BERDASARKAN KONDISI ANDA ===\n\n'),
(48, '2025-10-20 13:09:43', 'Fadhad Wahyu Aji', 4, 1.0000, '=== REKOMENDASI UMUM ===\nTerapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.\n\n=== REKOMENDASI BERDASARKAN KONDISI ANDA ===\n\n🔴 KONDISI: Diabetes + Kolesterol Tinggi\n📋 Apa rekomendasi untuk kombinasi diabetes dan kolesterol tinggi?\r\n💡 KOMBINASI DIET RENDAH GULA & RENDAH LEMAK:\r\n- Hindari gula tambahan DAN lemak jenuh\r\n- Pilih karbohidrat kompleks: nasi merah, roti gandum utuh, oat\r\n- Protein: ikan berlemak sehat (salmon, tuna, sarden) 2-3x/minggu, kacang-kacangan\r\n- Hindari: gorengan, jeroan, santan, margarin, mentega, kue manis, minuman manis\r\n- Perbanyak serat dari sayur dan buah rendah gula\r\n- Gunakan minyak zaitun atau minyak kanola maksimal 3-4 Sdm/hari\n\n'),
(49, '2025-10-20 13:12:22', 'Fadhad Wahyu Aji', 4, 1.0000, '=== REKOMENDASI UMUM ===\n\nTerapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.\n\n=== REKOMENDASI BERDASARKAN KONDISI ANDA ===\n\n🔴 KONDISI: Diabetes\n📋 Apa rekomendasi khusus terkait asupan gula dan karbohidrat?\r\n💡 Perbanyak minum air putih hangat (8-10 gelas/hari). Batasi asupan gula tidak lebih dari 50g/hari atau 4 Sdm. Hindari minuman manis (teh manis, kopi manis, soda, jus kemasan), kue manis, permen, coklat, dan makanan tinggi gula. Pilih karbohidrat kompleks (nasi merah, roti gandum, oat, kentang rebus) dengan porsi terkontrol. Hindari karbohidrat sederhana (nasi putih, roti tawar putih, mie instan).\n\n'),
(50, '2025-10-21 17:43:29', 'Fadhad Wahyu Aji', 1, 1.0000, '=== REKOMENDASI UMUM ===\nTingkatkan asupan bergizi: cukup protein, lemak sehat, karbohidrat seimbang, serat, vitamin, dan mineral.\n\n=== REKOMENDASI UNTUK DIABETES ===\nTingkatkan berat badan secara bertahap dengan pola makan teratur. Pilih karbohidrat kompleks (nasi merah, oatmeal), protein tanpa lemak, dan lemak sehat. Konsumsi makanan porsi kecil tapi sering (5-6 kali/hari). Hindari gula sederhana dan pantau kadar gula darah secara rutin.\n\n'),
(51, '2025-10-21 17:44:06', 'Fadhad Wahyu Aji', 2, 1.0000, '=== REKOMENDASI UMUM ===\nPertahankan asupan gizi seimbang, lakukan olahraga rutin, penuhi kebutuhan cairan, istirahat yang cukup, dan kelola stres dengan baik.\n\n=== REKOMENDASI UNTUK DIABETES ===\nPertahankan berat badan ideal dengan pola makan teratur 3 kali sehari + 2-3 snack sehat. Pilih karbohidrat kompleks dengan indeks glikemik rendah. Batasi konsumsi gula dan makanan manis. Olahraga teratur 150 menit/minggu. Pantau gula darah secara berkala.\n\n');

-- --------------------------------------------------------

--
-- Struktur dari tabel `status_gizi`
--

CREATE TABLE `status_gizi` (
  `id` int(11) NOT NULL,
  `kode_status` varchar(5) NOT NULL,
  `nama_status` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `status_gizi`
--

INSERT INTO `status_gizi` (`id`, `kode_status`, `nama_status`, `deskripsi`) VALUES
(1, 'S1', 'Gizi Kurang', 'IMT < 18,5'),
(2, 'S2', 'Normal', 'IMT 18,5 - 24,9'),
(3, 'S3', 'Gizi Lebih', 'IMT 25,0 - 29,9'),
(4, 'S4', 'Obesitas', 'IMT >= 30,0');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_gejala` (`kode_gejala`);

--
-- Indeks untuk tabel `halaman_informasi`
--
ALTER TABLE `halaman_informasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kondisi_tambahan`
--
ALTER TABLE `kondisi_tambahan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengetahuan`
--
ALTER TABLE `pengetahuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gejala_id` (`gejala_id`),
  ADD KEY `status_gizi_id` (`status_gizi_id`);

--
-- Indeks untuk tabel `rekomendasi`
--
ALTER TABLE `rekomendasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_gizi_id` (`status_gizi_id`),
  ADD KEY `kondisi_tambahan_id` (`kondisi_tambahan_id`);

--
-- Indeks untuk tabel `riwayat_diagnosa`
--
ALTER TABLE `riwayat_diagnosa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hasil_status_gizi_id` (`hasil_status_gizi_id`);

--
-- Indeks untuk tabel `status_gizi`
--
ALTER TABLE `status_gizi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_status` (`kode_status`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `gejala`
--
ALTER TABLE `gejala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `halaman_informasi`
--
ALTER TABLE `halaman_informasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kondisi_tambahan`
--
ALTER TABLE `kondisi_tambahan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pengetahuan`
--
ALTER TABLE `pengetahuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `rekomendasi`
--
ALTER TABLE `rekomendasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `riwayat_diagnosa`
--
ALTER TABLE `riwayat_diagnosa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `status_gizi`
--
ALTER TABLE `status_gizi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pengetahuan`
--
ALTER TABLE `pengetahuan`
  ADD CONSTRAINT `pengetahuan_ibfk_1` FOREIGN KEY (`gejala_id`) REFERENCES `gejala` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengetahuan_ibfk_2` FOREIGN KEY (`status_gizi_id`) REFERENCES `status_gizi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rekomendasi`
--
ALTER TABLE `rekomendasi`
  ADD CONSTRAINT `rekomendasi_ibfk_1` FOREIGN KEY (`status_gizi_id`) REFERENCES `status_gizi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rekomendasi_ibfk_2` FOREIGN KEY (`kondisi_tambahan_id`) REFERENCES `kondisi_tambahan` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `riwayat_diagnosa`
--
ALTER TABLE `riwayat_diagnosa`
  ADD CONSTRAINT `riwayat_diagnosa_ibfk_1` FOREIGN KEY (`hasil_status_gizi_id`) REFERENCES `status_gizi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
