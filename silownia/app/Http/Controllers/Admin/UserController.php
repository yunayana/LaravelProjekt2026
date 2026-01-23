<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Lista użytkowników.
     */
    public function index(Request $request): View
    {
        $query = User::query()->with('roles');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->get('role')) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Formularz edycji użytkownika.
     */
    public function edit(User $user): View
    {
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Aktualizacja danych i ról.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'roles' => ['array'],
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        $user->roles()->sync($request->input('roles', []));

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Użytkownik zaktualizowany.');
    }

    /**
     * Soft delete użytkownika.
     *
     * @throws AuthorizationException
     */
    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            throw new AuthorizationException('Nie możesz usunąć samego siebie.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Użytkownik usunięty.');
    }
    public function trashed(): View
{
    $users = User::onlyTrashed()->with('roles')->paginate(15);

    return view('admin.users.trashed', compact('users'));
}

public function restore(int $id): RedirectResponse
{
    $user = User::onlyTrashed()->findOrFail($id);
    $user->restore();

    return redirect()->route('admin.users.trashed')
        ->with('status', 'Użytkownik przywrócony.');
}

public function forceDestroy(int $id): RedirectResponse
{
    $user = User::onlyTrashed()->findOrFail($id);
    $user->forceDelete();

    return redirect()->route('admin.users.trashed')
        ->with('status', 'Użytkownik usunięty trwale.');
}

}
