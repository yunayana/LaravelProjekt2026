<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GymMembership;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Pobierz obecny karnet użytkownika
        $membership = $user->gymMembership;

        // Pobierz subskrypcje z karnetu (jeśli istnieje)
        $subscriptions = $membership ? $membership->subscriptions : collect();

        return view('client.membership.index', compact(
            'membership',
            'subscriptions'
        ));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'membership_type' => 'required|string|max:100',
            'duration'        => 'required|integer|min:1',   // dni lub miesiące
            'price'           => 'required|numeric|min:0',
        ]);

        $start = now();
        $end   = now()->addDays($data['duration']); 

        $membership = GymMembership::create([
            'user_id'         => $user->id,
            'start_date'      => $start,
            'end_date'        => $end,
            'status'          => 'active',
            'membership_type' => $data['membership_type'],
        ]);

        $subscription = Subscription::create([
            'user_id'       => $user->id,
            'membership_id' => $membership->id,
            'name'          => $data['membership_type'],
            'price'         => $data['price'],
            'duration'      => $data['duration'],
            'start_date'    => $start,
            'end_date'      => $end,
            'active'        => true,
        ]);

        // Jeśli masz tabelę payments – opcjonalnie
        if (class_exists(Payment::class)) {
            Payment::create([
                'user_id'         => $user->id,
                'subscription_id' => $subscription->id,
                'amount'          => $data['price'],
                'status'          => 'paid',
                'paid_at'         => now(),
            ]);
        }

        return redirect()
            ->route('client.membership.index')
            ->with('status', 'Karnet został aktywowany.');
    }
}

