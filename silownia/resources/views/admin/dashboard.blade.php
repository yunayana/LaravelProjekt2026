@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Panel Administratora</h2>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Łączna liczba użytkowników</h5>
                    <h2 class="text-primary">{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Liczba klientów</h5>
                    <h2 class="text-info">{{ $totalClients }}</h2>
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
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Aktywne karnety</h5>
                    <h2 class="text-warning">{{ $activeMemberships }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Sprzęt dostępny</h5>
                    <h2 class="text-danger">{{ $equipmentCount }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Ostatnio zarejestrowani użytkownicy</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Imię i Nazwisko</th>
                        <th>Email</th>
                        <th>Rola</th>
                        <th>Data rejestracji</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
