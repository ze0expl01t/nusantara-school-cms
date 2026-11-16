<?php

namespace App\Models\Translations;

use A17\Twill\Models\Model;
use App\Models\Achievement;

class AchievementTranslation extends Model
{
    protected $baseModuleModel = Achievement::class;
}
