@twillRepeaterTitle('FAQ Item')
@twillRepeaterTrigger('Tambah FAQ')

<x-twill::input
    name="question"
    label="Pertanyaan"
    placeholder="Contoh: Bagaimana cara mendaftar?"
    :translated="true"
    :maxlength="300"
    required
/>

<x-twill::input
    name="answer"
    label="Jawaban"
    type="textarea"
    :rows="4"
    :translated="true"
    required
/>

<x-twill::checkbox
    name="is_open"
    label="Buka default (accordion terbuka saat halaman load)"
    default="false"
/>
