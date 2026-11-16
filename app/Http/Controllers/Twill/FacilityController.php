<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class FacilityController extends BaseModuleController
{
    protected $moduleName = 'facilities';

    protected function setUpController(): void
    {
        $this->enableReorder();
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(
            Input::make()
                ->name('title')
                ->label('Nama Fasilitas')
                ->translatable()
                ->required()
        );

        $form->add(
            Input::make()
                ->name('badge')
                ->label('Badge / Status (Contoh: Baik)')
                ->translatable()
        );

        $form->add(
            Input::make()
                ->name('stat_label')
                ->label('Label Statistik (Contoh: Jumlah:)')
                ->translatable()
        );

        $form->add(
            Input::make()
                ->name('stat_value')
                ->label('Nilai Statistik (Contoh: 8)')
        );

        $form->add(
            Input::make()
                ->name('capacity_label')
                ->label('Label Daya Tampung (Contoh: Daya Tampung:)')
                ->translatable()
        );

        $form->add(
            Input::make()
                ->name('capacity_value')
                ->label('Nilai Daya Tampung (Contoh: 200 orang)')
        );

        $form->add(
            Input::make()
                ->name('icon')
                ->label('Ikon (kode SVG)')
                ->type('textarea')
                ->rows(3)
        );

        $form->add(
            Medias::make()
                ->name('cover')
                ->label('Foto Fasilitas (opsional)')
        );

        $form->add(
            Wysiwyg::make()
                ->name('description')
                ->label('Deskripsi Fasilitas')
                ->placeholder('Tulis deskripsi fasilitas di sini...')
                ->toolbarOptions(['bold', 'italic', 'underline', 'strike', 'blockquote', ['list' => 'ordered'], ['list' => 'bullet'], 'link', 'clean'])
                ->translatable()
        );

        // ===== SEO FIELDS =====
        $form->add(
            Input::make()
                ->name('meta_title')
                ->label('SEO — Meta Title (max 70 karakter)')
                ->maxlength(70)
                ->translatable()
        );

        $form->add(
            Input::make()
                ->name('meta_description')
                ->label('SEO — Meta Description (max 160 karakter)')
                ->type('textarea')
                ->rows(3)
                ->maxlength(160)
                ->translatable()
        );

        $form->add(
            Medias::make()
                ->name('og_image')
                ->label('SEO — OG Image (1200x630)')
        );

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            Text::make()
                ->field('badge')
                ->title('Badge')
        );

        $table->add(
            Text::make()
                ->field('stat_value')
                ->title('Jumlah')
        );

        return $table;
    }
}
