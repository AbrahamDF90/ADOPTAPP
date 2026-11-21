<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Refugios registrados
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}"
                   class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                    ← Volver al panel
                </a>
                <a href="{{ route('admin.refugios.create') }}"
                   class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded">
                    + Nuevo refugio
                </a>
            </div>

            {{-- Mensaje de éxito --}}
            @if(session('success'))
                <div class="bg-emerald-100 text-emerald-800 p-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-black text-emerald-400">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Ubicación</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Teléfono</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Acciones</th> {{-- NUEVA COLUMNA --}}
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($refugios as $refugio)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">{{ $refugio->nombre }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $refugio->user->email }}</td>
                                <td class="px-4 py-3 text-sm">{{ $refugio->ubicacion }}</td>
                                <td class="px-4 py-3 text-sm">{{ $refugio->telefono }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($refugio->activo)
                                        <span class="px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-700">Activo</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Bloqueado</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center gap-3">
                                        {{-- HU-10: Botón Editar --}}
                                        <a href="{{ route('admin.refugios.edit', $refugio) }}" class="text-blue-600 hover:underline font-bold">
                                            Editar
                                        </a>

                                        {{-- HU-10: Botón Bloquear/Habilitar --}}
                                        <form action="{{ route('admin.refugios.toggle', $refugio) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que querés cambiar el estado de este refugio?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="{{ $refugio->activo ? 'text-red-600' : 'text-emerald-600' }} hover:underline font-bold">
                                                {{ $refugio->activo ? 'Bloquear' : 'Habilitar' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    No hay refugios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $refugios->links() }}
            </div>
        </div>
    </div>
</x-app-layout>