<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IrrigationTimerSetting extends Model
{
    //
    protected $table = 'irrigation_timer_settings';

    protected $fillable = [
        'duration_minutes',
        'frequency_hours',
        'last_run_at',
        'is_active',
        'state',
    ];

    protected $casts = [
        'last_run_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
