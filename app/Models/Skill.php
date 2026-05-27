<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
    ];

    public function offers(): HasMany
    {
        return $this->hasMany(UserOffer::class);
    }

    public function wants(): HasMany
    {
        return $this->hasMany(UserWant::class);
    }
}
