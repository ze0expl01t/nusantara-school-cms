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

class Achievement extends Model implements Sortable
{
    use HasBlocks, HasFactory, HasFiles, HasMedias, HasPosition, HasRevisions, HasSlug, HasTranslation;

    protected $fillable = [
        'published',
        'position',
        'level',
        'rank',
        'achievement_date',
    ];

    protected $casts = [
        'published' => 'boolean',
        'achievement_date' => 'date',
    ];

    public $translatedAttributes = [
        'title',
        'student_name',
        'organizer',
        'excerpt',
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
        'photo' => [
            'default' => [
                ['name' => 'default', 'ratio' => 4 / 3],
            ],
        ],
        'certificate' => [
            'default' => [
                ['name' => 'default', 'ratio' => 3 / 4],
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
