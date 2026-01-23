<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Moje zajęcia
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr>
                            <th class="text-left px-4 py-2">Nazwa</th>
                            <th class="text-left px-4 py-2">Termin</th>
                            <th class="text-left px-4 py-2">Max osób</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $class->name }}</td>
                                <td class="px-4 py-2">{{ $class->schedule }}</td>
                                <td class="px-4 py-2">{{ $class->max_participants }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                                    Nie masz jeszcze przypisanych zajęć.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
