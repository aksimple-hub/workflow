<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Intervención') }} #{{ strtoupper(substr($orden->uuid, 0, 8)) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <span class="px-3 py-1 rounded-full text-sm font-bold @if($orden->prioridad == 'alta') bg-red-100 text-red-800 @else bg-blue-100 text-blue-800 @endif">
                        Prioridad {{ ucfirst($orden->prioridad) }}
                    </span>
                    <span class="text-gray-500 font-medium italic">{{ ucfirst($orden->estado) }}</span>
                </div>

                <div class="mb-8 border-b pb-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Cliente y Ubicación</h3>
                    <p class="text-gray-900 font-semibold">{{ $orden->cliente->nombre }}</p>
                    <p class="text-gray-600">{{ $orden->cliente->direccion }}</p>
                    <p class="text-blue-600 font-medium">{{ $orden->cliente->telefono }}</p>
                </div>

                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Descripción del Problema</h3>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 italic">
                        "{{ $orden->descripcion }}"
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @if($orden->estado == 'asignada')
                        <form action="{{ route('ordenes.update-estado', $orden) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="estado" value="en_curso">
                            <button class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg uppercase tracking-wider">
                                🚀 Iniciar Intervención
                            </button>
                        </form>
                    @elseif($orden->estado == 'en_curso')
                        <a href="{{ route('ordenes.finalizar', $orden) }}" class="w-full bg-green-500 text-center text-white font-bold py-4 rounded-xl shadow-lg uppercase tracking-wider">
                            ✅ Finalizar y Firmar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
