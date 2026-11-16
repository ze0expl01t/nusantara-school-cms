@twillBlockTitle('Informasi Perusahaan')
@twillBlockIcon('text')
@twillBlockGroup('app')

{{-- ============ HEADER ============ --}}
<x-twill::input
    name="eyebrow"
    label="Eyebrow / Label Kecil"
    placeholder="Contoh: Swasta (di bawah naungan Yayasan Majelis Dikdasmen PWM Prov. Jawa Tengah)"
    :translated="true"
/>

<x-twill::input
    name="title_main"
    label="Judul Utama"
    placeholder="Contoh: Identitas"
    :translated="true"
/>

<x-twill::input
    name="title_highlight"
    label="Judul Sorotan"
    placeholder="Contoh: Perusahaan"
    :translated="true"
/>

<x-twill::wysiwyg
    type="quill"
    name="description"
    label="Deskripsi"
    :toolbar-options="['bold', 'italic', 'link']"
    :translated="true"
/>

{{-- ============ GAMBAR ============ --}}
<x-twill::medias
    name="image"
    label="Gambar Ilustrasi (sisi kiri)"
    :max="1"
/>

{{-- ============ CTA BUTTONS ============ --}}
<hr class="my-4">
<h4 class="text-primary font-bold mb-3">CTA Buttons</h4>

<div class="row">
    <div class="col-5">
        <x-twill::input
            name="cta_primary_text"
            label="CTA Utama — Text"
            default="#Kontak Kami"
            :translated="true"
        />
    </div>
    <div class="col-7">
        <x-twill::input
            name="cta_primary_url"
            label="CTA Utama — URL"
            placeholder="#kontak atau /kontak"
        />
    </div>
</div>

<div class="row">
    <div class="col-5">
        <x-twill::input
            name="cta_secondary_text"
            label="CTA Sekunder — Text"
            default="Konsultasi"
            :translated="true"
        />
    </div>
    <div class="col-7">
        <x-twill::input
            name="cta_secondary_url"
            label="CTA Sekunder — URL"
            placeholder="https://wa.me/..."
        />
    </div>
</div>

{{-- ============ REPEATERS ============ --}}
<hr class="my-4">

<x-twill::repeater type="informasi_perusahaan_item" />

<x-twill::repeater type="informasi_perusahaan_stat" />
