<?php
// Selalu mulai session di awal
session_start();

// Panggil file koneksi dan fungsi
require_once 'config/databases.php';

// =================================================================
// LANGKAH 1: KUMPULKAN SEMUA INPUT DARI FORM
// =================================================================
$input = $_POST;
$nama_lengkap = trim($input['nama_lengkap'] ?? '');
$gejala_pengguna = [];

if (empty($nama_lengkap)) {
    $_SESSION['error'] = 'Nama lengkap harus diisi!';
    header('Location: diagnosis.php');
    exit();
}

// =================================================================
// LANGKAH 2: TERJEMAHKAN INPUT MENJADI KODE GEJALA
// =================================================================

// 2a. Hitung IMT dan tentukan kode gejalanya
$berat_badan = (float) $input['berat_badan'];
$tinggi_badan_cm = (float) $input['tinggi_badan'];
$tinggi_badan_m = $tinggi_badan_cm / 100;
$imt = 0;
if ($tinggi_badan_m > 0) {
    $imt = $berat_badan / ($tinggi_badan_m * $tinggi_badan_m);
}

if ($imt < 18.5) {
    $gejala_pengguna[] = 'G01';
} elseif ($imt >= 18.5 && $imt <= 24.9) {
    $gejala_pengguna[] = 'G02';
} elseif ($imt >= 25.0 && $imt <= 29.9) {
    $gejala_pengguna[] = 'G03';
} else {
    $gejala_pengguna[] = 'G04';
}

// 2b. Ambil gejala dari input select/dropdown
$gejala_pengguna[] = $input['jenis_olahraga'];
$gejala_pengguna[] = $input['frekuensi_olahraga'];

// 2c. Terjemahkan kebiasaan makan dari checkbox
$kebiasaan_makan = $input['makan'] ?? [];
if (in_array('G15', $kebiasaan_makan)) $gejala_pengguna[] = 'G15';
if (in_array('G17', $kebiasaan_makan)) $gejala_pengguna[] = 'G17';
if (in_array('G18_setiap_hari', $kebiasaan_makan) || in_array('G18_3-6', $kebiasaan_makan) || in_array('G18_1-2', $kebiasaan_makan)) $gejala_pengguna[] = 'G18';
if (in_array('G19_setiap_hari', $kebiasaan_makan) || in_array('G19_3-6', $kebiasaan_makan) || in_array('G19_1-2', $kebiasaan_makan)) $gejala_pengguna[] = 'G19';

// 2d. Ambil riwayat penyakit dan simpan ID kondisi tambahan
$riwayat_penyakit = $input['penyakit'] ?? [];
$kondisi_tambahan_ids = [];

// Mapping kode gejala ke nama kondisi di database
$mapping_kondisi = [
    'G21' => 'Diabetes',
    'G22' => 'Hipertensi',
    'G23' => 'Kolesterol Tinggi',
    'G24' => 'Penyakit Pencernaan (Maag)'
];

foreach ($riwayat_penyakit as $penyakit) {
    $gejala_pengguna[] = $penyakit;

    // Jika bukan G20 (tidak ada penyakit), ambil ID kondisi tambahan
    if ($penyakit != 'G20' && isset($mapping_kondisi[$penyakit])) {
        $stmt = $pdo->prepare("SELECT id FROM kondisi_tambahan WHERE nama_kondisi = ?");
        $stmt->execute([$mapping_kondisi[$penyakit]]);
        $kondisi_id = $stmt->fetchColumn();
        if ($kondisi_id) {
            $kondisi_tambahan_ids[] = $kondisi_id;
        }
    }
}

// Hilangkan duplikat dan pastikan tidak ada nilai kosong
$gejala_pengguna = array_unique(array_filter($gejala_pengguna));

// =================================================================
// LANGKAH 3: AMBIL ATURAN (RULES) DARI DATABASE
// =================================================================
$placeholders = implode(',', array_fill(0, count($gejala_pengguna), '?'));

