<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Solicitudes de Adopción') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Mensaje de éxito si se envía una solicitud --}}
                @if(session('success'))
                    <div class="bg-emerald-100 text-emerald-800 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif

                {{-- Listado de tarjetas de solicitudes --}}
                <div class="grid gap-4">
                    @forelse($solicitudes as $solicitud)
                        <div class="border border-gray-200 rounded-lg p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                            
                            <div class="flex items-center gap-4 w-full">
                                {{-- Imagen de la mascota --}}
                                <img src="{{ asset('storage/' . $solicitud->publicacion->foto) }}" class="w-16 h-16 object-cover rounded-md" alt="Mascota">
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ $solicitud->publicacion->especie }}</h4>
                                    {{-- Formateo de fecha con Carbon (incluido en Laravel) --}}
                                    <p class="text-sm text-gray-500">Solicitado el: {{ $solicitud->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            {{-- Badge de estado (Lógica condicional) --}}
                            <div class="w-full sm:w-auto text-right">
                                @if($solicitud->estado === 'pendiente')
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-bold">Pendiente</span>
                                @elseif($solicitud->estado === 'aceptada')
                                    <span class="inline-block bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-bold">¡Aceptada!</span>
                                @else
                                    <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-bold">{{ ucfirst($solicitud->estado) }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        {{-- Estado vacío --}}
                        <p class="text-gray-500 text-center py-8">Todavía no hiciste ninguna solicitud.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>