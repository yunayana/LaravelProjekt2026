<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'membership_id',   // FK do gym_memberships
        'name',
        'price',
        'duration',        
        'start_date',
        'end_date',
        'active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'active'     => 'boolean',
    ];

    public function gymMembership(): BelongsTo
    {
        return $this->belongsTo(GymMembership::class, 'membership_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

