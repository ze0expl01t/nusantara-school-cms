@twillBlockTitle('Video Gallery')
@twillBlockIcon('video')
@twillBlockGroup('app')

<x-twill::input
    name="eyebrow"
    label="Eyebrow / Label Kecil"
    placeholder="Contoh: Video Gallery"
    :translated="true"
/>

<x-twill::input
    name="title_main"
    label="Judul Utama"
    placeholder="Contoh: Watch our"
    :translated="true"
/>

<x-twill::input
    name="title_highlight"
    label="Judul Sorotan / Gradasi"
    placeholder="Contoh: latest videos"
    :translated="true"
/>

<x-twill::input
    name="subtitle"
    label="Subjudul / Deskripsi Section"
    type="textarea"
    :rows="3"
    :translated="true"
/>

{{-- Repeater untuk item video --}}
<x-twill::repeater type="video_item" />