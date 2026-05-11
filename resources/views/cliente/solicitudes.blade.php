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
            <div class="max-w-7xl mx-auto space-y-4">
                
                @forelse($ordenes as $orden)
                <!-- Tarjeta de Solicitud -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border border-gray-100 flex items-center justify-between hover:border-[#10B981] transition-all duration-200 ease-in-out">
                    <div class="flex flex-col gap-2 w-2/3">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-black text-gray-400 uppercase tracking-wider">ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <h3 class="text-xl font-medium text-[#1E3A5F]">{{ $orden->titulo }}</h3>
                        </div>
                        <p class="text-sm text-gray-600 truncate">{{ $orden->descripcion }}</p>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Fecha de Creación</p>
                            <p class="text-sm font-medium text-[#1E3A5F]">{{ $orden->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div class="w-32 text-right">
                            @if($orden->estado === 'finalizada')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#D1FAE5] text-[#065F46] border border-[#A7F3D0]">Finalizada</span>
                            @elseif($orden->estado === 'cancelada')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">Cancelada</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#DBEAFE] text-[#1D4ED8] border border-[#BFDBFE]">En Proceso</span>
                            @endif
                        </div>

                        <!-- Botón de eliminar (solo para órdenes no finalizadas ni canceladas) -->
                        @if($orden->estado !== 'finalizada' && $orden->estado !== 'cancelada')
                        <form action="{{ route('ordenes.destroy', $orden) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta solicitud?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors text-sm font-medium">
                                Cancelar
                            </button>
                        </form>
                        @endif
                    </div>
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
