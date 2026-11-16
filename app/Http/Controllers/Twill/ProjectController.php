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

class ProjectController extends BaseModuleController
{
    protected $moduleName = 'projects';

    protected $showOnlyParentItemsInBrowsers = true;

    protected $nestedItemsDepth = 1;

    protected function setUpController(): void
    {
        $this->enableReorder();
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        // Judul Project (Translatable)
        $form->add(
            Input::make()
                ->name('title')
                ->label('Judul Project')
                ->translatable()
        );

        // Kategori Project
        $form->add(
            Input::make()
                ->name('category')
                ->label('Kategori (Contoh: Construction, HSE, IT Infrastructure)')
        );

        // Tanggal Project
        $form->add(
            DatePicker::make()
                ->name('project_date')
                ->label('Tanggal Project')
                ->allowClear(true)
                ->withoutTime(true)
        );

        // Cover Image
        $form->add(
            Medias::make()
                ->name('cover')
                ->label('Cover Image')
        );

        // Deskripsi / Konten Project (Quill WYSIWYG)
        $form->add(
            Wysiwyg::make()
                ->name('description')
                ->label('Deskripsi Project')
                ->placeholder('Tulis deskripsi lengkap project di sini...')
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
                ->field('project_date')
                ->title('Tanggal')
        );

        return $table;
    }
}
