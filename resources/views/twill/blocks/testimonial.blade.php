@twillBlockTitle('Testimonial')
@twillBlockIcon('quote')
@twillBlockGroup('app')

<x-twill::input
    name="title_main"
    label="Judul Utama (Contoh: What they’re)"
    :translated="true"
/>

<x-twill::input
    name="title_highlight"
    label="Judul Sorotan/Biru (Contoh: talking about)"
    :translated="true"
/>

<x-twill::input
    name="subtitle"
    label="Subjudul / Deskripsi Section"
    type="textarea"
    :rows="3"
    :translated="true"
/>

{{-- Repeater untuk item testimoni --}}
<x-twill::repeater type="testimonial_item" />