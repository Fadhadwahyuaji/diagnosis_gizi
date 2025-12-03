# 📋 DOKUMENTASI PERBAIKAN STRUKTUR FOLDER

## ✅ MASALAH YANG TELAH DIPERBAIKI

### 1. **File di Folder `admin/`**

Semua file PHP di folder `admin/` telah diperbaiki:

#### File yang diperbaiki:

- ✅ `admin/gejala.php`
- ✅ `admin/status_gizi.php`
- ✅ `admin/informasi.php`
- ✅ `admin/pengetahuan.php`
- ✅ `admin/rekomendasi.php`
- ✅ `admin/riwayat.php`
- ✅ `admin/index.php`

#### Perubahan:

```php
// SEBELUM (SALAH):
require_once 'auth/auth_check.php';
require_once '../config/databases.php';
require_once '../middleware/role_guard.php';
require_once 'templates/header.php';
require_once 'templates/footer.php';

// SESUDAH (BENAR):
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/databases.php';
require_once __DIR__ . '/../middleware/role_guard.php';
require_once __DIR__ . '/../templates/admin/header.php';
require_once __DIR__ . '/../templates/admin/footer.php';
```

---

### 2. **File `auth/auth_check.php`**

✅ Diperbaiki recursive include yang berbahaya

#### Perubahan:

```php
// SEBELUM (SALAH - file memanggil dirinya sendiri):
require_once BASE_PATH . '/auth/auth_check.php';

// SESUDAH (BENAR):
require_once BASE_PATH . '/config/databases.php';
```

---

### 3. **File `dashboard.php`**

✅ Diperbaiki path footer dan link navigasi

#### Perubahan:

```php
// SEBELUM (SALAH):
require_once BASE_PATH . './templates/admin/footer.php';  // titik ganda
<a href="gejala.php">...</a>
<a href="status_gizi.php">...</a>
<a href="aturan.php">...</a>
<a href="riwayat.php">...</a>

// SESUDAH (BENAR):
require_once BASE_PATH . '/templates/admin/footer.php';
<a href="admin/gejala.php">...</a>
<a href="admin/status_gizi.php">...</a>
<a href="admin/pengetahuan.php">...</a>
<a href="admin/riwayat.php">...</a>
```

---

### 4. **File `templates/admin/sidebar.php`**

✅ Diperbaiki semua link navigasi menggunakan BASE_PATH

#### Perubahan:

```php
// SEBELUM (SALAH):
href="<?php echo BASE_URL; ?>/dashboard.php"
href="../../superadmin/kelola_admin.php"
href="gejala.php"
href="status_gizi.php"
href="auth/logout.php"

// SESUDAH (BENAR):
href="<?php echo BASE_PATH; ?>/dashboard.php"
href="<?php echo BASE_PATH; ?>/superadmin/kelola_admin.php"
href="<?php echo BASE_PATH; ?>/admin/gejala.php"
href="<?php echo BASE_PATH; ?>/admin/status_gizi.php"
href="<?php echo BASE_PATH; ?>/auth/logout.php"
```

---

### 5. **File `templates/umum/header.php`**

✅ Diperbaiki path logout

#### Perubahan:

```php
// SEBELUM (SALAH):
href="admin/auth/logout.php"

// SESUDAH (BENAR):
href="auth/logout.php"
```

---

### 6. **File `config/app.php`**

✅ Diperbaiki BASE_URL

#### Perubahan:

```php
// SEBELUM (SALAH):
define('BASE_URL', '/..');

// SESUDAH (BENAR):
define('BASE_URL', '/diagnosis_gizi');
```

**⚠️ CATATAN PENTING:**
Jika Anda menggunakan nama folder berbeda atau menjalankan di subdomain,
sesuaikan nilai `BASE_URL` dengan path actual Anda. Contoh:

- Jika di root: `define('BASE_URL', '');`
- Jika di subfolder: `define('BASE_URL', '/nama_folder_anda');`
- Jika di localhost: `define('BASE_URL', '/diagnosis_gizi');`

---

### 7. **File Auth (`auth/*.php`)**

✅ Diperbaiki semua file di folder auth

#### File yang diperbaiki:

- ✅ `auth/auth.php`
- ✅ `auth/modal_auth.php`
- ✅ `auth/seed_admin.php`

#### Perubahan:

```php
// SEBELUM (SALAH):
require_once '../../config/databases.php';
require_once __DIR__ . '/../../config/databases.php';

// SESUDAH (BENAR):
require_once __DIR__ . '/../config/databases.php';
```

---

### 8. **File Root (`*.php` di root)**

✅ Diperbaiki file-file di root folder

#### File yang diperbaiki:

- ✅ `proses_diagnosis.php`
- ✅ `informasi.php`

#### Perubahan:

```php
// SEBELUM (SALAH):
require_once 'config/databases.php';

// SESUDAH (BENAR):
require_once __DIR__ . '/config/databases.php';
```

---

## 🎯 STRUKTUR FOLDER FINAL

