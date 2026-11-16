# Nusantara Web Portal (Laravel + Twill CMC)

Portal informasi dan manajemen konten sekolah berbasis **Laravel**, **Twill CMS**, **Tailwind CSS**, dan **Alpine.js**


## Persyaratan Sistem
- **PHP >= 8.2**: `bcmath`, `ctype`, `fileinfo`, `gd`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`
- **Composer, or Node.js & NPM, or MySQL / MariaDB**


## Quick Setup (Setelah Clone)
1. **Clone repository:**
   ```bash
   git clone https://github.com/ze0expl01t/nusantara-twill-smk.git
   cd nusantara-twill-smk
   ```
2. **Jalankan Auto-Setup:**
   ```bash
   php setup.php
   ```
3. **Konfigurasi Environment:**
   Sesuaikan `APP_URL`, `ADMIN_APP_URL`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`.
4. **Migrasi Database & Buat Akun Admin Twill:**
   ```bash
   php artisan migrate
   php artisan twill:superadmin
   ```

---

## Konfigurasi Kredensial Google Cloud & GA4 (`spatie/laravel-analytics`)

1. **Ambil Property ID GA4:**
   - Buka **Google Analytics** -> menu **Admin** -> **Property Settings **-> **Property Details **.
   - Salin angka **Property ID** (contoh: `456789123` - bukan `session_id`/`G-XXXX).

2. **Buat Service Account di Google Cloud Console:**
   - Buka Google Cloud Console -> aktifkan **Google Analytics Data API**‚ˆHX\ÝZÈÙH
Š’PSH	ˆYZ[ˆOˆÙ\šXÙHXØÛÝ[È
Š‹Oˆ
ŠÜ™X]HÙ\šXÙHXØÛÝ[
Š‹‚ˆHZØHXˆ
Š’Ù^\ÊŠˆOˆ
ŠYÙ^HOˆÜ™X]H™]ÈÙ^HOˆ”ÓÓŠŠˆOˆ[™Zš[H”ÓÓ‹[žXK‚‚ŒËˆ
Š”Ú[\[ˆš[H”ÓÓˆÙHÝÜ˜YÙH\˜]™[ŠŠ‚ˆH]ZÚØ[ˆš[H”ÓÓˆ\œÙX]ÙH
š˜[™Ø[ˆKXÛÛ[Z]ÙHÚ]
ŠŽ‚ˆ^ˆÝÜ˜YÙKØ\Ø[˜[]XÜËÜÙ\šXÙKXXØÛÝ[XÜ™Y[X[ËšœÛÛ‚ˆ‚ˆ
Š™\šHZÜÙ\È™XYÙHÙ\šXÙHXØÛÝ[HÐMŠŠ‚ˆHØ[[ˆ[[X][XZ[Ù\šXÙHXØÛÝ[\šH[[Hš[H”ÓÓ‹‚ˆHÙ[X˜[HÙH
Š‘ÛÛÙÛH[˜[]XÜÈOˆYZ[ˆOˆ›Ü\HXØÙ\ÜÈX[˜YÙ[Y[
ŠˆOˆÛZÈÛX›Û
ŠŠÊŠˆOˆX\ÝZÚØ[ˆ[XZ[\œÙX][™Ø[ˆ›ÛH
Š•šY]Ù\ŠŠ‹‚‚Kˆ
Š•[X˜ZØ[ˆ›Ü\HQHš[H™[˜ŠŠ‚ˆ[‚ˆSSUPÔ×Ô“ÔT•WÒQMMÎLLŒÂˆ