<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;

class TeamMemberRequest extends Request
{
    public function rulesForCreate(): array
    {
        return $this->rulesForTranslatedFields([
            // ── Non-translatable fields ──
            'job_title' => 'nullable|max:100',
            'department' => 'nullable|max:50',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|max:30',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|url|max:255',
            'is_leadership' => 'nullable|boolean',
        ], [
            // ── Translatable fields ──
            'title' => 'required|max:200',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|max:70',
            'meta_description' => 'nullable|max:160',
        ]);
    }

    public function rulesForUpdate(): array
    {
        return $this->rulesForCreate();
    }
}
