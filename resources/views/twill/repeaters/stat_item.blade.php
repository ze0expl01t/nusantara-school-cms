@twillRepeaterTitle('Item Statistik')
@twillRepeaterGroup('app')

@formField('input', [
    'name' => 'number',
    'label' => 'Angka Utama (Contoh: 20, 85, 10)'
])

@formField('input', [
    'name' => 'suffix',
    'label' => 'Simbol/Akhiran (Contoh: YR, +, %)'
])

@formField('input', [
    'name' => 'label',
    'label' => 'Label Teks Bawah (Contoh: YEARS EXPERIENCE)',
    'translatable' => true
])