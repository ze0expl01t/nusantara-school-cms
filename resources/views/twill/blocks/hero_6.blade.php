@twillBlockTitle('Hero 6 (Visi & Misi Dua Kolom)')
@twillBlockIcon('text')
@twillBlockGroup('app')

{{-- Kolom Kiri (Visi) --}}
@formField('input', [
    'name' => 'left_title_main',
    'label' => 'Heading Kiri (Contoh: Visi)',
    'translatable' => true
])

@formField('input', [
    'name' => 'left_title_highlight',
    'label' => 'Sorotan Heading Kiri (Contoh: PT. Nama Perusahaan)',
    'translatable' => true
])

@formField('input', [
    'name' => 'left_sub_heading',
    'label' => 'Sub Heading Kiri (Contoh: Kami hadir untuk memberikan solusi terbaik.)',
    'translatable' => true
])

{{-- Editor WYSIWYG Quill Lengkap untuk Visi --}}
<x-twill::wysiwyg
    type="quill"
    name="left_description"
    label="Deskripsi Kiri (Isi Visi & Poin-poin)"
    placeholder="Tulis deskripsi atau poin visi di sini..."
    :toolbar-options="['bold', 'italic', 'underline', 'strike', 'blockquote', ['list' => 'ordered'], ['list' => 'bullet'], 'link', 'clean']"
    :translated="true"
/>


{{-- Kolom Kanan (Misi) --}}
@formField('input', [
    'name' => 'right_title_main',
    'label' => 'Heading Kanan (Contoh: Misi)',
    'translatable' => true
])

@formField('input', [
    'name' => 'right_title_highlight',
    'label' => 'Sorotan Heading Kanan (Contoh: PT. Nama Perusahaan)',
    'translatable' => true
])

@formField('input', [
    'name' => 'right_sub_heading',
    'label' => 'Sub Heading Kanan (Contoh: Langkah-langkah strategis...)',
    'translatable' => true
])

{{-- Editor WYSIWYG Quill Lengkap untuk Misi --}}
<x-twill::wysiwyg
    type="quill"
    name="right_description"
    label="Deskripsi Kanan (Isi Misi & Poin-poin)"
    placeholder="Tulis deskripsi atau poin misi di sini..."
    :toolbar-options="['bold', 'italic', 'underline', 'strike', 'blockquote', ['list' => 'ordered'], ['list' => 'bullet'], 'link', 'clean']"
    :translated="true"
/>