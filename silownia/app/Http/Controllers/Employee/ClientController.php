<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;

class ClientController extends Controller
{
    public function index()
    {
        $clients = User::whereHas('roles', function ($q) {
            $q->where('name', 'client');
        })->with('gymMembership')->paginate(15);

        return view('employee.clients.index', compact('clients'));
    }

    public function show(User $user)
    {
        $membership = $user->gymMembership;
        $registrations = $user->classRegistrations()->with('gymClass.trainer')->get();

        return view('employee.clients.show', compact('user', 'membership', 'registrations'));
    }
}
