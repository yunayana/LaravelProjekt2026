@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Moje Karnety</h2>

    @if($membership)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Aktywny Karnet</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Typ:</strong> {{ $membership->membership_type }}</p>
                        <p><strong>Status:</strong> <span class="badge bg-success">{{ $membership->status }}</span></p>
                        <p><strong>Data rozpoczęcia:</strong> {{ $membership->start_date->format('d.m.Y') }}</p>
                        <p><strong>Data wygaśnięcia:</strong> {{ $membership->end_date->format('d.m.Y') }}</p>
                    </div>
                </div>

                <h5 class="mt-4">Subskrypcje</h5>
                @if($subscriptions->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Plan</th>
                                <th>Cena</th>
                                <th>Ważność</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptions as $sub)
                                <tr>
                                    <td>{{ $sub->plan_name }}</td>
                                    <td>{{ $sub->price }} PLN</td>
                                    <td>{{ $sub->start_date->format('d.m.Y') }} - {{ $sub->end_date->format('d.m.Y') }}</td>
                                    <td>
                                        <span class="badge {{ $sub->active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $sub->active ? 'Aktywna' : 'Nieaktywna' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">Brak subskrypcji</p>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-info">
            Nie posiadasz aktywnego karnetu. <a href="#" class="alert-link">Kup karnet teraz</a>
        </div>
    @endif
</div>
@endsection
