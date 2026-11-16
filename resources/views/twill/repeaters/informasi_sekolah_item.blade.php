@twillRepeaterTitle('Detail Identitas')
@twillRepeaterGroup('app')

<x-twill::input
    name="label"
    label="Label"
    placeholder="Contoh: Nama Perusahaan"
    :translated="true"
    :maxlength="100"
/>

<x-twill::input
    name="value"
    label="Nilai"
    placeholder="Contoh: SMKS MUHAMMADIYAH 1 BLORA"
    type="textarea"
    :rows="2"
    :translated="true"
/>
