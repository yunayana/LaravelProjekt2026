<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymClass;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GymClassController extends Controller
{
    public function index(): View
    {
        $classes = GymClass::with('trainer')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.classes.index', compact('classes'));
    }

    public function create(): View
    {
        $trainers = User::whereHas('roles', fn($q) => $q->whereIn('name', ['employee', 'trainer']))
    ->orderBy('name')
    ->get();



       return view('admin.classes.create', compact('trainers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'trainer_id'  => ['required', 'exists:users,id'],
            'schedule'    => ['required', 'string', 'max:255'], // np. "Poniedziałek 18:00"
            'capacity'    => ['required', 'integer', 'min:1'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        GymClass::create($data);

        return redirect()->route('admin.classes.index')
            ->with('status', 'Zajęcia utworzone.');
    }

    public function edit(GymClass $class)
{
    $trainers = User::whereHas('roles', fn($q) => $q->where('name', 'trainer'))
        ->orderBy('name')
        ->get();

    return view('admin.classes.edit', [
        'class' => $class,
        'trainers' => $trainers,
    ]);
}

    public function update(Request $request, GymClass $class): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'trainer_id'  => ['required', 'exists:users,id'],
            'schedule'    => ['required', 'string', 'max:255'],
            'capacity'    => ['required', 'integer', 'min:1'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $class->update($data);

        return redirect()->route('admin.classes.index')
            ->with('status', 'Zajęcia zaktualizowane.');
    }

    public function destroy(GymClass $class): RedirectResponse
    {
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('status', 'Zajęcia usunięte.');
    }
}
