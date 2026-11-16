<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;

class AchievementRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [
            'title' => 'required|max:200',
        ];
    }

    public function rulesForUpdate(): array
    {
        return [
            'title' => 'required|max:200',
            'level' => 'nullable|in:sekolah,kecamatan,kabupaten,provinsi,nasional,internasional',
            'rank' => 'nullable|in:juara-1,juara-2,juara-3,harapan,finalis,partisipasi',
            'achievement_date' => 'nullable|date',
        ];
    }
}
