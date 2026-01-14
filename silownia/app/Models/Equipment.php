<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
        'purchase_date',
        'condition',
        'quantity',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
    ];

    public function usageLogs(): HasMany
    {
        return $this->hasMany(EquipmentUsageLog::class);
    }
}
