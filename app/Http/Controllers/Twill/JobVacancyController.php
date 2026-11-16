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

class JobVacancyController extends BaseModuleController
{
    protected $moduleName = 'jobVacancies';

    protected function setUpController(): void
    {
        $this->enableReorder();
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(
            Input::make()->name('title')->label('Posisi / Nama Lowongan')->translatable()->required()
        );

        $form->add(
            Input::make()->name('company_name')->label('Nama Perusahaan')
        );

        $form->add(
            Input::make()->name('excerpt')->label('Ringkasan (max 300)')
                ->type('textarea')->rows(3)->maxlength(300)->translatable()
        );

        $form->add(
            Select::make()->name('job_type')->label('Tipe Pekerjaan')
                ->options([
                    'full-time' => 'Full Time',
                    'part-time' => 'Part Time',
                    'kontrak' => 'Kontrak',
                    'magang' => 'Magang / Internship',
                    'freelance' => 'Freelance',
                ])
        );

        $form->add(
            Input::make()->name('location')->label('Lokasi Kerja')
                ->note('Contoh: Blora, Jawa Tengah')
        );

        $form->add(
            Input::make()->name('salary_range')->label('Range Gaji')
                ->note('Contoh: Rp 3.000.000 - Rp 5.000.000')
        );

        $form->add(
            Input::make()->name('deadline')->label('Batas Lamaran')->type('date')
        );

        $form->add(
            Input::make()->name('apply_url')->label('URL Lamaran (opsional)')
                ->note('Link ke form lamaran eksternal')
        );

        $form->add(
            Input::make()->name('apply_email')->label('Email Lamaran (opsional)')
        );

        $form->add(
            Input::make()->name('contact_person')->label('Contact Person (opsional)')
        );

        $form->add(
            Input::make()->name('contact_phone')->label('Telepon Contact Person (opsional)')
        );

        $form->add(
            Wysiwyg::make()->name('requirements')->label('Persyaratan')
                ->toolbarOptions(['bold', 'italic', 'underline', ['list' => 'ordered'], ['list' => 'bullet'], 'link', 'clean'])
                ->translatable()
        );

        $form->add(
            Wysiwyg::make()->name('description')->label('Deskripsi Pekerjaan')
                ->toolbarOptions(['bold', 'italic', 'underline', ['list' => 'ordered'], ['list' => 'bullet'], 'link', 'clean'])
                ->translatable()
        );

        $form->add(Medias::make()->name('company_logo')->label('Logo Perusahaan (1:1)'));
        $form->add(Medias::make()->name('cover')->label('Cover / Banner (16:9)'));
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

        $table->add(Text::make()->field('company_name')->title('Perusahaan'));
        $table->add(Text::make()->field('job_type')->title('Tipe'));
        $table->add(Text::make()->field('deadline')->title('Deadline'));

        return $table;
    }
}
