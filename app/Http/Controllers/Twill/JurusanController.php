<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class JurusanController extends BaseModuleController
{
    protected $moduleName = 'jurusans';

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
                ->label('Nama Jurusan')
                ->translatable()
                ->required()
        );

        $form->add(
            Input::make()
                ->name('short_name')
                ->label('Singkatan (TKJ, RPL, DKV)')
                ->maxlength(20)
                ->translatable()
        );

        $form->add(
            Input::make()
                ->name('excerpt')
                ->label('Deskripsi Singkat')
                ->type('textarea')
                ->rows(3)
                ->maxlength(500)
                ->translatable()
        );

        $form->add(
            Input::make()
                ->name('head_of_department')
                ->label('Kepala Jurusan')
        );

        $form->add(
            Select::make()
                ->name('accreditation')
                ->label('Akreditasi')
                ->options([
                    'A' => 'A (Unggul)',
                    'B' => 'B (Baik)',
                    'C' => 'C (Cukup)',
                    '-' => 'Belum Terakreditasi',
                ])
        );

        $form->add(
            Input::make()
                ->name('code')
                ->label('Kode Jurusan (internal)')
                ->maxlength(20)
        );

        $form->add(
            Input::make()
                ->name('color')
                ->label('Accent Color')
                ->maxlength(20)
        );

        $form->add(
            Input::make()
                ->name('vision')
                ->label('Visi Jurusan')
                ->type('textarea')
                ->rows(4)
                ->translatable()
        );

        $form->add(
            Input::make()
                ->name('mission')
                ->label('Misi Jurusan (satu per baris)')
                ->type('textarea')
                ->rows(6)
                ->translatable()
        );

        $form->add(
            Wysiwyg::make()
                ->name('description')
                ->label('Deskripsi Lengkap')
                ->toolbarOptions(['bold', 'italic', 'underline', 'strike', 'blockquote', ['list' => 'ordered'], ['list' => 'bullet'], 'link', 'clean'])
                ->translatable()
        );

        $form->add(
            Medias::make()->name('cover')->label('Cover / Banner (16:9)')
        );

        $form->add(
            Medias::make()->name('icon')->label('Icon Jurusan (1:1)')
        );

        $form->add(
            Medias::make()->name('gallery')->label('Galeri Foto')
        );

        // SEO
        $form->add(
            Input::make()
                ->name('meta_title')
                ->label('SEO — Meta Title (max 70)')
                ->maxlength(70)
                ->translatable()
        );

        $form->add(
            Input::make()
                ->name('meta_description')
                ->label('SEO — Meta Description (max 160)')
                ->type('textarea')
                ->rows(3)
                ->maxlength(160)
                ->translatable()
        );

        $form->add(
            Medias::make()->name('og_image')->label('SEO — OG Image (1200x630)')
        );

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            Text::make()->field('short_name')->title('Singkatan')
        );

        $table->add(
            Text::make()->field('accreditation')->title('Akreditasi')
        );

        return $table;
    }
}
