@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Zarządzanie Zajęciami</h2>
        <a href="{{ route('employee.classes.create') }}" class="btn btn-primary">Dodaj zajęcia</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nazwa</th>
                    <th>Instruktor</th>
                    <th>Plan</th>
                    <th>Zapisani</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $class)
                    <tr>
                        <td>{{ $class->name }}</td>
                        <td>{{ $class->trainer->name ?? 'N/A' }}</td>
                        <td>{{ $class->schedule }}</td>
                        <td>{{ $class->registrations->count() }}/{{ $class->max_participants }}</td>
                        <td>
                            <a href="{{ route('employee.classes.edit', $class) }}" class="btn btn-sm btn-warning">Edytuj</a>
                            <form method="POST" action="{{ route('employee.classes.destroy', $class) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Jesteś pewny?')">Usuń</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Brak zajęć</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $classes->links() }}
</div>
@endsection
