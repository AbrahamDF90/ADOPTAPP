<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Solicitudes de Adopción
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('refugio.solicitudes.index') }}" class="mb-6 flex flex-col md:flex-row gap-2">
                <select name="estado" class="border-gray-300 rounded-md shadow-sm w-full md:w-auto">
                    <option value="">Todos los estados</option>
                    @foreach(['pendiente','en_entrevista','aceptada','cerrada','cancelada'] as $estado)
                        <option value="{{ $estado }}" @selected(request('estado') === $estado)>
                            {{ ucfirst(str_replace('_', ' ', $estado)) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 transition">
                    Filtrar
                </button>
            </form>

            @if($solicitudes->isEmpty())
                <p class="text-gray-500">No hay solicitudes recibidas.</p>
            @else
                <div class="bg-white shadow rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mascota</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Adoptante</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-4 md:px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($solicitudes as $solicitud)
                                <tr>
                                    <td class="px-4 md:px-6 py-4">{{ $solicitud->publicacion->especie }} ({{ $solicitud->publicacion->sexo }})</td>
                                    <td class="px-4 md:px-6 py-4">{{ $solicitud->adoptante->alias ?? $solicitud->adoptante->user->name }}</td>
                                    <td class="px-4 md:px-6 py-4">
                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                            @if($solicitud->estado === 'pendiente') bg-amber-100 text-amber-800
                                            @elseif($solicitud->estado === 'aceptada') bg-emerald-100 text-emerald-800
                                            @elseif($solicitud->estado === 'cancelada') bg-red-100 text-red-800
                                            @elseif($solicitud->estado === 'en_entrevista') bg-sky-100 text-sky-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 text-sm text-gray-500">{{ $solicitud->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 md:px-6 py-4 text-right">
                                        <a href="{{ route('refugio.solicitudes.show', $solicitud) }}" class="text-emerald-600 hover:text-emerald-800 font-medium">Ver ficha</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $solicitudes->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>