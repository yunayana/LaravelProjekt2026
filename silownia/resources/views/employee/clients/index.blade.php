@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Zarządzanie Klientami</h2>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Imię i Nazwisko</th>
                    <th>Email</th>
                    <th>Karnet</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>
                            @if($client->gymMembership)
                                <span class="badge bg-success">{{ $client->gymMembership->status }}</span>
                            @else
                                <span class="badge bg-secondary">Brak</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('employee.clients.show', $client) }}" class="btn btn-sm btn-primary">Szczegóły</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Brak klientów</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $clients->links() }}
</div>
@endsection
