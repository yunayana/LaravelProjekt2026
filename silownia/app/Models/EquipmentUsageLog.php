<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentUsageLog extends Model
{
    protected $fillable = [
        'equipment_id',
        'user_id',
        'usage_start',
        'usage_end',
        'notes',
    ];

    protected $casts = [
        'usage_start' => 'datetime',
        'usage_end' => 'datetime',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
