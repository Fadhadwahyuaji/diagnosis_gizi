<?php
// filepath: d:\PROYEK\diagnosis_gizi\proses_diagnosis.php
session_start();
require_once 'config/databases.php';

// =================================================================
// FUNGSI-FUNGSI CF SESUAI DOKUMEN
// =================================================================

/**
 * Interpretasi CF User berdasarkan Tabel 3.11 dokumen
 */
function getCFUser($tingkat_keyakinan = 'yakin')
{
    $cf_mapping = [
        'sangat_yakin' => 1.0,   // Pasti/Sangat Yakin
        'yakin' => 0.6,           // Yakin
        'kurang_yakin' => 0.2     // Kurang Yakin
    ];

    return $cf_mapping[$tingkat_keyakinan] ?? 1.0;
}

/**
 * Kombinasi CF sesuai rumus dokumen
 * Kasus A (kedua positif): CF12 = CF1 + CF2 * (1 - CF1)
 */
function CFcombine($cf1, $cf2)
{
    if ($cf1 >= 0 && $cf2 >= 0) {
        return $cf1 + $cf2 * (1 - $cf1);
    }

    if ($cf1 < 0 && $cf2 < 0) {
        return $cf1 + $cf2 * (1 + $cf1);
    }

    $numerator = $cf1 + $cf2;
    $denominator = 1 - min(abs($cf1), abs($cf2));

    return ($denominator == 0) ? 0 : $numerator / $denominator;
}

/**
 * Hitung CF(H,E) = CF(E) * CF(H)
 */
function hitungCFHE($cf_pakar, $cf_user)
{
    return $cf_pakar * $cf_user;
}

// =================================================================
// VALIDASI INPUT
// =================================================================
$input = $_POST;
$nama_lengkap = trim($input['nama_lengkap'] ?? '');

if (empty($nama_lengkap)) {
    $_SESSION['error'] = 'Nama lengkap harus diisi!';
    header('Location: diagnosis.php');
    exit();
}

$berat_badan = (float) ($input['berat_badan'] ?? 0);
$tinggi_badan_cm = (float) ($input['tinggi_badan'] ?? 0);

if ($berat_badan <= 0 || $tinggi_badan_cm <= 0) {
    $_SESSION['error'] = 'Berat badan dan tinggi badan harus valid!';
    header('Location: diagnosis.php');
    exit();
}

// =================================================================
// KUMPULKAN GEJALA YANG DIALAMI USER
// =================================================================
$gejala_pengguna = [];
$gejala_detail = [];

// 1. HITUNG IMT (G01-G04)
$tinggi_badan_m = $tinggi_badan_cm / 100;
$imt = $berat_badan / ($tinggi_badan_m * $tinggi_badan_m);

if ($imt < 18.5) {
    $gejala_pengguna[] = ['kode' => 'G01', 'keyakinan' => 'sangat_yakin'];
    $gejala_detail[] = 'IMT < 18.5 (Underweight)';
} elseif ($imt >= 18.5 && $imt < 25.0) {
    $gejala_pengguna[] = ['kode' => 'G02', 'keyakinan' => 'sangat_yakin'];
    $gejala_detail[] = 'IMT 18.5-24.9 (Normal)';
} elseif ($imt >= 25.0 && $imt < 30.0) {
    $gejala_pengguna[] = ['kode' => 'G03', 'keyakinan' => 'sangat_yakin'];
    $gejala_detail[] = 'IMT 25.0-29.9 (Gizi Lebih)';
} else {
    $gejala_pengguna[] = ['kode' => 'G04', 'keyakinan' => 'sangat_yakin'];
    $gejala_detail[] = 'IMT ≥ 30.0 (Obesitas)';
}

// 2. FREKUENSI OLAHRAGA (G05-G09)
$frekuensi = $input['frekuensi_olahraga'] ?? '';
if ($frekuensi) {
    $gejala_pengguna[] = ['kode' => $frekuensi, 'keyakinan' => 'yakin'];
    $stmt = $pdo->prepare("SELECT nama_gejala FROM gejala WHERE kode_gejala = ?");
    $stmt->execute([$frekuensi]);
    $gejala_detail[] = $stmt->fetchColumn();
}

