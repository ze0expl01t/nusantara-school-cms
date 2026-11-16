<?php

namespace App\Models;

use A17\Twill\Models\AppSetting as BaseAppSetting;
use A17\Twill\Models\Behaviors\HasFiles;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasRevisions;

class AppSetting extends BaseAppSetting
{
    use HasFiles;
    use HasMedias;
    use HasRevisions;

    /**
     * Twill Settings Sections menyimpan media dengan morph type "settings"
     * bukan full class name.
     */
    public function getMorphClass(): string
    {
        return 'settings';
    }
}
