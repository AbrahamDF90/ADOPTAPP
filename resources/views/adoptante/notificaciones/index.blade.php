<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Notificaciones
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-emerald-100 text-emerald-800 p-4 mb-4 rounded">{{ session('success') }}</div>
            @endif

            @if($notificaciones->isEmpty())
                <p class="text-gray-500">No tienes notificaciones nuevas.</p>
            @else
                <div class="bg-white shadow rounded-lg divide-y divide-gray-200">
                    @foreach($notificaciones as $notificacion)
                        <div class="p-4 flex justify-between items-center {{ $notificacion->read_at ? 'bg-gray-50' : 'bg-emerald-50' }}">
                            <div>
                                <p class="font-bold text-gray-800">{{ $notificacion->data['mensaje'] }}</p>
                                <p class="text-sm text-gray-500">Estado de solicitud: {{ ucfirst($notificacion->data['estado']) }}</p>
                                <p class="text-xs text-gray-400">{{ $notificacion->created_at->diffForHumans() }}</p>
                            </div>
                            
                            @if(!$notificacion->read_at)
                                <form method="POST" action="{{ route('adoptante.notificaciones.leer', $notificacion->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">
                                        Marcar como leída
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $notificaciones->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>