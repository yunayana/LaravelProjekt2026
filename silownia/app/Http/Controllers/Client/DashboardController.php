<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $membership = $user->gymMembership;
        $registrations = $user->classRegistrations()->with('gymClass.trainer')->get();

        return view('client.dashboard', compact('membership', 'registrations'));
    }
}
