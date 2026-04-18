<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
        <p class="text-sm text-gray-500 font-medium uppercase">Total Órdenes Hoy</p>
        <p class="text-3xl font-bold text-gray-800">12</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
        <p class="text-sm text-gray-500 font-medium uppercase">Pendientes</p>
        <p class="text-3xl font-bold text-yellow-600">3</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center text-blue-600">
        <p class="text-sm text-gray-500 font-medium uppercase">En Curso</p>
        <p class="text-3xl font-bold text-blue-600">7</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
        <p class="text-sm text-gray-500 font-medium uppercase">Finalizadas</p>
        <p class="text-3xl font-bold text-green-600">2</p>
    </div>
</div>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Órdenes de Trabajo Activas</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Orden</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($ordenes as $orden)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                            #{{ strtoupper(substr($orden->uuid, 0, 8)) }} {{-- Mostramos solo el inicio del UUID --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $orden->cliente->nombre }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $orden->tecnico->name ?? 'Sin asignar' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                @if($orden->estado == 'abierta') bg-yellow-100 text-yellow-800
                @elseif($orden->estado == 'en_curso') bg-blue-100 text-blue-800
                @else bg-green-100 text-green-800 @endif">
                {{ ucfirst($orden->estado) }}
            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('ordenes.show', $orden) }}" class="text-green-600 hover:text-green-900 font-bold">Ver detalles</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                            No hay órdenes de trabajo activas actualmente.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
