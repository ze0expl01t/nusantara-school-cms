@twillRepeaterTitle('Item Progress Bar')
@twillRepeaterGroup('app')

@formField('input', [
    'name' => 'label',
    'label' => 'Nama Jurusan / Kategori (Contoh: Teknik Kendaraan Ringan (TKR))',
    'translatable' => true
])

@formField('input', [
    'name' => 'percentage',
    'label' => 'Nilai Persentase (Contoh: 95)',
    'placeholder' => '95'
])