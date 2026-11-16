<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamMember extends Model implements Sortable
{
    use HasFactory;
    use HasMedias;
    use HasPosition;
    use HasRevisions;
    use HasSlug;
    use HasTranslation;

    protected $fillable = [
        'published',
        'title',
        'description',
        'position',
        'job_title',
        'department',
        'email',
        'phone',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'twitter_url',
        'youtube_url',
        'whatsapp_url',
        'is_leadership',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_leadership' => 'boolean',
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
        'photo' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 1,
                ],
            ],
            'portrait' => [
                [
                    'name' => 'portrait',
                    'ratio' => 3 / 4,
                ],
            ],
        ],
    ];

    public function scopeLeadership($query)
    {
        return $query->where('is_leadership', true);
    }

    public function scopeDepartment($query, $dept)
    {
        return $query->where('department', $dept);
    }
}
