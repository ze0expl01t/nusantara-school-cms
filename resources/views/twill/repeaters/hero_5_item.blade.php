@twillRepeaterTitle('Statistik / Info Bawah')
@twillRepeaterGroup('app')

@formField('input', [
    'name' => 'icon',
    'label' => 'Kode SVG / Heroicon (Opsional, contoh: <svg>...</svg>)',
    'type' => 'textarea',
    'rows' => 3
])

@formField('input', [
    'name' => 'value',
    'label' => 'Nilai / Data (Contoh: Negeri, A, 1993, SMK)',
    'translatable' => true
])

@formField('input', [
    'name' => 'label',
    'label' => 'Label Keterangan (Contoh: Status Perusahaan, Akreditasi, Tahun Berdiri)',
    'translatable' => true
])