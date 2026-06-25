<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Crop extends Model
{
    //
    use SoftDeletes;

    protected $table = 'crops';

    protected $fillable = [
        'crop_name',
        'moisture_min',
        'moisture_max',
        // 'state',
    ];
}
