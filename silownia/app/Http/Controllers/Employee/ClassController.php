<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\GymClass;
use App\Models\Trainer;

class ClassController extends Controller
{
    public function index()
    {
        $classes = GymClass::with('trainer', 'registrations')->paginate(15);

        return view('employee.classes.index', compact('classes'));
    }

    public function create()
    {
        $trainers = Trainer::all();

        return view('employee.classes.create', compact('trainers'));
    }

    public function store()
    {
        $validated = request()->validate([
            'trainer_id' => 'required|exists:trainers,id',
            'name' => 'required|string',
            'description' => 'required|string',
            'schedule' => 'required|string',
            'max_participants' => 'required|integer|min:1',
        ]);

        GymClass::create($validated);

        return redirect()->route('employee.classes.index')->with('success', 'Zajęcia dodane!');
    }

    public function edit(GymClass $class)
    {
        $trainers = Trainer::all();

        return view('employee.classes.edit', compact('class', 'trainers'));
    }

    public function update(GymClass $class)
    {
        $validated = request()->validate([
            'trainer_id' => 'required|exists:trainers,id',
            'name' => 'required|string',
            'description' => 'required|string',
            'schedule' => 'required|string',
            'max_participants' => 'required|integer|min:1',
        ]);

        $class->update($validated);

        return redirect()->route('employee.classes.index')->with('success', 'Zajęcia zaktualizowane!');
    }

    public function destroy(GymClass $class)
    {
        $class->delete();

        return back()->with('success', 'Zajęcia usunięte!');
    }
}
