<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edycja karnetu') }}: {{ $plan->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">

                <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <x-input-label for="name" value="Nazwa karnetu" />
                            <x-text-input id="name" name="name" type="text"
                                          class="mt-1 block w-full"
                                          value="{{ old('name', $plan->name) }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Opis (opcjonalnie)" />
                            <textarea id="description" name="description"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                      rows="3">{{ old('description', $plan->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="price" value="Cena (PLN)" />
                                <x-text-input id="price" name="price" type="number" step="0.01" min="0"
                                              class="mt-1 block w-full"
                                              value="{{ old('price', $plan->price) }}" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="duration_months" value="Okres (miesiące)" />
                                <x-text-input id="duration_months" name="duration_months" type="number" min="1"
                                              class="mt-1 block w-full"
                                              value="{{ old('duration_months', $plan->duration_months) }}" required />
                                <x-input-error :messages="$errors->get('duration_months')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input id="is_active" name="is_active" type="checkbox"
                                   value="1" @checked(old('is_active', $plan->is_active))>
                            <x-input-label for="is_active" value="Karnet aktywny (widoczny dla klientów)" />
                        </div>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('admin.plans.index') }}"
                           class="text-gray-600 hover:underline">
                            Anuluj
                        </a>
                        <x-primary-button>
                            Zapisz
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
