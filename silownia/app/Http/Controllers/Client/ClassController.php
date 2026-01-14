<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GymClass;
use App\Models\ClassRegistration;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index()
    {
        $classes = GymClass::with('trainer', 'registrations')->get();
        $registeredClassIds = Auth::user()->classRegistrations->pluck('class_id');

        return view('client.classes.index', compact('classes', 'registeredClassIds'));
    }

    public function register(GymClass $class)
    {
        $user = Auth::user();

        $exists = ClassRegistration::where('user_id', $user->id)
            ->where('class_id', $class->id)
            ->exists();

        if (!$exists) {
            ClassRegistration::create([
                'user_id' => $user->id,
                'class_id' => $class->id,
                'registered_at' => now(),
                'status' => 'active',
            ]);
        }

        return back()->with('success', 'Zarejestrowano na zajęcia!');
    }

    public function unregister(GymClass $class)
    {
        Auth::user()->classRegistrations()
            ->where('class_id', $class->id)
            ->delete();

        return back()->with('success', 'Anulowano rejestrację!');
    }
}
