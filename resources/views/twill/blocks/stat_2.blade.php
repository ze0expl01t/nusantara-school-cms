@twillBlockTitle('Stat 2 (Kompetensi & Progress Bar)')
@twillBlockIcon('text')
@twillBlockGroup('app')

@formField('input', [
    'name' => 'badge_text',
    'label' => 'Teks Badge Atas (Contoh: PROFESIONALISME TENAGA PENDIDIK DI 5 JURUSAN UNGGULAN)',
    'translatable' => true
])

@formField('input', [
    'name' => 'title_main',
    'label' => 'Judul Utama (Contoh: Kompetensi Pedagogik dan)',
    'translatable' => true
])

@formField('input', [
    'name' => 'title_highlight',
    'label' => 'Judul Sorotan/Biru (Contoh: Profesional Staf)',
    'translatable' => true
])

@formField('input', [
    'name' => 'description',
    'label' => 'Deskripsi / Kualifikasi Akademik',
    'type' => 'textarea',
    'rows' => 3,
    'translatable' => true
])

{{-- Repeater untuk Item Progress Bar Statistik --}}
@formField('repeater', ['type' => 'stat_2_item'])

{{-- Repeater untuk Kolase Gambar di Sisi Kanan --}}
@formField('repeater', ['type' => 'stat_2_image_item'])