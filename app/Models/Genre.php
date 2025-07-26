<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;
use Storage;

class Genre extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_discoverable' => 'bool',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: function (string | null $value) {
                if (empty($value)) {
                    return asset('images/no-logo.png');
                }

                return str_contains($value, 'http') ? $value : Storage::url($value);
            }
        );
    }

    public static function generateSlug(string $name): string
    {
        $totalWords = Str::wordCount($name);

        if ($totalWords > 8) {
            $name = Str::words($name, 8);
        }

        $slug = \Str::slug($name, '-', 'unknown');

        if (empty($slug)) {
            $slug = preg_replace('/\s+/u', '-', trim($name));
        }

        $found = Genre::where('slug', $slug)
            ->exists();

        return $found ? "{$slug}-".(Str::lower(Str::random(3))) : $slug;
    }
}
