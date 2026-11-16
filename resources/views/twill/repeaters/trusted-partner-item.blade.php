@twillRepeaterTitle('Partner Logo')
@twillRepeaterTrigger('Add Partner')
@twillRepeaterGroup('app')
@twillRepeaterMax(20)

{{-- Logo Image --}}
<x-twill::medias
    name="logo"
    label="Logo Partner"
    :max="1"
    note="Format PNG/SVG transparan, ratio 16:9 atau 1:1"
/>

{{-- Nama Partner (untuk alt text) --}}
<x-twill::input
    name="name"
    label="Nama Partner"
    placeholder="Contoh: Google Cloud"
    note="Untuk alt text & SEO"
    :translated="true"
/>

{{-- URL (opsional) --}}
<x-twill::input
    name="url"
    label="Website URL (opsional)"
    placeholder="https://partner.com"
    note="Kalau diisi, logo bisa diklik"
/>

{{-- Baris Penempatan --}}
<x-twill::select
    name="row"
    label="Baris"
    default="top"
    :options="[
        ['value' => 'top', 'label' => 'Baris 1 (scroll ke kiri)'],
        ['value' => 'bottom', 'label' => 'Baris 2 (scroll ke kanan)'],
    ]"
/>

{{-- Urutan --}}
<x-twill::input
    name="order"
    label="Urutan (angka kecil dulu)"
    type="number"
    default="0"
/>