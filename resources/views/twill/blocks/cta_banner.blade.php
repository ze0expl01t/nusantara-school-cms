@twillBlockTitle('CTA Banner')
@twillBlockIcon('image-text')
@twillBlockGroup('app')

<x-twill::input
    name="badge_text"
    label="Badge / Label (opsional)"
    placeholder="Contoh: PPDB 2026/2027"
    :translated="true"
    :maxlength="60"
/>

<x-twill::input
    name="title"
    label="Judul Utama"
    placeholder="Contoh: Daftar Sekarang!"
    :translated="true"
    :maxlength="200"
    required
/>

<x-twill::input
    name="highlight_text"
    label="Kata Sorotan (opsional)"
    placeholder="Contoh: Gratis Biaya Pendaftaran"
    :translated="true"
    :maxlength="100"
    note="Akan ditampilkan dengan warna berbeda di bawah judul."
/>

<x-twill::input
    name="subtitle"
    label="Deskripsi (opsional)"
    type="textarea"
    :rows="3"
    :translated="true"
/>

<hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">

<x-twill::input
    name="button_primary_text"
    label="Tombol Utama — Text"
    placeholder="Contoh: Daftar Sekarang"
    default="Daftar Sekarang"
    :translated="true"
/>

<x-twill::input
    name="button_primary_url"
    label="Tombol Utama — URL"
    placeholder="/ppdb"
    default="/ppdb"
/>

<x-twill::input
    name="button_secondary_text"
    label="Tombol Sekunder — Text (opsional)"
    placeholder="Contoh: Hubungi Kami"
    :translated="true"
/>

<x-twill::input
    name="button_secondary_url"
    label="Tombol Sekunder — URL (opsional)"
    placeholder="/kontak"
/>

<hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">

<x-twill::select
    name="style"
    label="Style Background"
    default="gradient-emerald"
    :options="[
        ['value' => 'gradient-emerald', 'label' => 'Gradient Emerald (default)'],
        ['value' => 'gradient-blue', 'label' => 'Gradient Blue'],
        ['value' => 'gradient-amber', 'label' => 'Gradient Amber'],
        ['value' => 'gradient-dark', 'label' => 'Gradient Dark'],
        ['value' => 'solid-white', 'label' => 'Solid White (light)'],
    ]"
/>

<x-twill::select
    name="alignment"
    label="Perataan Konten"
    default="center"
    :options="[
        ['value' => 'center', 'label' => 'Tengah'],
        ['value' => 'left', 'label' => 'Kiri'],
        ['value' => 'split', 'label' => 'Kiri Text, Kanan Tombol'],
    ]"
/>

<x-twill::select
    name="size"
    label="Ukuran Padding"
    default="normal"
    :options="[
        ['value' => 'compact', 'label' => 'Kompak'],
        ['value' => 'normal', 'label' => 'Normal (default)'],
        ['value' => 'large', 'label' => 'Besar'],
    ]"
/>

<x-twill::medias
    name="background_image"
    label="Background Image (opsional)"
    note="Rasio 16:9 atau lebih lebar. Akan di-overlay gelap."
/>

<x-twill::checkbox
    name="show_pattern"
    label="Tampilkan Pattern Dekoratif"
    default="true"
/>
