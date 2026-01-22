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
        $classes = GymClass::with('trainer')->get();

    $schedule = [
        'Poniedziałek' => [],
        'Wtorek'       => [],
        'Środa'        => [],
        'Czwartek'     => [],
        'Piątek'       => [],
        'Sobota'       => [],
        'Niedziela'    => [],
    ];

    foreach ($classes as $class) {
        
        [$day, $time] = explode(' ', $class->schedule, 2);
        $schedule[$day][] = [
            'time'    => $time,
            'class'   => $class,
        ];
    }

    $registrations = ClassRegistration::where('user_id', $user->id)
        ->pluck('class_id')
        ->toArray();


        $myClasses = GymClass::with('trainer')
        ->whereIn('id', $registrations)
        ->orderBy('schedule')
        ->get();

    return view('client.classes.index', [
        'schedule'           => $schedule,
        'registrations'      => $registrations,
        'hasActiveMembership'=> $hasActiveMembership ?? true,
        'myClasses'          => $myClasses,
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
        ClassRegistration::firstOrCreate(
    [
        'user_id'  => $user->id,
        'class_id' => $class->id,
    ],
    [
        'registered_at' => now(),
        'status'        => 'active',
    ]
);




        return back()->with('status', 'Zostałeś zapisany na zajęcia.');
    }

    public function unregister(GymClass $class)
    {
        $user = Auth::user();

        ClassRegistration::where('user_id', $user->id)
            ->where('class_id', $class->id)
            ->delete();


        return back()->with('status', 'Zostałeś wypisany z zajęć.');
    }
}
