@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center gap-3 justify-between flex-shrink-0 flex-wrap">
            <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-[#1E3A5F] hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-wider">OT-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                    <span class="text-gray-300">·</span>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-[#D1FAE5] text-[#065F46]">Finalizado</span>
                </div>
                <h1 class="text-2xl font-medium text-[#1E3A5F] mt-1">{{ $orden->titulo }}</h1>
            </div>
            <a href="{{ route('dashboard') }}#historial"
               class="flex items-center gap-2 text-sm font-medium text-[#1E3A5F] border border-gray-200 hover:border-[#1E3A5F] px-4 py-2 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-5xl mx-auto grid grid-cols-3 gap-5">

                {{-- ── COLUMNA IZQUIERDA (2/3) ── --}}
                <div class="col-span-2 space-y-5">

                    {{-- Descripción --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6">
                        <h2 class="text-sm font-semibold text-[#1E3A5F] mb-3">Descripción del problema</h2>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $orden->descripcion ?: 'Sin descripción.' }}</p>
                    </div>

                    {{-- Comentarios del técnico --}}
                    @if($orden->observaciones)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6">
                        <h2 class="text-sm font-semibold text-[#1E3A5F] mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Informe del técnico
                        </h2>
                        <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $orden->observaciones }}</p>
                    </div>
                    @endif

                    {{-- Recomendaciones --}}
                    @if($orden->recomendaciones)
                    <div class="bg-[#FFF7ED] rounded-xl border border-[#FED7AA] p-6">
                        <h2 class="text-sm font-semibold text-[#92400E] mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#D97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            Recomendaciones para ti
                        </h2>
                        <p class="text-sm text-[#92400E] leading-relaxed">{{ $orden->recomendaciones }}</p>
                    </div>
                    @endif

                    {{-- Materiales utilizados --}}
                    @if($orden->Material->count())
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6">
                        <h2 class="text-sm font-semibold text-[#1E3A5F] mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Material utilizado
                        </h2>
                        <div class="space-y-2">
                            @foreach($orden->Material as $mat)
                            <div class="flex items-center justify-between bg-[#F5F7FA] rounded-xl px-4 py-2.5">
                                <span class="text-sm text-[#1E3A5F]">{{ $mat->nombre }}</span>
                                <span class="text-xs font-semibold text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200">
                                    x{{ $mat->cantidad }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- ── COLUMNA DERECHA (1/3) ── --}}
                <div class="col-span-1 space-y-5">

                    {{-- Técnico asignado --}}
                    @if($orden->tecnico)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Técnico</h2>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#1E3A5F] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ substr($orden->tecnico->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#1E3A5F]">
                                    {{ $orden->tecnico->name }}
                                    @if($orden->tecnico->perfil?->apellidos)
                                        {{ $orden->tecnico->perfil->apellidos }}
                                    @endif
                                </p>
                                @if($orden->tecnico->perfil?->telefono && $orden->tecnico->perfil->telefono !== 'N/A')
                                <p class="text-xs text-gray-400">{{ $orden->tecnico->perfil->telefono }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Detalles del servicio --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Detalles</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Fecha</span>
                                <span class="font-medium text-[#1E3A5F]">{{ $orden->updated_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Prioridad</span>
                                <span class="font-medium text-[#1E3A5F] capitalize">{{ $orden->prioridad }}</span>
                            </div>
                            @if($orden->hora_inicio && $orden->hora_fin)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Horario</span>
                                <span class="font-medium text-[#1E3A5F]">{{ $orden->hora_inicio }} – {{ $orden->hora_fin }}</span>
                            </div>
                            @php
                                [$hi,$mi] = explode(':', $orden->hora_inicio);
                                [$hf,$mf] = explode(':', $orden->hora_fin);
                                $mins = ($hf*60+$mf) - ($hi*60+$mi);
                                $h = floor($mins/60); $m = $mins%60;
                            @endphp
                            <div class="flex justify-between bg-[#D1FAE5] rounded-lg px-3 py-2">
                                <span class="text-[#065F46] font-medium">Duración</span>
                                <span class="font-bold text-[#10B981]">{{ ($h ? $h.'h ' : '') . ($m ? $m.'min' : '') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Valoración --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Tu valoración</h2>
                        @php $stars = (int)($orden->satisfaccion ?? 0); @endphp
                        @if($stars)
                        <div class="flex items-center gap-1 mb-1">
                            @for($s = 1; $s <= 5; $s++)
                            <svg class="w-6 h-6 {{ $s <= $stars ? 'text-[#F59E0B]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-400">
                            @php echo ['','Muy insatisfecho','Insatisfecho','Neutral','Satisfecho','Muy satisfecho'][$stars]; @endphp
                        </p>
                        @else
                        <p class="text-xs text-gray-400">Sin valoración registrada</p>
                        @endif
                    </div>

                    {{-- Firma --}}
                    @if($orden->firma_path)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Firma del cliente</h2>
                        <img src="{{ Storage::url($orden->firma_path) }}" alt="Firma" class="w-full rounded-lg border border-gray-100">
                    </div>
                    @endif

                </div>

            </div>
        </main>
    </div>
</div>
@endsection
