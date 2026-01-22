<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GymClass;
use App\Models\ClassRegistration;
use App\Models\GymMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Sprawdź, czy ma aktywny karnet
        $hasActiveMembership = GymMembership::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->exists();

        // Wszystkie zajęcia z trenerem
        $classes = GymClass::with('trainer')
            ->orderBy('schedule')
            ->get();

        // Zajęcia, na które użytkownik jest zapisany
        $registrations = ClassRegistration::where('user_id', $user->id)
            ->pluck('gym_class_id')
            ->toArray();

        return view('client.classes.index', [
            'classes'            => $classes,
            'registrations'      => $registrations,
            'hasActiveMembership'=> $hasActiveMembership,
        ]);
    }

    public function register(GymClass $class)
    {
        $user = Auth::user();

        // blokada bez aktywnego karnetu
        $hasActiveMembership = GymMembership::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->exists();

        if (! $hasActiveMembership) {
            return back()->with('status', 'Musisz mieć aktywny karnet, aby zapisać się na zajęcia.');
        }

        // Sprawdź, czy nie jest już zapisany
        ClassRegistration::firstOrCreate([
            'user_id'      => $user->id,
            'gym_class_id' => $class->id,
        ]);

        return back()->with('status', 'Zostałeś zapisany na zajęcia.');
    }

    public function unregister(GymClass $class)
    {
        $user = Auth::user();

        ClassRegistration::where('user_id', $user->id)
            ->where('gym_class_id', $class->id)
            ->delete();

        return back()->with('status', 'Zostałeś wypisany z zajęć.');
    }
}
