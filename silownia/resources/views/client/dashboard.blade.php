@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mój Karnet</h5>
                    @if($membership)
                        <p><strong>Status:</strong> <span class="badge bg-success">{{ $membership->status }}</span></p>
                        <p><strong>Od:</strong> {{ $membership->start_date->format('d.m.Y') }}</p>
                        <p><strong>Do:</strong> {{ $membership->end_date->format('d.m.Y') }}</p>
                        <a href="{{ route('client.membership.show') }}" class="btn btn-sm btn-primary">Szczegóły</a>
                    @else
                        <p>Brak aktywnego karnetu</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5>Moje Zajęcia ({{ $registrations->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($registrations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                        </div>
                    @else
                        <p class="text-muted">Nie jesteś zarejestrowany na żadne zajęcia</p>
                    @endif
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('client.classes.index') }}" class="btn btn-primary">Przeglądaj wszystkie zajęcia</a>
            </div>
        </div>
    </div>
</div>
@endsection
