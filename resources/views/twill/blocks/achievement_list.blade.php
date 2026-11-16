@twillBlockTitle('Daftar Pencapaian')
@twillBlockIcon('image-text')
@twillBlockGroup('app')

<x-twill::input
    name="badge_text"
    label="Badge / Label Section"
    placeholder="Contoh: Pencapaian"
    :translated="true"
/>

<x-twill::input
    name="title_main"
    label="Judul Utama"
    placeholder="Contoh: Pencapaian"
    :translated="true"
/>

<x-twill::input
    name="title_highlight"
    label="Judul Sorotan"
    placeholder="Contoh: & Penghargaan"
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
    name="filter_level"
    label="Filter Tingkat"
    default="all"
    :options="[
        ['value' => 'all', 'label' => 'Semua Tingkat'],
        ['value' => 'perusahaan', 'label' => 'Perusahaan'],
        ['value' => 'kecamatan', 'label' => 'Kecamatan'],
        ['value' => 'kabupaten', 'label' => 'Kabupaten/Kota'],
        ['value' => 'provinsi', 'label' => 'Provinsi'],
        ['value' => 'nasional', 'label' => 'Nasional'],
        ['value' => 'internasional', 'label' => 'Internasional'],
    ]"
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
    placeholder="Lihat Semua Pencapaian"
    :translated="true"
/>
