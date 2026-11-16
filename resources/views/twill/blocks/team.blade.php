@twillBlockTitle('Tim & Pengajar')
@twillBlockIcon('image-text')
@twillBlockGroup('app')

<x-twill::input
    name="title"
    label="Judul Section"
    placeholder="Tim & Pengajar Kami"
    default="Tim & Pengajar Kami"
    :translated="true"
    :maxlength="120"
/>

<x-twill::input
    name="badge_text"
    label="Badge Text (pill di atas judul)"
    placeholder="TIM KAMI"
    default="TIM KAMI"
    :translated="true"
    :maxlength="40"
/>

<x-twill::input
    name="subtitle"
    label="Subtitle / Deskripsi (opsional)"
    type="textarea"
    :rows="2"
    :translated="true"
/>

<hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">

<x-twill::select
    name="filter_department"
    label="Filter Departemen"
    default="all"
    :options="[
        ['value' => 'all', 'label' => 'Semua Departemen'],
        ['value' => 'MIPA', 'label' => 'MIPA'],
        ['value' => 'IPS', 'label' => 'IPS'],
        ['value' => 'Bahasa', 'label' => 'Bahasa'],
        ['value' => 'Kejuruan', 'label' => 'Kejuruan'],
        ['value' => 'Umum', 'label' => 'Umum'],
        ['value' => 'Pimpinan', 'label' => 'Pimpinan'],
    ]"
/>

<x-twill::checkbox
    name="only_leadership"
    label="Tampilkan Hanya Pimpinan"
    default="false"
/>

<x-twill::input
    name="limit"
    label="Jumlah Maksimal"
    type="number"
    default="8"
    note="Set 0 untuk tampilkan semua."
/>

<x-twill::select
    name="columns"
    label="Kolom per Baris (desktop)"
    default="4"
    :options="[
        ['value' => '3', 'label' => '3 Kolom'],
        ['value' => '4', 'label' => '4 Kolom (default)'],
        ['value' => '5', 'label' => '5 Kolom'],
    ]"
/>
