<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil de Adoptante') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="bg-emerald-100 text-emerald-800 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif

                {{-- Formulario con enctype para subir archivos --}}
                <form method="POST" action="{{ route('adoptante.perfil.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    {{-- DNI (Solo lectura) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700">DNI (No se puede modificar)</label>
                        <input type="text" value="{{ $adoptante->dni }}" class="w-full border-gray-300 rounded mt-1 bg-gray-100 cursor-not-allowed" readonly>
                    </div>

                    {{-- Foto actual --}}
                    <div class="mb-4 flex items-center gap-4">
                        @if($adoptante->foto)
                            <img src="{{ asset('storage/' . $adoptante->foto) }}" class="w-16 h-16 object-cover rounded-full">
                        @endif
                        <div>
                            <label class="block text-gray-700">Cambiar Foto de Perfil (Opcional)</label>
                            <input type="file" name="foto" class="w-full border-gray-300 rounded mt-1" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Alias</label>
                        <input type="text" name="alias" value="{{ $adoptante->alias }}" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Dirección</label>
                        <input type="text" name="direccion" value="{{ $adoptante->direccion }}" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" value="{{ $adoptante->telefono }}" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded hover:bg-emerald-700 transition">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
                </form> <!-- Fin del formulario de edición -->
                                <div class="mt-12 pt-6 border-t border-gray-200">
                                    <h3 class="text-lg font-bold text-red-600 mb-2">Zona de Peligro</h3>
                                    <p class="text-sm text-gray-600 mb-4">Al dar de baja tu cuenta, tus solicitudes pendientes se cancelarán. Podés volver a registrarte en el futuro con el mismo DNI y recuperar tu historial.</p>
                    
                    <form method="POST" action="{{ route('adoptante.perfil.destroy') }}" onsubmit="return confirm('¿Estás completamente seguro de que querés dar de baja tu cuenta?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                            Dar de baja mi cuenta
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>