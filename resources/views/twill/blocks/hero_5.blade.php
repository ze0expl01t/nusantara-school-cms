@twillBlockTitle('Hero 5 (Profil & Ilustrasi)')
@twillBlockIcon('text')
@twillBlockGroup('app')

@formField('input', [
    'name' => 'badge_text',
    'label' => 'Teks Badge Atas (Contoh: Sonorejo, Blora, Jawa Tengah, 10 April 1993)',
    'translatable' => true
])

@formField('input', [
    'name' => 'title_main',
    'label' => 'Judul Utama (Contoh: Profil Lulusan Unggul)',
    'translatable' => true
])

@formField('input', [
    'name' => 'sub_heading',
    'label' => 'Sub Heading (Contoh: PT. Nama Perusahaan - Perusahaan Vokasi Rujukan...)',
    'translatable' => true
])

@formField('input', [
    'name' => 'description',
    'label' => 'Deskripsi Panjang',
    'type' => 'textarea',
    'rows' => 5,
    'translatable' => true
])

@formField('medias', [
    'name' => 'hero_5_image',
    'label' => 'Gambar Ilustrasi Sisi Kanan (Widescreen)',
    'max' => 1
])

{{-- Tombol CTA Utama --}}
@formField('input', [
    'name' => 'cta_primary_text',
    'label' => 'Teks Tombol Utama (Contoh: #Kontak Kami)',
    'translatable' => true
])
@formField('input', [
    'name' => 'cta_primary_url',
    'label' => 'URL Tombol Utama'
])

{{-- Tombol CTA Kedua --}}
@formField('input', [
    'name' => 'cta_secondary_text',
    'label' => 'Teks Tombol Kedua (Contoh: #Kegiatan)',
    'translatable' => true
])
@formField('input', [
    'name' => 'cta_secondary_url',
    'label' => 'URL Tombol Kedua'
])

{{-- Repeater untuk statistik mini di bagian bawah --}}
@formField('repeater', ['type' => 'hero_5_item'])