<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ADOPTAPP - Adopción Responsable</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        
        <!-- Incluimos la barra de navegación (la negra con tu logo) -->
        @include('layouts.navigation')

        <!-- Encabezado Principal -->
        <header class="bg-emerald-600 py-16 text-center text-white">
            <h1 class="text-4xl font-extrabold mb-4 tracking-tight">Encontrá a tu nuevo mejor amigo</h1>
            <p class="text-lg text-emerald-100">Adopción responsable de mascotas en tu zona.</p>
        </header>

        <!-- Galería de Mascotas -->
        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            {{-- Grilla responsiva de Tailwind --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                
                @forelse($publicaciones as $publicacion)
                    <!-- Tarjeta de Mascota -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        <img src="{{ asset('storage/' . $publicacion->foto) }}" alt="Mascota" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-bold text-gray-800">{{ $publicacion->especie }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ $publicacion->sexo }} • {{ $publicacion->edad_aproximada }}</p>
                            <p class="text-xs text-emerald-600 font-semibold mb-4">Refugio: {{ $publicacion->refugio->nombre }}</p>
                            
                            <a href="#" class="block text-center w-full bg-gray-900 text-white py-2 rounded-lg hover:bg-gray-800 transition">
                                Ver Detalle
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-12">
                        <p class="text-gray-500 text-lg">No hay mascotas en adopción por el momento. ¡Volvé pronto!</p>
                    </div>
                @endforelse

            </div>

            <!-- Paginación -->
            <div class="mt-8">
                {{ $publicaciones->links() }}
            </div>
        </main>
    </div>
</body>
</html>