<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php
                        $user = auth()->user();
                    @endphp

                    @if($user->hasRole('admin'))
                        <h3 class="mb-4 font-bold text-lg">Witaj Admin - {{ $user->name }}!</h3>
                        <p class="mb-4">Wybierz co chcesz zrobić:</p>
                        <a href="{{ route('admin.dashboard') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded">
                            Przejdź do panelu admina
                        </a>
                    @elseif($user->hasRole('employee'))
                        <h3 class="mb-4 font-bold text-lg">Witaj Pracowniku - {{ $user->name }}!</h3>
                        <p class="mb-4">Wybierz co chcesz zrobić:</p>
                        <a href="{{ route('employee.dashboard') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded">
                            Przejdź do panelu pracownika
                        </a>
                    @elseif($user->hasRole('client'))
                        <h3 class="mb-4 font-bold text-lg">Witaj Kliencie - {{ $user->name }}!</h3>
                        <p class="mb-4">Wybierz co chcesz zrobić:</p>
                        <a href="{{ route('client.dashboard') }}" class="inline-block px-4 py-2 bg-purple-600 text-white rounded">
                            Przejdź do panelu klienta
                        </a>
                    @else
                        <p>Brak przypisanej roli.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
