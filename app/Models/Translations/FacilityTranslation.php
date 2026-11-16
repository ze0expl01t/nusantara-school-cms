<?php

namespace App\Models\Translations;

use A17\Twill\Models\Model;
use App\Models\Facility;

class FacilityTranslation extends Model
{
    protected $baseModuleModel = Facility::class;
}
