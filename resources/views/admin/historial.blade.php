@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-medium text-[#1E3A5F]">Historial de Órdenes</h1>
                <p class="text-base text-gray-500 mt-1">Registro completo de todos los trabajos realizados</p>
            </div>
            
            <div class="flex gap-3">
                <div class="relative">
                    <input type="text" placeholder="Buscar orden..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#10B981] w-64">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <button class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    Filtrar
                </button>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F5F7FA] text-gray-500 text-sm border-b border-gray-200">
                            <th class="px-6 py-4 font-medium">ID Orden</th>
                            <th class="px-6 py-4 font-medium">Fecha</th>
                            <th class="px-6 py-4 font-medium">Cliente</th>
                            <th class="px-6 py-4 font-medium">Técnico</th>
                            <th class="px-6 py-4 font-medium">Prioridad</th>
                            <th class="px-6 py-4 font-medium">Estado Final</th>
                            <th class="px-6 py-4 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($ordenes as $orden)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-500">ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $orden->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-[#1E3A5F] font-medium">{{ $orden->cliente->nombre ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $orden->tecnico->name ?? 'No asignado' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $orden->prioridad === 'alta' ? 'bg-[#FEF3C7] text-[#D97706]' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($orden->prioridad) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($orden->estado === 'finalizada')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#D1FAE5] text-[#065F46]">Finalizada</span>
                                @elseif($orden->estado === 'cancelada')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelada</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#DBEAFE] text-[#1D4ED8]">{{ ucfirst(str_replace('_', ' ', $orden->estado)) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orden.show', $orden->id) }}" class="text-[#1D4ED8] hover:underline font-medium">Ver detalles</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">No hay órdenes en el historial.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
@endsection
