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
        <table class="min-w-full border border-slate-200 text-sm">
            <thead class="bg-slate-100">
                <tr>
                    @foreach ($schedule as $dayName => $items)
                        <th class="px-3 py-2 text-left font-semibold text-slate-900">
                            {{ $dayName }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach ($schedule as $dayName => $items)
                        <td class="align-top px-3 py-2 border-t border-slate-200">
                            @forelse ($items as $item)
                                <div class="mb-2 p-2 rounded border border-slate-200">
                                    <div class="font-semibold">
                                        {{ $item['time'] }} – {{ $item['class']->name }}
                                    </div>
                                    <div class="text-xs text-slate-600">
                                        {{ $item['class']->trainer->name ?? 'Brak trenera' }}
                                    </div>

                                    @if (in_array($item['class']->id, $registrations))
                                        {{-- Wypisanie z zajęć --}}
                                        <form method="POST"
                                              action="{{ route('client.classes.unregister', $item['class']) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="mt-1 text-xs text-red-600 hover:underline">
                                                Wypisz się
                                            </button>
                                        </form>
                                    @else
                                        {{-- Zapis na zajęcia --}}
                                        <form method="POST"
                                              action="{{ route('client.classes.register', $item['class']) }}">
                                            @csrf
                                            <button class="mt-1 text-xs text-emerald-600 hover:underline"
                                                    @disabled(! $hasActiveMembership)>
                                                Zapisz się
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <span class="text-xs text-slate-400">Brak zajęć</span>
                            @endforelse
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Mój harmonogram klienta --}}
    <section class="mt-8">
        <h2 class="text-xl font-semibold text-slate-900 mb-4">
            Mój harmonogram zajęć
        </h2>

        @if ($myClasses->isEmpty())
            <p class="text-sm text-slate-500">
                Nie masz jeszcze żadnych zapisanych zajęć. Zapisz się, klikając „Zapisz się” w harmonogramie powyżej.
            </p>
        @else
            <div class="bg-white shadow-sm ring-1 ring-slate-200 rounded-lg divide-y divide-slate-200">
                @foreach ($myClasses as $class)
                    @php
                        [$day, $time] = explode(' ', $class->schedule, 2);
                    @endphp
                    <div class="flex items-center justify-between px-4 py-3">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">
                                {{ $class->name }}
                            </div>
                            <div class="text-xs text-slate-600">
                                {{ $day }} • {{ $time }} • {{ $class->trainer->name ?? 'Brak trenera' }}
                            </div>
                        </div>

                        <form method="POST"
                              action="{{ route('client.classes.unregister', $class) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center rounded-md border border-red-200 px-3 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100">
                                Wypisz się
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</main>
@endsection
