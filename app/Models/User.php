<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'location',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'is_admin',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function offers(): HasMany
    {
        return $this->hasMany(UserOffer::class);
    }

    public function wants(): HasMany
    {
        return $this->hasMany(UserWant::class);
    }

    public function sentSwapRequests(): HasMany
    {
        return $this->hasMany(SwapRequest::class, 'requester_id');
    }

    public function receivedSwapRequests(): HasMany
    {
        return $this->hasMany(SwapRequest::class, 'receiver_id');
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    public function averageRating(): ?float
    {
        $average = $this->reviewsReceived()->avg('rating');

        return $average !== null ? round((float) $average, 2) : null;
    }
}
