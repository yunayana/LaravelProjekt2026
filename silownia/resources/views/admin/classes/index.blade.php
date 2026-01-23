<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zajęcia') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}"
                   class="text-sm text-gray-600 hover:underline">
                    ← Powrót do panelu
                </a>
                <a href="{{ route('admin.classes.create') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Dodaj zajęcia
                </a>
            </div>

            @if(session('status'))
                <div class="rounded-md bg-green-50 border border-green-400 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left">Nazwa</th>
                        <th class="px-4 py-2 text-left">Trener</th>
                        <th class="px-4 py-2 text-left">Plan</th>
                        <th class="px-4 py-2 text-left">Limit</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-right">Akcje</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($classes as $class)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $class->name }}</td>
                            <td class="px-4 py-2">{{ $class->trainer->name ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $class->schedule }}</td>
                            <td class="px-4 py-2">{{ $class->capacity }}</td>
                            <td class="px-4 py-2">
                                @if($class->is_active)
                                    <span class="text-green-600">Aktywne</span>
                                @else
                                    <span class="text-gray-500">Ukryte</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('admin.classes.edit', $class) }}"
                                   class="text-indigo-600 hover:underline">
                                    Edytuj
                                </a>
                                <form action="{{ route('admin.classes.destroy', $class) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Usunąć te zajęcia?')"
                                            class="text-red-600 hover:underline">
                                        Usuń
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $classes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
