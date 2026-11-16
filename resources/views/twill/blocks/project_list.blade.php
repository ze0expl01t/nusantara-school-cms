@twillBlockTitle('Daftar Project / Portofolio')
@twillBlockIcon('text')

@formField('input', [
    'name' => 'title',
    'label' => 'Judul Section',
    'translatable' => true
])

@formField('input', [
    'name' => 'limit',
    'label' => 'Jumlah Maksimal Project yang Ditampilkan',
    'default' => 6
])
