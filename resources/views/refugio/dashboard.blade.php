<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel del Refugio') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HU-28: Métricas del refugio --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                @php
                    $cards = [
                        ['label' => 'Mis publicaciones', 'valor' => $metricas['total_publicaciones'], 'color' => 'emerald'],
                        ['label' => 'Activas', 'valor' => $metricas['publicaciones_activas'], 'color' => 'emerald'],
                        ['label' => 'Adoptadas', 'valor' => $metricas['publicaciones_adoptadas'], 'color' => 'sky'],
                        ['label' => 'Solicitudes pendientes', 'valor' => $metricas['solicitudes_pendientes'], 'color' => 'amber'],
                        ['label' => 'En entrevista', 'valor' => $metricas['solicitudes_en_entrevista'], 'color' => 'amber'],
                        ['label' => 'Adopciones activas', 'valor' => $metricas['adopciones_activas'], 'color' => 'red'],
                    ];
                @endphp

                @foreach ($cards as $card)
                    @php
                        $border = match($card['color']) {
                            'emerald' => 'border-emerald-500',
                            'sky' => 'border-sky-500',
                            'amber' => 'border-amber-500',
                            'red' => 'border-red-500',
                            default => 'border-gray-500',
                        };
                    @endphp
                    <div class="bg-white rounded-lg shadow p-5 border-l-4 {{ $border }}">
                        <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $card['valor'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Contenido original (conservado) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <h3 class="text-lg font-bold mb-4">Bienvenido, {{ auth()->user()->name }}</h3>

                @if(session('success'))
                    <div class="bg-emerald-100 text-emerald-800 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif

                <p class="mb-6 text-gray-600">Desde aquí podés gestionar las mascotas que tenés en adopción.</p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('refugio.publicaciones.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 transition">
                        + Publicar Nueva Mascota
                    </a>
                    <a href="{{ route('refugio.solicitudes.index') }}" class="bg-black text-emerald-400 px-4 py-2 rounded hover:bg-gray-800 transition">
                        Ver Solicitudes
                    </a>
                </div>
            </div>

            {{-- Listado de Publicaciones (HU-12 y HU-13) --}}
            <h4 class="text-xl font-bold mb-4 text-gray-800">Mis Publicaciones</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse(auth()->user()->refugio->publicaciones as $publicacion)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 flex flex-col justify-between shadow-sm">
                        <div>
                            <img src="{{ asset('storage/' . $publicacion->foto) }}" class="w-full h-40 object-cover rounded mb-3">
                            <h5 class="font-bold text-lg text-gray-800">{{ $publicacion->especie }} ({{ $publicacion->sexo }})</h5>
                            <p class="text-sm text-gray-500 mb-2">{{ $publicacion->edad_aproximada }}</p>
                            
                            {{-- Badge de estado --}}
                            <p class="text-xs font-bold mt-2 uppercase 
                                {{ $publicacion->estado === 'activa' ? 'text-emerald-600' : 'text-gray-500' }}">
                                Estado: {{ $publicacion->estado }}
                            </p>
                        </div>
                        
                        <div class="mt-4 flex items-center justify-end gap-4 border-t pt-3">
                            {{-- Botón Editar (HU-12) --}}
                            @if($publicacion->estado !== 'baja')
                                <a href="{{ route('refugio.publicaciones.edit', $publicacion) }}" class="text-blue-600 text-sm font-semibold hover:underline">
                                    Editar
                                </a>
                            @endif

                            {{-- Botón Dar de Baja (HU-13) --}}
                            @if($publicacion->estado !== 'baja')
                                <form method="POST" action="{{ route('refugio.publicaciones.destroy', $publicacion) }}" onsubmit="return confirm('¿Seguro que querés dar de baja esta publicación? Se perderá de la vista pública.');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="motivo_baja" value="Baja solicitada por el refugio.">
                                    <button type="submit" class="text-red-600 text-sm font-semibold hover:underline">
                                        Dar de Baja
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400 italic">Publicación dada de baja</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-6 rounded-lg text-center border">
                        <p class="text-gray-500">No tenés publicaciones todavía.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>