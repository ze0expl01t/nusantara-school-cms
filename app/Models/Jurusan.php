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

class Jurusan extends Model implements Sortable
{
    use HasBlocks, HasFactory, HasFiles, HasMedias, HasPosition, HasRevisions, HasSlug, HasTranslation;

    protected $fillable = [
        'published',
        'position',
        'code',
        'color',
        'head_of_department',
        'accreditation',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public $translatedAttributes = [
        'title',
        'short_name',
        'excerpt',
        'vision',
        'mission',
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
                ['name' => 'default', 'ratio' => 16 / 9],
            ],
            'thumbnail' => [
                ['name' => 'thumbnail', 'ratio' => 4 / 3],
            ],
        ],
        'icon' => [
            'default' => [
                ['name' => 'default', 'ratio' => 1],
            ],
        ],
        'gallery' => [
            'default' => [
                ['name' => 'default', 'ratio' => 16 / 9],
            ],
        ],
        'og_image' => [
            'default' => [
                ['name' => 'default', 'ratio' => 1200 / 630],
            ],
        ],
    ];
}
