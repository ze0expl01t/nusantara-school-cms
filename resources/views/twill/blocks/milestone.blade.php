@twillBlockTitle('Milestone / Timeline')
@twillBlockIcon('image-text')
@twillBlockGroup('app')

<x-twill::input
    name="badge_text"
    label="Badge / Label Section"
    placeholder="Contoh: Perjalanan Kami"
    default="Perjalanan Kami"
    :translated="true"
/>

<x-twill::input
    name="title_main"
    label="Judul Utama"
    placeholder="Contoh: Milestone"
    default="Milestone"
    :translated="true"
/>

<x-twill::input
    name="title_highlight"
    label="Judul Sorotan"
    placeholder="Contoh: & Sejarah"
    default="& Sejarah"
    :translated="true"
/>

<x-twill::input
    name="subtitle"
    label="Deskripsi Section (opsional)"
    type="textarea"
    :rows="2"
    :translated="true"
/>

<hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">

<x-twill::select
    name="layout"
    label="Layout Timeline"
    default="vertical"
    :options="[
        ['value' => 'vertical', 'label' => 'Vertikal (list ke bawah)'],
        ['value' => 'vertical_alternate', 'label' => 'Vertikal Zig-Zag (kiri-kanan)'],
    ]"
/>

<x-twill::repeater type="milestone_item"/>

<hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">

<x-twill::input
    name="closing_text"
    label="Text Penutup (opsional)"
    placeholder="Dan perjalanan masih berlanjut..."
    :translated="true"
/>
