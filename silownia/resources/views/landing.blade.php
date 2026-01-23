@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-4 py-10">
    {{-- Hero --}}
    <section class="grid gap-8 lg:grid-cols-2 items-center mb-12">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                Nowoczesny klub fitness w Twoim mieście
            </h1>
            <p class="text-slate-600 mb-6">
                Trenuj z doświadczonymi trenerami, zapisuj się na zajęcia online
                i zarządzaj swoim karnetem w prostym panelu klienta.
            </p>

            <div class="flex gap-3">
                @guest
                    {{-- gość: rejestracja / logowanie --}}
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        Dołącz teraz
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Zaloguj się
                    </a>
                @else
                    @php $user = auth()->user(); @endphp

                    @if ($user->hasRole('client'))
                        <a href="{{ route('client.dashboard') }}"
                           class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                            Panel klienta
                        </a>
                    @elseif ($user->hasRole('employee'))
                        <a href="{{ route('employee.dashboard') }}"
                           class="inline-flex items-center rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">
                            Panel pracownika
                        </a>
                    @elseif ($user->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center rounded-md bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                            Panel administratora
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Wyloguj
                        </button>
                    </form>
                @endguest
            </div>
        </div>

        <div class="hidden md:block">
            {{-- tu możesz dodać zdjęcie / grafikę siłowni --}}
            <div class="aspect-video rounded-2xl bg-gradient-to-br from-indigo-500 to-emerald-500 opacity-90"></div>
        </div>
    </section>

    {{-- Karnety --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Karnety</h2>

        <div class="grid grid-cols-2 gap-6">
            @foreach ($memberships as $plan)
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">
                        {{ $plan['name'] }}
                    </h3>
                    <p class="mt-1 text-sm text-slate-600">
                        {{ $plan['desc'] }}
                    </p>
                    <p class="mt-3 text-2xl font-bold text-indigo-600">
                        {{ number_format($plan['price'], 2, ',', ' ') }} PLN
                    </p>
                    <p class="text-xs text-slate-500">
                        Okres: {{ $plan['duration'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Przykładowe zajęcia --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Zajęcia grupowe</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($classes as $class)
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm max-w-sm w-full">
                    <h3 class="text-base font-semibold text-slate-900">
                        {{ $class->name }}
                    </h3>
                    <p class="mt-1 text-xs text-slate-600">
                        Instruktor: {{ $class->trainer->name }}
                    </p>
                    <p class="mt-1 text-xs text-slate-600">
                        Termin: {{ $class->schedule }}
                    </p>
                    <p class="mt-2 text-sm text-slate-600 line-clamp-2">
                        {{ $class->description }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            @auth
                <a href="{{ route('client.classes.index') }}"
                   class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                    Zobacz pełen harmonogram &rarr;
                </a>
            @else
                <span class="text-sm text-slate-500">
                    Zaloguj się, aby zapisać się na zajęcia.
                </span>
            @endauth
        </div>
    </section>

    {{-- Nasi trenerzy --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Nasi trenerzy</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($trainers as $trainer)
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm max-w-sm w-full">
                    <div class="flex items-center gap-3 mb-3">
                        @if ($trainer->photo)
                            <img src="{{ asset('storage/' . $trainer->photo) }}"
                                 alt="{{ $trainer->name }}"
                                 class="h-12 w-12 rounded-full object-cover">
                        @else
                            <div class="h-12 w-12 rounded-full bg-slate-200 flex items-center justify-center text-sm font-semibold text-slate-700">
                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($trainer->name, 0, 1)) }}
                            </div>
                        @endif

                        <div>
                            <h3 class="text-base font-semibold text-slate-900">
                                {{ $trainer->name }}
                            </h3>
                            <p class="mt-0.5 text-xs font-medium text-emerald-700">
                                {{ $trainer->specialization }}
                            </p>
                        </div>
                    </div>

                    <p class="mt-1 text-sm text-slate-600 line-clamp-3">
                        {{ $trainer->bio }}
                    </p>
                </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
