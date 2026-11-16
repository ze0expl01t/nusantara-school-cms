<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;

class JurusanRequest extends Request
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
            'short_name' => 'nullable|max:20',
            'accreditation' => 'nullable|in:A,B,C,-',
            'excerpt' => 'nullable|max:500',
        ];
    }
}
