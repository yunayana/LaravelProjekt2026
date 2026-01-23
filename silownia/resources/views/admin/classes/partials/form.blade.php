<div class="space-y-4">
    <div>
        <x-input-label for="name" value="Nazwa zajęć" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                      value="{{ old('name', $class->name ?? '') }}" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" value="Opis (opcjonalnie)" />
        <textarea id="description" name="description"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  rows="3">{{ old('description', $class->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="trainer_id" value="Trener" />
        <select id="trainer_id" name="trainer_id"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">Wybierz trenera...</option>
            @foreach($trainers as $trainer)
                <option value="{{ $trainer->id }}"
                    @selected(old('trainer_id', $class->trainer_id ?? null) == $trainer->id)>
                    {{ $trainer->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('trainer_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="schedule" value="Plan (np. Poniedziałek 18:00)" />
        <x-text-input id="schedule" name="schedule" type="text" class="mt-1 block w-full"
                      value="{{ old('schedule', $class->schedule ?? '') }}" required />
        <x-input-error :messages="$errors->get('schedule')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="capacity" value="Maksymalna liczba osób" />
        <x-text-input id="capacity" name="capacity" type="number" min="1"
                      class="mt-1 block w-full"
                      value="{{ old('capacity', $class->capacity ?? 10) }}" required />
        <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
    </div>

    <div class="flex items-center gap-2">
        <input id="is_active" name="is_active" type="checkbox" value="1"
               @checked(old('is_active', $class->is_active ?? true))>
        <x-input-label for="is_active" value="Zajęcia aktywne (widoczne dla klientów)" />
    </div>
    <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
</div>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.classes.index') }}"
       class="text-gray-600 hover:underline">
        Anuluj
    </a>
    <x-primary-button>
        Zapisz
    </x-primary-button>
</div>
