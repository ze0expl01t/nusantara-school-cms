<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasFiles;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasNesting;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brosur extends Model implements Sortable
{
    use HasBlocks, HasFactory, HasFiles, HasMedias, HasNesting, HasPosition, HasRevisions, HasSlug, HasTranslation;

    protected $fillable = [
        'published',
        'title',
        'description',
        'brosur_date',
        'position',
    ];

    protected $casts = [
        'published' => 'boolean',
        'brosur_date' => 'datetime',
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

    // Konfigurasi parameter file resmi Twill
    public $filesParams = [
        'download_file',
    ];

    // Ganti dari $mediasRoles → $mediasParams
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
}
