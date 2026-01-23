{{-- resources/views/client/membership/index.blade.php --}}
@extends('layouts.app')

@section('content')
<main id="main-content" class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold text-slate-900 mb-6">
        Mój karnet
    </h1>

    {{-- Komunikat statusu --}}
    @if (session('status'))
    <div class="mb-4 rounded-md bg-green-50 border border-green-400 px-4 py-3 text-sm text-green-800" role="status">
        {{ session('status') }}
    </div>

    @if ($membership)
        <p><strong>Plan:</strong> {{ $membership->membership_type }}</p>
    @endif
@endif


   {{-- Aktualny karnet --}}
<section aria-labelledby="current-membership-heading" class="mb-8">
    <h2 id="current-membership-heading" class="text-xl font-medium text-slate-900 mb-3">
        Aktualny karnet
    </h2>

    @if ($membership)
        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-slate-800">
                Typ: <span class="font-medium">{{ $membership->membership_type }}</span>
            </p>
            <p class="text-slate-800">
                Od: {{ $membership->start_date?->format('d.m.Y') }}
                do: {{ $membership->end_date?->format('d.m.Y') }}
            </p>
            <p class="text-slate-800">
                Status:
                <span class="font-medium text-green-700">aktywny</span>
            </p>

            <div class="flex gap-3 mt-4 pt-4 border-t border-slate-200">
    {{-- Przedłuż karnet --}}
    <a
        href="#renewal-form"
        class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
    >
        Przedłuż karnet
    </a>

    {{-- Cofnij ostatnie przedłużenie --}}
    <form action="{{ route('client.membership.cancel-last-extension') }}" method="POST" style="display: inline;">
        @csrf
        <button
            type="submit"
            class="inline-flex items-center rounded-md bg-yellow-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
        >
            Cofnij ostatnie przedłużenie
        </button>
    </form>

    {{-- Anuluj cały karnet --}}
    <form action="{{ route('client.membership.cancel') }}" method="POST"
          onsubmit="return confirm('Czy na pewno chcesz anulować cały karnet?');" style="display: inline;">
        @csrf
        @method('DELETE')
        <button
            type="submit"
            class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
        >
            Anuluj karnet
        </button>
    </form>
</div>

        </div>
    @else
        <p class="text-slate-800 mb-4">
            Nie masz jeszcze aktywnego karnetu.
        </p>
        <a
            href="#renewal-form"
            class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
        >
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
            </svg>
            Kup karnet
        </a>
    @endif
</section>



    {{-- Formularz zakupu/przedłużenia karnetu --}}
<section aria-labelledby="buy-membership-heading" class="mb-10" id="renewal-form">
    <h2 id="buy-membership-heading" class="text-xl font-medium text-slate-900 mb-3">
        Kup / przedłuż karnet
    </h2>

    <form action="{{ route('client.membership.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Typ karnetu --}}
        <div>
            <label for="membership_plan_id" class="block text-sm font-medium text-slate-900">
                Typ karnetu
            </label>

            <select id="membership_plan_id" name="membership_plan_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    required>
                <option value="">Wybierz...</option>
                @foreach($plans as $plan)
                    @php
                        // dla Basic/Premium/VIP value = nazwa,
                        // dla planów admina value = db:ID
                        $value = $plan->is_default
                            ? $plan->name
                            : 'db:' . $plan->id;
                    @endphp
                    <option value="{{ $value }}">
                        {{ $plan->name }} – {{ number_format($plan->price, 2) }} zł / {{ $plan->duration_months * 30 }} dni
                    </option>
                @endforeach
            </select>

            @error('membership_plan_id')
                <p class="mt-1 text-sm text-red-700">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Informacja o czasie trwania --}}
        <p class="text-sm text-slate-700">
            Czas trwania każdego karnetu: <span class="font-medium">30 dni</span> (ustawiany automatycznie).
        </p>

        {{-- Informacja o cenie --}}
        <p class="text-sm text-slate-700">
            Cena zależy od wybranego typu karnetu.
        </p>

        <button
            type="submit"
            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
            Zapisz karnet
        </button>
    </form>
</section>



    {{-- Historia subskrypcji --}}
    <section aria-labelledby="history-heading">
        <h2 id="history-heading" class="text-xl font-medium text-slate-900 mb-3">
            Historia karnetów
        </h2>

        @if ($subscriptions->isEmpty())
            <p class="text-slate-800">
                Brak historii karnetów.
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border border-slate-200 bg-white">
                    <thead class="bg-slate-100">
                        <tr>
                            <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-slate-900">
                                Typ
                            </th>
                            <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-slate-900">
                                Okres
                            </th>
                            <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-slate-900">
                                Cena
                            </th>
                            <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-slate-900">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($subscriptions as $subscription)
                            <tr>
                                <td class="px-3 py-2 text-sm text-slate-800">
                                    {{ $subscription->plan_name }}
                                </td>
                                <td class="px-3 py-2 text-sm text-slate-800">
                                    {{ $subscription->start_date?->format('d.m.Y') }}
                                    –
                                    {{ $subscription->end_date?->format('d.m.Y') }}
                                </td>
                                <td class="px-3 py-2 text-sm text-slate-800">
                                    {{ number_format($subscription->price, 2, ',', ' ') }} zł
                                </td>
                                <td class="px-3 py-2 text-sm text-slate-800">
                                    @if ($subscription->active)
                                        <span class="font-medium text-green-700">aktywny</span>
                                    @else
                                        <span class="font-medium text-slate-700">zakończony</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</main>
@endsection
