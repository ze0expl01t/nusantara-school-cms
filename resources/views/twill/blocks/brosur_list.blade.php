@twillBlockTitle('Daftar Brosur & Katalog')
@twillBlockIcon('text')

@formField('input', [
    'name' => 'title',
    'label' => 'Judul Section',
    'translatable' => true
])

@formField('input', [
    'name' => 'limit',
    'label' => 'Jumlah Maksimal Brosur Ditampilkan',
    'default' => 6
])
