@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Panel Pracownika</h2>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Łączna liczba klientów</h5>
                    <h2 class="text-primary">{{ $totalClients }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Dostępne zajęcia</h5>
                    <h2 class="text-success">{{ $totalClasses }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Najbliższe zajęcia</h5>
        </div>
        <div class="card-body">
            @if($upcomingClasses->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nazwa</th>
                            <th>Instruktor</th>
                            <th>Plan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingClasses as $class)
                            <tr>
                                <td>{{ $class->name }}</td>
                                <td>{{ $class->trainer->name ?? 'N/A' }}</td>
                                <td>{{ $class->schedule }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">Brak zajęć</p>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('employee.clients.index') }}" class="btn btn-primary">Zarządzaj klientami</a>
        <a href="{{ route('employee.classes.index') }}" class="btn btn-success">Zarządzaj zajęciami</a>
    </div>
</div>
@endsection
