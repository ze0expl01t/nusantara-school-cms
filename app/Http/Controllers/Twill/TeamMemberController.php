<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Fieldsets;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Image;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class TeamMemberController extends ModuleController
{
    protected $moduleName = 'teamMembers';

    protected $titleColumnKey = 'title';

    protected $titleFormKey = 'title';

    protected function setUpController(): void
    {
        $this->enableReorder();
    }

    /**
     * Form Builder — Twill 3.x approach
     */
    public function getForm(TwillModelContract $model): Form
    {
        return Form::make()
            ->withFieldSets(new Fieldsets([
                // ══════════════ FIELDSET 1: UTAMA ══════════════
                Fieldset::make()
                    ->title('Informasi Utama')
                    ->id('info')
                    ->fields([
                        Input::make()
                            ->name('title')
                            ->label('Nama Lengkap')
                            ->translatable()
                            ->maxlength(200)
                            ->note('Contoh: Drs. Ahmad Subarjo, M.Pd.'),

                        Input::make()
                            ->name('job_title')
                            ->label('Jabatan')
                            ->maxlength(100)
                            ->note('Contoh: Kepala Sekolah / Guru Matematika'),

                        Input::make()
                            ->name('department')
                            ->label('Departemen / Unit')
                            ->maxlength(50)
                            ->note('Contoh: MIPA / Kejuruan / Umum'),

                        Checkbox::make()
                            ->name('is_leadership')
                            ->label('Tandai sebagai Pimpinan (Kepala/Wakil)'),
                    ]),

                // ══════════════ FIELDSET 2: BIO & FOTO ══════════════
                Fieldset::make()
                    ->title('Biografi & Foto')
                    ->id('bio')
                    ->fields([
                        Medias::make()
                            ->name('photo')
                            ->label('Foto')
                            ->max(1)
                            ->note('Format JPG/PNG, rasio 1:1 (kotak). Ditampilkan bulat di frontend.'),

                        Wysiwyg::make()
                            ->name('description')
                            ->label('Biografi / Deskripsi')
                            ->translatable()
                            ->note('Ceritakan latar belakang, pendidikan, atau keahlian.'),
                    ]),

                // ══════════════ FIELDSET 3: KONTAK ══════════════
                Fieldset::make()
                    ->title('Kontak')
                    ->id('contact')
                    ->fields([
                        Input::make()
                            ->name('email')
                            ->label('Email')
                            ->type('email')
                            ->maxlength(150)
                            ->note('Contoh: nama@smkmuh1blora.sch.id'),

                        Input::make()
                            ->name('phone')
                            ->label('Nomor Telepon / WA')
                            ->maxlength(30)
                            ->note('Contoh: +62 8xx-xxxx-xxxx'),
                    ]),

                // ══════════════ FIELDSET 4: SOSIAL MEDIA ══════════════
                Fieldset::make()
                    ->title('Sosial Media')
                    ->id('social')
                    ->fields([
                        Input::make()->name('facebook_url')->label('Facebook URL'),
                        Input::make()->name('instagram_url')->label('Instagram URL'),
                        Input::make()->name('linkedin_url')->label('LinkedIn URL'),
                        Input::make()->name('twitter_url')->label('Twitter / X URL'),
                        Input::make()->name('youtube_url')->label('YouTube URL'),
                        Input::make()->name('whatsapp_url')->label('WhatsApp URL'),
                    ]),

                // ══════════════ FIELDSET 5: SEO ══════════════
                Fieldset::make()
                    ->title('SEO')
                    ->id('seo')
                    ->fields([
                        Input::make()
                            ->name('meta_title')
                            ->label('Meta Title')
                            ->translatable()
                            ->maxlength(70),

                        Input::make()
                            ->name('meta_description')
                            ->label('Meta Description')
                            ->translatable()
                            ->maxlength(160),
                    ]),
            ]));
    }

    /**
     * Kolom tambahan di listing table
     */
    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            Image::make()
                ->field('photo')
                ->title('Foto')
        );

        $table->add(
            Text::make()
                ->field('job_title')
                ->title('Jabatan')
                ->sortable()
        );

        $table->add(
            Text::make()
                ->field('department')
                ->title('Departemen')
                ->sortable()
        );

        return $table;
    }
}
