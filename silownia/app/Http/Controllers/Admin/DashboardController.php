<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GymClass;
use App\Models\GymMembership;
use App\Models\Equipment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalClients = User::whereHas('roles', function ($q) {
            $q->where('name', 'client');
        })->count();
        $totalClasses = GymClass::count();
        $activeMemberships = GymMembership::where('status', 'active')->count();
        $equipmentCount = Equipment::sum('quantity');

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalClients',
            'totalClasses',
            'activeMemberships',
            'equipmentCount',
            'recentUsers'
        ));
    }
}
