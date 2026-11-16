@twillBlockTitle('Management')
@twillBlockIcon('text')
@twillBlockGroup('app')

@formField('input', [
    'name' => 'title_main',
    'label' => 'Judul Utama (Contoh: Penanggung Jawab &)',
    'translatable' => true
])

@formField('input', [
    'name' => 'title_highlight',
    'label' => 'Judul Sorotan/Biru (Contoh: Management Perusahaan)',
    'translatable' => true
])

@formField('input', [
    'name' => 'subtitle',
    'label' => 'Subjudul (Opsional)',
    'translatable' => true
])

{{-- Memanggil repeater untuk item personel --}}
@formField('repeater', ['type' => 'management_item'])