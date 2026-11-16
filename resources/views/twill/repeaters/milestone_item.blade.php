@twillRepeaterTitle('Milestone Item')
@twillRepeaterTrigger('Tambah Milestone')

<x-twill::input
    name="year"
    label="Tahun / Tanggal"
    placeholder="Contoh: 2010 atau Januari 2010"
    :maxlength="30"
    required
/>

<x-twill::input
    name="title"
    label="Judul Milestone"
    placeholder="Contoh: Berdiri dengan 3 Divisi"
    :translated="true"
    :maxlength="200"
    required
/>

<x-twill::input
    name="description"
    label="Deskripsi"
    type="textarea"
    :rows="3"
    :translated="true"
    note="Ceritakan pencapaian atau peristiwa penting di tahun ini."
/>

<x-twill::medias
    name="image"
    label="Gambar (opsional)"
    note="Rasio 4:3, minimal 800x600px"
/>

<x-twill::checkbox
    name="is_highlight"
    label="Tandai sebagai Highlight (warna emas)"
    default="false"
/>
