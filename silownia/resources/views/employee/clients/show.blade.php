@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('employee.clients.index') }}" class="btn btn-secondary mb-3">Wróć</a>
    <h2 class="mb-4">{{ $user->name }}</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informacje</h5>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Rola:</strong> {{ implode(', ', $user->roles->pluck('name')->toArray()) }}</p>
                    @if($membership)
                        <p><strong>Karnet:</strong> <span class="badge bg-success">{{ $membership->status }}</span></p>
                    @else
                        <p><strong>Karnet:</strong> <span class="badge bg-secondary">Brak</span></p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Zarejestrowane zajęcia ({{ $registrations->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($registrations->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Zajęcia</th>
                                    <th>Instruktor</th>
                                    <th>Plan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrations as $reg)
                                    <tr>
                                        <td>{{ $reg->gymClass->name }}</td>
                                        <td>{{ $reg->gymClass->trainer->name ?? 'N/A' }}</td>
                                        <td>{{ $reg->gymClass->schedule }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Brak zarejestrowanych zajęć</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
