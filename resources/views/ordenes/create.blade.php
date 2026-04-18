<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión y Asignación de Técnicos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('ordenes.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Detalle de la Avería</h3>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700">Seleccionar Cliente:</label>
                            <select name="cliente_id" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                                <option value="">-- Seleccione un cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }} ({{ $cliente->dni_cif }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700">Título del Problema:</label>
                            <input type="text" name="titulo" class="w-full border-gray-300 rounded-lg shadow-sm" placeholder="Ej: Fuga de agua en cocina" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700">Descripción detallada:</label>
                            <textarea name="descripcion" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Prioridad:</label>
                                <select name="prioridad" class="w-full border-gray-300 rounded-lg shadow-sm">
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Tipo de Servicio:</label>
                                <select name="tipo" class="w-full border-gray-300 rounded-lg shadow-sm">
                                    <option value="Fontanería">Fontanería</option>
                                    <option value="Electricidad">Electricidad</option>
                                    <option value="Calefacción">Calefacción</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Asignar Técnico</h3>

                        <div class="space-y-4">
                            @foreach($tecnicos as $tecnico)
                                <label class="flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                    <input type="radio" name="tecnico_id" value="{{ $tecnico->id }}" class="text-blue-600 focus:ring-blue-500">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900">{{ $tecnico->name }}</p>
                                        <p class="text-xs text-green-600 font-medium">Disponible</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition">
                                Crear y Asignar Orden
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>

