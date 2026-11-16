@twillBlockTitle('Hero 3')
@twillBlockIcon('text')
@twillBlockGroup('app')

<x-twill::input
    name="subtitle"
    label="Subtitle / Badge (e.g. WHAT WE DELIVER)"
    :translated="true"
/>

<x-twill::input
    name="title"
    label="Main Title (e.g. Core Enterprise Services)"
    :translated="true"
/>

<x-twill::wysiwyg
    type="quill"
    name="description"
    label="Description"
    placeholder="Enter section description..."
    :toolbar-options="['bold', 'italic', 'link']"
    :translated="true"
/>