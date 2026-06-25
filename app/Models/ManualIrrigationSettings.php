<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualIrrigationSettings extends Model
{
    //
    protected $table = 'manual_irrigation_settings';

    protected $fillable = [
        'key',
        'value'
    ];
}
