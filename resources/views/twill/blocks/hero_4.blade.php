@twillBlockTitle('Hero 4 (Slider)')
@twillBlockIcon('image')
@twillBlockGroup('app')

@formField('input', [
    'name' => 'badge_text',
    'label' => 'Badge / Label Section',
    'translatable' => true,
    'default' => 'Sorotan Utama',
])

@formField('input', [
    'name' => 'title_main',
    'label' => 'Judul Utama',
    'translatable' => true,
])

@formField('input', [
    'name' => 'title_highlight',
    'label' => 'Judul Sorotan',
    'translatable' => true,
])

@formField('input', [
    'name' => 'subtitle',
    'label' => 'Deskripsi Section (opsional)',
    'type' => 'textarea',
    'rows' => 2,
    'translatable' => true,
])

@formField('repeater', ['type' => 'hero_4_slide'])
