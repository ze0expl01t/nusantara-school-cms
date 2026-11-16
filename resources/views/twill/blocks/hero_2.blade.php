@twillBlockTitle('Hero 2')
@twillBlockIcon('text')
@twillBlockGroup('app')

<x-twill::input
    name="badge"
    label="Badge / Subtitle (e.g. ABOUT TCPINDO)"
    :translated="true"
/>

<x-twill::input
    name="title"
    label="Main Title"
    :translated="true"
/>

<x-twill::wysiwyg
    type="quill"
    name="description"
    label="Description"
    placeholder="Enter description..."
    :toolbar-options="['bold', 'italic', 'link']"
    :translated="true"
/>

<hr class="my-4">

<div class="row">
    <div class="col-6">
        <x-twill::input
            name="stat_1_value"
            label="Statistic 1 Value (e.g. 100%)"
        />
    </div>
    <div class="col-6">
        <x-twill::input
            name="stat_1_label"
            label="Statistic 1 Label (e.g. Enterprise Security Standard)"
            :translated="true"
        />
    </div>
</div>

<div class="row">
    <div class="col-6">
        <x-twill::input
            name="stat_2_value"
            label="Statistic 2 Value (e.g. 24/7)"
        />
    </div>
    <div class="col-6">
        <x-twill::input
            name="stat_2_label"
            label="Statistic 2 Label (e.g. Professional Engineer Support)"
            :translated="true"
        />
    </div>
</div>

<x-twill::medias
    name="hero_image"
    label="Side Image"
    max="1"
/>