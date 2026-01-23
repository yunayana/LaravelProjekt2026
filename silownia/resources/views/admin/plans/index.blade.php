<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Karnety') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}"
                   class="text-sm text-gray-600 hover:underline">
                    ← Powrót do panelu
                </a>
                <a href="{{ route('admin.plans.create') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Dodaj karnet
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="px-4 py-2 text-left">Nazwa</th>
                        <th class="px-4 py-2 text-left">Cena</th>
                        <th class="px-4 py-2 text-left">Okres (mies.)</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-right">Akcje</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($plans as $plan)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $plan->name }}</td>
                            <td class="px-4 py-2">{{ number_format($plan->price, 2) }} PLN</td>
                            <td class="px-4 py-2">{{ $plan->duration_months }}</td>
                            <td class="px-4 py-2">
                                @if($plan->is_active)
                                    <span class="text-green-600">Aktywny</span>
                                @else
                                    <span class="text-gray-500">Ukryty</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('admin.plans.edit', $plan) }}"
                                   class="text-indigo-600 hover:underline">
                                    Edytuj
                                </a>
                                <form action="{{ route('admin.plans.destroy', $plan) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Usunąć ten karnet?')"
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
                    {{ $plans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
