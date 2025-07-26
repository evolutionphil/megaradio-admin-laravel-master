<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Str;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;

class Page extends Model
{
    use HasFactory, HasTranslations, HasRichText;

    public $guarded = [];

    public $translatable = ['contents', 'title', 'description', 'keywords'];

    protected $richTextFields = [
        'contents',
    ];

    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn ($value, $attributes) => Str::slug($attributes['name']),
        );
    }
}
