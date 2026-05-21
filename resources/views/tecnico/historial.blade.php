@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center gap-3">
            <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-brand-dark hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="text-2xl md:text-4xl font-medium text-brand-dark">Mi Historial</h1>
                <p class="text-sm md:text-base text-gray-500 mt-0.5">Órdenes completadas, pendientes de valoración y canceladas</p>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-5 mb-6">
                <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                    <p class="text-sm text-gray-500 mb-1">Total trabajos</p>
                    <p class="text-3xl font-bold text-brand-dark">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                    <p class="text-sm text-gray-500 mb-1">Finalizadas</p>
                    <p class="text-3xl font-bold text-brand-green">{{ $stats['finalizadas'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                    <p class="text-sm text-gray-500 mb-1">Canceladas</p>
                    <p class="text-3xl font-bold text-red-400">{{ $stats['canceladas'] }}</p>
                </div>
                @if($stats['pendiente_valoracion'] > 0)
                <div class="bg-white rounded-xl border border-amber-200 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                    <p class="text-sm text-gray-500 mb-1">Esperando valoración</p>
                    <p class="text-3xl font-bold text-amber-500">{{ $stats['pendiente_valoracion'] }}</p>
                </div>
                @endif
            </div>

            {{-- Lista --}}
            <div class="space-y-3">
                @forelse($ordenes as $orden)
                @php
                    $estado = $orden->estado;
                    $esFinalizada       = $estado === 'finalizada';
                    $esPendienteValor   = $estado === 'pendiente_valoracion';
                    $esCancelada        = $estado === 'cancelada';

                    $iconoBg    = $esFinalizada ? 'bg-[#D1FAE5]' : ($esPendienteValor ? 'bg-amber-100' : 'bg-red-100');
                    $iconoColor = $esFinalizada ? 'text-brand-green' : ($esPendienteValor ? 'text-amber-500' : 'text-red-400');
                    $badgeCss   = $esFinalizada
                        ? 'bg-[#D1FAE5] text-[#065F46]'
                        : ($esPendienteValor ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700');
                    $badgeLabel = $esFinalizada ? 'Finalizada' : ($esPendienteValor ? 'Pendiente valoración' : 'Cancelada');
                @endphp
                <div class="bg-white rounded-xl border {{ $esPendienteValor ? 'border-amber-200' : 'border-gray-100' }} shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5 flex items-start sm:items-center justify-between gap-4 flex-wrap sm:flex-nowrap">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $iconoBg }}">
                            @if($esFinalizada)
                                <svg class="w-5 h-5 {{ $iconoColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @elseif($esPendienteValor)
                                <svg class="w-5 h-5 {{ $iconoColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                                <svg class="w-5 h-5 {{ $iconoColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-wider">OT-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $badgeCss }}">
                                    {{ $badgeLabel }}
                                </span>
                            </div>
                            <p class="text-sm font-medium text-brand-dark truncate">{{ $orden->titulo }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $orden->cliente->nombre ?? '—' }}</p>
                            @if($esPendienteValor)
                                <p class="text-xs text-amber-600 mt-0.5">⏳ Esperando que el cliente valore el servicio</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-6 flex-shrink-0 text-right">
                        @if($orden->hora_inicio && $orden->hora_fin)
                        <div>
                            <p class="text-xs text-gray-400">Duración</p>
                            <p class="text-sm font-medium text-brand-dark">
                                @php
                                    [$hi,$mi] = explode(':', $orden->hora_inicio);
                                    [$hf,$mf] = explode(':', $orden->hora_fin);
                                    $mins = ($hf*60+$mf) - ($hi*60+$mi);
                                    $h = floor($mins/60); $m = $mins%60;
                                    echo ($h ? $h.'h ' : '') . ($m ? $m.'min' : '');
                                @endphp
                            </p>
                        </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-400">Fecha</p>
                            <p class="text-sm font-medium text-brand-dark">{{ $orden->updated_at->format('d/m/Y') }}</p>
                        </div>
                        @if($esFinalizada)
                        <a href="{{ route('ordenes.show', $orden) }}"
                           class="text-sm font-medium text-[#1D4ED8] hover:underline whitespace-nowrap">
                            Ver detalle
                        </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
                    <svg class="w-14 h-14 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-base font-medium text-gray-400">Aún no tienes órdenes completadas</p>
                </div>
                @endforelse
            </div>

        </main>
    </div>
</div>
@endsection
