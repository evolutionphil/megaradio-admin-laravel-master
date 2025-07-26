<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Language extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_published' => 'bool',
        'is_rtl' => 'bool',
    ];
}
