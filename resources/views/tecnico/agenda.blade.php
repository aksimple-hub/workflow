@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between gap-3 flex-shrink-0 flex-wrap">
            <div class="flex items-center gap-3 min-w-0">
                <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-brand-dark hover:bg-gray-100 transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-xl md:text-2xl font-semibold text-brand-dark">Mi Agenda Diaria</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ ucfirst($today->locale('es')->isoFormat('dddd, D [de] MMMM YYYY')) }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button onclick="toggleWeekView()"
                    class="flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="hidden sm:inline">Ver Semana</span>
                </button>
                @php
                    $firstWithAddr = $ordenes->first(fn($o) => $o->cliente && $o->cliente->direccion && $o->cliente->direccion !== 'N/A');
                @endphp
                <a href="https://www.google.com/maps/dir/?api=1{{ $firstWithAddr ? '&destination=' . urlencode($firstWithAddr->cliente->direccion) : '' }}"
                   target="_blank"
                   class="flex items-center gap-2 px-4 py-2 bg-brand-green hover:bg-brand-green-dark text-white rounded-xl text-sm font-semibold transition-colors shadow-[0px_2px_8px_rgba(16,185,129,0.3)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Iniciar Ruta
                </a>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-6">

            @php
                $serviciosHoy = $ordenes->count();
            @endphp

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-3 md:gap-4 mb-5">
                <div class="bg-white rounded-xl p-4 md:p-5 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100">
                    <p class="text-xs md:text-sm text-gray-500 mb-1">Servicios Hoy</p>
                    <p class="text-3xl font-bold text-brand-dark">{{ $serviciosHoy }}</p>
                </div>
                <div class="bg-white rounded-xl p-4 md:p-5 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100">
                    <p class="text-xs md:text-sm text-gray-500 mb-1">Completados</p>
                    <p class="text-3xl font-bold text-brand-green">{{ $completadosHoy }}</p>
                </div>
            </div>

            {{-- Vista Semanal --}}
            <div id="week-view" class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-4 md:p-5 mb-5">
                <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Vista Semanal</h2>
                <div class="grid grid-cols-5 gap-2">
                    @foreach($weekDays as $day)
                    @php
                        $isToday   = $day->isToday();
                        $dayCount  = $ordenes->filter(fn($o) =>
                            $o->fecha_asignacion &&
                            \Carbon\Carbon::parse($o->fecha_asignacion)->format('Y-m-d') === $day->format('Y-m-d')
                        )->count();
                        $dayNames  = ['Mon'=>'Lun','Tue'=>'Mar','Wed'=>'Mié','Thu'=>'Jue','Fri'=>'Vie'];
                        $dayLabel  = ($dayNames[$day->format('D')] ?? $day->format('D')) . ' ' . $day->day;
                    @endphp
                    <button onclick="filterByDay('{{ $day->format('Y-m-d') }}', this)"
                        data-day="{{ $day->format('Y-m-d') }}"
                        class="day-btn flex flex-col items-center py-3 px-1 rounded-xl text-center transition-all
                            {{ $isToday ? 'bg-brand-dark text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                        <span class="text-[11px] font-semibold {{ $isToday ? 'text-white/70' : 'text-gray-400' }}">{{ $dayLabel }}</span>
                        <span class="text-2xl font-bold mt-0.5 leading-none">{{ $dayCount }}</span>
                        <span class="text-[10px] mt-1 {{ $isToday ? 'text-white/70' : 'text-gray-400' }}">servicios</span>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Título lista --}}
            <div class="flex items-center justify-between mb-3">
                <h2 id="list-title" class="text-base font-semibold text-brand-dark">Servicios de Hoy</h2>
                <button onclick="clearFilter()" id="btn-ver-todos"
                    class="hidden text-xs font-medium text-brand-green hover:underline">
                    Ver todos
                </button>
            </div>

            {{-- Lista de órdenes --}}
            <div class="space-y-3" id="ordenes-list">
                @forelse($ordenes as $index => $orden)
                <div class="orden-card bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border {{ $index === 0 ? 'border-brand-green' : 'border-gray-100' }} p-4 md:p-5 transition-all"
                     data-date="{{ $orden->fecha_asignacion ? \Carbon\Carbon::parse($orden->fecha_asignacion)->format('Y-m-d') : 'sin-fecha' }}">

                    <div class="flex items-start gap-3 md:gap-4">

                        {{-- Hora --}}
                        <div class="flex-shrink-0 w-14 text-center pt-0.5">
                            <p class="text-lg md:text-xl font-bold leading-tight {{ $index === 0 ? 'text-brand-green' : 'text-brand-dark' }}">
                                {{ $orden->fecha_asignacion ? \Carbon\Carbon::parse($orden->fecha_asignacion)->format('H:i') : '--:--' }}
                            </p>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <h3 class="font-semibold text-brand-dark text-base leading-tight">
                                    {{ $orden->cliente->nombre ?? 'Cliente' }}
                                </h3>
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                    {{ $orden->prioridad === 'alta' ? 'bg-[#FEF3C7] text-[#D97706]' : ($orden->prioridad === 'media' ? 'bg-[#DBEAFE] text-[#1D4ED8]' : 'bg-gray-100 text-gray-600') }}">
                                    {{ ucfirst($orden->prioridad) }}
                                </span>
                            </div>

                            @if($orden->cliente && $orden->cliente->direccion && $orden->cliente->direccion !== 'N/A')
                            <div class="flex items-center gap-1 mb-1">
                                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-xs text-gray-500 truncate">{{ $orden->cliente->direccion }}</span>
                            </div>
                            @endif

                            <p class="text-sm text-brand-green font-medium truncate">{{ $orden->titulo }}</p>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-50 flex-wrap">

                        @if($orden->cliente && $orden->cliente->telefono && $orden->cliente->telefono !== 'N/A')
                        <a href="tel:{{ $orden->cliente->telefono }}"
                           class="flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 hover:border-brand-green hover:text-brand-green transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Llamar
                        </a>
                        @endif

                        @if($orden->cliente && $orden->cliente->direccion && $orden->cliente->direccion !== 'N/A')
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($orden->cliente->direccion) }}" target="_blank"
                           class="flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 hover:border-brand-green hover:text-brand-green transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Ver Mapa
                        </a>
                        @endif

                        <div class="ml-auto flex items-center gap-2">
                            <a href="{{ route('ordenes.show', $orden) }}"
                               class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                Ver Detalle
                            </a>

                            @if(in_array($orden->estado, ['asignada', 'pendiente']))
                            <form action="{{ route('ordenes.update-estado', $orden) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="estado" value="en_proceso">
                                <button type="submit"
                                    class="flex items-center gap-1.5 px-4 py-1.5 bg-brand-green hover:bg-brand-green-dark text-white rounded-lg text-xs font-semibold shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    Iniciar Ruta
                                </button>
                            </form>

                            @elseif($orden->estado === 'en_proceso')
                            <a href="{{ route('ordenes.cierre', $orden) }}"
                               class="flex items-center gap-1.5 px-4 py-1.5 bg-brand-dark hover:bg-brand-dark-mid text-white rounded-lg text-xs font-semibold shadow-[0px_2px_8px_rgba(30,58,95,0.25)] transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Finalizar
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                @empty
                <div class="text-center py-14 bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)]">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-gray-500 font-medium">Sin servicios programados</p>
                    <p class="text-sm text-gray-400 mt-1">No tienes órdenes asignadas en este momento</p>
                </div>
                @endforelse
            </div>

            {{-- Mensaje cuando filtro no tiene resultados --}}
            <div id="no-results" class="hidden text-center py-10 bg-white rounded-xl border border-gray-100 mt-3">
                <p class="text-gray-500 text-sm">No hay servicios para este día</p>
            </div>

        </main>
    </div>
