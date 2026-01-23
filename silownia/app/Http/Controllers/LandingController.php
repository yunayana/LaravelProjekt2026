<?php

namespace App\Http\Controllers;

use App\Models\GymClass;
use App\Models\MembershipPlan;
use App\Models\User;

class LandingController extends Controller
{
    public function index()
    {
        $memberships = [
            [
                'name'     => 'Standard 3 miesiące',
                'price'    => 99.99,
                'desc'     => 'Dostęp do siłowni i zajęć grupowych',
                'duration' => '3 miesiące',
            ],
            [
                'name'     => 'Premium 6 miesięcy',
                'price'    => 179.99,
                'desc'     => 'Nielimitowane zajęcia + sauna',
                'duration' => '6 miesięcy',
            ],
        ];

        $classes = GymClass::with('trainer')
            ->orderBy('schedule')
            ->take(4)
            ->get();

        // NOWE: trenerzy jako users z rolą `trainer`
        $trainers = User::whereHas('roles', fn($q) => $q->where('name', 'trainer'))
            ->orderBy('name')
            ->take(4)
            ->get();

        $plans = MembershipPlan::where('is_active', true)
            ->orderBy('price')
            ->get();

        return view('landing', compact('memberships', 'classes', 'trainers', 'plans'));
    }
}
