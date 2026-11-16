<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasFiles;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Extracurricular extends Model implements Sortable
{
    use HasBlocks, HasFactory, HasFiles, HasMedias, HasPosition, HasRevisions, HasSlug, HasTranslation;

    protected $fillable = [
        'published',
        'position',
        'category',
        'schedule',
        'location',
        'advisor_name',
        'advisor_role',
    ];

    protected $casts = [
        'published' => 'boolean',
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

    // Ganti dari $mediasRoles → $mediasParams agar crop terdefinisi
    public $mediasParams = [
        'cover' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 16 / 9,
                ],
            ],
        ],
        'advisor_photo' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 1,
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
