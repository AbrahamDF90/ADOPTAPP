<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Publicaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Mensaje de éxito --}}
                @if(session('success'))
                    <div class="bg-emerald-100 text-emerald-800 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-100 text-gray-700">
                                <th class="p-3">ID</th>
                                <th class="p-3">Mascota</th>
                                <th class="p-3">Refugio</th>
                                <th class="p-3">Estado</th>
                                <th class="p-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($publicaciones as $publicacion)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">{{ $publicacion->id }}</td>
                                    <td class="p-3 flex items-center gap-2">
                                        <img src="{{ asset('storage/' . $publicacion->foto) }}" class="w-10 h-10 object-cover rounded">
                                        <span>{{ $publicacion->especie }} ({{ $publicacion->sexo }})</span>
                                    </td>
                                    <td class="p-3">{{ $publicacion->refugio->nombre }}</td>
                                    <td class="p-3">
                                        {{-- Lógica para mostrar el estado considerando el SoftDelete --}}
                                        @if($publicacion->trashed())
                                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold">Dada de baja</span>
                                        @else
                                            <span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded text-xs font-bold">{{ ucfirst($publicacion->estado) }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3 flex gap-2">
                                        @if($publicacion->trashed())
                                            {{-- Botón y Formulario para restaurar --}}
                                            <form action="{{ route('admin.publicaciones.restore', $publicacion->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-blue-600 hover:underline text-sm font-bold">Restaurar</button>
                                            </form>
                                        @else
                                            {{-- Botón y Formulario para dar de baja --}}
                                            <form action="{{ route('admin.publicaciones.destroy', $publicacion) }}" method="POST" onsubmit="return confirm('¿Seguro que querés dar de baja esta publicación?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline text-sm font-bold">Dar de baja</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-gray-500">No hay publicaciones registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-4">
                    {{ $publicaciones->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>