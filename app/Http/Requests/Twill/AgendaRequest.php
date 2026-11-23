<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;

class AgendaRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [
            'time_slot' => ['nullable', 'string', 'max:100'],
            'venue' => ['nullable', 'string', 'max:255'],
            'organizer' => ['nullable', 'string', 'max:255'],
            'participants' => ['nullable', 'string', 'max:255'],
            'external_url' => ['nullable', 'string', 'max:2048', function ($attribute, $value, $fail) {
                if (blank($value)) {
                    return;
                }
                $trimmed = trim((string) $value);
                if (! preg_match('#^https?://#i', $trimmed) || filter_var($trimmed, FILTER_VALIDATE_URL) === false) {
                    $fail('URL harus diawali http:// atau https://.');
                }
            }],
        ];
    }

    public function rulesForUpdate(): array
    {
        return $this->rulesForCreate();
    }
}
