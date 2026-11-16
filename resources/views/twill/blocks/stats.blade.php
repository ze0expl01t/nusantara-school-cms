@twillBlockTitle('Statistik (Stats Row)')
@twillBlockIcon('text')
@twillBlockGroup('app')

@formField('input', [
    'name' => 'title_main',
    'label' => 'Teks Judul Utama (Contoh: Pengalaman)',
    'translatable' => true
])

@formField('input', [
    'name' => 'title_highlight',
    'label' => 'Teks Judul Sorotan/Warna Hijau (Contoh: & Mutu Pendidikan)',
    'translatable' => true
])

@formField('repeater', ['type' => 'stat_item'])