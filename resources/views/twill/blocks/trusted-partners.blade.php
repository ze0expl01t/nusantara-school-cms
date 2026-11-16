@twillBlockTitle('Trusted Partners')
@twillBlockIcon('image-text')
@twillBlockGroup('app')

{{-- ============ HEADER ============ --}}
<x-twill::input
    name="label"
    label="Label Kecil (di atas title)"
    placeholder="PARTNERS"
    default="PARTNERS"
    :translated="true"
/>

<x-twill::input
    name="title_main"
    label="Title — Bagian Utama"
    placeholder="Our"
    default="Our"
    :translated="true"
/>

<x-twill::input
    name="title_highlight"
    label="Title — Bagian Highlight (warna emerald)"
    placeholder="Trusted"
    default="Trusted"
    :translated="true"
/>

<x-twill::input
    name="title_suffix"
    label="Title — Bagian Akhir"
    placeholder="Partners"
    default="Partners"
    :translated="true"
/>

<x-twill::input
    name="subtitle"
    label="Subtitle / Deskripsi"
    type="textarea"
    :rows="2"
    :translated="true"
/>

<hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">

{{-- ============ SETTINGS ============ --}}
<x-twill::input
    name="per_page"
    label="Jumlah Kartu per Halaman"
    type="number"
    default="2"
    note="Recommended: 2 (mobile 1 otomatis)"
/>

<x-twill::input
    name="autoplay_delay"
    label="Autoplay Delay (ms)"
    type="number"
    default="5000"
    note="5000 = 5 detik. Set 0 untuk disable autoplay."
/>

<hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">

{{-- ============ REPEATER ============ --}}
<x-twill::repeater type="trusted-partner-item" />