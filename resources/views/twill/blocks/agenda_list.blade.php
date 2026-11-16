@twillBlockTitle('Daftar Agenda')
@twillBlockIcon('text')
@twillBlockGroup('app')

@formField('input', [
    'name' => 'title',
    'label' => 'Judul Section',
    'translatable' => true
])

@formField('input', [
    'name' => 'limit',
    'label' => 'Jumlah Maksimal Agenda yang Ditampilkan',
    'default' => 6
])

{{-- Editor WYSIWYG Quill Lengkap untuk Agenda --}}
<x-twill::wysiwyg
    type="quill"
    name="description"
    label="Deskripsi Agenda"
    placeholder="Tulis deskripsi atau informasi detail agenda di sini..."
    :toolbar-options="['bold', 'italic', 'underline', 'strike', 'blockquote', ['list' => 'ordered'], ['list' => 'bullet'], 'link', 'clean']"
    :translated="true"
/>