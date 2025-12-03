# 🏥 Sistem Pakar Penentuan Status Gizi Ideal

Sistem pakar berbasis web untuk menentukan status gizi ideal dan memberikan rekomendasi nutrisi seimbang untuk dewasa usia 19-25 tahun menggunakan metode **Certainty Factor (CF)**.

---

## 📋 Daftar Isi

- [Tentang Proyek](#-tentang-proyek)
- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Metode Certainty Factor](#-metode-certainty-factor)
- [Instalasi](#-instalasi)
- [Struktur Database](#-struktur-database)
- [Struktur Proyek](#-struktur-proyek)
- [Penjelasan File dan Fungsi](#-penjelasan-file-dan-fungsi)
- [Alur Kerja Sistem](#-alur-kerja-sistem)
- [Penggunaan](#-penggunaan)
- [Role dan Hak Akses](#-role-dan-hak-akses)
- [Troubleshooting](#-troubleshooting)

---

## 🎯 Tentang Proyek

Sistem Pakar Penentuan Status Gizi Ideal adalah aplikasi web yang dirancang untuk:

- Melakukan diagnosis status gizi berdasarkan data fisik dan kebiasaan pengguna
- Menggunakan metode **Certainty Factor (CF)** untuk menghitung tingkat keyakinan diagnosis
- Memberikan rekomendasi nutrisi yang dipersonalisasi
- Menyimpan riwayat diagnosis untuk tracking kesehatan
- Mengelola basis pengetahuan sistem pakar melalui panel admin

### Target Pengguna

- **Usia:** 19-25 tahun (dewasa muda)
- **Tujuan:** Mengetahui status gizi dan mendapatkan rekomendasi nutrisi seimbang

---

## ✨ Fitur Utama

### 🌐 Untuk Pengguna Umum (Public)

1. **Diagnosis Status Gizi**

   - Input data diri (nama, berat badan, tinggi badan)
   - Pilih aktivitas fisik dan olahraga
   - Input kebiasaan makan
   - Input riwayat penyakit

2. **Hasil Diagnosis**

   - Perhitungan IMT (Indeks Massa Tubuh)
   - Status gizi dengan tingkat keyakinan (CF)
   - Rekomendasi nutrisi umum dan khusus
   - Berat badan ideal

3. **Riwayat Diagnosa**

   - Melihat riwayat diagnosa sebelumnya (berbasis session)
   - Detail hasil diagnosa lengkap

4. **Informasi Kesehatan**
   - Artikel tentang gizi dan kesehatan
   - Tabel IMT standar WHO

### 👨‍💼 Untuk Admin

1. **Manajemen Data Gejala**
   - CRUD gejala (kode dan nama gejala)
2. **Manajemen Status Gizi**
   - CRUD status gizi (S1-S4)
3. **Manajemen Aturan (Rules)**
   - CRUD aturan pengetahuan (IF-THEN)
   - Setting nilai CF Pakar
4. **Manajemen Rekomendasi**
   - CRUD rekomendasi umum dan khusus
   - Rekomendasi berdasarkan kondisi tambahan (diabetes, hipertensi, dll)
5. **Riwayat Diagnosis**

   - Melihat semua riwayat diagnosa pengguna
   - Detail lengkap setiap diagnosis
   - Export data (opsional)

6. **Manajemen Informasi**
   - Edit konten halaman informasi

### 🔐 Untuk Super Admin

1. **Semua fitur Admin**
2. **Manajemen User**
   - CRUD akun admin
   - Pengaturan role (admin/superadmin)

---

## 🛠 Teknologi yang Digunakan

### Backend

- **PHP 8.x** - Server-side scripting
- **MySQL/MariaDB** - Database management
- **PDO** - Database abstraction layer

### Frontend

- **HTML5** - Struktur halaman
- **CSS3** - Styling
- **Bootstrap 5** - Framework CSS responsive
- **Bootstrap Icons** - Icon library
- **JavaScript** - Interaktivitas client-side

### Server

- **Apache** - Web server (via XAMPP/WAMP/LAMP)
- **Session PHP** - State management

---

## 🧮 Metode Certainty Factor

### Konsep Dasar

**Certainty Factor (CF)** adalah metode untuk mengukur kepastian terhadap suatu fakta atau aturan dalam sistem pakar. Nilai CF berkisar antara -1 hingga 1.

### Formula CF

#### 1. CF User (Tingkat Keyakinan Pengguna)

Berdasarkan Tabel 3.11:

```
- Sangat Yakin  = 1.0
- Yakin         = 0.6
- Kurang Yakin  = 0.2
```

#### 2. CF(H,E) = CF(E) × CF(H)

Dimana:

- **CF(H,E)**: Certainty Factor hipotesis H berdasarkan evidence E
- **CF(E)**: CF dari user (tingkat keyakinan user)
- **CF(H)**: CF dari pakar (nilai CF dalam database)

#### 3. Kombinasi CF (Multiple Evidence)

**Kasus A - Kedua positif:**

```
CF_combine = CF1 + CF2 × (1 - CF1)
```

**Kasus B - Kedua negatif:**

```
CF_combine = CF1 + CF2 × (1 + CF1)
```

**Kasus C - Satu positif, satu negatif:**

```
CF_combine = (CF1 + CF2) / (1 - min(|CF1|, |CF2|))
```

### Implementasi dalam Kode

File: `proses_diagnosis.php`

```php
/**
 * Interpretasi CF User
 */
function getCFUser($tingkat_keyakinan = 'yakin')
{
    $cf_mapping = [
        'sangat_yakin' => 1.0,
        'yakin' => 0.6,
        'kurang_yakin' => 0.2
    ];
    return $cf_mapping[$tingkat_keyakinan] ?? 1.0;
}

/**
 * Hitung CF(H,E)
 */
function hitungCFHE($cf_pakar, $cf_user)
{
    return $cf_pakar * $cf_user;
}

/**
 * Kombinasi CF
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
```

### Proses Perhitungan

1. **Pengumpulan Gejala**

   - Sistem mengumpulkan gejala berdasarkan input user
   - Setiap gejala memiliki tingkat keyakinan

2. **Pengambilan Rules**

   - Query database untuk mendapatkan aturan yang sesuai
   - Setiap aturan memiliki CF Pakar

3. **Hitung CF untuk Setiap Gejala**

   ```
   CF(H,E) = CF_pakar × CF_user
   ```

4. **Kombinasi CF untuk Setiap Status Gizi**

   - Jika hanya 1 gejala: CF_final = CF(H,E)
   - Jika > 1 gejala: Kombinasikan menggunakan CFcombine()

5. **Penentuan Hasil**
   - Pilih status gizi dengan CF tertinggi
   - CF × 100% = Persentase keyakinan

### Contoh Perhitungan

**Input User:**

- IMT = 22 (Normal) → G02, tingkat keyakinan: sangat_yakin
- Olahraga 3x/minggu → G07, tingkat keyakinan: yakin
- Jarang makan buah → G15, tingkat keyakinan: yakin

**Rules dari Database:**

- R2: IF G02 THEN S2 (CF_pakar = 1.0)
- R7: IF G07 THEN S2 (CF_pakar = 0.4)
- R15: IF G15 THEN S2 (CF_pakar = -0.2)

**Perhitungan:**

1. CF(H,E)₁ = 1.0 × 1.0 = 1.0
2. CF(H,E)₂ = 0.4 × 0.6 = 0.24
3. CF(H,E)₃ = -0.2 × 0.6 = -0.12

**Kombinasi:**

1. CF₁₂ = 1.0 + 0.24 × (1 - 1.0) = 1.0
2. CF_final = (1.0 + (-0.12)) / (1 - min(1.0, 0.12)) = 0.88 / 0.88 = 1.0

**Hasil:** Status Gizi Normal (S2) dengan CF = 1.0 atau 100%

---

## 📦 Instalasi

### Prasyarat

- **XAMPP/WAMP/LAMP** (PHP 8.x + MySQL)
- **Web Browser** (Chrome, Firefox, Edge, dll)
- **Text Editor** (VS Code, Sublime, dll) - opsional

### Langkah Instalasi

#### 1. Persiapan Server

```bash
# Install XAMPP dari https://www.apachefriends.org/
# Atau gunakan WAMP/LAMP sesuai OS

# Jalankan Apache dan MySQL
```

#### 2. Clone/Download Proyek

```bash
# Clone proyek ke folder htdocs (untuk XAMPP)
cd C:\xampp\htdocs\
# atau untuk Linux/Mac
cd /opt/lampp/htdocs/

# Clone repository (jika ada)
git clone [repository-url] diagnosis_gizi

# Atau extract file ZIP ke folder diagnosis_gizi
```

#### 3. Setup Database

**a. Buat Database**

```sql
-- Akses phpMyAdmin: http://localhost/phpmyadmin
-- Atau gunakan MySQL CLI

CREATE DATABASE db_diagnosis_gizi CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

**b. Import Database**

```bash
# Via phpMyAdmin:
# 1. Pilih database db_diagnosis_gizi
# 2. Tab Import
# 3. Pilih file db_gizi_ideal.sql
# 4. Klik Go

# Atau via MySQL CLI:
mysql -u root -p db_diagnosis_gizi < db_gizi_ideal.sql
```

#### 4. Konfigurasi Database

Edit file `config/databases.php`:

```php
<?php
$host = 'localhost';          // Sesuaikan jika berbeda
$db_name = 'db_diagnosis_gizi'; // Nama database
$username = 'root';           // Username MySQL
$password = '';               // Password MySQL (kosong untuk default XAMPP)
```

#### 5. Konfigurasi Base URL (Opsional)

File `config/app.php` sudah auto-detect, tapi bisa disesuaikan jika perlu:

```php
// Auto-detect akan mengenali folder proyek
// Biasanya: http://localhost/diagnosis_gizi
```

#### 6. Akses Aplikasi

**Untuk Pengguna Umum:**

```
http://localhost/diagnosis_gizi/
```

**Untuk Admin/Super Admin:**

```
http://localhost/diagnosis_gizi/dashboard.php

Kredensial Default:
- Username: admin
- Password: admin123
```

#### 7. Seed Admin (Opsional)

Jika perlu membuat admin baru:

```
http://localhost/diagnosis_gizi/auth/seed_admin.php
```

---

## 🗄 Struktur Database

### Tabel Utama

#### 1. `user`

Menyimpan data admin dan super admin

```sql
CREATE TABLE user (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'superadmin') DEFAULT 'admin'
);
```

#### 2. `gejala`

Menyimpan daftar gejala (G01-G24)

```sql
CREATE TABLE gejala (
  id INT PRIMARY KEY AUTO_INCREMENT,
  kode_gejala VARCHAR(5) NOT NULL,
  nama_gejala TEXT NOT NULL
);
```

**Kategori Gejala:**

- **G01-G04:** Status IMT
- **G05-G09:** Frekuensi olahraga
- **G10-G13:** Jenis olahraga
- **G14-G19:** Kebiasaan makan
- **G20-G24:** Riwayat penyakit

#### 3. `status_gizi`

Menyimpan status gizi (S1-S4)

```sql
CREATE TABLE status_gizi (
  id INT PRIMARY KEY AUTO_INCREMENT,
  kode_status VARCHAR(5) NOT NULL,
  nama_status VARCHAR(100) NOT NULL,
  keterangan TEXT
);
```

**Status Gizi:**

- **S1:** Gizi Kurang (Underweight)
- **S2:** Gizi Normal
- **S3:** Gizi Lebih (Overweight)
- **S4:** Obesitas

#### 4. `pengetahuan`

Menyimpan aturan IF-THEN dengan CF Pakar

```sql
CREATE TABLE pengetahuan (
  id INT PRIMARY KEY AUTO_INCREMENT,
  gejala_id INT,
  status_gizi_id INT,
  cf_pakar DECIMAL(3,2) NOT NULL,
  FOREIGN KEY (gejala_id) REFERENCES gejala(id),
  FOREIGN KEY (status_gizi_id) REFERENCES status_gizi(id)
);
```

**Contoh Rule:**

```
R1: IF G01 (IMT < 18.5) THEN S1 (Gizi Kurang) [CF = 1.0]
R2: IF G02 (IMT Normal) THEN S2 (Gizi Normal) [CF = 1.0]
```

#### 5. `kondisi_tambahan`

Penyakit/kondisi khusus

```sql
CREATE TABLE kondisi_tambahan (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nama_kondisi VARCHAR(100) NOT NULL,
  keterangan TEXT
);
```

**Kondisi:**

- Diabetes
- Hipertensi
- Kolesterol Tinggi
- Penyakit Pencernaan (Maag)

#### 6. `rekomendasi`

Saran nutrisi berdasarkan status gizi dan kondisi

```sql
CREATE TABLE rekomendasi (
  id INT PRIMARY KEY AUTO_INCREMENT,
  status_gizi_id INT,
  kondisi_tambahan_id INT NULL,
  saran TEXT NOT NULL,
  FOREIGN KEY (status_gizi_id) REFERENCES status_gizi(id),
  FOREIGN KEY (kondisi_tambahan_id) REFERENCES kondisi_tambahan(id)
);
```

#### 7. `riwayat_diagnosa`

Menyimpan hasil diagnosis

```sql
CREATE TABLE riwayat_diagnosa (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nama_lengkap VARCHAR(100) NOT NULL,
  berat_badan DECIMAL(5,2) NOT NULL,
  tinggi_badan DECIMAL(5,2) NOT NULL,
  imt DECIMAL(5,2) NOT NULL,
  data_gejala TEXT,
  hasil_status_gizi_id INT,
  hasil_cf DECIMAL(4,3) NOT NULL,
  rekomendasi_diberikan TEXT,
  waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (hasil_status_gizi_id) REFERENCES status_gizi(id)
);
```

#### 8. `halaman_informasi`

Konten informasi/artikel

```sql
CREATE TABLE halaman_informasi (
  id INT PRIMARY KEY AUTO_INCREMENT,
  judul VARCHAR(255) NOT NULL,
  konten TEXT NOT NULL,
  urutan INT DEFAULT 0
);
```

### Relasi Tabel

```
user (admin data)

gejala ←──┐
          │
status_gizi ←──┬── pengetahuan (rules)
               │
               ├── rekomendasi ──→ kondisi_tambahan
               │
               └── riwayat_diagnosa
```

---

## 📁 Struktur Proyek

```
diagnosis_gizi/
│
├── config/                      # Konfigurasi aplikasi
│   ├── app.php                  # Config base URL & path
│   ├── databases.php            # Koneksi database PDO
│   └── db_gizi_ideal_old.sql   # Backup database
│
├── auth/                        # Autentikasi
│   ├── auth.php                 # Proses login
│   ├── auth_check.php           # Pengecekan sesi login
│   ├── logout.php               # Proses logout
│   ├── modal_auth.php           # Modal login (UI)
│   └── seed_admin.php           # Seed data admin
│
├── middleware/                  # Middleware guard
│   └── role_guard.php           # Pengecekan role akses
│
├── templates/                   # Template UI
│   ├── admin/                   # Template untuk admin
│   │   ├── header.php
│   │   ├── footer.php
│   │   └── sidebar.php
│   └── umum/                    # Template untuk publik
│       ├── header.php
│       └── footer.php
│
├── admin/                       # Halaman admin
│   ├── index.php                # Redirect dashboard
│   ├── gejala.php               # CRUD gejala
│   ├── status_gizi.php          # CRUD status gizi
│   ├── pengetahuan.php          # CRUD rules
│   ├── rekomendasi.php          # CRUD rekomendasi
│   ├── riwayat.php              # Lihat semua riwayat
│   ├── detail_riwayat.php       # Detail riwayat
│   └── informasi.php            # Edit informasi
│
├── superadmin/                  # Halaman super admin
│   └── kelola_admin.php         # CRUD user admin
│
├── assets/                      # Asset statis
│   ├── css/
│   │   └── style.css            # Custom CSS
│   ├── js/
│   │   └── script.js            # Custom JavaScript
│   └── images/                  # Gambar
│
├── index.php                    # Halaman utama (landing)
├── diagnosis.php                # Form diagnosis
├── proses_diagnosis.php         # Engine CF & pemrosesan
├── hasil_diagnosis.php          # Tampil hasil
├── riwayat_saya.php             # Riwayat user (session)
├── dashboard.php                # Dashboard admin
├── informasi.php                # Halaman informasi publik
│
├── db_gizi_ideal.sql            # File SQL database
├── debug_url.php                # Debug base URL
├── test_base_url.php            # Test base URL
└── README.md                    # Dokumentasi (file ini)
```

---

## 📖 Penjelasan File dan Fungsi

### 🔧 Folder Config

#### `config/app.php`

**Fungsi:** Konfigurasi aplikasi dan auto-detect base URL

- Mendefinisikan `BASE_PATH` untuk path absolut
- Auto-detect protocol (http/https)
- Auto-detect base folder dari URL
- Mendefinisikan `BASE_URL` untuk link dinamis

**Fitur:**

- Support multi-folder deployment
- Otomatis mendeteksi environment

#### `config/databases.php`

**Fungsi:** Koneksi database menggunakan PDO

- Setup koneksi MySQL/MariaDB
- Error handling koneksi
- Setting PDO attributes:
  - `PDO::ATTR_ERRMODE`: Exception mode
  - `PDO::ATTR_DEFAULT_FETCH_MODE`: Associative array

**Keamanan:**

- Menggunakan PDO prepared statements
- Charset UTF8MB4 untuk dukungan Unicode penuh

---

### 🔐 Folder Auth

#### `auth/auth.php`

**Fungsi:** Proses autentikasi login

- Validasi CSRF token
- Validasi username & password
- Verifikasi password dengan `password_verify()`
- Set session user (id, username, role)
- Redirect ke dashboard jika sukses

**Alur:**

1. Terima POST dari form login
2. Validasi CSRF token
3. Query database berdasarkan username
4. Verifikasi password (hashed)
5. Set session
6. Redirect

#### `auth/auth_check.php`

**Fungsi:** Middleware untuk cek login

- Memulai session
- Mengecek apakah user sudah login
- Redirect ke index jika belum login
- Mengambil data user dari session

**Include di:**

- Semua halaman admin
- Dashboard
- Halaman yang memerlukan autentikasi

#### `auth/logout.php`

**Fungsi:** Proses logout

- Destroy session
- Redirect ke halaman utama

#### `auth/modal_auth.php`

**Fungsi:** UI modal login Bootstrap

- Form login responsive
- CSRF token protection
- Error message display

#### `auth/seed_admin.php`

**Fungsi:** Seed/generate admin baru

- Hash password dengan `password_hash()`
- Insert ke database
- Untuk setup awal atau reset admin

---

### 🛡 Folder Middleware

#### `middleware/role_guard.php`

**Fungsi:** Role-based access control (RBAC)

**Functions:**

```php
requireRole($allowedRoles)  // Cek role umum
requireAdmin()              // Hanya admin
requireSuperAdmin()         // Hanya superadmin
```

**Alur:**

1. Cek session login
2. Cek role user
3. Izinkan atau tolak akses
4. Redirect jika tidak diizinkan

**Penggunaan:**

```php
require_once 'middleware/role_guard.php';
requireAdmin();  // Hanya admin yang bisa akses
```

---

### 🎨 Folder Templates

#### `templates/admin/header.php`

**Fungsi:** Header untuk halaman admin

- Navbar dengan logo dan menu
- Link ke halaman admin (gejala, status, rules, dll)
- Tombol logout
- Include Bootstrap 5 CSS

#### `templates/admin/sidebar.php`

**Fungsi:** Sidebar navigation admin (jika ada)

#### `templates/admin/footer.php`

**Fungsi:** Footer admin

- Include Bootstrap 5 JS
- Custom JS

#### `templates/umum/header.php`

**Fungsi:** Header untuk halaman publik

- Navbar publik
- Menu: Home, Diagnosis, Riwayat, Informasi
- Tombol login admin
- Include Bootstrap & icons

#### `templates/umum/footer.php`

**Fungsi:** Footer publik

- Copyright
- Credits
- Scripts

---

### 🏠 Halaman Publik

#### `index.php`

**Fungsi:** Landing page / homepage

- Hero section dengan CTA
- Deskripsi sistem
- Button "Mulai Diagnosis"

**Fitur:**

- Responsive design
- Informasi singkat metode CF

#### `diagnosis.php`

**Fungsi:** Form input diagnosis

- **Bagian 1:** Data diri (nama, BB, TB)
- **Bagian 2:** Aktivitas fisik (frekuensi & jenis olahraga)
- **Bagian 3:** Kebiasaan makan (buah, sayur, fastfood, minuman manis)
- **Bagian 4:** Riwayat penyakit

**Validasi:**

- Required fields
- Input type number untuk BB & TB
- Dropdown untuk pilihan

**Submit:** POST ke `proses_diagnosis.php`

#### `proses_diagnosis.php`

**Fungsi:** ⚙️ **ENGINE UTAMA** - Proses diagnosis dengan CF

**Tahapan:**

1. **Validasi Input**

   ```php
   - Cek nama lengkap
   - Cek berat & tinggi badan
   - Redirect jika invalid
   ```

2. **Hitung IMT**

   ```php
   $imt = $berat_badan / ($tinggi_badan_m²)

   Kategori:
   - < 18.5: G01 (Underweight)
   - 18.5-24.9: G02 (Normal)
   - 25.0-29.9: G03 (Overweight)
   - ≥ 30.0: G04 (Obesitas)
   ```

3. **Kumpulkan Gejala**

   ```php
   $gejala_pengguna = [
     ['kode' => 'G02', 'keyakinan' => 'sangat_yakin'],
     ['kode' => 'G07', 'keyakinan' => 'yakin'],
     ...
   ]
   ```

4. **Ambil Rules dari Database**

   ```sql
   SELECT g.kode_gejala, p.cf_pakar, s.nama_status
   FROM pengetahuan p
   JOIN gejala g ON p.gejala_id = g.id
   JOIN status_gizi s ON p.status_gizi_id = s.id
   WHERE g.kode_gejala IN (?)
   ```

5. **Hitung CF untuk Setiap Gejala**

   ```php
   foreach ($rules as $rule) {
     $cf_user = getCFUser($tingkat_keyakinan);
     $cf_he = hitungCFHE($cf_pakar, $cf_user);
     $cf_groups[$status]['values'][] = $cf_he;
   }
   ```

6. **Kombinasi CF per Status**

   ```php
   if (count($cf_values) == 1) {
     $cf_gabungan = $cf_values[0];
   } else {
     $cf_gabungan = CFcombine($cf1, $cf2, ...);
   }
   ```

7. **Tentukan Hasil Akhir**

   ```php
   uasort($hasil_cf); // Sort berdasarkan CF tertinggi
   $hasil_akhir = key($hasil_cf);
   ```

8. **Ambil Rekomendasi**

   - Rekomendasi umum berdasarkan status gizi
   - Rekomendasi khusus jika ada kondisi tambahan

9. **Simpan ke Database**

   ```sql
   INSERT INTO riwayat_diagnosa
   (nama_lengkap, berat_badan, tinggi_badan, imt,
    data_gejala, hasil_status_gizi_id, hasil_cf,
    rekomendasi_diberikan)
   ```

10. **Simpan ke Session**

    ```php
    $_SESSION['hasil_diagnosa'] = [...];
    $_SESSION['riwayat_pengguna'][] = $riwayat_id;
    ```

11. **Redirect ke Hasil**
    ```php
    header('Location: hasil_diagnosis.php');
    ```

**Functions:**

- `getCFUser()`: Konversi tingkat keyakinan ke nilai CF
- `hitungCFHE()`: Hitung CF(H,E)
- `CFcombine()`: Kombinasi multiple CF

#### `hasil_diagnosis.php`

**Fungsi:** Menampilkan hasil diagnosis

**Data yang Ditampilkan:**

- Informasi pasien (nama, BB, TB, IMT)
- Status gizi hasil diagnosis
- Tingkat keyakinan (CF × 100%)
- Berat badan ideal
- Gejala yang terdeteksi
- Rekomendasi nutrisi (umum & khusus)
- Detail perhitungan CF (collapsible)

**Sumber Data:**

- Session `$_SESSION['hasil_diagnosa']`
- Atau dari database via parameter `?id=`

**Fitur:**

- Print hasil
- Kembali ke diagnosis
- Lihat detail perhitungan

#### `riwayat_saya.php`

**Fungsi:** Menampilkan riwayat diagnosa user

**Cara Kerja:**

- Ambil ID riwayat dari session `$_SESSION['riwayat_pengguna']`
- Query database berdasarkan ID
- Tampilkan dalam tabel/card
- Link ke detail hasil

**Fitur:**

- Tanggal diagnosis
- Status gizi
- Tingkat keyakinan
- Button "Lihat Detail"

**Limitasi:**

- Maksimal 10 riwayat terakhir (session-based)
- Terhapus jika session cleared

#### `informasi.php`

**Fungsi:** Halaman informasi kesehatan

**Konten:**

- Artikel dari database (`halaman_informasi`)
- Tabel IMT standar WHO
- Informasi tentang gizi seimbang
- Cara menggunakan sistem

---

### 👨‍💼 Halaman Admin

#### `dashboard.php`

**Fungsi:** Dashboard setelah login

**Untuk Admin:**

- Card menu ke:
  - Gejala
  - Status Gizi
  - Aturan (Pengetahuan)
  - Rekomendasi
  - Riwayat
  - Informasi

**Untuk Super Admin:**

- Card menu ke:
  - Kelola User/Admin

**Guard:** `require_once 'auth/auth_check.php'`

#### `admin/gejala.php`

**Fungsi:** CRUD gejala (G01-G24)

**Fitur:**

- Tabel list gejala
- Form tambah gejala (modal)
- Form edit gejala (modal)
- Hapus gejala

**Guard:** `requireAdmin()`

**CRUD Operations:**

```php
POST tambah: INSERT INTO gejala
POST edit:   UPDATE gejala SET ... WHERE id
POST hapus:  DELETE FROM gejala WHERE id
```

#### `admin/status_gizi.php`

**Fungsi:** CRUD status gizi (S1-S4)

**Kolom:**

- Kode status (S1, S2, S3, S4)
- Nama status
- Keterangan

**Guard:** `requireAdmin()`

#### `admin/pengetahuan.php`

**Fungsi:** CRUD aturan (rules) sistem pakar

**Fitur:**

- Tabel rules: Gejala → Status Gizi [CF Pakar]
- Form tambah rule
- Form edit CF Pakar
- Hapus rule

**Relasi:**

- Dropdown gejala dari tabel `gejala`
- Dropdown status dari tabel `status_gizi`
- Input CF Pakar (0.0 - 1.0)

**Validasi:**

- CF Pakar harus antara -1.0 s/d 1.0
- Tidak boleh duplikat (gejala + status)

**Guard:** `requireAdmin()`

#### `admin/rekomendasi.php`

**Fungsi:** CRUD rekomendasi nutrisi

**Jenis Rekomendasi:**

1. **Umum:** Berdasarkan status gizi saja
2. **Khusus:** Berdasarkan status gizi + kondisi tambahan

**Form:**

- Pilih status gizi (required)
- Pilih kondisi tambahan (opsional)
- Textarea saran/rekomendasi

**Guard:** `requireAdmin()`

#### `admin/riwayat.php`

**Fungsi:** Melihat semua riwayat diagnosa

**Fitur:**

- Tabel semua diagnosa dari semua user
- Kolom: Tanggal, Nama, IMT, Status, CF
- Link ke detail riwayat
- Filter/search (opsional)
- Export data (opsional)

**Guard:** `requireAdmin()`

#### `admin/detail_riwayat.php`

**Fungsi:** Detail lengkap satu riwayat

**Parameter:** `?id=X`

**Data:**

- Informasi pasien
- Gejala yang terdeteksi
- Hasil CF per status
- Rekomendasi yang diberikan
- Detail perhitungan

**Guard:** `requireAdmin()`

#### `admin/informasi.php`

**Fungsi:** Edit konten halaman informasi

**Fitur:**

- CRUD artikel/konten
- WYSIWYG editor (opsional)
- Urutan tampilan
- Preview

**Guard:** `requireAdmin()`

---

### 🔐 Halaman Super Admin

#### `superadmin/kelola_admin.php`

**Fungsi:** CRUD user admin

**Fitur:**

- Tabel list user
- Tambah admin baru
- Edit username, password, role
- Hapus admin (kecuali diri sendiri)

**Role Options:**

- `admin`: Akses normal
- `superadmin`: Akses penuh

**Security:**

- Password di-hash dengan `password_hash()`
- Tidak bisa hapus akun sendiri
- CSRF protection

**Guard:** `requireSuperAdmin()`

---

### 🎨 Folder Assets

#### `assets/css/style.css`

**Fungsi:** Custom CSS

- Override Bootstrap
- Styling khusus
- Responsive adjustments

#### `assets/js/script.js`

**Fungsi:** Custom JavaScript

- Interaktivitas form
- Validasi client-side
- AJAX requests (opsional)

#### `assets/images/`

**Fungsi:** Menyimpan gambar

- Logo
- Icons
- Illustrations

---

### 🧪 File Debugging

#### `debug_url.php`

**Fungsi:** Debug base URL detection

- Menampilkan semua variabel $\_SERVER
- Menampilkan BASE_URL yang terdeteksi
- Untuk troubleshooting path issues

#### `test_base_url.php`

**Fungsi:** Test BASE_URL configuration

- Verifikasi BASE_URL benar
- Test link generation

---

## 🔄 Alur Kerja Sistem

### 1. Alur Diagnosis (User Publik)

```
┌─────────────┐
│  index.php  │ ← Landing page
└──────┬──────┘
       │
       ├→ Klik "Mulai Diagnosis"
       │
┌──────▼────────┐
│ diagnosis.php │ ← Form input
└──────┬────────┘
       │
       ├→ Submit form (POST)
       │
┌──────▼─────────────┐
│proses_diagnosis.php│ ← Engine CF
└──────┬─────────────┘
       │
       ├→ 1. Hitung IMT
       ├→ 2. Kumpulkan gejala
       ├→ 3. Query rules
       ├→ 4. Hitung CF(H,E)
       ├→ 5. Kombinasi CF
       ├→ 6. Tentukan hasil
       ├→ 7. Ambil rekomendasi
       ├→ 8. Simpan ke DB & Session
       │
┌──────▼────────────┐
│hasil_diagnosis.php│ ← Tampil hasil
└──────┬────────────┘
       │
       ├→ Lihat hasil
       ├→ Print
       └→ Diagnosa lagi
```

### 2. Alur Login & Dashboard (Admin)

```
┌────────────┐
│ index.php  │
└─────┬──────┘
      │
      ├→ Klik "Login Admin"
      │
┌─────▼──────────┐
│modal_auth.php  │ ← Modal login
└─────┬──────────┘
      │
      ├→ Submit (POST)
      │
┌─────▼────────┐
│  auth.php    │ ← Validasi
└─────┬────────┘
      │
      ├→ Cek username
      ├→ Verify password
      ├→ Set session
      │
┌─────▼────────┐
│dashboard.php │ ← Admin panel
└─────┬────────┘
      │
      ├→ Admin: CRUD menu
      └→ Super Admin: Kelola user
```

### 3. Alur CRUD (Admin)

```
┌──────────────┐
│ dashboard.php│
└──────┬───────┘
       │
       ├→ Pilih menu (misal: Gejala)
       │
┌──────▼────────┐
│  gejala.php   │
└──────┬────────┘
       │
       ├→ [Tampilkan] Query SELECT
       ├→ [Tambah]    POST → INSERT
       ├→ [Edit]      POST → UPDATE
       └→ [Hapus]     POST → DELETE
       │
       └→ Refresh halaman
```

### 4. Alur Akses Role-Based

```
User akses halaman
       │
       ├→ Include auth_check.php
       │   └→ Cek login? → No → Redirect index
       │        └→ Yes
       │
       ├→ Include role_guard.php
       │   └→ requireAdmin() / requireSuperAdmin()
       │        └→ Cek role? → No → Redirect dashboard
       │             └→ Yes
       │
       └→ Tampilkan halaman
```

---

## 🚀 Penggunaan

### Untuk Pengguna Umum

1. **Akses Website**

   ```
   http://localhost/diagnosis_gizi/
   ```

2. **Mulai Diagnosis**

   - Klik "Mulai Diagnosis Sekarang!"
   - Isi form dengan lengkap:
     - Nama lengkap
     - Berat badan (kg)
     - Tinggi badan (cm)
     - Frekuensi olahraga
     - Jenis olahraga
     - Kebiasaan makan (centang semua yang sesuai)
     - Riwayat penyakit (centang jika ada)

3. **Lihat Hasil**

   - Sistem akan menampilkan:
     - Status gizi Anda
     - Tingkat keyakinan (%)
     - IMT dan berat ideal
     - Rekomendasi nutrisi

4. **Lihat Riwayat**

   - Menu "Riwayat Saya"
   - Klik "Lihat Detail" untuk hasil sebelumnya

5. **Baca Informasi**
   - Menu "Informasi"
   - Artikel tentang gizi seimbang

### Untuk Admin

1. **Login**

   ```
   Username: admin
   Password: admin123
   ```

2. **Kelola Data Gejala**

   - Dashboard → Gejala
   - Tambah/Edit/Hapus gejala
   - Gunakan kode konsisten (G01-G24)

3. **Kelola Status Gizi**

   - Dashboard → Status Gizi
   - Edit keterangan status

4. **Kelola Aturan**

   - Dashboard → Aturan
   - Tambah rule baru:
     - Pilih gejala
     - Pilih status gizi
     - Masukkan CF Pakar (-1.0 s/d 1.0)
   - Edit CF Pakar jika perlu
   - Hapus rule yang tidak relevan

5. **Kelola Rekomendasi**

   - Dashboard → Rekomendasi
   - Tambah rekomendasi:
     - **Umum:** Pilih status gizi, kosongkan kondisi
     - **Khusus:** Pilih status gizi + kondisi tambahan
   - Tulis saran nutrisi lengkap

6. **Lihat Riwayat**

   - Dashboard → Riwayat
   - Analisis data diagnosis
   - Klik "Detail" untuk info lengkap

7. **Edit Informasi**
   - Dashboard → Informasi
   - Edit konten halaman publik

### Untuk Super Admin

1. **Login**

   ```
   (Gunakan kredensial super admin yang tersedia)
   ```

2. **Kelola Admin**

   - Dashboard → Kelola User
   - Tambah admin baru:
     - Username unik
     - Password kuat
     - Pilih role (admin/superadmin)
   - Edit admin:
     - Ganti username
     - Reset password (opsional)
     - Ubah role
   - Hapus admin (kecuali diri sendiri)

3. **Akses Semua Fitur Admin**
   - Super admin memiliki akses penuh
   - Bisa mengelola semua data

---

## 🔐 Role dan Hak Akses

### Public (Pengguna Umum)

| Fitur                  | Akses |
| ---------------------- | ----- |
| Lihat landing page     | ✅    |
| Diagnosis gizi         | ✅    |
| Lihat hasil            | ✅    |
| Riwayat saya (session) | ✅    |
| Lihat informasi        | ✅    |
| Akses panel admin      | ❌    |

### Admin

| Fitur               | Akses |
| ------------------- | ----- |
| Login admin         | ✅    |
| Dashboard           | ✅    |
| CRUD Gejala         | ✅    |
| CRUD Status Gizi    | ✅    |
| CRUD Pengetahuan    | ✅    |
| CRUD Rekomendasi    | ✅    |
| Lihat semua riwayat | ✅    |
| Edit informasi      | ✅    |
| Kelola user admin   | ❌    |

### Super Admin

| Fitur             | Akses |
| ----------------- | ----- |
| Semua fitur admin | ✅    |
| CRUD user admin   | ✅    |
| Ubah role admin   | ✅    |
| Hapus admin lain  | ✅    |

---

## 🛠 Troubleshooting

### 1. Database Connection Error

**Gejala:**

```
Koneksi gagal: SQLSTATE[HY000] [1049] Unknown database
```

**Solusi:**

- Pastikan database `db_diagnosis_gizi` sudah dibuat
- Cek kredensial di `config/databases.php`
- Pastikan MySQL service running
- Import file SQL: `db_gizi_ideal.sql`

### 2. Base URL Tidak Sesuai

**Gejala:**

- CSS/JS tidak load
- Link mengarah ke path salah
- 404 Not Found

**Solusi:**

- Akses `debug_url.php` untuk cek BASE_URL
- Edit manual di `config/app.php` jika perlu:
  ```php
  define('BASE_URL', 'http://localhost/diagnosis_gizi');
  ```

### 3. Session Tidak Tersimpan

**Gejala:**

- Logout otomatis
- Data hilang saat pindah halaman

**Solusi:**

- Cek `session_start()` dipanggil di awal file
- Pastikan folder temp PHP writable
- Cek `session.save_path` di php.ini

### 4. Login Gagal Terus

**Gejala:**

- Username/password salah terus

**Solusi:**

- Pastikan user ada di database
- Cek password sudah di-hash
- Re-seed admin via `auth/seed_admin.php`
- Default: username `admin`, password `admin123`

### 5. CF Hasil Selalu 0 atau Salah

**Gejala:**

- Semua CF = 0
- Hasil tidak masuk akal

**Solusi:**

- Cek data di tabel `pengetahuan`
- Pastikan CF Pakar terisi (-1.0 s/d 1.0)
- Cek rules sesuai gejala yang dipilih
- Debug di `proses_diagnosis.php`:
  ```php
  var_dump($gejala_pengguna);
  var_dump($rules);
  var_dump($hasil_cf);
  ```

### 6. Rekomendasi Tidak Muncul

**Gejala:**

- Hasil diagnosis ada, rekomendasi kosong

**Solusi:**

- Cek tabel `rekomendasi` ada data
- Pastikan `status_gizi_id` sesuai
- Untuk rekomendasi khusus, cek `kondisi_tambahan_id`

### 7. Role Guard Tidak Bekerja

**Gejala:**

- User biasa bisa akses admin
- Super admin tidak bisa akses

**Solusi:**

- Pastikan `middleware/role_guard.php` di-include
- Cek session `$_SESSION['user_role']`
- Panggil `requireAdmin()` atau `requireSuperAdmin()`

### 8. Error 500 Internal Server Error

**Solusi:**

- Enable error reporting:
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```
- Cek error log Apache/PHP
- Cek syntax error di file PHP
- Cek file permissions

### 9. Bootstrap/CSS Tidak Load

**Solusi:**

- Cek CDN Bootstrap di header:
  ```html
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
  />
  ```
- Cek koneksi internet
- Atau download Bootstrap lokal ke `assets/`

### 10. Data Riwayat Hilang

**Gejala:**

- Riwayat saya kosong padahal sudah diagnosis

**Penyebab:**

- Riwayat berbasis session (`$_SESSION['riwayat_pengguna']`)
- Session cleared/expired

**Solusi:**

- Login sebagai admin untuk lihat semua riwayat
- Atau implementasikan user login untuk pengguna umum (development)

---

## 📚 Referensi

### Metode Certainty Factor

- Shortliffe, E. H., & Buchanan, B. G. (1975). A model of inexact reasoning in medicine.
- Durkin, J. (1994). Expert Systems: Design and Development.

### Gizi dan IMT

- World Health Organization (WHO). (2021). Body Mass Index Classification.
- Kementerian Kesehatan RI. Pedoman Gizi Seimbang.

### Teknologi

- PHP Official Documentation: https://www.php.net/docs.php
- Bootstrap 5 Documentation: https://getbootstrap.com/docs/5.3/
- MySQL Documentation: https://dev.mysql.com/doc/

---

## 📞 Kontak & Support

Jika mengalami kendala atau ingin berkontribusi:

- **Email:** [your-email@example.com]
- **GitHub Issues:** [repository-url/issues]
- **Documentation:** Baca README.md ini dengan seksama

---

## 📄 Lisensi

Proyek ini dibuat untuk tujuan edukasi dan penelitian.

---

## 🙏 Acknowledgments

- Bootstrap Team untuk framework CSS
- PHP Community
- Semua kontributor proyek

---

## 📝 Changelog

### Version 1.0.0 (Current)

- ✅ Implementasi metode Certainty Factor
- ✅ CRUD lengkap untuk admin
- ✅ Role-based access control
- ✅ Riwayat diagnosis session-based
- ✅ Rekomendasi nutrisi dinamis
- ✅ Responsive design

### Future Development

- 🔜 User login untuk pengguna umum
- 🔜 Export hasil ke PDF
- 🔜 Grafik statistik IMT
- 🔜 API endpoint
- 🔜 Multi-language support

---

## 🎓 Cara Menggunakan Dokumentasi Ini

1. **Instalasi:** Ikuti bagian [Instalasi](#-instalasi)
2. **Pahami Struktur:** Baca [Struktur Proyek](#-struktur-proyek)
3. **Pelajari Metode:** Pahami [Metode CF](#-metode-certainty-factor)
4. **Eksplorasi Kode:** Gunakan [Penjelasan File](#-penjelasan-file-dan-fungsi)
5. **Troubleshoot:** Cek [Troubleshooting](#-troubleshooting)

---

**Selamat menggunakan Sistem Pakar Penentuan Status Gizi Ideal! 🎉**

Dibuat dengan ❤️ menggunakan PHP, MySQL, dan Bootstrap.
