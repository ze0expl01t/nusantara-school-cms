<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleSlugs;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\TeamMember;

class TeamMemberRepository extends ModuleRepository
{
    use HandleMedias;
    use HandleRevisions;
    use HandleSlugs;
    use HandleTranslations;

    public function __construct(TeamMember $model)
    {
        $this->model = $model;
    }
}
