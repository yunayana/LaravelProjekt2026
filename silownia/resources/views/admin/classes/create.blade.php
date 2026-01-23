<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dodaj zajęcia') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
                <form method="POST" action="{{ route('admin.classes.store') }}">
                    @csrf

                    @include('admin.classes.partials.form', ['class' => null])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
