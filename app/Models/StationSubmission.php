<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use MongoDB\Laravel\Eloquent\Model;

class StationSubmission extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    protected function logo(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                if (empty($value)) {
                    return asset('images/no-logo.png');
                }

                return str_contains($value, 'http') ? $value : Storage::url($value);
            }
        );
    }
}
