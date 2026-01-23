<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GymMembership;
use App\Models\Subscription;
use App\Models\MembershipPlan;
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

        $membership = GymMembership::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->orderByDesc('end_date')
            ->first();

        $subscriptions = Subscription::query()
            ->where('user_id', $user->id)
            ->orderByDesc('start_date')
            ->get();

        // stare „domyślne” plany
        $defaultPlans = collect([
            (object)[
                'id'              => 'Basic',
                'name'            => 'Basic',
                'price'           => 79,
                'duration_months' => 1,
                'is_default'      => true,
            ],
            (object)[
                'id'              => 'Premium',
                'name'            => 'Premium',
                'price'           => 99,
                'duration_months' => 1,
                'is_default'      => true,
            ],
            (object)[
                'id'              => 'VIP',
                'name'            => 'VIP',
                'price'           => 129,
                'duration_months' => 1,
                'is_default'      => true,
            ],
        ]);

        // plany z bazy
        $dbPlans = MembershipPlan::where('is_active', true)
            ->orderBy('price')
            ->get()
            ->map(function ($plan) {
                $plan->is_default = false;
                return $plan;
            });

        // połączone razem
        $allPlans = $defaultPlans->concat($dbPlans);

        return view('client.membership.index', [
            'membership'    => $membership,
            'subscriptions' => $subscriptions,
            'plans'         => $allPlans,
        ]);
    }

    /**
     * Kup / przedłuż karnet.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // wartość może być "Basic"/"Premium"/"VIP" albo "db:ID"
        $validated = $request->validate([
            'membership_plan_id' => ['required', 'string'],
        ]);

        $value = $validated['membership_plan_id'];

        // stare plany domyślne
        $defaultPlans = [
            'Basic'   => ['days' => 30, 'price' => 79],
            'Premium' => ['days' => 30, 'price' => 99],
            'VIP'     => ['days' => 30, 'price' => 129],
        ];

        if (str_starts_with($value, 'db:')) {
            // plan z bazy
            $planId = (int) substr($value, 3);

            $plan = MembershipPlan::where('is_active', true)
                ->findOrFail($planId);

            $durationDays = (int) ($plan->duration_months * 30);
            $price        = $plan->price;
            $planName     = $plan->name;
        } else {
            // plan domyślny (Basic/Premium/VIP)
            if (! isset($defaultPlans[$value])) {
                abort(422, 'Nieprawidłowy typ karnetu.');
            }

            $p           = $defaultPlans[$value];
            $durationDays = (int) $p['days'];
            $price        = $p['price'];
            $planName     = $value;

            // sztuczny obiekt tylko po to, aby dalej mieć duration_months
            $plan = (object)[
                'duration_months' => 1,
            ];
        }

        DB::transaction(function () use ($user, $durationDays, $price, $planName, $plan) {
            // Aktualny aktywny karnet
            $currentMembership = GymMembership::query()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->whereDate('end_date', '>=', now())
                ->orderByDesc('end_date')
                ->first();

            if ($currentMembership) {
                // PRZEDŁUŻENIE: od dnia po obecnym końcu
                $startDate = $currentMembership->end_date->copy()->addDay();
                $endDate   = $startDate->copy()->addDays($durationDays);

                $currentMembership->update([
                    'end_date'        => $endDate,
                    'status'          => 'active',
                    'membership_type' => $planName,
                ]);

                $membership = $currentMembership;
            } else {
                // PIERWSZY ZAKUP / po anulowaniu – od dziś
                $startDate = now();
                $endDate   = $startDate->copy()->addDays($durationDays);

                $membership = GymMembership::create([
                    'user_id'         => $user->id,
                    'start_date'      => $startDate,
                    'end_date'        => $endDate,
                    'status'          => 'active',
                    'membership_type' => $planName,
                ]);
            }

            // Dezaktywuj poprzednią aktywną subskrypcję
            Subscription::query()
                ->where('user_id', $user->id)
                ->where('active', true)
                ->update(['active' => false]);

            // Nowa subskrypcja dla tego okresu
            Subscription::create([
                'user_id'           => $user->id,
                'gym_membership_id' => $membership->id,
                'plan_name'         => $planName,
                'price'             => $price,
                'duration_months'   => $plan->duration_months,
                'start_date'        => $startDate,
                'end_date'          => $endDate,
                'active'            => true,
            ]);
        });

        return redirect()
            ->route('client.membership.index')
            ->with('status', 'Karnet został zapisany / przedłużony.');
    }

   public function cancel()
{
    $user = Auth::user();

    $membership = GymMembership::query()
        ->where('user_id', $user->id)
        ->where('status', 'active')
        ->whereDate('end_date', '>=', now())
        ->orderByDesc('end_date')
        ->first();

    if ($membership) {
        $membership->update([
            'status' => 'cancelled',
        ]);
    }

    return redirect()
        ->route('client.membership.index')
        ->with('status', 'Karnet został anulowany.');
}

public function cancelLastExtension()
{
    $user = Auth::user();

    // aktualny aktywny karnet
    $membership = GymMembership::query()
        ->where('user_id', $user->id)
        ->where('status', 'active')
        ->whereDate('end_date', '>=', now())
        ->orderByDesc('end_date')
        ->first();

    if ($membership) {
        
        $lastSubscription = Subscription::query()
            ->where('user_id', $user->id)
            ->where('gym_membership_id', $membership->id)
            ->orderByDesc('start_date')
            ->first();

        if ($lastSubscription) {
            
            $durationDays = (int) ($lastSubscription->duration_months * 30);

            $membership->end_date = $membership->end_date->copy()->subDays($durationDays);
            $membership->save();

           
            $lastSubscription->update(['active' => false]);
        }
    }

    return redirect()
        ->route('client.membership.index')
        ->with('status', 'Ostatnie przedłużenie zostało cofnięte.');
}

}
