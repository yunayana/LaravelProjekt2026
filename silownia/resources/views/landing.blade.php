@extends('layouts.app')

@section('title', 'GymPro – Twoja siłownia')

@section('content')
<main id="main-content">

    {{-- Sekcja hero --}}
    <section class="py-5 bg-dark text-white text-center">
        <div class="container">
            <h1 class="display-4 mb-3">Nowoczesna siłownia w Twoim mieście</h1>
            <p class="lead mb-4">
                Treningi personalne, zajęcia grupowe i elastyczne karnety dopasowane do Twoich potrzeb.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                    Zaloguj się
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                    Dołącz teraz
                </a>
            </div>
        </div>
    </section>

    {{-- Sekcja karnetów --}}
    <section class="py-5 bg-light" aria-labelledby="memberships-heading">
        <div class="container">
            <h2 id="memberships-heading" class="mb-4">Karnety</h2>
            <div class="row g-4">
                @forelse($memberships as $membership)
                    <div class="col-md-3">
                        <article class="card h-100">
                            <div class="card-body">
                                <h3 class="h5 card-title">{{ $membership->name }}</h3>
                                <p class="card-text mb-1">{{ $membership->duration_days }} dni</p>
                                <p class="card-text fw-bold mb-3">{{ $membership->price }} PLN</p>
                                @auth
                                    @if(auth()->user()->hasRole('client'))
                                        <a href="{{ route('client.membership.index') }}"
                                           class="btn btn-primary">
                                            Kup / przedłuż
                                        </a>
                                    @else
                                        <a href="{{ route('client.dashboard') }}"
                                           class="btn btn-outline-primary">
                                            Przejdź do panelu
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary">
                                        Zaloguj się, aby kupić
                                    </a>
                                @endauth
                            </div>
                        </article>
                    </div>
                @empty
                    <p>Brak zdefiniowanych karnetów.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Sekcja zajęć --}}
    <section class="py-5" aria-labelledby="classes-heading">
        <div class="container">
            <h2 id="classes-heading" class="mb-4">Zajęcia grupowe</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Nazwa</th>
                            <th scope="col">Instruktor</th>
                            <th scope="col">Dzień / godzina</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td>{{ $class->name }}</td>
                                <td>{{ optional($class->trainer)->name }}</td>
                                <td>{{ $class->formatted_schedule ?? $class->start_time }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Aktualnie brak zaplanowanych zajęć.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @auth
                @if(auth()->user()->hasRole('client'))
                    <a href="{{ route('client.classes.index') }}" class="btn btn-primary">
                        Przeglądaj wszystkie zajęcia
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                    Zaloguj się, aby zapisać się na zajęcia
                </a>
            @endauth
        </div>
    </section>

    {{-- Sekcja trenerów --}}
    <section class="py-5 bg-light" aria-labelledby="trainers-heading">
        <div class="container">
            <h2 id="trainers-heading" class="mb-4">Nasi trenerzy</h2>
            <div class="row g-4">
                @forelse($trainers as $trainer)
                    <div class="col-md-4">
                        <article class="card h-100">
                            <div class="card-body">
                                <h3 class="h5 card-title">{{ $trainer->name }}</h3>
                                <p class="card-text mb-1">{{ $trainer->specialization }}</p>
                                <p class="card-text">{{ $trainer->bio }}</p>
                            </div>
                        </article>
                    </div>
                @empty
                    <p>Brak dodanych trenerów.</p>
                @endforelse
            </div>
        </div>
    </section>

</main>
@endsection
