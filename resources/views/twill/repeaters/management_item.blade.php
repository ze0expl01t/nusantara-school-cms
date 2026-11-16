@twillRepeaterTitle('Profil Personel')
@twillRepeaterGroup('app')

@formField('medias', [
    'name' => 'photo',
    'label' => 'Foto Personel (Rekomendasi rasio portrait 3:4)',
    'max' => 1
])

@formField('input', [
    'name' => 'name',
    'label' => 'Nama Personel',
    'translatable' => true
])

@formField('input', [
    'name' => 'position',
    'label' => 'Jabatan (Contoh: Kepala Perusahaan)',
    'translatable' => true
])

{{-- Sosial Media (Opsional) --}}
@formField('input', [
    'name' => 'facebook_url',
    'label' => 'Link Facebook (Kosongkan jika tidak ada)'
])

@formField('input', [
    'name' => 'instagram_url',
    'label' => 'Link Instagram (Kosongkan jika tidak ada)'
])

@formField('input', [
    'name' => 'linkedin_url',
    'label' => 'Link LinkedIn (Kosongkan jika tidak ada)'
])