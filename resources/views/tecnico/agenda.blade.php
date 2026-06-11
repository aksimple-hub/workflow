@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center gap-3">
            <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-brand-dark hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="text-2xl md:text-4xl font-medium text-brand-dark">Mi Agenda</h1>
                <p class="text-sm md:text-base text-gray-500 mt-0.5">Órdenes asignadas y en curso</p>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <!-- Week View Grid (Top) -->
            <div class="grid grid-cols-3 md:grid-cols-5 gap-3 md:gap-4 mb-6">
                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $index => $dia)
                <div class="bg-white p-4 rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] text-center border-t-4 {{ $index === 0 ? 'border-brand-green' : 'border-transparent' }} hover:border-brand-green transition-all duration-200 ease-in-out cursor-pointer">
                    <span class="text-sm font-semibold {{ $index === 0 ? 'text-brand-green' : 'text-gray-500' }} uppercase">{{ $dia }}</span>
                </div>
                @endforeach
            </div>

            <!-- Service List -->
            <div class="space-y-4">
                @forelse($ordenes as $orden)
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-4 md:p-6 flex flex-col md:flex-row md:items-center border border-gray-100 hover:border-brand-green transition-all duration-200 ease-in-out gap-4">
                    <!-- Hora Destacada -->
                    <div class="flex items-center gap-4 md:block md:w-32 md:pr-4 md:border-r md:border-gray-200 pb-3 md:pb-0 border-b md:border-b-0 border-gray-100">
                        <span class="text-2xl md:text-3xl font-bold text-brand-dark">
                            {{ $orden->created_at->format('H:i') }}
                        </span>
                        <span class="text-xs font-black text-gray-400 md:block mt-1 uppercase">
                            ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    <!-- Detalles -->
                    <div class="flex-1 md:px-6">
                        <div class="flex items-center gap-2 mb-2">
                            <!-- Badge de prioridad: Naranja Alta, Azul Media -->
                            <span class="px-3 py-1 text-xs font-semibold rounded-full transition-all duration-200 ease-in-out
                                {{ $orden->prioridad === 'alta' ? 'bg-[#FEF3C7] text-[#D97706]' : 'bg-[#DBEAFE] text-[#1D4ED8]' }}">
                                Prioridad {{ ucfirst($orden->prioridad) }}
                            </span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 transition-all duration-200 ease-in-out">
                                {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                            </span>
                        </div>
                        <h3 class="text-xl font-medium text-brand-dark mb-1">{{ $orden->titulo }}</h3>
                        <p class="text-gray-600 text-sm line-clamp-2">{{ $orden->descripcion }}</p>
                    </div>

                    <!-- Acciones -->
                    <div class="flex flex-col sm:flex-row md:flex-col gap-2 w-full md:w-48">
                        @if($orden->estado === 'asignada' || $orden->estado === 'pendiente')
                            <a href="{{ route('ordenes.show', $orden) }}" class="w-full bg-gray-100 hover:bg-gray-200 text-brand-dark px-6 py-3 rounded-xl transition-all duration-200 ease-in-out text-center font-medium text-sm">
                                Ver Detalle
                            </a>
                            <form action="{{ route('ordenes.update-estado', $orden) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="estado" value="en_camino">
                                <button type="submit" class="w-full bg-brand-green hover:bg-brand-green-dark text-white px-6 py-3 rounded-xl shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-all duration-200 ease-in-out font-medium text-sm">
                                    En Camino
                                </button>
                            </form>
                        @elseif($orden->estado === 'en_camino')
                            <a href="{{ route('ordenes.show', $orden) }}" class="w-full bg-gray-100 hover:bg-gray-200 text-brand-dark px-6 py-3 rounded-xl transition-all duration-200 ease-in-out text-center font-medium text-sm">
                                Ver Detalle
                            </a>
                            <form action="{{ route('ordenes.update-estado', $orden) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="estado" value="en_proceso">
                                <button type="submit" class="w-full bg-brand-green hover:bg-brand-green-dark text-white px-6 py-3 rounded-xl shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-all duration-200 ease-in-out font-medium text-sm">
                                    Iniciar Trabajo
                                </button>
                            </form>
                        @elseif($orden->estado === 'en_proceso')
                            <a href="{{ route('ordenes.show', $orden) }}" class="w-full bg-gray-100 hover:bg-gray-200 text-brand-dark px-6 py-3 rounded-xl transition-all duration-200 ease-in-out text-center font-medium text-sm">
                                Ver Detalle
                            </a>
                            <a href="{{ route('ordenes.cierre', $orden) }}" class="w-full bg-brand-dark hover:bg-brand-dark-mid text-white px-6 py-3 rounded-xl transition-all duration-200 ease-in-out text-center font-medium text-sm shadow-[0px_2px_8px_rgba(30,58,95,0.25)]">
                                Finalizar
                            </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12 bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 transition-all duration-200 ease-in-out">
                    <p class="text-gray-500">No tienes órdenes asignadas en este momento.</p>
                </div>
                @endforelse
            </div>
        </main>
    </div>
</div>
@endsection
