@extends('layouts.app')

@section('content')
<main id="main-content" class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold text-slate-900 mb-6">
        Zajęcia grupowe
    </h1>

    @if (session('status'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-400 px-4 py-3 text-sm text-green-800" role="status">
            {{ session('status') }}
        </div>
    @endif

    @unless($hasActiveMembership)
        <div class="mb-4 rounded-md bg-yellow-50 border border-yellow-400 px-4 py-3 text-sm text-yellow-800">
            Aby zapisać się na zajęcia, potrzebujesz aktywnego karnetu.
        </div>
    @endunless

    <div class="overflow-x-auto">
        <table class="min-w-full border border-slate-200 bg-white">
            <thead class="bg-slate-100">
                <tr>
                    <th class="px-3 py-2 text-left text-sm font-semibold text-slate-900">Nazwa</th>
                    <th class="px-3 py-2 text-left text-sm font-semibold text-slate-900">Instruktor</th>
                    <th class="px-3 py-2 text-left text-sm font-semibold text-slate-900">Dzień / godzina</th>
                    <th class="px-3 py-2 text-left text-sm font-semibold text-slate-900">Akcja</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($classes as $class)
                    <tr>
                        <td class="px-3 py-2 text-sm text-slate-800">
                            {{ $class->name }}
                        </td>
                        <td class="px-3 py-2 text-sm text-slate-800">
                            {{ optional($class->trainer)->name }}
                        </td>
                        <td class="px-3 py-2 text-sm text-slate-800">
                            {{ $class->schedule }}
                        </td>
                        <td class="px-3 py-2 text-sm text-slate-800">
                            @if (in_array($class->id, $registrations))
                                <form action="{{ route('client.classes.unregister', $class) }}" method="POST" class="inline">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="rounded-md bg-red-600 px-3 py-1 text-xs font-semibold text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                    >
                                        Wypisz się
                                    </button>
                                </form>
                            @elseif($hasActiveMembership)
                                <form action="{{ route('client.classes.register', $class) }}" method="POST" class="inline">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="rounded-md bg-indigo-600 px-3 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        Zapisz się
                                    </button>
                                </form>
                            @else
                                <span class="text-sm text-slate-500">Brak aktywnego karnetu</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-4 text-sm text-slate-800">
                            Brak zaplanowanych zajęć.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection
