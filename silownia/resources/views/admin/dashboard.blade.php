<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel administratora') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                

                

                <div class="flex flex-wrap gap-3">
                    {{-- Użytkownicy --}}
                    <a href="{{ route('admin.users.index') }}"
                       class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        Przejdź do listy użytkowników
                    </a>

                    {{-- Usunięci użytkownicy (kosz) --}}
                    <a href="{{ route('admin.users.trashed') }}"
                       class="inline-flex items-center rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-300">
                        Zobacz usuniętych użytkowników
                    </a>

                    {{-- Karnety --}}
                    <a href="{{ route('admin.plans.index') }}"
                       class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                        Zarządzaj karnetami
                    </a>
                    <a href="{{ route('admin.classes.index') }}"
                    class="inline-flex items-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">
                        Zarządzaj zajęciami
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
