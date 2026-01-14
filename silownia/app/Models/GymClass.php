<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GymClass extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'trainer_id',
        'name',
        'description',
        'schedule',
        'max_participants',
    ];

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(ClassRegistration::class, 'class_id');
    }
}
