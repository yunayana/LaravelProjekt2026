<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'gym_membership_id',
        'plan_name',
        'price',
        'duration_months',
        'start_date',
        'end_date',
        'active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'active' => 'boolean',
    ];

    public function gymMembership(): BelongsTo
    {
        return $this->belongsTo(GymMembership::class);
    }
}
