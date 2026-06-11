@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center gap-3 justify-between flex-shrink-0 flex-wrap">
            <div class="flex items-center gap-3 min-w-0">
                <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-brand-dark hover:bg-gray-100 transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-2xl font-semibold text-brand-dark">
                        Detalle de Avería #OT-{{ str_pad($orden->id, 3, '0', STR_PAD_LEFT) }}
                    </h1>
                    @php
                        $prioridadBg = $orden->prioridad === 'alta' ? 'bg-[#FEF3C7] text-[#D97706]' : ($orden->prioridad === 'media' ? 'bg-[#DBEAFE] text-[#1D4ED8]' : 'bg-gray-100 text-gray-600');
                    @endphp
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $prioridadBg }}">
                        Prioridad {{ ucfirst($orden->prioridad) }}
                    </span>
                </div>
                <p class="text-sm text-gray-500">
                    Asignado a mí
                    @if($orden->fecha_asignacion)
                        · Inicio programado: {{ \Carbon\Carbon::parse($orden->fecha_asignacion)->format('H:i') }}
                    @endif
                </p>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-medium text-brand-dark hover:text-brand-green transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver a Agenda
            </a>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                <!-- Columna Izquierda (2/5) -->
                <div class="lg:col-span-2 space-y-4">

                    <!-- Datos del Cliente -->
                    <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                        <h2 class="text-base font-semibold text-brand-dark mb-4">Datos del Cliente</h2>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                <svg class="w-7 h-7 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-brand-dark text-base">{{ $orden->cliente->nombre ?? 'Cliente no asignado' }}</p>
                                <p class="text-sm text-brand-green font-medium">Cliente activo</p>
                                @if($orden->cliente && $orden->cliente->created_at)
                                    <p class="text-xs text-gray-400">Miembro desde {{ $orden->cliente->created_at->format('Y') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-2">
                            @if($orden->cliente && $orden->cliente->telefono && $orden->cliente->telefono !== 'N/A')
                            <a href="tel:{{ $orden->cliente->telefono }}"
                               class="flex items-center justify-center gap-2 w-full bg-brand-green hover:bg-brand-green-dark text-white py-2.5 rounded-xl font-medium text-sm transition-colors shadow-[0px_2px_8px_rgba(16,185,129,0.25)]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                Llamar al Cliente
                            </a>
                            @else
                            <button disabled class="flex items-center justify-center gap-2 w-full bg-gray-200 text-gray-400 py-2.5 rounded-xl font-medium text-sm cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                Sin teléfono
                            </button>
                            @endif
                            @if($orden->cliente && $orden->cliente->direccion && $orden->cliente->direccion !== 'N/A')
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($orden->cliente->direccion) }}" target="_blank"
                               class="flex items-center justify-center gap-2 w-full bg-brand-dark hover:bg-brand-dark-mid text-white py-2.5 rounded-xl font-medium text-sm transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Ver en Mapa
                            </a>
                            @else
                            <button disabled class="flex items-center justify-center gap-2 w-full bg-gray-200 text-gray-400 py-2.5 rounded-xl font-medium text-sm cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Sin dirección
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Dirección del Servicio -->
                    <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                        <h2 class="text-base font-semibold text-brand-dark mb-3">Dirección del Servicio</h2>
                        <div class="flex items-start gap-2 mb-4">
                            <svg class="w-5 h-5 text-brand-green flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <div>
                                <p class="text-sm font-semibold text-[#1D4ED8]">{{ $orden->cliente?->direccion ?? 'Dirección no especificada' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">España</p>
                            </div>
                        </div>
                        <!-- Mapa placeholder -->
                        @if($orden->cliente && $orden->cliente->direccion && $orden->cliente->direccion !== 'N/A')
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($orden->cliente->direccion) }}" target="_blank"
                           class="bg-gray-100 rounded-xl h-32 flex flex-col items-center justify-center gap-1 hover:bg-gray-200 transition-colors group">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-brand-green transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-xs text-gray-500 group-hover:text-brand-green font-medium transition-colors">Abrir en Google Maps</p>
                        </a>
                        @else
                        <div class="bg-gray-100 rounded-xl h-32 flex flex-col items-center justify-center gap-1">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-xs text-gray-400 font-medium">Sin dirección registrada</p>
                        </div>
                        @endif
                    </div>

                    <!-- Detalles del Servicio -->
                    <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                        <h2 class="text-base font-semibold text-brand-dark mb-4">Detalles del Servicio</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Asunto:</span>
                                <span class="font-medium text-[#1D4ED8]">{{ $orden->titulo }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Prioridad:</span>
                                <span class="font-medium text-[#1D4ED8]">{{ ucfirst($orden->prioridad) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Fecha de creación:</span>
                                <span class="font-medium text-[#1D4ED8]">{{ $orden->created_at->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha (3/5) -->
                <div class="lg:col-span-3 space-y-4 flex flex-col">

                    <!-- Descripción del Problema -->
                    <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-[#D97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h2 class="text-base font-semibold text-brand-dark">Descripción del Problema</h2>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $orden->descripcion ?? 'Sin descripción proporcionada.' }}</p>

                        @if($orden->observaciones)
                        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <p class="text-xs font-semibold text-[#1D4ED8] mb-1">Notas adicionales del administrador:</p>
                            <p class="text-sm text-blue-700">{{ $orden->observaciones }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Aviso contextual según estado -->
                    @if(in_array($orden->estado, ['asignada', 'pendiente']))
                    <div class="bg-[#EFF6FF] border border-[#BFDBFE] rounded-xl px-4 py-3 flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#1D4ED8] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold text-[#1D4ED8]">Antes de salir, llama al cliente</p>
                            <p class="text-xs text-[#3B82F6] mt-0.5">Confirma que está en casa y disponible. Si no hay respuesta, no marques "En camino".</p>
                            @if($orden->cliente && $orden->cliente->telefono && $orden->cliente->telefono !== 'N/A')
                            <a href="tel:{{ $orden->cliente->telefono }}" class="inline-flex items-center gap-1.5 mt-2 text-xs font-semibold text-[#1D4ED8] hover:underline">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                Llamar: {{ $orden->cliente->telefono }}
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Fotos Adjuntas -->
                    @if($orden->fotos->count())
                    @php $fotosUrls = $orden->fotos->map(fn($f) => Storage::url($f->path))->values(); @endphp
                    <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6"
                         x-data="{ lightbox: false, current: 0, fotos: {{ $fotosUrls->toJson() }} }">
                        <h2 class="text-base font-semibold text-brand-dark mb-4">
                            Fotos del Cliente
                            <span class="text-xs font-normal text-gray-400 ml-1">({{ $orden->fotos->count() }})</span>
                        </h2>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach($orden->fotos as $i => $foto)
                            <button type="button" @click="current = {{ $i }}; lightbox = true"
                               class="relative aspect-square rounded-xl overflow-hidden border border-gray-100 hover:border-brand-green transition-colors group">
                                <img src="{{ Storage::url($foto->path) }}"
                                     alt="Foto avería"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </button>
                            @endforeach
                        </div>

                        <!-- Lightbox -->
                        <div x-show="lightbox" x-cloak
                             @keydown.escape.window="lightbox = false"
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/90"
                             @click.self="lightbox = false">

                            <!-- Cerrar -->
                            <button @click="lightbox = false"
                                class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                            <!-- Contador -->
                            <div class="absolute top-4 left-1/2 -translate-x-1/2 text-white/60 text-sm" x-text="(current + 1) + ' / ' + fotos.length"></div>

                            <!-- Imagen -->
                            <img :src="fotos[current]" alt="Foto avería"
                                 class="max-h-[85vh] max-w-[90vw] rounded-xl object-contain select-none">

                            <!-- Anterior -->
                            <button x-show="fotos.length > 1" @click="current = (current - 1 + fotos.length) % fotos.length"
                                class="absolute left-4 w-11 h-11 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>

                            <!-- Siguiente -->
                            <button x-show="fotos.length > 1" @click="current = (current + 1) % fotos.length"
                                class="absolute right-4 w-11 h-11 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Historial de la Orden -->
                    <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                        <h2 class="text-base font-semibold text-brand-dark mb-4">Historial de la Orden</h2>
                        <div class="space-y-4">
                            @if($orden->fecha_asignacion)
                            <div class="flex items-start gap-3">
                                <div class="w-3 h-3 rounded-full bg-brand-green flex-shrink-0 mt-1"></div>
                                <div>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($orden->fecha_asignacion)->format('d M Y - H:i') }}</p>
                                    <p class="text-sm font-medium text-brand-dark">Orden asignada al técnico</p>
                                    <p class="text-xs text-gray-500">Por: Sistema</p>
                                </div>
                            </div>
                            @endif
                            <div class="flex items-start gap-3">
                                <div class="w-3 h-3 rounded-full bg-brand-green flex-shrink-0 mt-1"></div>
                                <div>
                                    <p class="text-xs text-gray-400">{{ $orden->created_at->translatedFormat('d M Y - H:i') }}</p>
                                    <p class="text-sm font-medium text-brand-dark">Orden creada por el cliente</p>
                                    <p class="text-xs text-gray-500">Por: {{ $orden->cliente?->nombre ?? 'Cliente' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex gap-3 mt-3 pb-1">
                        @if(in_array($orden->estado, ['asignada', 'pendiente']))
                            <form action="{{ route('ordenes.update-estado', $orden) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="estado" value="en_proceso">
                                <button type="submit" class="w-full bg-brand-green hover:bg-brand-green-dark text-white py-3.5 rounded-xl font-semibold text-sm shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    Iniciar Ruta
                                </button>
                            </form>
                        @elseif($orden->estado === 'en_proceso')
                            <a href="{{ route('ordenes.cierre', $orden) }}" class="flex-1 flex items-center justify-center gap-2 bg-brand-green hover:bg-brand-green-dark text-white py-3.5 rounded-xl font-semibold text-sm shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Finalizar Orden
                            </a>
                        @endif

                        @if(!in_array($orden->estado, ['finalizada', 'cancelada', 'pendiente_valoracion', 'pendiente_reprogramacion']))
                        <button type="button" onclick="document.getElementById('modal-aplazar').classList.remove('hidden')"
                            class="px-5 flex items-center justify-center gap-1.5 bg-white border border-amber-300 hover:border-amber-400 hover:bg-amber-50 text-amber-600 py-3.5 rounded-xl font-semibold text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Aplazar
                        </button>
                        <button type="button" onclick="document.getElementById('modal-cancelar').classList.remove('hidden')"
                            class="px-5 flex items-center justify-center bg-white border border-red-200 hover:border-red-400 hover:bg-red-50 text-red-600 py-3.5 rounded-xl font-semibold text-sm transition-colors">
                            Cancelar
                        </button>
                        @endif
                        @if($orden->estado === 'pendiente_reprogramacion')
                        <div class="flex-1 flex items-center justify-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 py-3.5 rounded-xl text-sm font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Pendiente de reagendar por el admin
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal Aplazar Servicio -->
<div id="modal-aplazar" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">Aplazar servicio</h3>
                <p class="text-xs text-gray-500">La orden quedará pendiente de reagendar por el administrador</p>
            </div>
        </div>

        <p class="text-sm text-gray-600 mb-4">Selecciona el motivo por el que no se ha podido realizar el servicio hoy.</p>

        <form action="{{ route('ordenes.aplazar', $orden) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Motivo <span class="text-red-500">*</span></label>
                <select name="motivo" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent bg-white">
                    <option value="" disabled selected>Selecciona un motivo...</option>
                    <option value="Cliente ausente en el domicilio">Cliente ausente en el domicilio</option>
                    <option value="Sin acceso al inmueble">Sin acceso al inmueble</option>
                    <option value="Material o pieza pendiente de llegada">Material o pieza pendiente de llegada</option>
                    <option value="Avería del vehículo del técnico">Avería del vehículo del técnico</option>
                    <option value="Emergencia o imprevisto personal">Emergencia o imprevisto personal</option>
                    <option value="Otro motivo">Otro motivo</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nota adicional <span class="text-gray-400">(opcional)</span></label>
                <textarea name="nota" rows="3" placeholder="Información adicional para el administrador..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent resize-none"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modal-aplazar').classList.add('hidden')"
                    class="flex-1 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                    Volver
                </button>
                <button type="submit"
                    class="flex-1 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold transition-colors">
                    Confirmar aplazamiento
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Cancelar Servicio -->
<div id="modal-cancelar" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">Cancelar servicio</h3>
                <p class="text-xs text-gray-500">Esta acción no se puede deshacer</p>
            </div>
        </div>

        <p class="text-sm text-gray-600 mb-4">Explica el motivo por el que no puedes realizar el servicio hoy. El administrador recibirá esta información.</p>

        <form action="{{ route('ordenes.cancelar-tecnico', $orden) }}" method="POST">
            @csrf
            <textarea name="motivo" rows="4" required placeholder="Ej: El cliente no estaba disponible, no pude desplazarme por avería del vehículo..."
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent resize-none"></textarea>

            <div class="flex gap-3 mt-4">
                <button type="button" onclick="document.getElementById('modal-cancelar').classList.add('hidden')"
                    class="flex-1 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                    Volver
                </button>
                <button type="submit"
                    class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-colors">
                    Confirmar cancelación
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
