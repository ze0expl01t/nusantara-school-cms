@twillRepeaterTitle('Item Video')
@twillRepeaterGroup('app')

<x-twill::input
    name="youtube_url"
    label="URL YouTube"
    note="Format: youtube.com/watch?v=ID, youtu.be/ID, youtube.com/embed/ID, youtube.com/shorts/ID, atau ID 11 karakter langsung."
    :maxlength="255"
/>

<x-twill::input
    name="title"
    label="Judul Video"
    :translated="true"
    :maxlength="150"
/>

<x-twill::input
    name="description"
    label="Deskripsi Singkat"
    type="textarea"
    :rows="3"
    :translated="true"
/>

<x-twill::input
    name="duration"
    label="Durasi (opsional)"
    placeholder="Contoh: 05:24"
    :maxlength="20"
/>

<x-twill::medias
    name="custom_thumbnail"
    label="Thumbnail Kustom (opsional)"
    :max="1"
/>