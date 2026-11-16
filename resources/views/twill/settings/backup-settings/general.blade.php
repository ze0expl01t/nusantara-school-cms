{{-- Note: Link ke UI Backup Management — DYNAMIC URL --}}
@php
    // Dynamic admin path — otomatis mengikuti ADMIN_APP_PATH di .env
    $adminPath = config('twill.admin_app_path', 'admin');
    $backupManagementUrl = url("/{$adminPath}/backup");
@endphp

<div style="background: #ebf8ff; border-left: 4px solid #3182ce; padding: 20px 24px; margin-bottom: 32px; border-radius: 6px; font-size: 13px; line-height: 1.8; color: #2c5282;">
    <div style="margin-bottom: 8px;">
        <strong style="font-size: 14px;">ℹ️ Catatan</strong>
    </div>
    <div style="margin-bottom: 10px;">
        Halaman ini untuk <strong>konfigurasi</strong> backup (retention, jadwal, email notifikasi).
    </div>
    <div>
        Untuk <strong>backup manual</strong>, melihat daftar file, download, atau hapus backup — buka
        <a href="{{ $backupManagementUrl }}" style="color: #2b6cb0; text-decoration: underline; font-weight: 600;">Backup Management →</a>
    </div>
</div>

@twillBlockTitle('Umum')
@twillBlockIcon('cog')

<x-twill::checkbox
    name="enable_backup"
    label="Aktifkan Backup Otomatis"
    default="true"
    note="Jika dinonaktifkan, backup otomatis tidak akan berjalan."
/>

<x-twill::input
    name="retention_days"
    label="Simpan Backup (hari)"
    type="number"
    default="7"
    note="Backup disimpan penuh selama N hari. Setelahnya diarsipkan mingguan."
/>

<x-twill::input
    name="max_size_mb"
    label="Batas Ukuran Total (MB)"
    type="number"
    default="2048"
    note="Jika total backup melebihi nilai ini, backup tertua dihapus otomatis."
/>

<hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">

<x-twill::checkbox
    name="backup_database"
    label="Backup Database"
    default="true"
/>

<x-twill::checkbox
    name="backup_files"
    label="Backup File Uploads"
    default="true"
    note="Folder: storage/app/public/uploads (Twill)"
/>