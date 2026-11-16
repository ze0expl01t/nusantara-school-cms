@twillRepeaterTitle('Slide Item')
@twillRepeaterGroup('app')

@formField('medias', [
    'name' => 'background_image',
    'label' => 'Background Image',
    'max' => 1
])

@formField('input', [
    'name' => 'headline_main',
    'label' => 'Headline (Teks Putih Utama)',
    'translatable' => true
])

@formField('input', [
    'name' => 'headline_highlight',
    'label' => 'Headline (Teks Berwarna Merah)',
    'translatable' => true
])

@formField('input', [
    'name' => 'subheading_1',
    'label' => 'Subheading 1 (Baris Pertama)',
    'translatable' => true
])

@formField('input', [
    'name' => 'subheading_2',
    'label' => 'Subheading 2 (Baris Kedua)',
    'translatable' => true
])