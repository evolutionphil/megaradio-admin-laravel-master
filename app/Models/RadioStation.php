<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;
use MongoDB\Laravel\Relations\BelongsToMany;
use MongoDB\Laravel\Relations\EmbedsMany;
use Storage;

class RadioStation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'hls' => 'bool',
        'popular' => 'bool',
        'featured' => 'bool',
        'is_working' => 'bool',
        'is_global' => 'bool',
        'has_uploaded_favicon' => 'bool',
        'deleted_at' => 'datetime',
        'last_checked_at' => 'datetime',
        'lastchangetime' => 'datetime',
        'lastchangetime_iso8601' => 'datetime',
        'lastchecktime' => 'datetime',
        'lastchecktime_iso8601' => 'datetime',
        'lastcheckoktime' => 'datetime',
        'lastcheckoktime_iso8601' => 'datetime',
        'lastlocalchecktime' => 'datetime',
        'lastlocalchecktime_iso8601' => 'datetime',
        'clicktimestamp' => 'datetime',
        'clicktimestamp_iso8601' => 'datetime',
    ];

    public function hasLogo(): Attribute
    {
        return Attribute::make(
            get: function () {
                return ! empty($this->attributes['favicon']);
            }
        );
    }

    protected function faviconUrl(): Attribute
    {
        return Attribute::make(
            get: function (string | null $value, $attributes) {
                if (empty($attributes['favicon'])) {
                    return null;
                }

                return str_contains($attributes['favicon'], 'http') ? $attributes['favicon'] : Storage::url($attributes['favicon']);
            }
        );
    }

    public function genres(): EmbedsMany
    {
        return $this->embedsMany(Genre::class);
    }

    public static function generateSlug(string $name): string
    {
        $totalWords = Str::wordCount($name);

        if ($totalWords > 8) {
            $name = Str::words($name, 8);
        }

        $slug = \Str::slug($name, '-', 'unknown');

        if ($totalWords == 2) {
            $slug = \Str::slug($name, '', 'unknown');
        }

        if (empty($slug)) {
            $slug = preg_replace('/\s+/u', '-', trim($name));
        }

        $found = RadioStation::where('slug', $slug)
            ->count();

        return $found ? "{$slug}-".(Str::lower(Str::random(3))) : $slug;
    }

    public function linkedStations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany|BelongsToMany
    {
        return $this->belongsToMany(RadioStation::class, 'linked_station_collection', 'parent_key', 'linked_stations');
    }
}
