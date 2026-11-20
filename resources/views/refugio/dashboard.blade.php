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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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
        </div>
    </div>
</x-app-layout>