<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Casts\ObjectId;
use MongoDB\Laravel\Eloquent\Model;

class LinkedStation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'parent_station_id' => ObjectId::class,
        'child_station_id' => ObjectId::class,
    ];
}
