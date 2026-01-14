@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Panel administratora</h1>
    <p>Witaj, {{ auth()->user()->name }}!</p>
    <p>Tu możesz zarządzać wszystkimi użytkownikami i ustawieniami systemu.</p>
</div>
@endsection
