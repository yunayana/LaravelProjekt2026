<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GymMembership;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    public function index()
    {
        $membership = Auth::user()->gymMembership;
        $subscriptions = $membership ? $membership->subscriptions : collect();

        return view('client.membership.show', compact('membership', 'subscriptions'));
    }
}
