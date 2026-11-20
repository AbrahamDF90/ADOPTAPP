<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Adoptantes registrados
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.dashboard') }}"
                   class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                    ← Volver al panel
                </a>
            </div>

            <div class="bg-white shadow rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-black text-emerald-400">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Alias</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">DNI</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Teléfono</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($adoptantes as $adoptante)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">{{ $adoptante->alias }}</td>
                                <td class="px-4 py-3 text-sm">{{ $adoptante->user->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $adoptante->user->email }}</td>
                                <td class="px-4 py-3 text-sm">{{ $adoptante->dni }}</td>
                                <td class="px-4 py-3 text-sm">{{ $adoptante->telefono }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    No hay adoptantes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $adoptantes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>