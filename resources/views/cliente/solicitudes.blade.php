@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">

        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3 min-w-0">
                <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-brand-dark hover:bg-gray-100 transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-2xl md:text-4xl font-medium text-brand-dark">Mis Servicios</h1>
                    <p class="text-sm md:text-base text-gray-500 mt-0.5">Portal del Cliente</p>
                </div>
            </div>
            <a href="{{ route('solicitud.nueva') }}"
               class="flex-shrink-0 bg-brand-green hover:bg-brand-green-dark text-white px-5 py-2.5 rounded-xl font-medium text-sm flex items-center gap-2 shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nueva Solicitud
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-6 space-y-8">

            {{-- ── SERVICIO ACTIVO ─────────────────────────────────────────── --}}
            <section>
                <h2 class="text-lg font-semibold text-brand-dark mb-3">Servicio Activo</h2>

                @if($ordenActiva)
                @php
                    $statusMsg = match($ordenActiva->estado) {
                        'pendiente'            => ['El servicio está pendiente de asignación.',           'text-gray-500',    'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'asignada'             => ['El técnico ha sido asignado a su servicio.',          'text-[#1D4ED8]',   'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        'en_camino'            => ['El técnico está en camino a su domicilio.',           'text-brand-green', 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
                        'en_proceso'           => ['El técnico está trabajando en su servicio.',          'text-[#D97706]',   'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z'],
                        'pendiente_valoracion' => ['El técnico ha finalizado el servicio. Por favor, valora la atención recibida.', 'text-[#065F46]', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        default                => ['Estado desconocido.', 'text-gray-500', 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    };
                    $badge = match($ordenActiva->estado) {
                        'pendiente'            => ['Pendiente',           'bg-gray-100 text-gray-600'],
                        'asignada'             => ['Asignada',            'bg-[#FEF3C7] text-[#D97706]'],
                        'en_camino'            => ['En camino',           'bg-[#D1FAE5] text-[#065F46]'],
                        'en_proceso'           => ['En proceso',          'bg-[#DBEAFE] text-[#1D4ED8]'],
                        'pendiente_valoracion' => ['Pendiente valoración','bg-[#D1FAE5] text-[#065F46]'],
                        default                => [ucfirst($ordenActiva->estado), 'bg-gray-100 text-gray-600'],
                    };
                @endphp

                <div class="bg-white rounded-2xl border-2 border-brand-green shadow-[0px_4px_16px_rgba(16,185,129,0.12)] p-6">
                    <div class="flex items-start justify-between gap-4 flex-wrap">

                        {{-- Icono + info --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-[#D1FAE5] flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-lg font-semibold text-brand-dark">{{ $ordenActiva->titulo }}</h3>
                                    <span class="text-xs font-bold text-gray-400">#OT-{{ str_pad($ordenActiva->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="flex items-center gap-5 mt-2 flex-wrap text-sm text-gray-500">
                                    @if($ordenActiva->tecnico)
                                    <div>
                                        <span class="text-xs text-gray-400 block">Técnico asignado</span>
                                        <span class="font-medium text-brand-dark">{{ $ordenActiva->tecnico->name }}</span>
                                    </div>
                                    @endif
                                    <div>
                                        <span class="text-xs text-gray-400 block">Estado</span>
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $badge[1] }}">{{ $badge[0] }}</span>
                                    </div>
                                    @if($ordenActiva->fecha_entrega_prevista)
                                    <div>
                                        <span class="text-xs text-gray-400 block">Fecha prevista</span>
                                        <span class="font-medium text-brand-dark">{{ \Carbon\Carbon::parse($ordenActiva->fecha_entrega_prevista)->format('d/m/Y') }}</span>
                                    </div>
                                    @endif
                                </div>
                                @if($ordenActiva->cliente?->direccion && $ordenActiva->cliente->direccion !== 'N/A')
                                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $ordenActiva->cliente->direccion }}
                                </p>
                                @endif
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="flex items-center gap-3 flex-shrink-0 flex-wrap">
                            @if($ordenActiva->estado === 'pendiente_valoracion')
                            <a href="{{ route('cliente.orden.valorar', $ordenActiva) }}"
                               class="px-5 py-2.5 rounded-xl bg-brand-green hover:bg-brand-green-dark text-white text-sm font-semibold transition-all shadow-[0px_2px_8px_rgba(16,185,129,0.3)] flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                Valorar Servicio
                            </a>
                            @else
                            <a href="{{ route('cliente.orden.show', $ordenActiva) }}"
                               class="px-5 py-2 rounded-xl border border-gray-200 hover:border-brand-green text-sm font-semibold text-brand-dark hover:text-brand-green transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Ver detalle
                            </a>
                            @endif
                        </div>
                    </div>

                    {{-- Seguimiento por pasos --}}
                    @php
                        $pasos = [
                            ['key' => 'pendiente',            'label' => 'Solicitud recibida'],
                            ['key' => 'asignada',             'label' => 'Técnico asignado'],
                            ['key' => 'en_camino',            'label' => 'En camino'],
                            ['key' => 'en_proceso',           'label' => 'En reparación'],
                            ['key' => 'pendiente_valoracion', 'label' => 'Finalizado'],
                        ];
                        $ordenEstados = ['pendiente', 'asignada', 'en_camino', 'en_proceso', 'pendiente_valoracion'];
                        $pasoActual   = array_search($ordenActiva->estado, $ordenEstados) ?? 0;
                    @endphp
                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between gap-1">
                            @foreach($pasos as $i => $paso)
                            @php
                                $completado = $i < $pasoActual;
                                $activo     = $i === $pasoActual;
                            @endphp
                            <div class="flex flex-col items-center flex-1 min-w-0">
                                <div class="w-full flex items-center">
                                    {{-- Línea izquierda --}}
                                    @if($i > 0)
                                    <div class="flex-1 h-0.5 {{ $completado || $activo ? 'bg-brand-green' : 'bg-gray-200' }}"></div>
                                    @endif
                                    {{-- Círculo --}}
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-all
                                        {{ $completado ? 'bg-brand-green' : ($activo ? 'bg-brand-green ring-4 ring-[#D1FAE5]' : 'bg-gray-200') }}">
                                        @if($completado)
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($activo)
                                        <div class="w-2.5 h-2.5 rounded-full bg-white"></div>
                                        @endif
                                    </div>
                                    {{-- Línea derecha --}}
                                    @if($i < count($pasos) - 1)
                                    <div class="flex-1 h-0.5 {{ $completado ? 'bg-brand-green' : 'bg-gray-200' }}"></div>
                                    @endif
                                </div>
                                <p class="text-xs mt-1.5 text-center leading-tight px-0.5
                                    {{ $activo ? 'text-brand-green font-semibold' : ($completado ? 'text-gray-500' : 'text-gray-300') }}">
                                    {{ $paso['label'] }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-center mt-3 {{ $statusMsg[1] }}">{{ $statusMsg[0] }}</p>
                    </div>
                </div>

                @else
                <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-8 text-center">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-gray-400">No tienes ningún servicio activo en este momento.</p>
                </div>
                @endif
            </section>

            {{-- ── HISTORIAL DE SERVICIOS ──────────────────────────────────── --}}
            <section id="historial">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="text-lg font-semibold text-brand-dark">Historial de Servicios</h2>
                        <p class="text-xs text-gray-400">Servicios completados anteriormente</p>
                    </div>
                </div>

                @if($historial->isEmpty())
                <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-10 text-center">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-gray-400">Aún no tienes servicios completados.</p>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($historial as $orden)
                    @php $stars = (int)($orden->satisfaccion ?? 0); @endphp
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5 hover:border-brand-green transition-colors">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-[#D1FAE5] flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-brand-dark leading-tight">{{ $orden->titulo }}</h3>
                                    <span class="text-xs text-gray-400">#OT-{{ str_pad($orden->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-[#D1FAE5] text-[#065F46] flex-shrink-0">Finalizado</span>
                        </div>

                        <div class="space-y-1 text-xs text-gray-500 mb-3">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $orden->updated_at->format('d M Y') }}
                            </div>
                            @if($orden->tecnico)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Técnico: {{ $orden->tecnico->name }}
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-0.5">
                                @for($s = 1; $s <= 5; $s++)
                                <svg class="w-4 h-4 {{ $s <= $stars ? 'text-[#F59E0B]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                            <a href="{{ route('cliente.orden.show', $orden) }}"
                               class="text-xs font-medium text-brand-green hover:underline">Ver detalles</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </section>

        </main>
    </div>
</div>
@endsection
