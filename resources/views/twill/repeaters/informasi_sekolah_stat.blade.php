@twillRepeaterTitle('Statistik Singkat')
@twillRepeaterGroup('app')

<x-twill::input
    name="value"
    label="Nilai (besar, contoh: Negeri, A, 1993, SMK)"
    placeholder="Contoh: Negeri"
    :translated="true"
    :maxlength="50"
/>

<x-twill::input
    name="label"
    label="Label (contoh: Status Perusahaan, Akreditasi)"
    placeholder="Contoh: Status Perusahaan"
    :translated="true"
    :maxlength="100"
/>
