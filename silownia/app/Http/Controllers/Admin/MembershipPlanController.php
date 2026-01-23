<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller
{
   public function index(): View
{
    $plans = MembershipPlan::orderBy('is_active', 'desc')
        ->orderBy('price')
        ->paginate(10);

    return view('admin.plans.index', compact('plans'));
}


    public function create(): View
    {
        return view('admin.plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'price'           => ['required', 'numeric', 'min:0'],
            'duration_months' => ['required', 'integer', 'min:1'],
            'is_active'       => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        MembershipPlan::create($data);

        return redirect()->route('admin.plans.index')
            ->with('status', 'Karnet utworzony.');
    }

    public function edit(MembershipPlan $plan): View
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, MembershipPlan $plan): RedirectResponse
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'price'           => ['required', 'numeric', 'min:0'],
            'duration_months' => ['required', 'integer', 'min:1'],
            'is_active'       => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $plan->update($data);

        return redirect()->route('admin.plans.index')
            ->with('status', 'Karnet zaktualizowany.');
    }

    public function destroy(MembershipPlan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('status', 'Karnet usunięty.');
    }
}
