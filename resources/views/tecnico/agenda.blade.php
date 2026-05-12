@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6">
            <h1 class="text-4xl font-medium text-[#1E3A5F]">Mi Agenda</h1>
            <p class="text-base text-gray-500 mt-1">Órdenes asignadas y en curso</p>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <!-- Week View Grid (Top) -->
            <div class="grid grid-cols-5 gap-4 mb-6">
                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $index => $dia)
                <div class="bg-white p-4 rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] text-center border-t-4 {{ $index === 0 ? 'border-[#10B981]' : 'border-transparent' }} hover:border-[#10B981] transition-all duration-200 ease-in-out cursor-pointer">
                    <span class="text-sm font-semibold {{ $index === 0 ? 'text-[#10B981]' : 'text-gray-500' }} uppercase">{{ $dia }}</span>
                </div>
                @endforeach
            </div>

            <!-- Service List -->
            <div class="space-y-4">
                @forelse($ordenes as $orden)
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 flex items-center border border-gray-100 hover:border-[#10B981] transition-all duration-200 ease-in-out">
                    <!-- Hora Destacada -->
                    <div class="w-32 pr-4 border-r border-gray-200">
                        <span class="text-3xl font-bold text-[#1E3A5F]">
                            {{ $orden->created_at->format('H:i') }}
                        </span>
                        <span class="text-xs font-black text-gray-400 block mt-1 uppercase">
                            ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    <!-- Detalles -->
                    <div class="flex-1 px-6">
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
                        <h3 class="text-xl font-medium text-[#1E3A5F] mb-1">{{ $orden->titulo }}</h3>
                        <p class="text-gray-600 text-sm line-clamp-2">{{ $orden->descripcion }}</p>
                    </div>

                    <!-- Acciones -->
                    <div class="flex flex-col gap-2 w-48">
                        @if($orden->estado === 'asignada' || $orden->estado === 'pendiente')
                            <a href="{{ route('ordenes.show', $orden) }}" class="w-full bg-gray-100 hover:bg-gray-200 text-[#1E3A5F] px-6 py-3 rounded-xl transition-all duration-200 ease-in-out text-center font-medium text-sm">
                                Ver Detalle
                            </a>
                            <form action="{{ route('ordenes.update-estado', $orden) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="estado" value="en_camino">
                                <button type="submit" class="w-full bg-[#10B981] hover:bg-[#059669] text-white px-6 py-3 rounded-xl shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-all duration-200 ease-in-out font-medium text-sm">
                                    En Camino
                                </button>
                            </form>
                        @elseif($orden->estado === 'en_camino')
                            <a href="{{ route('ordenes.show', $orden) }}" class="w-full bg-gray-100 hover:bg-gray-200 text-[#1E3A5F] px-6 py-3 rounded-xl transition-all duration-200 ease-in-out text-center font-medium text-sm">
                                Ver Detalle
                            </a>
                            <form action="{{ route('ordenes.update-estado', $orden) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="estado" value="en_proceso">
                                <button type="submit" class="w-full bg-[#10B981] hover:bg-[#059669] text-white px-6 py-3 rounded-xl shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-all duration-200 ease-in-out font-medium text-sm">
                                    Iniciar Trabajo
                                </button>
                            </form>
                        @elseif($orden->estado === 'en_proceso')
                            <a href="{{ route('ordenes.show', $orden) }}" class="w-full bg-gray-100 hover:bg-gray-200 text-[#1E3A5F] px-6 py-3 rounded-xl transition-all duration-200 ease-in-out text-center font-medium text-sm">
                                Ver Detalle
                            </a>
                            <a href="{{ route('ordenes.cierre', $orden) }}" class="w-full bg-[#1E3A5F] hover:bg-[#2C5282] text-white px-6 py-3 rounded-xl transition-all duration-200 ease-in-out text-center font-medium text-sm shadow-[0px_2px_8px_rgba(30,58,95,0.25)]">
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
