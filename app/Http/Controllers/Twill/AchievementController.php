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

class AchievementController extends BaseModuleController
{
    protected $moduleName = 'achievements';

    protected function setUpController(): void
    {
        $this->enableReorder();
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(
            Input::make()->name('title')->label('Nama Prestasi')->translatable()->required()
        );

        $form->add(
            Input::make()->name('student_name')->label('Nama Siswa / Tim')->translatable()
        );

        $form->add(
            Input::make()->name('excerpt')->label('Ringkasan (max 300)')
                ->type('textarea')->rows(3)->maxlength(300)->translatable()
        );

        $form->add(
            Input::make()->name('organizer')->label('Penyelenggara')->translatable()
        );

        $form->add(
            Select::make()->name('level')->label('Tingkat')
                ->options([
                    'sekolah' => 'Sekolah',
                    'kecamatan' => 'Kecamatan',
                    'kabupaten' => 'Kabupaten/Kota',
                    'provinsi' => 'Provinsi',
                    'nasional' => 'Nasional',
                    'internasional' => 'Internasional',
                ])
        );

        $form->add(
            Select::make()->name('rank')->label('Peringkat')
                ->options([
                    'juara-1' => 'Juara 1',
                    'juara-2' => 'Juara 2',
                    'juara-3' => 'Juara 3',
                    'harapan' => 'Juara Harapan',
                    'finalis' => 'Finalis',
                    'partisipasi' => 'Partisipasi',
                ])
        );

        $form->add(
            Input::make()->name('achievement_date')
                ->label('Tanggal Prestasi')
                ->type('date')
        );

        $form->add(
            Wysiwyg::make()->name('description')->label('Deskripsi Lengkap')
                ->toolbarOptions(['bold', 'italic', 'underline', 'strike', 'blockquote', ['list' => 'ordered'], ['list' => 'bullet'], 'link', 'clean'])
                ->translatable()
        );

        $form->add(Medias::make()->name('cover')->label('Cover / Banner (16:9)'));
        $form->add(Medias::make()->name('photo')->label('Foto Siswa / Tim'));
        $form->add(Medias::make()->name('certificate')->label('Foto Sertifikat / Piala'));
        $form->add(Medias::make()->name('gallery')->label('Galeri Foto'));

        $form->add(
            Input::make()->name('meta_title')->label('SEO — Meta Title (max 70)')
                ->maxlength(70)->translatable()
        );

        $form->add(
            Input::make()->name('meta_description')->label('SEO — Meta Description (max 160)')
                ->type('textarea')->rows(3)->maxlength(160)->translatable()
        );

        $form->add(Medias::make()->name('og_image')->label('SEO — OG Image (1200x630)'));

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(Text::make()->field('level')->title('Tingkat'));
        $table->add(Text::make()->field('rank')->title('Peringkat'));
        $table->add(Text::make()->field('achievement_date')->title('Tanggal'));

        return $table;
    }
}
