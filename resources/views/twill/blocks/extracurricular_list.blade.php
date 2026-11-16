@twillBlockTitle('Daftar Ekstrakurikuler')
@twillBlockIcon('image-text')
@twillBlockGroup('app')

<x-twill::input
    name="badge_text"
    label="Badge / Label Section"
    placeholder="Contoh: Ekstrakurikuler"
    :translated="true"
/>

<x-twill::input
    name="title_main"
    label="Judul Utama"
    placeholder="Contoh: Ekstrakurikuler"
    :translated="true"
/>

<x-twill::input
    name="title_highlight"
    label="Judul Sorotan"
    placeholder="Contoh: & Pengembangan Diri"
    :translated="true"
/>

<x-twill::input
    name="subtitle"
    label="Deskripsi Section (opsional)"
    type="textarea"
    :rows="2"
    :translated="true"
/>

<x-twill::input
    name="limit"
    label="Jumlah Maksimal Ekstrakurikuler"
    type="number"
    default="6"
/>

<x-twill::checkbox
    name="show_button"
    label="Tampilkan tombol 'Lihat Semua'"
/>

<x-twill::input
    name="button_text"
    label="Teks Tombol"
    placeholder="Lihat Semua Ekstrakurikuler"
    :translated="true"
/>