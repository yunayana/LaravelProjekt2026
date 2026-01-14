<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trainer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialization',
        'bio',
    ];

    public function classes(): HasMany
    {
        return $this->hasMany(GymClass::class);
    }
}