// 3. JENIS OLAHRAGA (G10-G13)
$jenis = $input['jenis_olahraga'] ?? '';
if ($jenis) {
    $gejala_pengguna[] = ['kode' => $jenis, 'keyakinan' => 'yakin'];
    $stmt = $pdo->prepare("SELECT nama_gejala FROM gejala WHERE kode_gejala = ?");
    $stmt->execute([$jenis]);
    $gejala_detail[] = $stmt->fetchColumn();
}

// 4. KEBIASAAN MAKAN (G14-G19)
$kebiasaan_makan = $input['makan'] ?? [];

if (in_array('buah_setiap_hari', $kebiasaan_makan)) {
    $gejala_pengguna[] = ['kode' => 'G14', 'keyakinan' => 'yakin'];
    $gejala_detail[] = 'Sering makan buah (≥2 porsi/hari)';
}

if (in_array('buah_jarang', $kebiasaan_makan)) {
    $gejala_pengguna[] = ['kode' => 'G15', 'keyakinan' => 'yakin'];
    $gejala_detail[] = 'Jarang makan buah (<3 kali/minggu)';
}

if (in_array('sayur_setiap_hari', $kebiasaan_makan)) {
    $gejala_pengguna[] = ['kode' => 'G16', 'keyakinan' => 'yakin'];
    $gejala_detail[] = 'Sering makan sayur (>3 porsi/hari)';
}

if (in_array('sayur_jarang', $kebiasaan_makan)) {
    $gejala_pengguna[] = ['kode' => 'G17', 'keyakinan' => 'yakin'];
    $gejala_detail[] = 'Jarang makan sayur (<3 kali/minggu)';
}

if (in_array('fastfood_sering', $kebiasaan_makan)) {
    $gejala_pengguna[] = ['kode' => 'G18', 'keyakinan' => 'yakin'];
    $gejala_detail[] = 'Sering fast food (>3x/minggu)';
}

if (in_array('minuman_manis_sering', $kebiasaan_makan)) {
    $gejala_pengguna[] = ['kode' => 'G19', 'keyakinan' => 'yakin'];
    $gejala_detail[] = 'Sering minuman manis (>1x/hari)';
}

// 5. RIWAYAT PENYAKIT (G20-G24)
$riwayat_penyakit = $input['penyakit'] ?? [];
$kondisi_tambahan_ids = [];

$mapping_kondisi = [
    'G21' => 'Diabetes',
    'G22' => 'Hipertensi',
    'G23' => 'Kolesterol Tinggi',
    'G24' => 'Penyakit Pencernaan (Maag)'
];

foreach ($riwayat_penyakit as $penyakit) {
    $gejala_pengguna[] = ['kode' => $penyakit, 'keyakinan' => 'sangat_yakin'];

    $stmt = $pdo->prepare("SELECT nama_gejala FROM gejala WHERE kode_gejala = ?");
    $stmt->execute([$penyakit]);
    $gejala_detail[] = $stmt->fetchColumn();

    if ($penyakit != 'G20' && isset($mapping_kondisi[$penyakit])) {
        $stmt = $pdo->prepare("SELECT id FROM kondisi_tambahan WHERE nama_kondisi = ?");
        $stmt->execute([$mapping_kondisi[$penyakit]]);
        $kondisi_id = $stmt->fetchColumn();
        if ($kondisi_id) {
            $kondisi_tambahan_ids[] = $kondisi_id;
        }
    }
}

// =================================================================
// AMBIL RULES DARI DATABASE
// =================================================================
if (empty($gejala_pengguna)) {
    $_SESSION['error'] = 'Tidak ada gejala yang terdeteksi!';
    header('Location: diagnosis.php');
    exit();
}

$kode_gejala = array_column($gejala_pengguna, 'kode');
$placeholders = implode(',', array_fill(0, count($kode_gejala), '?'));

