@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-[#1E3A5F] transition-all duration-200 ease-in-out">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-4xl font-medium text-[#1E3A5F]">Detalle de Intervención</h1>
                </div>
                <p class="text-base text-gray-500 mt-1 ml-9">ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }} - {{ $orden->titulo }}</p>
            </div>
            
            @if($orden->estado === 'en_proceso')
                <a href="{{ route('ordenes.cierre', $orden) }}" class="bg-[#10B981] hover:bg-[#059669] text-white px-6 py-3 rounded-xl shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-all duration-200 ease-in-out font-medium text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Completar Trabajo
                </a>
            @endif
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="grid grid-cols-2 gap-6 h-full">
                
                <!-- Columna Izquierda: Datos del Cliente y Avería -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100">
                        <h2 class="text-xl font-medium text-[#1E3A5F] mb-4 border-b pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Datos del Cliente
                        </h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Nombre</p>
                                <p class="text-base text-[#1E3A5F] font-medium">{{ $orden->cliente->nombre ?? 'Cliente No Asignado' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Dirección</p>
                                <p class="text-base text-gray-700">{{ $orden->cliente->direccion ?? 'Dirección no especificada' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Contacto</p>
                                <p class="text-base text-gray-700">{{ $orden->cliente->telefono ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 flex-1">
                        <h2 class="text-xl font-medium text-[#1E3A5F] mb-4 border-b pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Detalles de la Avería
                        </h2>
                        <div class="mb-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#DBEAFE] text-[#1D4ED8] inline-block mb-3">
                                {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                            </span>
                        </div>
                        <div class="bg-[#F5F7FA] p-4 rounded-xl text-sm text-gray-700 leading-relaxed h-48 overflow-y-auto">
                            {{ $orden->descripcion }}
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Mapa y Línea de Tiempo -->
                <div class="space-y-6">
                    <!-- Mapa Simulacion -->
                    <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden h-64 relative group cursor-pointer">
                        <div class="absolute inset-0 bg-gray-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 group-hover:scale-110 transition-transform duration-200 ease-in-out" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                        </div>
                        <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur px-4 py-2 rounded-lg shadow-sm">
                            <p class="text-sm font-medium text-[#1E3A5F]">Ver ruta en Maps</p>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white p-6 rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100">
                        <h2 class="text-xl font-medium text-[#1E3A5F] mb-6 border-b pb-2">Progreso</h2>
                        
                        <div class="relative pl-6 space-y-8 border-l-2 border-gray-200 ml-3">
                            
                            <!-- Asignada -->
                            <div class="relative">
                                <div class="absolute -left-[31px] bg-[#10B981] w-4 h-4 rounded-full border-4 border-white shadow-sm transition-all duration-200 ease-in-out"></div>
                                <h3 class="text-base font-medium text-[#1E3A5F]">Orden Asignada</h3>
                                <p class="text-sm text-gray-500">{{ $orden->fecha_asignacion ? \Carbon\Carbon::parse($orden->fecha_asignacion)->format('H:i - d/m/Y') : 'Pendiente' }}</p>
                            </div>

                            <!-- En Camino -->
                            <div class="relative">
                                <div class="absolute -left-[31px] {{ in_array($orden->estado, ['en_camino', 'en_proceso', 'finalizada']) ? 'bg-[#10B981]' : 'bg-gray-300' }} w-4 h-4 rounded-full border-4 border-white shadow-sm transition-all duration-200 ease-in-out"></div>
                                <h3 class="text-base font-medium {{ in_array($orden->estado, ['en_camino', 'en_proceso', 'finalizada']) ? 'text-[#1E3A5F]' : 'text-gray-400' }}">Técnico en Camino</h3>
                            </div>

                            <!-- En Proceso -->
                            <div class="relative">
                                <div class="absolute -left-[31px] {{ in_array($orden->estado, ['en_proceso', 'finalizada']) ? 'bg-[#10B981]' : 'bg-gray-300' }} w-4 h-4 rounded-full border-4 border-white shadow-sm transition-all duration-200 ease-in-out"></div>
                                <h3 class="text-base font-medium {{ in_array($orden->estado, ['en_proceso', 'finalizada']) ? 'text-[#1E3A5F]' : 'text-gray-400' }}">Trabajo Iniciado</h3>
                            </div>

                            <!-- Finalizada -->
                            <div class="relative">
                                <div class="absolute -left-[31px] {{ $orden->estado === 'finalizada' ? 'bg-[#10B981]' : 'bg-gray-300' }} w-4 h-4 rounded-full border-4 border-white shadow-sm transition-all duration-200 ease-in-out"></div>
                                <h3 class="text-base font-medium {{ $orden->estado === 'finalizada' ? 'text-[#1E3A5F]' : 'text-gray-400' }}">Finalizada</h3>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
