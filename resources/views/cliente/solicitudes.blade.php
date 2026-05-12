@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-medium text-[#1E3A5F]">Mis Solicitudes</h1>
                <p class="text-base text-gray-500 mt-1">Historial y estado de tus requerimientos</p>
            </div>
            
            <a href="{{ route('solicitud.nueva') }}" class="bg-[#10B981] hover:bg-[#059669] text-white px-6 py-3 rounded-xl shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-all duration-200 ease-in-out font-medium text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nueva Solicitud
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Solicitudes</p>
                    <p class="text-3xl font-bold text-[#1E3A5F]">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">En Proceso</p>
                    <p class="text-3xl font-bold text-[#1D4ED8]">{{ $stats['en_proceso'] }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Finalizadas</p>
                    <p class="text-3xl font-bold text-[#10B981]">{{ $stats['finalizadas'] }}</p>
                </div>
            </div>

            <div class="max-w-7xl mx-auto space-y-4">
                
                @forelse($ordenes as $orden)
                @php
                    $badge = match($orden->estado) {
                        'pendiente'  => ['Pendiente',        'bg-gray-100 text-gray-600 border-gray-200'],
                        'asignada'   => ['Asignada',         'bg-[#FEF3C7] text-[#D97706] border-[#FDE68A]'],
                        'en_camino'  => ['Técnico en camino','bg-[#DBEAFE] text-[#1D4ED8] border-[#BFDBFE]'],
                        'en_proceso' => ['En curso',         'bg-blue-500 text-white border-blue-500'],
                        'finalizada' => ['Finalizada',       'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0]'],
                        'cancelada'  => ['Cancelada',        'bg-red-100 text-red-800 border-red-200'],
                        default      => [ucfirst(str_replace('_', ' ', $orden->estado)), 'bg-gray-100 text-gray-600 border-gray-200'],
                    };
                    $tecnicoAsignado = $orden->tecnico && !in_array($orden->estado, ['pendiente', 'cancelada', 'finalizada']);
                @endphp

                <!-- Tarjeta de Solicitud -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 hover:border-[#10B981] transition-all duration-200 ease-in-out overflow-hidden">

                    <!-- Fila principal -->
                    <div class="p-6 flex items-center justify-between gap-6">
                        <div class="flex flex-col gap-1 flex-1 min-w-0">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-black text-gray-400 uppercase tracking-wider whitespace-nowrap">ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="text-xl font-medium text-[#1E3A5F] truncate">{{ $orden->titulo }}</h3>
                            </div>
                            <p class="text-sm text-gray-600 truncate">{{ $orden->descripcion }}</p>
                        </div>

                        <div class="flex items-center gap-6 flex-shrink-0">
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Fecha de Creación</p>
                                <p class="text-sm font-medium text-[#1E3A5F]">{{ $orden->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $badge[1] }} whitespace-nowrap">
                                {{ $badge[0] }}
                            </span>

                            @if(in_array($orden->estado, ['pendiente', 'asignada']))
                            <form action="{{ route('ordenes.destroy', $orden) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta solicitud?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors text-sm font-medium">
                                    Cancelar
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    <!-- Panel del técnico asignado -->
                    @if($tecnicoAsignado)
                    <div class="border-t border-gray-100 bg-[#F5F7FA] px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#1E3A5F] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ substr($orden->tecnico->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Técnico asignado</p>
                                <p class="text-sm font-semibold text-[#1E3A5F]">
                                    {{ $orden->tecnico->name }}
                                    @if($orden->tecnico->perfil?->apellidos)
                                        {{ $orden->tecnico->perfil->apellidos }}
                                    @endif
                                </p>
                                @if($orden->tecnico->perfil?->telefono && $orden->tecnico->perfil->telefono !== 'N/A')
                                <p class="text-xs text-gray-500 mt-0.5">{{ $orden->tecnico->perfil->telefono }}</p>
                                @endif
                            </div>
                        </div>

                        @if($orden->tecnico->perfil?->telefono && $orden->tecnico->perfil->telefono !== 'N/A')
                        <a href="tel:{{ $orden->tecnico->perfil->telefono }}"
                           class="flex items-center gap-2 bg-[#10B981] hover:bg-[#059669] text-white px-4 py-2 rounded-xl font-medium text-sm transition-colors shadow-[0px_2px_8px_rgba(16,185,129,0.25)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Contactar
                        </a>
                        @else
                        <span class="text-xs text-gray-400 italic">Teléfono no disponible</span>
                        @endif
                    </div>
                    @endif

                </div>
                @empty
                <!-- Empty State -->
                <div class="bg-white rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <h3 class="text-xl font-medium text-[#1E3A5F] mb-2">Aún no tienes solicitudes</h3>
                    <p class="text-gray-500 mb-6">Crea tu primera solicitud de servicio técnico haciendo clic en el botón.</p>
                    <a href="{{ route('solicitud.nueva') }}" class="inline-flex bg-[#1E3A5F] hover:bg-[#2C5282] text-white px-6 py-3 rounded-lg transition-colors font-medium text-sm items-center gap-2">
                        Comenzar
                    </a>
                </div>
                @endforelse

            </div>
        </main>
    </div>
</div>
@endsection
