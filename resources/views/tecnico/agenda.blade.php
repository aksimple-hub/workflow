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
                <!-- Example days -->
                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia)
                <div class="bg-white p-4 rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] text-center border-t-4 border-transparent hover:border-[#10B981] transition-colors cursor-pointer">
                    <span class="text-sm font-semibold text-gray-500 uppercase">{{ $dia }}</span>
                </div>
                @endforeach
            </div>

            <!-- Service List -->
            <div class="space-y-4">
                @forelse($ordenes as $orden)
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 flex items-center border border-gray-100 hover:border-[#10B981] transition-colors">
                    <!-- Hora Destacada -->
                    <div class="w-32 pr-4 border-r border-gray-200">
                        <span class="text-3xl font-bold text-[#1E3A5F]">
                            {{ $orden->created_at->format('H:i') }}
                        </span>
                        <span class="text-sm text-gray-500 block">
                            {{ $orden->created_at->format('d M') }}
                        </span>
                    </div>

                    <!-- Detalles -->
                    <div class="flex-1 px-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $orden->prioridad === 'alta' ? 'bg-[#FEF3C7] text-[#D97706]' : 'bg-[#DBEAFE] text-[#1D4ED8]' }}">
                                Prioridad {{ ucfirst($orden->prioridad) }}
                            </span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                            </span>
                        </div>
                        <h3 class="text-xl font-medium text-[#1E3A5F] mb-1">{{ $orden->titulo }}</h3>
                        <p class="text-gray-600 text-sm line-clamp-2">{{ $orden->descripcion }}</p>
                    </div>

                    <!-- Acciones -->
                    <div class="flex flex-col gap-2">
                        @if($orden->estado === 'pendiente')
                            <form action="{{ route('ordenes.update-estado', $orden) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="estado" value="en_camino">
                                <button type="submit" class="w-full bg-[#10B981] hover:bg-[#059669] text-white px-6 py-3 rounded-xl shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-all font-medium text-sm">
                                    En Camino
                                </button>
                            </form>
                        @elseif($orden->estado === 'en_camino')
                            <form action="{{ route('ordenes.update-estado', $orden) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="estado" value="en_proceso">
                                <button type="submit" class="w-full bg-[#10B981] hover:bg-[#059669] text-white px-6 py-3 rounded-xl shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-all font-medium text-sm">
                                    Iniciar Trabajo
                                </button>
                            </form>
                        @elseif($orden->estado === 'en_proceso')
                            <a href="{{ route('ordenes.cierre', $orden) }}" class="w-full bg-[#1E3A5F] hover:bg-[#2C5282] text-white px-6 py-3 rounded-xl transition-all text-center font-medium text-sm">
                                Finalizar
                            </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12 bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)]">
                    <p class="text-gray-500">No tienes órdenes asignadas pendientes.</p>
                </div>
                @endforelse
            </div>
        </main>
    </div>
</div>
@endsection
