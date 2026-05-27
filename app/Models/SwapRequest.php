<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SwapRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_id',
        'receiver_id',
        'offered_skill_id',
        'requested_skill_id',
        'status',
        'message',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function offeredSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'offered_skill_id');
    }

    public function requestedSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'requested_skill_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
