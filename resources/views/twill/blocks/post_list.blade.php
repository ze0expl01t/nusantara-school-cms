@twillBlockTitle('Daftar Post / Artikel')
@twillBlockIcon('text')

@formField('input', [
    'name' => 'title',
    'label' => 'Judul Section',
    'translatable' => true
])

@formField('input', [
    'name' => 'limit',
    'label' => 'Jumlah Maksimal Post yang Ditampilkan',
    'default' => 6
])