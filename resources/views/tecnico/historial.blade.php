@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center gap-3">
            <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-[#1E3A5F] hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="text-2xl md:text-4xl font-medium text-[#1E3A5F]">Mi Historial</h1>
                <p class="text-sm md:text-base text-gray-500 mt-0.5">Órdenes finalizadas y canceladas</p>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-5 mb-6">
                <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                    <p class="text-sm text-gray-500 mb-1">Total trabajos</p>
                    <p class="text-3xl font-bold text-[#1E3A5F]">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                    <p class="text-sm text-gray-500 mb-1">Finalizadas</p>
                    <p class="text-3xl font-bold text-[#10B981]">{{ $stats['finalizadas'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                    <p class="text-sm text-gray-500 mb-1">Canceladas</p>
                    <p class="text-3xl font-bold text-red-400">{{ $stats['canceladas'] }}</p>
                </div>
            </div>

            {{-- Lista --}}
            <div class="space-y-3">
                @forelse($ordenes as $orden)
                @php
                    $finalizada = $orden->estado === 'finalizada';
                @endphp
                <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5 flex items-start sm:items-center justify-between gap-4 flex-wrap sm:flex-nowrap">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $finalizada ? 'bg-[#D1FAE5]' : 'bg-red-100' }}">
                            @if($finalizada)
                            <svg class="w-5 h-5 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-wider">OT-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $finalizada ? 'bg-[#D1FAE5] text-[#065F46]' : 'bg-red-100 text-red-700' }}">
                                    {{ $finalizada ? 'Finalizada' : 'Cancelada' }}
                                </span>
                            </div>
                            <p class="text-sm font-medium text-[#1E3A5F] truncate">{{ $orden->titulo }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $orden->cliente->nombre ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 flex-shrink-0 text-right">
                        @if($orden->hora_inicio && $orden->hora_fin)
                        <div>
                            <p class="text-xs text-gray-400">Duración</p>
                            <p class="text-sm font-medium text-[#1E3A5F]">
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
                            <p class="text-sm font-medium text-[#1E3A5F]">{{ $orden->updated_at->format('d/m/Y') }}</p>
                        </div>
                        @if($finalizada)
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
