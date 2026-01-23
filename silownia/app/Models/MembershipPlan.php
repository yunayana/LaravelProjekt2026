<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MembershipPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_months',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}


