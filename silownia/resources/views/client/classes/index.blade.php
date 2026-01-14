@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dostępne Zajęcia</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($classes as $class)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $class->name }}</h5>
                        <p class="card-text">{{ Str::limit($class->description, 100) }}</p>
                        <p><strong>Instruktor:</strong> {{ $class->trainer->name ?? 'N/A' }}</p>
                        <p><strong>Plan:</strong> {{ $class->schedule }}</p>
                        <p><strong>Miejsc dostępnych:</strong> {{ $class->max_participants - $class->registrations->count() }}/{{ $class->max_participants }}</p>

                        @if($registeredClassIds->contains($class->id))
                            <form method="POST" action="{{ route('client.classes.unregister', $class) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-danger btn-sm">Wyrejestruj się</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('client.classes.register', $class) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-primary btn-sm">Zarejestruj się</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">Brak dostępnych zajęć</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
