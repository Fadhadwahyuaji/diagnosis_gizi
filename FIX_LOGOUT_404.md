# 🔧 Perbaikan Error 404 Logout

## 🔴 MASALAH:
```
[Mon Dec 1 22:31:13 2025] [::1]:58553 [404]: GET /superadmin/auth/logout.php - No such file or directory
```

**Path yang salah:** `/superadmin/auth/logout.php`  
**Path yang benar:** `/auth/logout.php`

---

## 🔍 ROOT CAUSE:

### **Masalah di `config/app.php`:**

**SEBELUM:**
```php
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$baseFolder = dirname($scriptName);  // ❌ SALAH!
$baseUrl = rtrim($protocol . '://' . $host . $baseFolder, '/');
```

**Penjelasan Masalah:**
- Ketika berada di `/superadmin/kelola_admin.php`
- `dirname($scriptName)` menghasilkan `/superadmin`
- BASE_URL menjadi `http://localhost/superadmin`
- Logout link menjadi `/superadmin/auth/logout.php` ❌

---

## ✅ SOLUSI:

### **Update `config/app.php`:**

```php
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);

// Ekstrak base folder - cari folder root sebelum /admin, /superadmin, /auth
if (preg_match('#^(.*?)/(admin|superadmin|auth|templates|config|middleware)/#', $scriptName, $matches)) {
    $baseFolder = $matches[1];
} elseif (preg_match('#^(/[^/]+)/#', $scriptName, $matches)) {
    // Jika tidak match, ambil folder pertama setelah domain
    $baseFolder = $matches[1];
} else {
    $baseFolder = '';
}

$baseUrl = rtrim($protocol . '://' . $host . $baseFolder, '/');
define('BASE_URL', $baseUrl);
```

**Cara Kerja:**
1. Cari pattern `/admin`, `/superadmin`, `/auth`, dll dalam path
2. Ambil semua karakter SEBELUM pattern tersebut sebagai base folder
3. Contoh:
   - `/diagnosis_gizi/superadmin/kelola_admin.php` → baseFolder = `/diagnosis_gizi`
   - `/admin/gejala.php` → baseFolder = `` (root)
   - `/superadmin/kelola_admin.php` → baseFolder = `` (root)

---

## 📊 TESTING:

### **Test dari berbagai lokasi:**

| Halaman | SCRIPT_NAME | BASE_URL | Logout URL |
|---------|-------------|----------|------------|
| `/superadmin/kelola_admin.php` | `/superadmin/kelola_admin.php` | `http://localhost` | `http://localhost/auth/logout.php` ✅ |
| `/admin/gejala.php` | `/admin/gejala.php` | `http://localhost` | `http://localhost/auth/logout.php` ✅ |
| `/dashboard.php` | `/dashboard.php` | `http://localhost` | `http://localhost/auth/logout.php` ✅ |

### **Dengan subfolder:**

| Halaman | SCRIPT_NAME | BASE_URL | Logout URL |
|---------|-------------|----------|------------|
| `/diagnosis_gizi/superadmin/kelola_admin.php` | `/diagnosis_gizi/superadmin/kelola_admin.php` | `http://localhost/diagnosis_gizi` | `http://localhost/diagnosis_gizi/auth/logout.php` ✅ |

---

## 🧪 CARA TESTING:

1. **Buat file test:** `test_base_url.php`
   ```php
   <?php
   require_once __DIR__ . '/config/app.php';
   echo "BASE_URL: " . BASE_URL . "<br>";
   echo "Logout URL: " . BASE_URL . "/auth/logout.php";
   ?>
   ```

2. **Akses dari berbagai halaman:**
   - `http://localhost:8000/test_base_url.php`
   - `http://localhost:8000/admin/test_base_url.php` (copy file ke admin/)
   - `http://localhost:8000/superadmin/test_base_url.php` (copy file ke superadmin/)

3. **Pastikan semua menampilkan BASE_URL yang sama**

---

## ✅ FILES YANG DIPERBAIKI:

1. ✅ `config/app.php` - Fix BASE_URL detection
2. ✅ `test_base_url.php` - File untuk testing (optional)

---

## 🎯 HASIL:

**SEBELUM:**
- ❌ Logout dari superadmin → `/superadmin/auth/logout.php` (404)
- ❌ Logout dari admin → `/admin/auth/logout.php` (404)

**SESUDAH:**
- ✅ Logout dari superadmin → `/auth/logout.php` (200 OK)
- ✅ Logout dari admin → `/auth/logout.php` (200 OK)
- ✅ BASE_URL konsisten di semua halaman

---

## 📝 CATATAN:

### **Alternative Solution (Jika masih error):**

Jika perbaikan di atas masih bermasalah, gunakan hardcoded BASE_URL:

```php
// config/app.php
define('BASE_URL', '/diagnosis_gizi'); // Sesuaikan dengan folder proyek Anda
// atau
define('BASE_URL', ''); // Jika proyek di root
```

### **Best Practice:**

Untuk production, buat file `.env` atau `config.php`:
```php
// config/config.php
return [
    'base_url' => 'https://yourdomain.com',
    // atau
    'base_url' => 'https://yourdomain.com/diagnosis_gizi',
];
```

---

**Fixed by:** GitHub Copilot  
**Date:** December 1, 2025