$stmt = $pdo->prepare("
    SELECT g.kode_gejala, g.nama_gejala, p.cf_pakar, 
           s.id as status_id, s.nama_status, s.kode_status
    FROM pengetahuan p
    JOIN gejala g ON p.gejala_id = g.id
    JOIN status_gizi s ON p.status_gizi_id = s.id
    WHERE g.kode_gejala IN ($placeholders)
    ORDER BY s.kode_status ASC
");
$stmt->execute($kode_gejala);
$rules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// =================================================================
// HITUNG CF UNTUK SETIAP HIPOTESIS (S1, S2, S3, S4)
// =================================================================

$keyakinan_map = [];
foreach ($gejala_pengguna as $gejala) {
    $keyakinan_map[$gejala['kode']] = $gejala['keyakinan'];
}

$cf_groups = [];
$detail_perhitungan = [];

foreach ($rules as $rule) {
    $status = $rule['nama_status'];
    $kode = $rule['kode_gejala'];
    $cf_pakar = (float) $rule['cf_pakar'];

    $tingkat_keyakinan = $keyakinan_map[$kode] ?? 'yakin';
    $cf_user = getCFUser($tingkat_keyakinan);

    // CF(H,E) = CF(E) * CF(H)
    $cf_he = hitungCFHE($cf_pakar, $cf_user);

    if (!isset($cf_groups[$status])) {
        $cf_groups[$status] = [
            'id' => $rule['status_id'],
            'kode' => $rule['kode_status'],
            'values' => [],
            'details' => []
        ];
    }

    $cf_groups[$status]['values'][] = $cf_he;
    $cf_groups[$status]['details'][] = [
        'gejala' => $rule['nama_gejala'],
        'kode_gejala' => $kode,
        'cf_pakar' => $cf_pakar,
        'cf_user' => $cf_user,
        'cf_he' => $cf_he
    ];
}

// Kombinasi CF untuk setiap status
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
        'kode' => $group['kode'],
        'cf' => $cf_gabungan,
        'details' => $group['details']
    ];

    $detail_perhitungan[$status] = [
        'cf_final' => $cf_gabungan,
        'persentase' => $cf_gabungan * 100,
        'gejala_count' => count($group['details']),
        'details' => $group['details']
    ];
}

// Urutkan CF dari tertinggi
uasort($hasil_cf, function ($a, $b) {
    return $b['cf'] <=> $a['cf'];
});

// =================================================================
// TENTUKAN HASIL AKHIR
// =================================================================
$hasil_akhir_status = key($hasil_cf);
$hasil_akhir_data = current($hasil_cf);
$hasil_akhir_cf = $hasil_akhir_data['cf'];
$hasil_akhir_id = $hasil_akhir_data['id'];

// =================================================================
// AMBIL REKOMENDASI
// =================================================================
$rekomendasi_list = [];

// Rekomendasi umum
$stmt = $pdo->prepare("
    SELECT saran 
    FROM rekomendasi 
    WHERE status_gizi_id = ? AND kondisi_tambahan_id IS NULL
");
$stmt->execute([$hasil_akhir_id]);
$rekomendasi_umum = $stmt->fetchColumn();

if ($rekomendasi_umum) {
    $rekomendasi_list[] = [
        'judul' => 'Rekomendasi Umum - ' . $hasil_akhir_status,
        'isi' => $rekomendasi_umum,
        'icon' => 'clipboard-heart'
    ];
}

// Rekomendasi spesifik
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
                'judul' => 'Khusus: ' . $rek_spesifik['nama_kondisi'],
                'isi' => $rek_spesifik['saran'],
                'icon' => 'exclamation-triangle-fill'
            ];
        }
    }
}

$rekomendasi_lengkap = "";
foreach ($rekomendasi_list as $rek) {
    $rekomendasi_lengkap .= "=== " . strtoupper($rek['judul']) . " ===\n";
    $rekomendasi_lengkap .= $rek['isi'] . "\n\n";
}

// =================================================================
// SIMPAN KE DATABASE
// =================================================================
try {
    $stmt = $pdo->prepare("
        INSERT INTO riwayat_diagnosa (nama_lengkap, hasil_status_gizi_id, hasil_cf, rekomendasi_diberikan) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$nama_lengkap, $hasil_akhir_id, $hasil_akhir_cf, $rekomendasi_lengkap]);
} catch (PDOException $e) {
    error_log("Error: " . $e->getMessage());
}

// =================================================================
// KIRIM KE HALAMAN HASIL
// =================================================================
$_SESSION['hasil_diagnosa'] = [
    'nama_lengkap' => $nama_lengkap,
    'berat_badan' => $berat_badan,
    'tinggi_badan' => $tinggi_badan_cm,
    'imt' => $imt,
    'status' => $hasil_akhir_status,
    'cf' => $hasil_akhir_cf,
    'gejala_terdeteksi' => $gejala_detail,
    'rekomendasi_list' => $rekomendasi_list,
    'detail_perhitungan' => $detail_perhitungan,
    'semua_hasil_cf' => $hasil_cf
];

header('Location: hasil_diagnosis.php');
exit();
