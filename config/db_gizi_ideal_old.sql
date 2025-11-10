-- Membuat Tabel: admin
-- Tabel ini untuk menyimpan data login admin.
CREATE TABLE `admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Membuat Tabel: gejala
-- Tabel ini berisi semua kemungkinan gejala atau parameter input.
CREATE TABLE `gejala` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `kode_gejala` VARCHAR(5) NOT NULL,
  `nama_gejala` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_gejala` (`kode_gejala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- Membuat Tabel: pengetahuan
-- Tabel ini adalah inti dari sistem pakar, berisi aturan (rules).
CREATE TABLE `pengetahuan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `gejala_id` INT(11) NOT NULL,
  `status_gizi_id` INT(11) NOT NULL,
  `cf_pakar` DECIMAL(3, 2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gejala_id` (`gejala_id`),
  KEY `status_gizi_id` (`status_gizi_id`),
  CONSTRAINT `pengetahuan_ibfk_1` FOREIGN KEY (`gejala_id`) REFERENCES `gejala` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pengetahuan_ibfk_2` FOREIGN KEY (`status_gizi_id`) REFERENCES `status_gizi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Membuat Tabel: status_gizi
-- Tabel ini berisi kategori hasil diagnosis.
CREATE TABLE `status_gizi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `kode_status` VARCHAR(5) NOT NULL,
  `nama_status` VARCHAR(100) NOT NULL,
  `deskripsi` TEXT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_status` (`kode_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `kondisi_tambahan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_kondisi` VARCHAR(100) NOT NULL COMMENT 'Nama penyakit/kondisi',
  `keterangan` TEXT DEFAULT NULL COMMENT 'Deskripsi tambahan tentang kondisi',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Membuat Tabel: rekomendasi
-- Tabel ini menyimpan teks rekomendasi untuk setiap status gizi.
CREATE TABLE `rekomendasi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `status_gizi_id` INT(11) NOT NULL COMMENT 'FK ke status gizi (wajib)',
  `kondisi_tambahan_id` INT(11) DEFAULT NULL COMMENT 'FK ke kondisi tambahan (opsional)',
  `saran` TEXT NOT NULL COMMENT 'Teks saran atau rekomendasi',
  PRIMARY KEY (`id`),
  KEY `status_gizi_id` (`status_gizi_id`),
  KEY `kondisi_tambahan_id` (`kondisi_tambahan_id`),
  CONSTRAINT `rekomendasi_ibfk_1` FOREIGN KEY (`status_gizi_id`) REFERENCES `status_gizi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rekomendasi_ibfk_2` FOREIGN KEY (`kondisi_tambahan_id`) REFERENCES `kondisi_tambahan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `kondisi_tambahan` (`nama_kondisi`, `keterangan`) VALUES
('Diabetes', 'Pernah/sedang mengalami diabetes mellitus, Penyakit metabolik dengan kadar gula darah tinggi'),
('Hipertensi', 'Tekanan darah tinggi ≥140/90 mmHg'),
('Kolesterol Tinggi', 'Riwayat kolesterol melebihi batas normal, Kadar kolesterol total >200 mg/dL'),
('Penyakit Pencernaan (Maag)', 'Gangguan pada lambung/sistem pencernaan');


-- Membuat Tabel: riwayat_diagnosa
-- Tabel ini akan merekam setiap hasil diagnosis dari pengguna.
CREATE TABLE `riwayat_diagnosa` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `waktu` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hasil_status_gizi_id` INT(11) NOT NULL,
  `hasil_cf` DECIMAL(5, 4) NOT NULL,
  `rekomendasi_diberikan` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hasil_status_gizi_id` (`hasil_status_gizi_id`),
  CONSTRAINT `riwayat_diagnosa_ibfk_1` FOREIGN KEY (`hasil_status_gizi_id`) REFERENCES `status_gizi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `informasi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `judul` VARCHAR(255) NOT NULL,
  `konten` TEXT NOT NULL,
  `urutan` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan kolom nama_lengkap pada tabel riwayat_diagnosa
ALTER TABLE `riwayat_diagnosa` 
ADD COLUMN `nama_lengkap` VARCHAR(100) NOT NULL AFTER `waktu`;

-- Mengisi Tabel: status_gizi
INSERT INTO `status_gizi` (`id`, `kode_status`, `nama_status`, `deskripsi`) VALUES
(1, 'S1', 'Gizi Kurang', 'IMT < 18,5'),
(2, 'S2', 'Normal', 'IMT 18,5 - 24,9'),
(3, 'S3', 'Gizi Lebih', 'IMT 25,0 - 29,9'),
(4, 'S4', 'Obesitas', 'IMT >= 30,0');

-- Mengisi Tabel: gejala
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


-- Mengisi Tabel: rekomendasi
-- Mengisi Tabel: rekomendasi
-- Rekomendasi umum berdasarkan status gizi saja (tanpa kondisi tambahan)
INSERT INTO `rekomendasi` (`status_gizi_id`, `kondisi_tambahan_id`, `saran`) VALUES
-- Gizi Kurang (S1) - Rekomendasi Umum
((SELECT id FROM status_gizi WHERE kode_status = 'S1'), NULL, 'Tingkatkan asupan bergizi: cukup protein, lemak sehat, karbohidrat seimbang, serat, vitamin, dan mineral.'),

-- Normal (S2) - Rekomendasi Umum
((SELECT id FROM status_gizi WHERE kode_status = 'S2'), NULL, 'Pertahankan asupan gizi seimbang, lakukan olahraga rutin, penuhi kebutuhan cairan, istirahat yang cukup, dan kelola stres dengan baik.'),

-- Gizi Lebih (S3) - Rekomendasi Umum
((SELECT id FROM status_gizi WHERE kode_status = 'S3'), NULL, 'Batasi asupan kalori berlebih, cukupi kebutuhan protein, konsumsi makanan rendah lemak, cukup karbohidrat kompleks, serta kurangi asupan gula, garam, dan minyak.'),

-- Obesitas (S4) - Rekomendasi Umum
((SELECT id FROM status_gizi WHERE kode_status = 'S4'), NULL, 'Terapkan diet rendah lemak, gula, dan garam. Batasi asupan karbohidrat, penuhi kebutuhan protein dan lemak sehat, serta perbanyak konsumsi serat, cairan, vitamin dan mineral.'),

-- ========== REKOMENDASI DENGAN KONDISI TAMBAHAN ==========

-- Gizi Kurang + Diabetes
((SELECT id FROM status_gizi WHERE kode_status = 'S1'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Diabetes'), 'Tingkatkan berat badan secara bertahap dengan pola makan teratur. Pilih karbohidrat kompleks (nasi merah, oatmeal), protein tanpa lemak, dan lemak sehat. Konsumsi makanan porsi kecil tapi sering (5-6 kali/hari). Hindari gula sederhana dan pantau kadar gula darah secara rutin.'),

-- Gizi Kurang + Hipertensi
((SELECT id FROM status_gizi WHERE kode_status = 'S1'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Hipertensi'), 'Tingkatkan asupan kalori dengan makanan bergizi tinggi namun rendah garam (maksimal 5 gram/hari). Perbanyak konsumsi buah, sayuran, protein tanpa lemak, dan lemak sehat. Hindari makanan olahan dan makanan kalengan yang tinggi natrium.'),

-- Gizi Kurang + Kolesterol Tinggi
((SELECT id FROM status_gizi WHERE kode_status = 'S1'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Kolesterol Tinggi'), 'Tingkatkan berat badan dengan memilih lemak sehat (omega-3, minyak zaitun, alpukat, kacang-kacangan). Hindari lemak trans dan lemak jenuh. Perbanyak serat larut dari oatmeal, buah-buahan, dan sayuran. Konsumsi ikan 2-3 kali seminggu.'),

-- Gizi Kurang + Penyakit Pencernaan (Maag)
((SELECT id FROM status_gizi WHERE kode_status = 'S1'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Penyakit Pencernaan (Maag)'), 'Makan dalam porsi kecil tapi sering (5-6 kali/hari). Pilih makanan lunak dan mudah dicerna. Hindari makanan pedas, asam, berlemak, dan bersantan. Kunyah makanan dengan baik. Hindari makan 2-3 jam sebelum tidur.'),

-- Normal + Diabetes
((SELECT id FROM status_gizi WHERE kode_status = 'S2'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Diabetes'), 'Pertahankan berat badan ideal dengan pola makan teratur 3 kali sehari + 2-3 snack sehat. Pilih karbohidrat kompleks dengan indeks glikemik rendah. Batasi konsumsi gula dan makanan manis. Olahraga teratur 150 menit/minggu. Pantau gula darah secara berkala.'),

-- Normal + Hipertensi
((SELECT id FROM status_gizi WHERE kode_status = 'S2'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Hipertensi'), 'Pertahankan berat badan dengan diet DASH (Dietary Approaches to Stop Hypertension). Batasi natrium maksimal 5 gram/hari. Perbanyak kalium dari pisang, jeruk, bayam. Kurangi kafein dan alkohol. Olahraga aerobik rutin 30 menit/hari.'),

-- Normal + Kolesterol Tinggi
((SELECT id FROM status_gizi WHERE kode_status = 'S2'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Kolesterol Tinggi'), 'Pertahankan berat badan ideal dengan diet rendah lemak jenuh dan kolesterol. Perbanyak serat larut (minimal 25-30 gram/hari). Konsumsi ikan berlemak, kacang-kacangan, dan minyak zaitun. Hindari gorengan dan makanan berlemak tinggi. Olahraga teratur untuk meningkatkan HDL.'),

-- Normal + Penyakit Pencernaan (Maag)
((SELECT id FROM status_gizi WHERE kode_status = 'S2'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Penyakit Pencernaan (Maag)'), 'Pertahankan pola makan teratur dengan porsi sedang. Hindari makanan pemicu maag (pedas, asam, kafein berlebih). Makan perlahan dan kunyah dengan baik. Hindari stres dan makan terburu-buru. Jaga jarak waktu makan dengan tidur minimal 2-3 jam.'),

-- Gizi Lebih + Diabetes
((SELECT id FROM status_gizi WHERE kode_status = 'S3'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Diabetes'), 'Turunkan berat badan 5-10% secara bertahap (0.5-1 kg/minggu). Terapkan diet rendah kalori dan karbohidrat dengan indeks glikemik rendah. Perbanyak serat dan protein. Olahraga kombinasi aerobik dan resistance 150-300 menit/minggu. Monitor gula darah secara teratur.'),

-- Gizi Lebih + Hipertensi
((SELECT id FROM status_gizi WHERE kode_status = 'S3'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Hipertensi'), 'Turunkan berat badan bertahap untuk menurunkan tekanan darah. Terapkan diet DASH dengan kalori terkontrol. Batasi natrium <5 gram/hari. Perbanyak kalium, magnesium, dan kalsium. Hindari lemak jenuh. Olahraga aerobik teratur minimal 150 menit/minggu.'),

-- Gizi Lebih + Kolesterol Tinggi
((SELECT id FROM status_gizi WHERE kode_status = 'S3'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Kolesterol Tinggi'), 'Turunkan berat badan untuk memperbaiki profil lipid. Diet rendah lemak jenuh (<7% total kalori) dan kolesterol (<200 mg/hari). Tingkatkan serat larut 10-25 gram/hari. Konsumsi sterol/stanol nabati 2 gram/hari. Olahraga teratur untuk meningkatkan HDL.'),

-- Gizi Lebih + Penyakit Pencernaan (Maag)
((SELECT id FROM status_gizi WHERE kode_status = 'S3'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Penyakit Pencernaan (Maag)'), 'Turunkan berat badan dengan porsi terkontrol namun tetap makan teratur. Hindari diet ketat yang memicu asam lambung. Pilih makanan rendah lemak dan mudah dicerna. Hindari makanan pedas, asam, dan bersantan. Makan porsi kecil tapi sering.'),

-- Obesitas + Diabetes
((SELECT id FROM status_gizi WHERE kode_status = 'S4'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Diabetes'), 'Program penurunan berat badan intensif (target 7-10%). Diet rendah kalori (defisit 500-750 kkal/hari) dengan karbohidrat kompleks rendah GI. Perbanyak protein dan serat. Olahraga kombinasi aerobik dan resistance minimal 300 menit/minggu. Monitoring gula darah ketat. Konsultasi dengan dokter dan ahli gizi.'),

-- Obesitas + Hipertensi
((SELECT id FROM status_gizi WHERE kode_status = 'S4'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Hipertensi'), 'Penurunan berat badan prioritas utama (10% dalam 6 bulan). Diet DASH dengan kalori terkontrol. Natrium maksimal 5 gram/hari. Perbanyak buah, sayur, whole grain. Hindari lemak jenuh dan trans. Olahraga teratur 300 menit/minggu. Kelola stres dengan baik.'),

-- Obesitas + Kolesterol Tinggi
((SELECT id FROM status_gizi WHERE kode_status = 'S4'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Kolesterol Tinggi'), 'Penurunan berat badan untuk memperbaiki profil lipid. Diet rendah lemak jenuh dan kolesterol. Tingkatkan serat 25-35 gram/hari. Konsumsi lemak tak jenuh (omega-3, minyak zaitun). Hindari makanan tinggi kolesterol (jeroan, kuning telur berlebih). Olahraga intensitas sedang-tinggi minimal 300 menit/minggu.'),

-- Obesitas + Penyakit Pencernaan (Maag)
((SELECT id FROM status_gizi WHERE kode_status = 'S4'), (SELECT id FROM kondisi_tambahan WHERE nama_kondisi = 'Penyakit Pencernaan (Maag)'), 'Turunkan berat badan bertahap dengan pola makan teratur. Hindari diet ekstrem yang memicu asam lambung. Makan porsi kecil tapi sering (6-7 kali/hari). Pilih makanan rendah lemak dan mudah dicerna. Hindari makanan pedas, asam, gorengan, dan bersantan. Jangan berbaring setelah makan.');

-- Mengisi Tabel: pengetahuan
-- Tabel ini berisi aturan (rules) berdasarkan data dari Tabel 3.12 Nilai Hasil Terminasi Pakar.
INSERT INTO `pengetahuan` (`gejala_id`, `status_gizi_id`, `cf_pakar`) VALUES
((SELECT id FROM gejala WHERE kode_gejala = 'G01'), (SELECT id FROM status_gizi WHERE kode_status = 'S1'), 1.0),
((SELECT id FROM gejala WHERE kode_gejala = 'G02'), (SELECT id FROM status_gizi WHERE kode_status = 'S2'), 1.0),
((SELECT id FROM gejala WHERE kode_gejala = 'G03'), (SELECT id FROM status_gizi WHERE kode_status = 'S3'), 1.0),
((SELECT id FROM gejala WHERE kode_gejala = 'G04'), (SELECT id FROM status_gizi WHERE kode_status = 'S4'), 1.0),
((SELECT id FROM gejala WHERE kode_gejala = 'G05'), (SELECT id FROM status_gizi WHERE kode_status = 'S4'), 0.9),
((SELECT id FROM gejala WHERE kode_gejala = 'G06'), (SELECT id FROM status_gizi WHERE kode_status = 'S4'), 0.9),
((SELECT id FROM gejala WHERE kode_gejala = 'G09'), (SELECT id FROM status_gizi WHERE kode_status = 'S2'), 0.9),
((SELECT id FROM gejala WHERE kode_gejala = 'G14'), (SELECT id FROM status_gizi WHERE kode_status = 'S2'), 0.5),
((SELECT id FROM gejala WHERE kode_gejala = 'G15'), (SELECT id FROM status_gizi WHERE kode_status = 'S1'), 0.6),
((SELECT id FROM gejala WHERE kode_gejala = 'G17'), (SELECT id FROM status_gizi WHERE kode_status = 'S1'), 0.6),
((SELECT id FROM gejala WHERE kode_gejala = 'G18'), (SELECT id FROM status_gizi WHERE kode_status = 'S4'), 0.9),
((SELECT id FROM gejala WHERE kode_gejala = 'G19'), (SELECT id FROM status_gizi WHERE kode_status = 'S4'), 0.8),
((SELECT id FROM gejala WHERE kode_gejala = 'G21'), (SELECT id FROM status_gizi WHERE kode_status = 'S4'), 1.0),
((SELECT id FROM gejala WHERE kode_gejala = 'G22'), (SELECT id FROM status_gizi WHERE kode_status = 'S4'), 0.9),
((SELECT id FROM gejala WHERE kode_gejala = 'G24'), (SELECT id FROM status_gizi WHERE kode_status = 'S1'), 0.4);