<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GymMembership extends Model
{
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'status',
        'membership_type',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    public function isActive(): bool
    {
    return $this->status === 'active'
        && $this->start_date
        && $this->end_date
        && now()->between($this->start_date, $this->end_date);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
