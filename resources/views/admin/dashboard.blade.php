@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-medium text-[#1E3A5F]">Dashboard Admin</h1>
                <p class="text-base text-gray-500 mt-1">Resumen general de órdenes de trabajo</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-4 gap-6 mb-8">
                <!-- Total -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Órdenes</p>
                    <p class="text-3xl font-bold text-[#1E3A5F]">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <!-- Pendientes -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Pendientes</p>
                    <p class="text-3xl font-bold text-[#1D4ED8]">{{ $stats['pendientes'] ?? 0 }}</p>
                </div>
                <!-- En Curso -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">En Curso</p>
                    <p class="text-3xl font-bold text-[#D97706]">{{ $stats['en_curso'] ?? 0 }}</p>
                </div>
                <!-- Finalizadas -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Finalizadas</p>
                    <p class="text-3xl font-bold text-[#10B981]">{{ $stats['finalizadas'] ?? 0 }}</p>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-medium text-[#1E3A5F]">Órdenes de Trabajo Activas</h2>
                    <a href="{{ route('ordenes.create') }}" class="bg-[#10B981] hover:bg-[#059669] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Nueva Orden</a>
                </div>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F5F7FA] text-gray-500 text-sm border-b border-gray-200">
                                <th class="px-6 py-3 font-medium whitespace-nowrap">ID Orden</th>
                                <th class="px-6 py-3 font-medium">Cliente</th>
                                <th class="px-6 py-3 font-medium">Dirección del Servicio</th>
                                <th class="px-6 py-3 font-medium">Técnico Asignado</th>
                                <th class="px-6 py-3 font-medium">Fecha</th>
                                <th class="px-6 py-3 font-medium">Estado</th>
                                <th class="px-6 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            @forelse($ordenes as $orden)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-[#1E3A5F]">#OT-{{ $orden->id }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $orden->cliente->nombre ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-500 text-sm max-w-xs truncate" title="{{ $orden->cliente->direccion ?? 'N/A' }}">{{ $orden->cliente->direccion ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $orden->tecnico->name ?? 'No asignado' }}</td>
                                <td class="px-6 py-4 text-gray-500 text-sm whitespace-nowrap">{{ $orden->created_at->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $bg = 'bg-gray-100 text-gray-600';
                                        if(in_array($orden->estado, ['en_camino', 'en_curso', 'en_proceso'])) $bg = 'bg-blue-500 text-white';
                                        if($orden->estado == 'finalizada') $bg = 'bg-[#10B981] text-white';
                                        if(in_array($orden->estado, ['pendiente', 'asignada'])) $bg = 'bg-gray-400 text-white';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $bg }}">
                                        {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.orden.show', $orden->id) }}" class="text-[#10B981] hover:text-[#059669] font-medium text-sm">Ver detalles</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">No hay órdenes registradas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
