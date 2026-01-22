<?php

namespace App\Http\Controllers;

use App\Models\GymMembership;
use App\Models\GymClass;
use App\Models\Trainer;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        // 3–4 przykładowe karnety
        $memberships = GymMembership::query()
            ->orderBy('price')
            ->take(4)
            ->get();

        // Najbliższe zajęcia z relacją trener
        $classes = GymClass::query()
            ->with('trainer')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // Lista trenerów
        $trainers = Trainer::query()
            ->orderBy('name')
            ->take(6)
            ->get();

        return view('landing', [
            'memberships' => $memberships,
            'classes'     => $classes,
            'trainers'    => $trainers,
        ]);
    }
}
