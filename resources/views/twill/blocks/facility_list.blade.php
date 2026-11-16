@twillBlockTitle('Daftar Fasilitas')
@twillBlockIcon('image-text')
@twillBlockGroup('app')

<x-twill::input
    name="badge_text"
    label="Badge / Label Section"
    placeholder="Contoh: Fasilitas & Sarana"
    :translated="true"
/>

<x-twill::input
    name="title_main"
    label="Judul Utama"
    placeholder="Contoh: Pendidikan"
    :translated="true"
/>

<x-twill::input
    name="title_highlight"
    label="Judul Sorotan"
    placeholder="Contoh: & Fasilitas"
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
    label="Jumlah Maksimal Fasilitas"
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
    placeholder="Lihat Semua Fasilitas"
    :translated="true"
/>