</div>

<script>
let activeDay = null;

function filterByDay(date, btn) {
    if (activeDay === date) {
        clearFilter();
        return;
    }

    activeDay = date;
    const cards = document.querySelectorAll('.orden-card');
    let visible = 0;

    cards.forEach(c => {
        const show = c.dataset.date === date;
        c.classList.toggle('hidden', !show);
        if (show) visible++;
    });

    document.getElementById('no-results').classList.toggle('hidden', visible > 0);
    document.getElementById('btn-ver-todos').classList.remove('hidden');

    // Resaltar día activo
    document.querySelectorAll('.day-btn').forEach(b => {
        b.classList.remove('ring-2', 'ring-offset-1', 'ring-brand-green');
    });
    if (btn) btn.classList.add('ring-2', 'ring-offset-1', 'ring-brand-green');
}

function clearFilter() {
    activeDay = null;
    document.querySelectorAll('.orden-card').forEach(c => c.classList.remove('hidden'));
    document.getElementById('no-results').classList.add('hidden');
    document.getElementById('btn-ver-todos').classList.add('hidden');
    document.querySelectorAll('.day-btn').forEach(b => {
        b.classList.remove('ring-2', 'ring-offset-1', 'ring-brand-green');
    });
}

function toggleWeekView() {
    document.getElementById('week-view').classList.toggle('hidden');
}
</script>
@endsection
