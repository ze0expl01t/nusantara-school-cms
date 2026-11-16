# Nusantara Web Portal (Laravel + Twill CMS)

Portal informasi dan manajemen konten sekolah berbasis **Laravel**, **Twill CMS**, **Tailwind CSS**, dan **Alpine.js**.

## Persyaratan Sistem
- PHP >= 8.2 (bcmath, ctype, fileinfo, gd/imagick, json, mbstring, openssl, pdo_mysql, tokenizer, xml)
- Composer
- Node.js & NPM
- MySQL / MariaDB

## Quick Setup (Setelah Clone)
1. **Clone repository:**
   ```bash
   git clone [https://github.com/ze0expl01t/nusantara-twill-smk.git](https://github.com/ze0expl01t/nusantara-twill-smk.git)
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

## Konfigurasi Kredensial Google Cloud & GA4 (spatie/laravel-analytics)

1. **Ambil Property ID GA4:**
   - Buka **Google Analytics** -> menu **Admin** -> **Property Settings** -> **Property Details**.
   - Salin angka **Property ID** (contoh: `456789123` - bukan Measurement ID `G-XXXX`).

2. **Buat Service Account di Google Cloud Console:**
   - Buka Google Cloud Console -> aktifkan **Google Analytics Data API**.
   - Masuk ke **IAM & Admin -> Service Accounts** -> klik **Create Service Account** (misal: `laravel-ga4-reader`).
   - Buka tab **Keys** -> **Add Key -> Create new key -> JSON** -> unduh file JSON-nya.

3. **Simpan File JSON ke Storage Laravel:**
   - Letakkan file JSON tersebut ke path berikut (jangan di-commit ke Git):
     `storage/app/analytics/service-account-credentials.json`

4. **Beri Akses Read ke Service Account di GA4:**
   - Salin alamat email Service Account dari dalam file JSON.
   - Kembali ke **Google Analytics -> Admin -> Property Access Management** -> klik tombol **+** -> masukkan email tersebut dengan role **Viewer**.

5. **Tambahkan Property ID di File `.env`:**
   ```env
   ANALYTICS_PROPERTY_ID=456789123
   ```
