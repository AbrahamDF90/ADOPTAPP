<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Publicar una Mascota en Adopción') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Formulario con enctype para subir archivos --}}
                <form method="POST" action="{{ route('refugio.publicaciones.store') }}" enctype="multipart/form-data">
                    @csrf 
                    
                    <div class="mb-4">
                        <label class="block text-gray-700">Foto de la mascota (JPG/PNG, máx 2MB)</label>
                        <input type="file" name="foto" class="w-full border-gray-300 rounded mt-1" accept="image/*" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Especie (Ej: Perro, Gato)</label>
                        <input type="text" name="especie" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Sexo</label>
                        <select name="sexo" class="w-full border-gray-300 rounded mt-1" required>
                            <option value="Macho">Macho</option>
                            <option value="Hembra">Hembra</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Edad Aproximada</label>
                        <input type="text" name="edad_aproximada" class="w-full border-gray-300 rounded mt-1" placeholder="Ej: 2 años, 6 meses" required>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded hover:bg-emerald-700 transition">
                            Publicar Mascota
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>