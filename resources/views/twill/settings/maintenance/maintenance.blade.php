@twillBlockTitle('Maintenance Mode')
@twillBlockIcon('text')
@twillBlockGroup('app')

<div style="padding: 0.5rem 0;">
    <p style="color: #6b7280; margin-bottom: 1.5rem; padding: 0.75rem 1rem; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 0.25rem; font-size: 0.875rem;">
        <strong>⚠️ Perhatian:</strong> Ketika maintenance mode aktif, website publik tidak bisa diakses. Admin yang login tetap bisa mengakses website untuk testing.
    </p>
</div>

<x-twill::checkbox
    name="maintenance_enabled"
    label="Aktifkan Maintenance Mode"
/>

<x-twill::input
    name="maintenance_title"
    label="Judul Halaman"
    default="Sedang Dalam Perbaikan"
    :translated="true"
    :maxlength="100"
/>

<x-twill::input
    name="maintenance_message"
    label="Pesan / Deskripsi"
    type="textarea"
    :rows="4"
    :translated="true"
    default="Kami sedang melakukan pemeliharaan sistem untuk meningkatkan layanan. Mohon maaf atas ketidaknyamanannya."
/>

<x-twill::input
    name="maintenance_eta"
    label="Estimasi Selesai (opsional)"
    placeholder="Contoh: Kembali online dalam 2 jam"
    :translated="true"
    :maxlength="100"
/>

<x-twill::input
    name="maintenance_contact_email"
    label="Email Kontak (opsional)"
    placeholder="Contoh: info@perusahaan.sch.id"
    default="hse@smkmuh1blora.sch.id"
/>

<x-twill::input
    name="maintenance_whitelist_ips"
    label="Whitelist IP (opsional)"
    type="textarea"
    :rows="3"
    placeholder="Satu IP per baris. IP yang di-whitelist tetap bisa akses website meskipun maintenance aktif"
/>
