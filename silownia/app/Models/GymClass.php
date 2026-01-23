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

    public function trainer()
{
    return $this->belongsTo(\App\Models\User::class, 'trainer_id');
}

    public function registrations(): HasMany
    {
        return $this->hasMany(ClassRegistration::class, 'class_id');
    }
}
