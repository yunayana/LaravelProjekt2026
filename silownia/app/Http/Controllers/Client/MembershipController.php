<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GymMembership;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MembershipController extends Controller
{
    /**
     * Widok "Mój karnet".
     */
   public function index()
    {
        $user = Auth::user();

        // Aktualny karnet użytkownika: aktywny i jeszcze nie wygasł
        $membership = GymMembership::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->orderByDesc('end_date')
            ->first();

        // Historia subskrypcji użytkownika
        $subscriptions = Subscription::query()
            ->where('user_id', $user->id)
            ->orderByDesc('start_date')
            ->get();

        return view('client.membership.index', [
            'membership'    => $membership,
            'subscriptions' => $subscriptions,
        ]);
    }


            /**
             * Kup / przedłuż karnet.
             */
          public function store(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'membership_type' => ['required', 'string', 'max:255'],
        'duration'        => ['required', 'integer', 'min:1'],   // dni
        'price'           => ['required', 'numeric', 'min:0'],
    ]);

    DB::transaction(function () use ($user, $validated) {
        $durationDays = (int) $validated['duration'];

        // Aktualny karnet (ostatni, jaki ma użytkownik)
        $currentMembership = GymMembership::query()
        ->where('user_id', $user->id)
        ->where('status', 'active')
        ->whereDate('end_date', '>=', now())
        ->orderByDesc('end_date')
        ->first();


       if ($currentMembership) {
    // PRZEDŁUŻENIE
    $startDate = $currentMembership->end_date->copy()->addDay();
    $endDate   = $startDate->copy()->addDays($durationDays);

    $currentMembership->update([
        'end_date'        => $endDate,
        'status'          => 'active',
        'membership_type' => $validated['membership_type'],
    ]);

    $membership = $currentMembership;
} else {
    // PIERWSZY ZAKUP lub po anulowaniu – od dziś
    $startDate = now();
    $endDate   = $startDate->copy()->addDays($durationDays);

    $membership = GymMembership::create([
        'user_id'         => $user->id,
        'start_date'      => $startDate,
        'end_date'        => $endDate,
        'status'          => 'active',
        'membership_type' => $validated['membership_type'],
    ]);
}


        // Dezaktywuj poprzednią subskrypcję
        Subscription::query()
            ->where('user_id', $user->id)
            ->where('active', true)
            ->update(['active' => false]);

        // Dodaj nową subskrypcję dla tego przedłużenia/zakupu
        Subscription::create([
            'user_id'           => $user->id,
            'gym_membership_id' => $membership->id,
            'plan_name'         => $validated['membership_type'],
            'price'             => $validated['price'],
            'duration_months'   => 1,
            'start_date'        => $startDate,
            'end_date'          => $endDate,
            'active'            => true,
        ]);
    });

    return redirect()
        ->route('client.membership.index')
        ->with('status', 'Karnet został zapisany / przedłużony.');
}


    /**
     * Anulowanie karnetu.
     */
    public function cancel()
    {
        $user = Auth::user();

        DB::transaction(function () use ($user) {
            $membership = GymMembership::query()
                ->where('user_id', $user->id)
                ->where('status', 'active')          // tylko aktywny
                ->whereDate('end_date', '>=', now()) // i jeszcze nie wygasł
                ->orderByDesc('end_date')
                ->first();


            if ($membership) {
                $membership->update([
                    'status' => 'cancelled',
                ]);
            }

            Subscription::query()
                ->where('user_id', $user->id)
                ->where('active', true)
                ->update(['active' => false]);
        });

        return redirect()
            ->route('client.membership.index')
            ->with('status', 'Karnet został anulowany.');
    }

    public function cancelLastExtension()
{
    $user = Auth::user();

    DB::transaction(function () use ($user) {
        // Ostatnia aktywna subskrypcja (ostatnie przedłużenie/zakup)
        $lastActive = Subscription::query()
            ->where('user_id', $user->id)
            ->where('active', true)
            ->orderByDesc('start_date')
            ->first();

        if (! $lastActive) {
            return;
        }

        // Poprzednia subskrypcja (sprzed ostatniego przedłużenia)
        $previous = Subscription::query()
            ->where('user_id', $user->id)
            ->where('id', '<', $lastActive->id)
            ->orderByDesc('start_date')
            ->first();

        // Dezaktywuj ostatnią subskrypcję
        $lastActive->update(['active' => false]);

        if ($previous) {
            // Przywróć poprzednią jako aktywną
            $previous->update(['active' => true]);

            // Skróć karnet do poprzedniego okresu
            GymMembership::query()
                ->where('id', $lastActive->gym_membership_id)
                ->where('user_id', $user->id)
                ->update([
                    'end_date' => $previous->end_date,
                    'status'   => 'active',
                ]);
        } else {
            // Nie ma poprzedniej subskrypcji – karnet w praktyce anulowany
            GymMembership::query()
                ->where('id', $lastActive->gym_membership_id)
                ->where('user_id', $user->id)
                ->update([
                    'status' => 'cancelled',
                ]);
        }
    });

    return redirect()
        ->route('client.membership.index')
        ->with('status', 'Ostatnie przedłużenie zostało anulowane.');
}

}
