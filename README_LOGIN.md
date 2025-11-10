# Sistem Diagnosis Gizi - Setup Guide

## Petunjuk Setup Sistem Login Admin

### 1. Persiapan Database

1. **Pastikan XAMPP/MySQL sudah running**
2. **Import Database:**
   - Buka `http://localhost/diagnosis_gizi/admin/import_db.php`
   - Klik "Import Database Sekarang"
   - Atau import manual file `config/db_gizi_ideal.sql` ke phpMyAdmin

### 2. Setup Admin

1. **Buka halaman setup:** `http://localhost/diagnosis_gizi/admin/setup.php`
2. **Atau buat admin manual via:** `http://localhost/diagnosis_gizi/admin/create_admin.php`

**Admin Default:**

- Username: `admin`
- Password: `admin123`

### 3. Test Sistem

1. **Test Database:** `http://localhost/diagnosis_gizi/admin/test_db.php`
2. **Test Login:** `http://localhost/diagnosis_gizi/admin/test_login.php`
3. **Login:** `http://localhost/diagnosis_gizi/admin/index.php`

### 4. File-file Penting

- `config/databases.php` - Konfigurasi database
- `admin/auth.php` - Proses authentication
- `admin/templates/auth_check.php` - Proteksi halaman admin
- `admin/logout.php` - Logout functionality

### 5. Struktur Database

#### Tabel Admin

```sql
CREATE TABLE `admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
);
```

### 6. Fitur Keamanan

- ✅ Password hashing dengan `password_hash()`
- ✅ CSRF token protection
- ✅ Session validation
- ✅ Input sanitization
- ✅ Database prepared statements

### 7. Troubleshooting

**Error "Koneksi gagal":**

- Pastikan MySQL running
- Cek konfigurasi di `config/databases.php`

**Error "Tabel tidak ditemukan":**

- Import database via `import_db.php`

**Login gagal:**

- Gunakan `test_login.php` untuk debug
- Pastikan admin sudah dibuat via `setup.php`

**Session error:**

- Clear browser cookies/cache
- Restart session via logout

### 8. URL Akses

- **Login Admin:** `http://localhost/diagnosis_gizi/admin/`
- **Dashboard:** `http://localhost/diagnosis_gizi/admin/dashboard.php`
- **Setup Tools:** `http://localhost/diagnosis_gizi/admin/setup.php`
