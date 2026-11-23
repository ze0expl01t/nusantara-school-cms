<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\NestedModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\DatePicker;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class AgendaController extends BaseModuleController
{
    protected $moduleName = 'agendas';

    protected $showOnlyParentItemsInBrowsers = true;

    protected $nestedItemsDepth = 1;

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
                ->label('Judul Agenda')
                ->translatable()
        );

        $form->add(
            DatePicker::make()
                ->name('agenda_date')
                ->label('Tanggal & Waktu Agenda')
                ->allowClear(true)
                ->withoutTime(false)
        );

        $form->add(
            Input::make()
                ->name('time_slot')
                ->label('Jam')
                ->placeholder('Contoh: 14:00 - 16:00 WIB')
        );

        $form->add(
            Input::make()
                ->name('location')
                ->label('Lokasi Acara')
        );

        $form->add(
            Input::make()
                ->name('venue')
                ->label('Tempat')
                ->placeholder('Contoh: CADL, ITB Kampus Ganesha')
        );

        $form->add(
            Input::make()
                ->name('organizer')
                ->label('Penyelenggara')
                ->placeholder('Contoh: Museum ITB')
        );

        $form->add(
            Input::make()
                ->name('participants')
                ->label('Peserta')
                ->placeholder('Contoh: Umum peserta')
        );

        $form->add(
            Input::make()
                ->name('external_url')
                ->label('URL')
                ->placeholder('https://...')
        );

        $form->add(
            Wysiwyg::make()
                ->name('description')
                ->label('Deskripsi Agenda')
                ->placeholder('Tulis deskripsi atau informasi lengkap agenda di sini...')
                ->toolbarOptions(['bold', 'italic', 'underline', 'strike', 'blockquote', ['list' => 'ordered'], ['list' => 'bullet'], 'link', 'clean'])
                ->translatable()
        );

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
                ->field('agenda_date')
                ->title('Tanggal Acara')
        );

        $table->add(
            Text::make()
                ->field('time_slot')
                ->title('Jam')
        );

        $table->add(
            Text::make()
                ->field('venue')
                ->title('Tempat')
        );

        $table->add(
            Text::make()
                ->field('organizer')
                ->title('Penyelenggara')
        );

        return $table;
    }
}
