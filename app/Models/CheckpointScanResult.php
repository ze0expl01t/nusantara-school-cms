<?php

namespace App\Models;

use A17\Twill\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckpointScanResult extends Model
{
    protected $fillable = [
        'user_id',
        'triggered_by',
        'total_checks',
        'pass_count',
        'warn_count',
        'fail_count',
        'duration_seconds',
        'exit_code',
        'raw_output',
        'parsed_output',
    ];

    protected $casts = [
        'parsed_output' => 'array',
        'duration_seconds' => 'float',
        'total_checks' => 'integer',
        'pass_count' => 'integer',
        'warn_count' => 'integer',
        'fail_count' => 'integer',
        'exit_code' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Status agregat: pass | warn | fail
     */
    public function getStatusAttribute(): string
    {
        if ($this->fail_count > 0) {
            return 'fail';
        }

        if ($this->warn_count > 0) {
            return 'warn';
        }

        return 'pass';
    }
}