```
diagnosis_gizi/
├── index.php              ✅ File utama
├── dashboard.php          ✅ Dashboard admin
├── diagnosis.php          ✅ Form diagnosis
├── proses_diagnosis.php   ✅ Proses diagnosis
├── hasil_diagnosis.php    ✅ Hasil diagnosis
├── informasi.php          ✅ Halaman informasi
│
├── admin/                 ✅ Folder halaman admin
│   ├── index.php          ✅ Redirect ke dashboard
│   ├── gejala.php         ✅ CRUD gejala
│   ├── status_gizi.php    ✅ CRUD status gizi
│   ├── pengetahuan.php    ✅ CRUD basis pengetahuan
│   ├── rekomendasi.php    ✅ CRUD rekomendasi
│   ├── riwayat.php        ✅ Lihat riwayat
│   └── informasi.php      ✅ CRUD informasi
│
├── auth/                  ✅ Folder autentikasi
│   ├── auth_check.php     ✅ Middleware login
│   ├── auth.php           ✅ Proses login
│   ├── modal_auth.php     ✅ Login via modal
│   ├── logout.php         ✅ Proses logout
│   └── seed_admin.php     ✅ Seed data admin
│
├── config/                ✅ Folder konfigurasi
│   ├── app.php            ✅ Konfigurasi aplikasi
│   └── databases.php      ✅ Koneksi database
│
├── middleware/            ✅ Folder middleware
│   └── role_guard.php     ✅ Proteksi role
│
├── superadmin/            ✅ Folder superadmin
│   └── kelola_admin.php   ✅ CRUD admin
│
├── templates/             ✅ Folder template
│   ├── admin/             ✅ Template admin
│   │   ├── header.php     ✅ Header admin
│   │   ├── footer.php     ✅ Footer admin
│   │   └── sidebar.php    ✅ Sidebar admin
│   └── umum/              ✅ Template umum
│       ├── header.php     ✅ Header umum
│       └── footer.php     ✅ Footer umum
│
└── assets/                ✅ Folder asset
    ├── css/
    ├── js/
    └── images/
```

---

## 🔧 CARA MENJALANKAN PROYEK

### 1. **Setup Database**

```sql
-- Import file SQL ke database MySQL Anda
-- File: db_gizi_ideal.sql
```

### 2. **Konfigurasi Database**

Edit file `config/databases.php`:

```php
$host = 'localhost';
$db_name = 'db_diagnosis_gizi';  // Sesuaikan nama database
$username = 'root';               // Sesuaikan username
$password = '';                   // Sesuaikan password
```

### 3. **Konfigurasi Base URL**

Edit file `config/app.php`:

```php
// Sesuaikan dengan lokasi proyek Anda
define('BASE_URL', '/diagnosis_gizi');  // atau '/' jika di root
```

### 4. **Buat Admin Pertama** (Opsional)

Akses: `http://localhost/diagnosis_gizi/auth/seed_admin.php`

Ini akan membuat akun:

- Username: `admin`
- Password: `admin123`
- Role: `admin`

### 5. **Akses Website**

- **Halaman Utama**: `http://localhost/diagnosis_gizi/`
- **Login Admin**: Klik tombol "Login" di navbar
- **Dashboard**: `http://localhost/diagnosis_gizi/dashboard.php`

---

## ⚠️ CHECKLIST SEBELUM MENJALANKAN

- [ ] Database sudah dibuat dan di-import
- [ ] File `config/databases.php` sudah dikonfigurasi
- [ ] File `config/app.php` BASE_URL sudah disesuaikan
- [ ] Web server (Apache/Nginx) sudah berjalan
- [ ] PHP versi minimal 7.4
- [ ] Extension PHP PDO sudah aktif
- [ ] Folder `assets/` dapat diakses (untuk CSS/JS)

---

## 🐛 TROUBLESHOOTING

### Error: "Call to undefined function"

- Pastikan extension PDO di php.ini sudah aktif
- Restart web server

### Error: "Failed to connect to database"

- Cek konfigurasi di `config/databases.php`
- Pastikan MySQL server sudah berjalan
- Pastikan database sudah dibuat

### Error: "404 Not Found" pada link

- Periksa nilai `BASE_URL` di `config/app.php`
- Sesuaikan dengan path actual folder proyek

### CSS/JS tidak load

- Periksa path di `templates/umum/header.php`
- Pastikan folder `assets/` dapat diakses dari web browser
- Cek console browser untuk error 404

### Redirect loop pada login

- Hapus semua session: `session_destroy()`
- Clear browser cache dan cookies
- Periksa logic di `auth/auth_check.php`

---

## 📝 CATATAN TAMBAHAN

1. **Keamanan**:

   - Ganti password default admin setelah login pertama
   - Jangan gunakan di production tanpa HTTPS
   - Validasi semua input dari user

2. **Struktur Path**:

   - Semua path menggunakan `__DIR__` untuk keamanan
   - Menggunakan `BASE_PATH` untuk path absolut
   - Menggunakan path relatif yang konsisten

3. **Session**:
   - Session sudah dimulai di header.php
   - CSRF token sudah diimplementasi
   - Session timeout dapat dikonfigurasi

---

## ✅ SUMMARY

**Total File Diperbaiki: 20+ file**

Semua error path akibat perubahan struktur folder sudah diperbaiki.
Proyek sekarang menggunakan path yang konsisten dan aman dengan `__DIR__`.

Website seharusnya sudah dapat berjalan dengan normal! 🎉
