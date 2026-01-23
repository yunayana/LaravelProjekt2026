<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Użytkownicy') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Szukaj po imieniu lub emailu"
                       class="border-gray-300 rounded-md shadow-sm">
                <select name="role" class="border-gray-300 rounded-md shadow-sm">
                    <option value="">Wszystkie role</option>
                    <option value="client" @selected(request('role') === 'client')>Client</option>
                    <option value="employee" @selected(request('role') === 'employee')>Employee</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                </select>
                <x-primary-button>Filtruj</x-primary-button>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Imię</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-right">Akcje</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $user->id }}</td>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">
                                {{ $user->roles->pluck('name')->join(', ') ?: 'brak' }}
                            </td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="text-indigo-600 hover:underline">Edytuj</a>

                                @if(! $user->hasRole('admin') || auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                          method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Na pewno usunąć użytkownika?')"
                                                class="text-red-600 hover:underline">
                                            Usuń
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
