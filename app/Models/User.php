<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Mahmudz\LaravelMongoNotifiable\Notifiable;
use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLES = [
        'ADMIN' => 1,
        'USER' => 2,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'social_provider',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'role' => 'int',
        'email_verified_at' => 'datetime',
        'is_public_profile' => 'bool',
    ];

    public function avatar(): Attribute
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
}
