@twillRepeaterTitle('Partner')
@twillRepeaterTrigger('Add Partner')
@twillRepeaterGroup('app')
@twillRepeaterMax(20)

{{-- Logo --}}
<x-twill::medias
    name="logo"
    label="Logo Partner"
    :max="1"
    note="Format PNG/SVG transparan. Recommended ratio 1:1 atau 16:9."
/>

{{-- Nama Partner --}}
<x-twill::input
    name="name"
    label="Nama Partner"
    placeholder="Contoh: Google Cloud"
    :translated="true"
/>

{{-- Kategori / Label --}}
<x-twill::input
    name="category"
    label="Kategori / Subtitle"
    placeholder="Contoh: Cloud Infrastructure"
    :translated="true"
/>

{{-- Deskripsi Singkat --}}
<x-twill::input
    name="description"
    label="Deskripsi Singkat (opsional)"
    type="textarea"
    :rows="3"
    :translated="true"
/>

{{-- URL --}}
<x-twill::input
    name="url"
    label="Website URL (opsional)"
    placeholder="https://partner.com"
/>

{{-- Urutan --}}
<x-twill::input
    name="order"
    label="Urutan (angka kecil dulu)"
    type="number"
    default="0"
/>