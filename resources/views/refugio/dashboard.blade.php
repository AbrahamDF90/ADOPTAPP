<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel del Refugio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Bienvenido, {{ auth()->user()->name }}</h3>
                
                {{-- Muestra mensaje de éxito si se creó una publicación --}}
                @if(session('success'))
                    <div class="bg-emerald-100 text-emerald-800 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif

                <p class="mb-6 text-gray-600">Desde aquí podés gestionar las mascotas que tenés en adopción.</p>

                <a href="{{ route('refugio.publicaciones.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 transition">
                    + Publicar Nueva Mascota
                </a>
            </div>
        </div>
    </div>
</x-app-layout>