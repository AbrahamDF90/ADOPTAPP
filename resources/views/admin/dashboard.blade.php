<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Administración') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HU-28: Métricas globales --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                @php
                    $cards = [
                        ['label' => 'Adoptantes', 'valor' => $metricas['total_adoptantes'], 'color' => 'emerald'],
                        ['label' => 'Refugios', 'valor' => $metricas['total_refugios'], 'color' => 'sky'],
                        ['label' => 'Refugios activos', 'valor' => $metricas['refugios_activos'], 'color' => 'amber'],
                        ['label' => 'Publicaciones', 'valor' => $metricas['total_publicaciones'], 'color' => 'emerald'],
                        ['label' => 'Publicaciones activas', 'valor' => $metricas['publicaciones_activas'], 'color' => 'emerald'],
                        ['label' => 'Publicaciones adoptadas', 'valor' => $metricas['publicaciones_adoptadas'], 'color' => 'red'],
                        ['label' => 'Solicitudes pendientes', 'valor' => $metricas['solicitudes_pendientes'], 'color' => 'amber'],
                        ['label' => 'Adopciones activas', 'valor' => $metricas['adopciones_activas'], 'color' => 'sky'],
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

            {{-- HU-28: Distribución por estado --}}
            <div class="bg-white rounded-lg shadow p-5 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Publicaciones por estado</h3>
                @if ($porEstado->isEmpty())
                    <p class="text-gray-500 text-sm">No hay publicaciones registradas.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach ($porEstado as $estado => $total)
                            <li class="flex justify-between py-2">
                                <span class="capitalize text-gray-700">{{ $estado }}</span>
                                <span class="font-semibold text-emerald-600">{{ $total }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Contenido original (conservado) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Bienvenido Administrador</h3>

                @if(session('success'))
                    <div class="bg-emerald-100 text-emerald-800 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.refugios.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 transition">
                        + Crear Nuevo Refugio
                    </a>
                    <a href="{{ route('admin.adoptantes.index') }}" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 transition">
                        Ver Adoptantes
                    </a>
                    <a href="{{ route('admin.refugios.index') }}" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 transition">
                        Ver Refugios
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>