<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dar de alta un Refugio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Formulario de envío POST --}}
                <form method="POST" action="{{ route('admin.refugios.store') }}">
                    @csrf <!-- Token de seguridad obligatorio -->
                    
                    <div class="mb-4">
                        <label class="block text-gray-700">Nombre del Refugio</label>
                        <input type="text" name="nombre" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Email (Credencial de acceso)</label>
                        <input type="email" name="email" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Contraseña temporal</label>
                        <input type="password" name="password" class="w-full border-gray-300 rounded mt-1" required minlength="8">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Ubicación (Ciudad)</label>
                        <input type="text" name="ubicacion" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Dirección exacta</label>
                        <input type="text" name="direccion" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Teléfono de contacto</label>
                        <input type="text" name="telefono" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded hover:bg-emerald-700 transition">
                            Guardar Refugio
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>