<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;

class Page extends Model
{
    use HasBlocks, HasMedias, HasRevisions, HasSlug, HasTranslation;

    protected $fillable = [
        'published',
        'title',
        'description',
    ];

    public $translatedAttributes = [
        'title',
        'description',
        'meta_title',
        'meta_description',
    ];

    public $slugAttributes = [
        'title',
    ];

    public $mediasParams = [
        'cover' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 16 / 9,
                ],
            ],
        ],
        'og_image' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 1200 / 630,
                ],
            ],
        ],
    ];

    /**
     * ═══ Auto-invalidate fragment cache saat Page disimpan/dihapus ═══
     */
    protected static function booted(): void
    {
        static::saved(function ($page) {
            if (function_exists('forget_page_cache')) {
                forget_page_cache($page->id);
            }
        });

        static::deleted(function ($page) {
            if (function_exists('forget_page_cache')) {
                forget_page_cache($page->id);
            }
        });
    }
}