$stmt = $pdo->prepare("
    SELECT p.cf_pakar, s.id as status_id, s.nama_status
    FROM pengetahuan p
    JOIN gejala g ON p.gejala_id = g.id
    JOIN status_gizi s ON p.status_gizi_id = s.id
    WHERE g.kode_gejala IN ($placeholders)
");
$stmt->execute($gejala_pengguna);
$rules = $stmt->fetchAll();

// =================================================================
// LANGKAH 4: HITUNG CERTAINTY FACTOR (CF)
// =================================================================

function CFcombine($cf1, $cf2)
{
    if ($cf1 >= 0 && $cf2 >= 0) {
        return $cf1 + $cf2 * (1 - $cf1);
    } elseif ($cf1 <= 0 && $cf2 <= 0) {
        return $cf1 + $cf2 * (1 + $cf1);
    } else {
        $numerator = $cf1 + $cf2;
        $denominator = 1 - min(abs($cf1), abs($cf2));
        if ($denominator == 0) {
            return 0;
        }
        return $numerator / $denominator;
    }
}

$cf_groups = [];
foreach ($rules as $rule) {
    $cf_groups[$rule['nama_status']]['id'] = $rule['status_id'];
    $cf_groups[$rule['nama_status']]['values'][] = (float) $rule['cf_pakar'];
}

$hasil_cf = [];
foreach ($cf_groups as $status => $group) {
    $cf_values = $group['values'];

    if (count($cf_values) == 1) {
        $cf_gabungan = $cf_values[0];
    } else {
        $cf_gabungan = array_shift($cf_values);
        foreach ($cf_values as $cf_value) {
            $cf_gabungan = CFcombine($cf_gabungan, $cf_value);
        }
    }

    $hasil_cf[$status] = [
        'id' => $group['id'],
        'cf' => $cf_gabungan
    ];
}

uasort($hasil_cf, function ($a, $b) {
    return $b['cf'] <=> $a['cf'];
});

// =================================================================
// LANGKAH 5: TENTUKAN HASIL AKHIR
// =================================================================
$hasil_akhir_status = key($hasil_cf);
$hasil_akhir_cf = current($hasil_cf)['cf'];
$hasil_akhir_id = current($hasil_cf)['id'];

// =================================================================
// LANGKAH 6: AMBIL REKOMENDASI DARI DATABASE
// =================================================================

$rekomendasi_lengkap = "";
$rekomendasi_list = [];

// Ambil rekomendasi umum (tanpa kondisi tambahan)
$stmt = $pdo->prepare("
    SELECT saran 
    FROM rekomendasi 
    WHERE status_gizi_id = ? AND kondisi_tambahan_id IS NULL
");
$stmt->execute([$hasil_akhir_id]);
$rekomendasi_umum = $stmt->fetchColumn();

if ($rekomendasi_umum) {
    $rekomendasi_list[] = [
        'judul' => 'Rekomendasi Umum',
        'isi' => $rekomendasi_umum
    ];
}

// Ambil rekomendasi spesifik berdasarkan kondisi tambahan
if (!empty($kondisi_tambahan_ids)) {
    foreach ($kondisi_tambahan_ids as $kondisi_id) {
        $stmt = $pdo->prepare("
            SELECT r.saran, kt.nama_kondisi
            FROM rekomendasi r
            JOIN kondisi_tambahan kt ON r.kondisi_tambahan_id = kt.id
            WHERE r.status_gizi_id = ? AND r.kondisi_tambahan_id = ?
        ");
        $stmt->execute([$hasil_akhir_id, $kondisi_id]);
        $rek_spesifik = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rek_spesifik) {
            $rekomendasi_list[] = [
                'judul' => 'Rekomendasi untuk ' . $rek_spesifik['nama_kondisi'],
                'isi' => $rek_spesifik['saran']
            ];
        }
    }
}

// Format rekomendasi untuk disimpan di database
foreach ($rekomendasi_list as $rek) {
    $rekomendasi_lengkap .= "=== " . strtoupper($rek['judul']) . " ===\n";
    $rekomendasi_lengkap .= $rek['isi'] . "\n\n";
}

// =================================================================
// LANGKAH 7: SIMPAN HASIL KE RIWAYAT
// =================================================================
try {
    $stmt = $pdo->prepare("
        INSERT INTO riwayat_diagnosa (nama_lengkap, hasil_status_gizi_id, hasil_cf, rekomendasi_diberikan) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$nama_lengkap, $hasil_akhir_id, $hasil_akhir_cf, $rekomendasi_lengkap]);
} catch (PDOException $e) {
    // Log error jika diperlukan
    error_log("Error menyimpan riwayat: " . $e->getMessage());
}

// =================================================================
// LANGKAH 8: SIMPAN HASIL KE SESSION & REDIRECT
// =================================================================
$_SESSION['hasil_diagnosa'] = [
    'nama_lengkap' => $nama_lengkap,
    'berat_badan' => $berat_badan,
    'imt' => $imt,
    'tinggi_badan' => $tinggi_badan_cm,
    'status' => $hasil_akhir_status,
    'cf' => $hasil_akhir_cf,
    'rekomendasi_list' => $rekomendasi_list, // Array rekomendasi terstruktur
    'rekomendasi' => $rekomendasi_lengkap,  // String untuk disimpan
];

header('Location: hasil_diagnosis.php');
exit();
