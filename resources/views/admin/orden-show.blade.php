@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center gap-3 md:gap-4 flex-shrink-0 flex-wrap">
            <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-brand-dark hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="javascript:history.back()" class="text-gray-400 hover:text-brand-green transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <span class="text-xs font-black text-gray-400 uppercase tracking-wider">ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                <h1 class="text-2xl font-medium text-brand-dark">{{ $orden->titulo }}</h1>
            </div>
            <div class="ml-auto">
                @php
                    $badgeClass = match($orden->estado) {
                        'finalizada'  => 'bg-[#D1FAE5] text-[#065F46]',
                        'en_proceso'  => 'bg-[#DBEAFE] text-[#1D4ED8]',
                        'en_camino'   => 'bg-[#D1FAE5] text-[#065F46]',
                        'asignada'    => 'bg-[#FEF3C7] text-[#D97706]',
                        default       => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $badgeClass }}">
                    {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                </span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- ── COLUMNA IZQUIERDA (2/3) ── --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Descripción --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6">
                        <h2 class="text-sm font-semibold text-brand-dark mb-3">Descripción del problema</h2>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $orden->descripcion ?: 'Sin descripción.' }}</p>
                    </div>

                    {{-- Informe del técnico --}}
                    @if($orden->observaciones)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6">
                        <h2 class="text-sm font-semibold text-brand-dark mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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
                            Recomendaciones al cliente
                        </h2>
                        <p class="text-sm text-[#92400E] leading-relaxed">{{ $orden->recomendaciones }}</p>
                    </div>
                    @endif

                    {{-- Materiales utilizados --}}
                    @if($orden->Material->count())
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6">
                        <h2 class="text-sm font-semibold text-brand-dark mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Materiales utilizados
                        </h2>
                        <div class="space-y-2">
                            @foreach($orden->Material as $mat)
                            <div class="flex items-center justify-between bg-[#F5F7FA] rounded-xl px-4 py-2.5">
                                <span class="text-sm text-brand-dark">{{ $mat->nombre }}</span>
                                <span class="text-xs font-semibold text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200">x{{ $mat->cantidad }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- ── COLUMNA DERECHA (1/3) ── --}}
                <div class="lg:col-span-1 space-y-5">

                    {{-- Info del cliente --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Cliente</h2>
                        @if($orden->cliente)
                            <p class="font-semibold text-brand-dark text-sm">{{ $orden->cliente->nombre }}</p>
                            @if($orden->cliente->telefono && $orden->cliente->telefono !== 'N/A')
                            <p class="text-xs text-gray-400 mt-1">{{ $orden->cliente->telefono }}</p>
                            @endif
                            @if($orden->cliente->direccion && $orden->cliente->direccion !== 'N/A')
                            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $orden->cliente->direccion }}
                            </p>
                            @endif
                        @else
                            <p class="text-sm text-gray-400">Sin cliente asignado</p>
                        @endif
                    </div>

                    {{-- Técnico asignado --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Técnico</h2>
                        @if($orden->tecnico)
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-full bg-brand-dark text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    {{ substr($orden->tecnico->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-brand-dark text-sm">{{ $orden->tecnico->name }}</p>
                                    <p class="text-xs text-gray-400">Técnico de Campo</p>
                                </div>
                            </div>
                            @if($orden->fecha_asignacion)
                            <p class="text-xs text-gray-400">Asignado el {{ $orden->fecha_asignacion->translatedFormat('d M Y H:i') }}</p>
                            @endif
                        @else
                            <p class="text-sm text-gray-400">Sin técnico asignado</p>
                        @endif

                        @php $tecnicos = \App\Models\User::where('role', 'tecnico')->get(); @endphp

                        {{-- PENDIENTE: asignación directa --}}
                        @if($orden->estado === 'pendiente' && $tecnicos->count())
                        <form action="{{ route('ordenes.assign-tecnico', $orden) }}" method="POST" class="mt-4 space-y-3">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Seleccionar Técnico</label>
                                <select name="usuario_id" required
                                    class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-brand-green rounded-xl px-4 py-2 focus:outline-none transition-colors text-sm appearance-none">
                                    <option value="" disabled selected>Elige un técnico...</option>
                                    @foreach($tecnicos as $tec)
                                        <option value="{{ $tec->id }}">{{ $tec->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-brand-green hover:bg-brand-green-dark text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
                                Asignar Técnico
                            </button>
                        </form>
                        @endif

                        {{-- ASIGNADA: reasignación con motivo --}}
                        @if($orden->estado === 'asignada' && $tecnicos->count())
                        <div class="mt-4">
                            <button type="button" id="btn-reasignar"
                                onclick="document.getElementById('panel-reasignar').classList.toggle('hidden'); this.classList.toggle('hidden')"
                                class="w-full flex items-center justify-center gap-2 border-2 border-orange-300 text-orange-600 hover:bg-orange-50 px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                Reasignar Técnico
                            </button>

                            <div id="panel-reasignar" class="hidden mt-3 bg-orange-50 border border-orange-200 rounded-xl p-4 space-y-3">
                                <div class="flex items-start gap-2 mb-1">
                                    <svg class="w-4 h-4 text-orange-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-xs text-orange-700 font-medium">Se notificará al nuevo técnico por correo.</p>
                                </div>

                                <form action="{{ route('ordenes.assign-tecnico', $orden) }}" method="POST" class="space-y-3">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="block text-xs font-semibold text-orange-700 uppercase tracking-wide mb-1.5">Motivo de reasignación <span class="text-red-500">*</span></label>
                                        <textarea name="motivo" required rows="2" placeholder="Ej: El técnico ha sufrido un accidente..."
                                            class="w-full bg-white border-2 border-orange-200 focus:border-orange-400 rounded-xl px-3 py-2 text-sm focus:outline-none transition-colors resize-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-orange-700 uppercase tracking-wide mb-1.5">Nuevo Técnico</label>
                                        <select name="usuario_id" required
                                            class="w-full bg-white border-2 border-orange-200 focus:border-orange-400 rounded-xl px-4 py-2 focus:outline-none transition-colors text-sm appearance-none">
                                            <option value="" disabled selected>Selecciona el sustituto...</option>
                                            @foreach($tecnicos as $tec)
                                                @if($tec->id !== $orden->usuario_id)
                                                <option value="{{ $tec->id }}">{{ $tec->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
                                            Confirmar reasignación
                                        </button>
                                        <button type="button"
                                            onclick="document.getElementById('panel-reasignar').classList.add('hidden'); document.getElementById('btn-reasignar').classList.remove('hidden')"
                                            class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition-colors">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Detalles del servicio --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Detalles</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Fecha</span>
                                <span class="font-medium text-brand-dark">{{ $orden->updated_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Prioridad</span>
                                <span class="font-medium text-brand-dark capitalize">{{ $orden->prioridad }}</span>
                            </div>
                            @if($orden->hora_inicio && $orden->hora_fin)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Horario</span>
                                <span class="font-medium text-brand-dark">{{ $orden->hora_inicio }} – {{ $orden->hora_fin }}</span>
                            </div>
                            @php
                                [$hi,$mi] = explode(':', $orden->hora_inicio);
                                [$hf,$mf] = explode(':', $orden->hora_fin);
                                $mins = ($hf*60+$mf) - ($hi*60+$mi);
                                $h = floor($mins/60); $m = $mins%60;
                            @endphp
                            <div class="flex justify-between bg-[#D1FAE5] rounded-lg px-3 py-2">
                                <span class="text-[#065F46] font-medium">Duración</span>
                                <span class="font-bold text-brand-green">{{ ($h ? $h.'h ' : '') . ($m ? $m.'min' : '') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Valoración del cliente --}}
                    @if($orden->estado === 'finalizada')
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Valoración del cliente</h2>
                        @php $stars = (int)($orden->satisfaccion ?? 0); @endphp
                        @if($stars)
                        <div class="flex items-center gap-1 mb-1">
                            @for($s = 1; $s <= 5; $s++)
                            <svg class="w-5 h-5 {{ $s <= $stars ? 'text-[#F59E0B]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
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
                    @endif

                    {{-- Valoración del técnico sobre el cliente --}}
                    @if($orden->satisfaccion_tecnico)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Trato del cliente <span class="normal-case text-gray-300">(según técnico)</span></h2>
                        @php $starsTec = (int)$orden->satisfaccion_tecnico; @endphp
                        <div class="flex items-center gap-1 mb-1">
                            @for($s = 1; $s <= 5; $s++)
                            <svg class="w-5 h-5 {{ $s <= $starsTec ? 'text-[#F59E0B]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-400">
                            @php echo ['','Muy mal trato','Mal trato','Normal','Buen trato','Excelente trato'][$starsTec]; @endphp
                        </p>
                    </div>
                    @endif

                    {{-- Comentario del cliente --}}
                    @if($orden->comentario_cliente)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Comentario del cliente</h2>
                        <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $orden->comentario_cliente }}</p>
                    </div>
                    @endif

                    {{-- Firma del cliente --}}
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
