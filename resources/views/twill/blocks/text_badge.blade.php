@twillBlockTitle('Text Badge')
@twillBlockIcon('text')
@twillBlockGroup('app')

<x-twill::input
    name="badge"
    label="Badge / Subtitle"
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
    :toolbar-options="['bold', 'italic', 'link', ['list' => 'ordered'], ['list' => 'bullet']]"
    :translated="true"
/>