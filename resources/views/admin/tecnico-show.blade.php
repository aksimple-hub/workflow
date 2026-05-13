@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-[#1E3A5F] hover:bg-gray-100 transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <a href="{{ route('admin.tecnicos') }}" class="text-gray-400 hover:text-[#10B981] transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <h1 class="text-xl md:text-2xl font-medium text-[#1E3A5F]">Perfil del Técnico</h1>
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
                <span class="px-4 py-2 bg-red-50 border border-red-200 rounded-xl text-sm font-medium text-red-400">
                    Cuenta inactiva
                </span>
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
                    <div class="w-20 h-20 rounded-full bg-[#1E3A5F] text-white flex items-center justify-center font-bold text-3xl flex-shrink-0">
                        {{ substr($tecnico->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <h2 class="text-3xl font-medium text-[#1E3A5F]">{{ $tecnico->name }} {{ $perfil->apellidos ?? '' }}</h2>
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
                    </div>
                </div>

                <!-- Formulario de edición -->
                <div id="edit-mode" class="hidden bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-[#10B981] p-8">
                    <h3 class="text-lg font-semibold text-[#1E3A5F] mb-6">Editar datos del técnico</h3>
                    <form method="POST" action="{{ route('admin.tecnico.update', $tecnico->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre</label>
                                <input type="text" name="name" value="{{ old('name', $tecnico->name) }}" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#10B981] focus:ring-1 focus:ring-[#10B981]">
                                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Apellidos</label>
                                <input type="text" name="apellidos" value="{{ old('apellidos', $perfil->apellidos ?? '') }}" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#10B981] focus:ring-1 focus:ring-[#10B981]">
                                @error('apellidos')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                <input type="email" name="email" value="{{ old('email', $tecnico->email) }}" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#10B981] focus:ring-1 focus:ring-[#10B981]">
                                @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">DNI / NIE</label>
                                <input type="text" name="dni_nie" value="{{ old('dni_nie', $perfil->dni_nie ?? '') }}" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#10B981] focus:ring-1 focus:ring-[#10B981]">
                                @error('dni_nie')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono', $perfil->telefono ?? '') }}" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#10B981] focus:ring-1 focus:ring-[#10B981]">
                                @error('telefono')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Dirección</label>
                                <input type="text" name="direccion" value="{{ old('direccion', $perfil->direccion ?? '') }}" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#10B981] focus:ring-1 focus:ring-[#10B981]">
                                @error('direccion')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Experiencia</label>
                                <textarea name="experiencia" rows="3"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#10B981] focus:ring-1 focus:ring-[#10B981] resize-none">{{ old('experiencia', $perfil->experiencia ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button type="submit" class="px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-medium rounded-xl transition-colors">
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
                    <h3 class="text-xl font-medium text-[#1E3A5F] mb-4">
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
                        <div class="border border-gray-100 rounded-xl p-4 flex justify-between items-center hover:border-[#10B981] transition-colors">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase">#OT-{{ $orden->id }}</span>
                                <h4 class="text-base font-medium text-[#1E3A5F]">{{ $orden->titulo }}</h4>
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

            </div>
        </main>
    </div>
</div>

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
