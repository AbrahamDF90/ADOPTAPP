<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Adopciones y Estado de Mascotas
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-emerald-100 text-emerald-800 p-4 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(isset($adopciones) && $adopciones->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($adopciones as $adopcion)
                        <div class="bg-white p-6 rounded-lg shadow">
                            <p><strong>Mascota:</strong> {{ $adopcion->publicacion->especie }}</p>
                            <p><strong>Estado actual:</strong> {{ ucfirst($adopcion->estado_mascota) }}</p>

                            <form method="POST" action="{{ route('refugio.adopciones.estado', $adopcion) }}" class="mt-4 flex gap-2">
                                @csrf
                                @method('PATCH')
                                
                                <select name="estado_mascota" class="border-gray-300 rounded-md shadow-sm">
                                    <option value="adoptada" @selected($adopcion->estado_mascota === 'adoptada')>Adoptada</option>
                                    <option value="fallecida" @selected($adopcion->estado_mascota === 'fallecida')>Fallecida</option>
                                    <option value="robada" @selected($adopcion->estado_mascota === 'robada')>Robada</option>
                                    <option value="perdida" @selected($adopcion->estado_mascota === 'perdida')>Perdida</option>
                                </select>

                                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">
                                    Actualizar
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No hay adopciones registradas aún.</p>
            @endif
        </div>
    </div>
</x-app-layout>