@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dodaj Zajęcia</h2>

    <div class="row">
        <div class="col-md-8">
            <form method="POST" action="{{ route('employee.classes.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Instruktor</label>
                    <select name="trainer_id" class="form-control @error('trainer_id') is-invalid @enderror" required>
                        <option value="">Wybierz instruktora</option>
                        @foreach($trainers as $trainer)
                            <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                        @endforeach
                    </select>
                    @error('trainer_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nazwa zajęć</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Opis</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Plan zajęć</label>
                    <input type="text" name="schedule" class="form-control @error('schedule') is-invalid @enderror" placeholder="np. Poniedziałek 18:00" value="{{ old('schedule') }}" required>
                    @error('schedule')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Maksymalna liczba uczestników</label>
                    <input type="number" name="max_participants" class="form-control @error('max_participants') is-invalid @enderror" value="{{ old('max_participants', 20) }}" min="1" required>
                    @error('max_participants')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="btn btn-primary">Dodaj zajęcia</button>
                <a href="{{ route('employee.classes.index') }}" class="btn btn-secondary">Anuluj</a>
            </form>
        </div>
    </div>
</div>
@endsection
