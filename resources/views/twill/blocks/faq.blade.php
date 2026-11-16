@twillBlockTitle('FAQ Accordion')
@twillBlockIcon('image-text')
@twillBlockGroup('app')

<x-twill::input
    name="badge_text"
    label="Badge / Label Section"
    placeholder="Contoh: Tanya Jawab"
    default="FAQ"
    :translated="true"
/>

<x-twill::input
    name="title_main"
    label="Judul Utama"
    placeholder="Contoh: Pertanyaan"
    default="Pertanyaan"
    :translated="true"
/>

<x-twill::input
    name="title_highlight"
    label="Judul Sorotan"
    placeholder="Contoh: Yang Sering Diajukan"
    default="Yang Sering Diajukan"
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

<x-twill::repeater type="faq_item"/>

<hr style="margin: 24px 0; border: none; border-top: 1px solid #e2e8f0;">

<x-twill::input
    name="contact_cta_text"
    label="CTA Text (opsional)"
    placeholder="Masih ada pertanyaan?"
    default="Masih ada pertanyaan?"
    :translated="true"
/>

<x-twill::input
    name="contact_button_text"
    label="CTA Button Text (opsional)"
    placeholder="Hubungi Kami"
    default="Hubungi Kami"
    :translated="true"
/>

<x-twill::input
    name="contact_button_url"
    label="CTA Button URL (opsional)"
    placeholder="/kontak"
    default="/kontak"
/>
