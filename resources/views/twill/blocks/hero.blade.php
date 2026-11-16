@twillBlockTitle('Hero')
@twillBlockIcon('text')
@twillBlockGroup('app')

<x-twill::input
    name="badge"
    label="Badge Text"
    :translated="true"
/>

<x-twill::input
    name="title"
    label="Title"
    :translated="true"
/>

<x-twill::input
    name="animated_words"
    label="Animated Words"
    type="textarea"
    :rows="4"
    :translated="true"
    placeholder="Workplace Safety. Health Protection. Environmental Care. Risk Management."
    note="Satu frasa per baris. Setiap baris akan muncul bergantian dengan animasi."
/>

<x-twill::wysiwyg
    type="quill"
    name="text"
    label="Text / Description"
    placeholder="Enter description..."
    :toolbar-options="[
        'bold',
        'italic',
        'ordered',
        'bullet',
        [ 'script' => 'super' ],
        [ 'script' => 'sub' ],
        'link',
        'clean'
    ]"
    :translated="true"
/>

<div class="row">
    <div class="col-6">
        <x-twill::input
            name="btn_primary_text"
            label="Primary Button Text"
            :translated="true"
        />
    </div>
    <div class="col-6">
        <x-twill::input
            name="btn_primary_url"
            label="Primary Button URL / Anchor"
        />
    </div>
</div>

<div class="row">
    <div class="col-6">
        <x-twill::input
            name="btn_secondary_text"
            label="Secondary Button Text"
            :translated="true"
        />
    </div>
    <div class="col-6">
        <x-twill::input
            name="btn_secondary_url"
            label="Secondary Button URL / Anchor"
        />
    </div>
</div>

<x-twill::medias
    name="background_image"
    label="Background Image"
    max="1"
/>