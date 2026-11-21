<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Publicación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('refugio.publicaciones.update', $publicacion) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    {{-- Foto actual --}}
                    <div class="mb-4 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $publicacion->foto) }}" class="w-24 h-24 object-cover rounded">
                        <div>
                            <label class="block text-gray-700">Cambiar Foto (Opcional)</label>
                            <input type="file" name="foto" class="w-full border-gray-300 rounded mt-1" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Especie</label>
                        <input type="text" name="especie" value="{{ $publicacion->especie }}" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Sexo</label>
                        <select name="sexo" class="w-full border-gray-300 rounded mt-1" required>
                            <option value="Macho" {{ $publicacion->sexo == 'Macho' ? 'selected' : '' }}>Macho</option>
                            <option value="Hembra" {{ $publicacion->sexo == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Edad Aproximada</label>
                        <input type="text" name="edad_aproximada" value="{{ $publicacion->edad_aproximada }}" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <a href="{{ route('refugio.dashboard') }}" class="text-gray-600 hover:underline">Cancelar</a>
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded hover:bg-emerald-700 transition">
                            Actualizar Publicación
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>