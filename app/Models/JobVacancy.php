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

class JobVacancy extends Model implements Sortable
{
    use HasBlocks, HasFactory, HasFiles, HasMedias, HasPosition, HasRevisions, HasSlug, HasTranslation;

    protected $fillable = [
        'published',
        'position',
        'company_name',
        'location',
        'job_type',
        'salary_range',
        'deadline',
        'apply_url',
        'apply_email',
        'contact_person',
        'contact_phone',
    ];

    protected $casts = [
        'published' => 'boolean',
        'deadline' => 'date',
    ];

    public $translatedAttributes = [
        'title',
        'excerpt',
        'requirements',
        'description',
        'meta_title',
        'meta_description',
    ];

    public $slugAttributes = [
        'title',
    ];

    public $mediasParams = [
        'company_logo' => [
            'default' => [
                ['name' => 'default', 'ratio' => 1],
            ],
        ],
        'cover' => [
            'default' => [
                ['name' => 'default', 'ratio' => 16 / 9],
            ],
            'thumbnail' => [
                ['name' => 'thumbnail', 'ratio' => 4 / 3],
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

    public function isActive(): bool
    {
        if (! $this->deadline) {
            return true;
        }

        return $this->deadline->isFuture() || $this->deadline->isToday();
    }
}
