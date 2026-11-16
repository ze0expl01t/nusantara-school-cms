<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;

class JobVacancyRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [
            'title' => 'required|max:200',
            'company_name' => 'nullable|max:200',
        ];
    }

    public function rulesForUpdate(): array
    {
        return [
            'title' => 'required|max:200',
            'company_name' => 'nullable|max:200',
            'job_type' => 'nullable|in:full-time,part-time,kontrak,magang,freelance',
            'deadline' => 'nullable|date',
            'apply_url' => 'nullable|url|max:500',
            'apply_email' => 'nullable|email|max:150',
            'contact_phone' => 'nullable|max:50',
        ];
    }
}
