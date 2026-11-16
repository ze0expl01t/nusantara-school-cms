@twillBlockTitle('Daftar Lowongan Kerja')
@twillBlockIcon('image-text')
@twillBlockGroup('app')

<x-twill::input
    name="badge_text"
    label="Badge / Label Section"
    placeholder="Contoh: Bursa Kerja"
    :translated="true"
/>

<x-twill::input
    name="title_main"
    label="Judul Utama"
    placeholder="Contoh: Lowongan Kerja"
    :translated="true"
/>

<x-twill::input
    name="title_highlight"
    label="Judul Sorotan"
    placeholder="Contoh: & Karier"
    :translated="true"
/>

<x-twill::input
    name="subtitle"
    label="Deskripsi Section (opsional)"
    type="textarea"
    :rows="2"
    :translated="true"
/>

<x-twill::select
    name="filter_type"
    label="Filter Tipe Pekerjaan"
    default="all"
    :options="[
        ['value' => 'all', 'label' => 'Semua Tipe'],
        ['value' => 'full-time', 'label' => 'Full Time'],
        ['value' => 'part-time', 'label' => 'Part Time'],
        ['value' => 'kontrak', 'label' => 'Kontrak'],
        ['value' => 'magang', 'label' => 'Magang'],
        ['value' => 'freelance', 'label' => 'Freelance'],
    ]"
/>

<x-twill::checkbox
    name="only_active"
    label="Hanya Tampilkan Lowongan Aktif"
    default="true"
/>

<x-twill::input
    name="limit"
    label="Jumlah Maksimal"
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
    placeholder="Lihat Semua Lowongan"
    :translated="true"
/>
