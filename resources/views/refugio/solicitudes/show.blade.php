<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ficha del Adoptante</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Mensajes de éxito / error tras acciones --}}
            @if(session('success'))
                <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-emerald-600">Datos del Adoptante</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <p><strong>Nombre:</strong> {{ $solicitud->adoptante->user->name }}</p>
                    <p><strong>Alias:</strong> {{ $solicitud->adoptante->alias ?? '—' }}</p>
                    <p><strong>DNI:</strong> {{ $solicitud->adoptante->dni }}</p>
                    <p><strong>Teléfono:</strong> {{ $solicitud->adoptante->telefono ?? '—' }}</p>
                    <p class="md:col-span-2"><strong>Dirección:</strong> {{ $solicitud->adoptante->direccion ?? '—' }}</p>
                </div>

                @if($solicitud->adoptante->foto)
                    <img src="{{ Storage::url($solicitud->adoptante->foto) }}" alt="Foto del adoptante" class="w-32 h-32 object-cover rounded-md">
                @endif

                <hr>

                <h3 class="text-lg font-semibold text-emerald-600">Solicitud</h3>
                <p><strong>Mascota:</strong> {{ $solicitud->publicacion->especie }} ({{ $solicitud->publicacion->sexo }})</p>
                <p><strong>Estado:</strong> {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}</p>

                {{-- HU-20: Acción para aceptar la solicitud --}}
                @if(in_array($solicitud->estado, ['pendiente', 'en_entrevista']))
                    <form action="{{ route('refugio.solicitudes.aceptar', $solicitud) }}"
                          method="POST"
                          class="inline-block mt-4"
                          onsubmit="return confirm('¿Confirmás aceptar esta solicitud? Se cerrarán las demás solicitudes activas.');">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-4 py-2 rounded transition">
                            Aceptar solicitud
                        </button>
                    </form>
                @endif

                {{-- HU-21: Acción para revertir una adopción aceptada --}}
                @if($solicitud->estado === 'aceptada')
                    <form action="{{ route('refugio.solicitudes.revertir', $solicitud) }}"
                          method="POST"
                          class="inline-block mt-4 ml-2"
                          onsubmit="return confirm('¿Revertir la adopción? La publicación volverá a estar disponible y la solicitud quedará pendiente.');">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded transition">
                            Revertir adopción
                        </button>
                    </form>
                @endif

                <a href="{{ route('refugio.solicitudes.index') }}" class="inline-block mt-4 text-emerald-600 hover:text-emerald-800">← Volver al listado</a>
            </div>
        </div>
    </div>
</x-app-layout>