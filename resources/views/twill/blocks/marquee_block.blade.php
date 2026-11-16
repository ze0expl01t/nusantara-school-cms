@twillBlockTitle('Marquee Berjalan (Modern Slider)')
@twillBlockIcon('text')
@twillBlockGroup('app')

@formField('input', [
    'name' => 'title_main',
    'label' => 'Judul Utama (Opsional, Contoh: Kualitas Karyawan & Siswi)',
    'translatable' => true
])

{{-- Repeater untuk teks/item yang berjalan di dalam marquee --}}
@formField('repeater', ['type' => 'marquee_item'])