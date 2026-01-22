<?php

namespace App\Http\Controllers;

use App\Models\GymClass;
use App\Models\Trainer;

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

        $trainers = Trainer::take(4)->get();

        return view('landing', compact('memberships', 'classes', 'trainers'));
    }
}
