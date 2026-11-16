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

class ExtracurricularController extends BaseModuleController
{
    protected $moduleName = 'extracurriculars';

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
                ->label('Nama Ekstrakurikuler')
                ->translatable()
                ->required()
        );

        $form->add(
            Input::make()
                ->name('category')
                ->label('Kategori (Contoh: Olahraga, Seni, Akademik)')
        );

        $form->add(
            Input::make()
                ->name('schedule')
                ->label('Jadwal (Contoh: Sabtu, 07.30 - 10.00 WIB)')
        );

        $form->add(
            Input::make()
                ->name('location')
                ->label('Lokasi (Contoh: Lapangan Sekolah)')
        );

        $form->add(
            Input::make()
                ->name('advisor_name')
                ->label('Nama Pembina')
        );

        $form->add(
            Input::make()
                ->name('advisor_role')
                ->label('Jabatan Pembina (Contoh: Pembina Ekskul)')
        );

        $form->add(
            Medias::make()
                ->name('cover')
                ->label('Cover Image')
        );

        $form->add(
            Medias::make()
                ->name('advisor_photo')
                ->label('Foto Pembina (opsional)')
        );

        $form->add(
            Wysiwyg::make()
                ->name('description')
                ->label('Deskripsi Ekstrakurikuler')
                ->placeholder('Tulis deskripsi ekstrakurikuler di sini...')
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
                ->field('category')
                ->title('Kategori')
        );

        $table->add(
            Text::make()
                ->field('schedule')
                ->title('Jadwal')
        );

        return $table;
    }
}
