<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\GymClass;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = User::whereHas('roles', function ($q) {
            $q->where('name', 'client');
        })->count();

        $totalClasses = GymClass::count();
        $upcomingClasses = GymClass::limit(5)->get();

        return view('employee.dashboard', compact('totalClients', 'totalClasses', 'upcomingClasses'));
    }
}
