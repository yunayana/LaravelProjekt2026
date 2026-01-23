<?php

namespace App\Http\Controllers;

use App\Models\GymClass;
use Illuminate\Support\Facades\Auth;

class TrainerController extends Controller
{
    public function index()
    {
        $classes = GymClass::where('trainer_id', Auth::id())->get();

        return view('trainer.classes.index', compact('classes'));
    }
}
