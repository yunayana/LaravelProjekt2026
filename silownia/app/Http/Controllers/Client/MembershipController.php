<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GymMembership;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    public function index()
    {
        // tu zaraz dopiszemy logikę
    }

    public function store(Request $request)
    {
        // tu zaraz dopiszemy logikę zakupu/przedłużenia
    }

    public function cancel()
    {
        // tu logika anulowania
    }
}
