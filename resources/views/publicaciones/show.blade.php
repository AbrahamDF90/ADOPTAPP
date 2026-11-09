<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $publicacion->especie }} en adopción - ADOPTAPP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <main class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden md:flex">
                
                {{-- Foto de la mascota (ocupa la mitad en escritorio) --}}
                <div class="md:w-1/2">
                    <img src="{{ asset('storage/' . $publicacion->foto) }}" alt="Mascota" class="w-full h-96 md:h-full object-cover">
                </div>

                {{-- Información de la mascota --}}
                <div class="p-8 md:w-1/2 flex flex-col justify-between">
                    <div>
                        <span class="inline-block bg-emerald-100 text-emerald-800 text-xs px-3 py-1 rounded-full font-bold mb-4 uppercase tracking-wide">
                            En Adopción
                        </span>
                        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $publicacion->especie }}</h1>
                        <p class="text-lg text-gray-600 mb-6">{{ $publicacion->sexo }} • {{ $publicacion->edad_aproximada }}</p>
                        
                        <div class="border-t border-gray-200 pt-4 mb-6">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Acerca del Refugio</h3>
                            <p class="text-gray-800 font-bold text-lg">{{ $publicacion->refugio->nombre }}</p>
                            <p class="text-gray-600 text-sm">{{ $publicacion->refugio->ubicacion }}</p>
                        </div>
                    </div>

                    <div>
                        @auth
                            {{-- Si el usuario está logueado y es adoptante, ve el botón de solicitar --}}
                            @if(auth()->user()->role === 'adoptante')
                                <button class="w-full bg-emerald-600 text-white py-3 rounded-lg font-bold hover:bg-emerald-700 transition">
                                    Solicitar Adopción (HU-16)
                                </button>
                            @else
                                <p class="text-center text-sm text-gray-500">Solo los adoptantes pueden solicitar.</p>
                            @endif
                        @else
                            {{-- Si es visitante, lo mandamos a loguearse --}}
                            <a href="{{ route('login') }}" class="block text-center w-full bg-gray-900 text-white py-3 rounded-lg font-bold hover:bg-gray-800 transition">
                                Iniciá sesión para solicitar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>