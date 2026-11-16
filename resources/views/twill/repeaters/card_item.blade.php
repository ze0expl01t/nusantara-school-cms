@twillRepeaterTitle('Item Card')
@twillRepeaterGroup('app')

{{-- Diganti menjadi field teks agar bisa diisi kode SVG, class FontAwesome, atau nama Blade Icon --}}
@formField('input', [
    'name' => 'icon',
    'label' => 'Icon (Masukkan Kode SVG / Class / Nama Blade Icon)',
    'type' => 'textarea', 
    'rows' => 3
])

@formField('input', [
    'name' => 'title',
    'label' => 'Judul Fasilitas (Contoh: Gedung Milik Sendiri)',
    'translatable' => true
])

@formField('input', [
    'name' => 'description',
    'label' => 'Deskripsi Singkat',
    'translatable' => true,
    'type' => 'textarea',
    'rows' => 3
])

@formField('input', [
    'name' => 'button_text',
    'label' => 'Teks Tombol (Biarkan kosong untuk default: Read More)',
    'translatable' => true
])

@formField('input', [
    'name' => 'button_url',
    'label' => 'Link/URL Tombol (Contoh: /fasilitas/gedung)'
])