<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GymClass;
use App\Models\ClassRegistration;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $classes = GymClass::with('trainer')
            ->orderBy('schedule')
            ->get();

        $registeredIds = $user->classRegistrations()
            ->pluck('class_id')
            ->toArray();

        return view('client.classes.index', compact('classes', 'registeredIds'));
    }

    public function register(GymClass $class, Request $request)
    {
        $user = $request->user();

        // Sprawdź, czy ma aktywny karnet
        $hasActiveMembership = $user->gymMemberships()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->exists();

        if (! $hasActiveMembership) {
            return back()->with('status', 'Nie masz aktywnego karnetu.');
        }

        // Limit miejsc
        $currentCount = $class->registrations()->count();
        if ($currentCount >= $class->max_participants) {
            return back()->with('status', 'Brak wolnych miejsc na te zajęcia.');
        }

        // Uniknij duplikatów
        ClassRegistration::firstOrCreate([
            'user_id'  => $user->id,
            'class_id' => $class->id,
        ]);

        return back()->with('status', 'Zostałeś zapisany na zajęcia.');
    }

    public function unregister(GymClass $class, Request $request)
    {
        $user = $request->user();

        ClassRegistration::where('user_id', $user->id)
            ->where('class_id', $class->id)
            ->delete();

        return back()->with('status', 'Zostałeś wypisany z zajęć.');
    }
}
