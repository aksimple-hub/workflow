@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-brand-dark hover:bg-gray-100 transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <a href="{{ route('admin.tecnicos') }}" class="text-gray-400 hover:text-brand-green transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <h1 class="text-xl md:text-2xl font-medium text-brand-dark">Perfil del Técnico</h1>
            </div>
            <div class="flex items-center gap-3 flex-wrap flex-shrink-0">
                <button onclick="toggleEdit()"
                    class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Editar datos
                </button>
                @if($tecnico->is_approved)
                <button onclick="document.getElementById('modal-baja').classList.remove('hidden')"
                    class="flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 rounded-xl text-sm font-medium text-red-600 hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    Dar de baja
                </button>
                @else
                <button onclick="document.getElementById('modal-activar').classList.remove('hidden')"
                    class="flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-300 rounded-xl text-sm font-semibold text-green-700 hover:bg-green-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Activar técnico
                </button>
                @endif
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-[#D1FAE5] border border-[#6EE7B7] text-[#065F46] rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
            @endif

            <div class="max-w-5xl mx-auto space-y-6">

                <!-- Vista de datos -->
                <div id="view-mode" class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-8 flex items-start gap-6">
                    <div class="w-20 h-20 rounded-full bg-brand-dark text-white flex items-center justify-center font-bold text-3xl flex-shrink-0">
                        {{ substr($tecnico->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <h2 class="text-3xl font-medium text-brand-dark">{{ $tecnico->name }} {{ $perfil->apellidos ?? '' }}</h2>
                            @if($tecnico->is_approved)
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Activo</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-100 text-red-600 text-xs font-medium rounded-full">Inactivo</span>
                            @endif
                        </div>
                        <p class="text-gray-500 text-sm mb-4">{{ $tecnico->email }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">DNI / NIE</p>
                                <p class="text-sm font-medium text-gray-700">{{ $perfil->dni_nie ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Teléfono</p>
                                <p class="text-sm font-medium text-gray-700">{{ $perfil->telefono ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Dirección</p>
                                <p class="text-sm font-medium text-gray-700">{{ $perfil->direccion ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Órdenes asignadas</p>
                                <p class="text-sm font-medium text-gray-700">{{ $ordenes->count() }}</p>
                            </div>
                            @if($perfil && $perfil->experiencia)
                            <div class="col-span-2">
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Experiencia</p>
                                <p class="text-sm font-medium text-gray-700">{{ $perfil->experiencia }}</p>
                            </div>
                            @endif
                        </div>

                        @if($perfil && $perfil->cv_path)
                        <div class="mt-5 pt-5 border-t border-gray-100">
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-3">Currículum Vitae</p>
                            <div class="flex items-center gap-4 p-4 bg-red-50 border border-red-100 rounded-xl">
                                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800">CV adjunto</p>
                                    <p class="text-xs text-gray-400 truncate">{{ basename($perfil->cv_path) }}</p>
                                </div>
                                <a href="{{ Storage::url($perfil->cv_path) }}" target="_blank"
                                   class="flex items-center gap-1.5 px-4 py-2 bg-[#214371] hover:bg-[#1a3560] text-white text-xs font-semibold rounded-lg transition-colors flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Ver / Descargar
                                </a>
                            </div>
                        </div>
                        @else
                        <div class="mt-5 pt-5 border-t border-gray-100">
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Currículum Vitae</p>
                            <p class="text-sm text-gray-400 italic">No adjuntó CV al registrarse</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Formulario de edición -->
                <div id="edit-mode" class="hidden bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-brand-green p-8">
                    <h3 class="text-lg font-semibold text-brand-dark mb-6">Editar datos del técnico</h3>
                    <form method="POST" action="{{ route('admin.tecnico.update', $tecnico->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre</label>
                                <input type="text" name="name" value="{{ old('name', $tecnico->name) }}" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green">
                                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Apellidos</label>
                                <input type="text" name="apellidos" value="{{ old('apellidos', $perfil->apellidos ?? '') }}" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green">
                                @error('apellidos')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                <input type="email" name="email" value="{{ old('email', $tecnico->email) }}" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green">
                                @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">DNI / NIE</label>
                                <input type="text" name="dni_nie" value="{{ old('dni_nie', $perfil->dni_nie ?? '') }}" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green">
                                @error('dni_nie')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono', $perfil->telefono ?? '') }}" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green">
                                @error('telefono')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Dirección</label>
                                <input type="text" name="direccion" value="{{ old('direccion', $perfil->direccion ?? '') }}" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green">
                                @error('direccion')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Experiencia</label>
                                <textarea name="experiencia" rows="3"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green resize-none">{{ old('experiencia', $perfil->experiencia ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button type="submit" class="px-6 py-2.5 bg-brand-green hover:bg-brand-green-dark text-white text-sm font-medium rounded-xl transition-colors">
                                Guardar cambios
                            </button>
                            <button type="button" onclick="toggleEdit()" class="px-6 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Órdenes asignadas -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                    <h3 class="text-xl font-medium text-brand-dark mb-4">
                        Órdenes asignadas
                        <span class="text-sm font-normal text-gray-400 ml-2">{{ $ordenes->count() }} en total</span>
                    </h3>
                    <div class="space-y-3">
                        @forelse($ordenes as $orden)
                        @php
                            $bg = match($orden->estado) {
                                'finalizada'             => 'bg-green-100 text-green-700',
                                'cancelada'              => 'bg-red-100 text-red-600',
                                'en_camino','en_proceso' => 'bg-blue-100 text-blue-700',
                                default                  => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <div class="border border-gray-100 rounded-xl p-4 flex justify-between items-center hover:border-brand-green transition-colors">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase">#OT-{{ $orden->id }}</span>
                                <h4 class="text-base font-medium text-brand-dark">{{ $orden->titulo }}</h4>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $orden->cliente->nombre ?? '—' }} · {{ $orden->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $bg }}">
                                    {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                                </span>
                                <a href="{{ route('admin.orden.show', $orden->id) }}" class="text-[#1D4ED8] hover:underline text-sm font-medium">Ver</a>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-6">No tiene órdenes asignadas.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Valoraciones recibidas del técnico -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="text-xl font-medium text-brand-dark">Valoraciones recibidas</h3>
                            <p class="text-sm text-gray-400 mt-0.5">Opiniones de clientes sobre este técnico</p>
                        </div>
                        <div class="text-right">
                            <x-star-rating :rating="$ratingAvg" :count="$ratingCount" size="md" />
                        </div>
                    </div>

                    @if($valoraciones->isEmpty())
                        <div class="text-center py-8">
                            <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <p class="text-sm text-gray-400">Este técnico aún no tiene valoraciones</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($valoraciones as $val)
                            <div class="border border-gray-100 rounded-xl p-4 hover:border-brand-green transition-colors">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <x-star-rating :rating="$val->satisfaccion" :count="0" size="sm" />
                                        </div>
                                        <p class="text-sm font-medium text-brand-dark truncate">{{ $val->titulo }}</p>
                                        @if($val->comentario_cliente)
                                            <p class="text-sm text-gray-500 mt-1 italic">"{{ $val->comentario_cliente }}"</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1.5">
                                            Valorado por: <strong>{{ $val->cliente->nombre ?? '—' }}</strong>
                                            · {{ $val->updated_at->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    <span class="text-2xl font-bold flex-shrink-0 {{ $val->satisfaccion >= 4 ? 'text-green-500' : ($val->satisfaccion >= 3 ? 'text-yellow-500' : 'text-red-400') }}">
                                        {{ $val->satisfaccion }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Modal activar técnico (2 pasos) -->
@if(!$tecnico->is_approved)
<div id="modal-activar" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-gray-900/60" onclick="cerrarModalActivar()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8">

        {{-- Paso 1 --}}
        <div id="activar-paso-1">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Activar cuenta de técnico</h2>
                    <p class="text-sm text-gray-500">{{ $tecnico->name }} · {{ $tecnico->email }}</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-2">
                Al activar esta cuenta el técnico podrá iniciar sesión y recibir órdenes de trabajo. Se le enviará una notificación automáticamente.
            </p>
            <p class="text-sm font-medium text-gray-700 mb-6">¿Has revisado sus datos y currículum antes de continuar?</p>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="cerrarModalActivar()"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="button" onclick="activarPaso2()"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-[#214371] rounded-xl hover:bg-[#1a3560] transition-colors">
                    Sí, he revisado los datos →
                </button>
            </div>
        </div>

        {{-- Paso 2: confirmación final --}}
        <div id="activar-paso-2" class="hidden">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Confirmación final</h2>
                    <p class="text-sm text-gray-500">Esta acción activará la cuenta</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-6">
                <strong>{{ $tecnico->name }}</strong> recibirá acceso completo a la plataforma. ¿Confirmas la activación?
            </p>
            <form method="POST" action="{{ route('admin.users.validate', $tecnico->id) }}">
                @csrf
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="activarPaso1()"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                        ← Volver
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-xl transition-colors">
                        Activar técnico
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function cerrarModalActivar() {
        document.getElementById('modal-activar').classList.add('hidden');
        activarPaso1();
    }
    function activarPaso2() {
        document.getElementById('activar-paso-1').classList.add('hidden');
        document.getElementById('activar-paso-2').classList.remove('hidden');
    }
    function activarPaso1() {
        document.getElementById('activar-paso-2').classList.add('hidden');
        document.getElementById('activar-paso-1').classList.remove('hidden');
    }
</script>
@endif

<!-- Modal dar de baja -->
<div id="modal-baja" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-gray-900/60" onclick="document.getElementById('modal-baja').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">¿Dar de baja a este técnico?</h2>
                <p class="text-sm text-gray-500">{{ $tecnico->name }}</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 mb-6">
            El técnico perderá el acceso a la plataforma. Sus órdenes e historial se conservarán. Podrás reactivar la cuenta desde el panel de usuarios.
        </p>
        <form method="POST" action="{{ route('admin.tecnico.destroy', $tecnico->id) }}">
            @csrf
            @method('DELETE')
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="document.getElementById('modal-baja').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors">
                    Sí, dar de baja
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleEdit() {
    document.getElementById('view-mode').classList.toggle('hidden');
    document.getElementById('edit-mode').classList.toggle('hidden');
}

@if($errors->any())
    toggleEdit();
@endif
</script>
@endsection
