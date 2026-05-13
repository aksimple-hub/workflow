@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-medium text-[#1E3A5F]">Nueva Solicitud</h1>
                <p class="text-base text-gray-500 mt-1">Registra una nueva petición de servicio</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-[#1E3A5F] hover:underline font-medium text-sm">
                Volver a mis solicitudes
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-4xl mx-auto space-y-4">

            {{-- Paso 1: Confirmar Dirección --}}
            @php
                $direccionActual = ($cliente && $cliente->direccion && $cliente->direccion !== 'N/A')
                    ? $cliente->direccion
                    : '';
            @endphp
            <div class="bg-white p-6 rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-[#10B981] flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-[#1E3A5F]">Paso 1: Confirmar Dirección de Domicilio</h2>
                </div>

                {{-- Vista lectura --}}
                <div id="address-display" class="bg-[#F5F7FA] rounded-xl px-5 py-4 flex items-center justify-between">
                    <div>
                        <p id="address-text" class="text-sm font-medium text-[#1E3A5F]">
                            {{ $direccionActual ?: 'Sin dirección registrada' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">Dirección de servicio</p>
                    </div>
                    <button type="button" onclick="openAddressEdit()"
                        class="text-sm font-medium text-[#1D4ED8] hover:underline flex-shrink-0 ml-4">
                        Cambiar dirección
                    </button>
                </div>

                {{-- Vista edición --}}
                <div id="address-edit" class="hidden mt-3 space-y-2">
                    <input type="text" id="direccion_input"
                        value="{{ old('direccion_servicio', $direccionActual) }}"
                        placeholder="Ej: Calle Mayor 45, 3º B, 28013 Madrid, España"
                        class="w-full bg-[#F5F7FA] border-2 border-[#10B981] rounded-xl px-4 py-3 focus:outline-none text-sm">
                    <div class="flex gap-2">
                        <button type="button" onclick="confirmAddressEdit()"
                            class="text-sm bg-[#10B981] hover:bg-[#059669] text-white px-4 py-2 rounded-lg transition-colors font-medium">
                            Confirmar
                        </button>
                        <button type="button" onclick="cancelAddressEdit()"
                            class="text-sm text-gray-500 hover:bg-gray-100 px-4 py-2 rounded-lg transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>

                {{-- Campo oculto que se envía con el formulario --}}
                <input type="hidden" name="direccion_servicio" id="direccion_servicio"
                    value="{{ old('direccion_servicio', $direccionActual) }}">
            </div>

            {{-- Aviso de límite diario --}}
            @php $restantes = 3 - $hoyCount; @endphp

            @if($hoyCount >= 3)
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                <div>
                    <p class="text-sm font-semibold text-red-800">Límite diario alcanzado</p>
                    <p class="text-sm text-red-700 mt-0.5">Has enviado las 3 solicitudes permitidas hoy. Podrás crear nuevas solicitudes mañana.</p>
                </div>
            </div>
            @elseif($hoyCount === 2)
            <div class="bg-[#FEF3C7] border border-[#FDE68A] rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-[#D97706] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <div>
                    <p class="text-sm font-semibold text-[#92400E]">Solo te queda 1 solicitud para hoy</p>
                    <p class="text-sm text-[#92400E] mt-0.5">Has usado {{ $hoyCount }} de 3 solicitudes permitidas hoy.</p>
                </div>
            </div>
            @elseif($hoyCount === 1)
            <div class="bg-[#DBEAFE] border border-[#BFDBFE] rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-[#1D4ED8] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-sm font-semibold text-[#1E3A5F]">Te quedan {{ $restantes }} solicitudes para hoy</p>
                    <p class="text-sm text-[#1D4ED8] mt-0.5">Has usado {{ $hoyCount }} de 3 solicitudes permitidas hoy.</p>
                </div>
            </div>
            @endif

            @error('limite')
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                <p class="text-sm font-medium text-red-800">{{ $message }}</p>
            </div>
            @enderror

            <div class="bg-white p-8 rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100">
                <form action="{{ route('ordenes.store') }}" method="POST" {{ $hoyCount >= 3 ? 'onsubmit=return false' : '' }}>
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Título (Ocupa 2 columnas) -->
                        <div class="col-span-2">
                            <label for="titulo" class="block text-sm font-medium text-[#1E3A5F] mb-2">Asunto de la solicitud *</label>
                            <input type="text" id="titulo" name="titulo" required
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl px-4 py-3 focus:outline-none transition-colors text-base"
                                placeholder="Ej: Falla en el sistema de refrigeración">
                        </div>

                        <!-- Prioridad -->
                        <div>
                            <label for="prioridad" class="block text-sm font-medium text-[#1E3A5F] mb-2">Prioridad Estimada *</label>
                            <select id="prioridad" name="prioridad" required
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl px-4 py-3 focus:outline-none transition-colors text-base appearance-none">
                                <option value="" disabled selected>Selecciona una opción...</option>
                                <option value="baja">Baja - Puede esperar</option>
                                <option value="media">Media - Necesita atención pronta</option>
                                <option value="alta">Alta - Urgente</option>
                            </select>
                        </div>

                        <!-- Tipo de Servicio (Simulado, no está en la BD actual pero es común) -->
                        <div>
                            <label for="tipo_servicio" class="block text-sm font-medium text-[#1E3A5F] mb-2">Tipo de Servicio</label>
                            <select id="tipo_servicio" name="tipo_servicio"
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl px-4 py-3 focus:outline-none transition-colors text-base appearance-none">
                                <option value="mantenimiento">Mantenimiento Preventivo</option>
                                <option value="reparacion">Reparación de Avería</option>
                                <option value="instalacion">Nueva Instalación</option>
                            </select>
                        </div>

                        <!-- Descripción (Ocupa 2 columnas) -->
                        <div class="col-span-2">
                            <label for="descripcion" class="block text-sm font-medium text-[#1E3A5F] mb-2">Descripción detallada *</label>
                            <textarea id="descripcion" name="descripcion" required rows="6"
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl p-4 resize-none focus:outline-none transition-colors text-base"
                                placeholder="Por favor, describe con detalle el problema o la necesidad..."></textarea>
                        </div>

                        <!-- Separador cita -->
                        <div class="col-span-2 border-t border-gray-100 pt-4">
                            <p class="text-sm font-semibold text-[#1E3A5F] mb-1">Preferencia de horario</p>
                            <p class="text-xs text-gray-500">El administrador asignará la fecha definitiva de la visita</p>
                        </div>

                        <!-- Horario de preferencia -->
                        <div class="col-span-2">
                            <label for="horario_preferido" class="block text-sm font-medium text-[#1E3A5F] mb-2">Horario de preferencia *</label>
                            <select id="horario_preferido" name="horario_preferido" required
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl px-4 py-3 focus:outline-none transition-colors text-base appearance-none">
                                <option value="" disabled selected>Selecciona un horario...</option>
                                <option value="mañana" {{ old('horario_preferido') === 'mañana' ? 'selected' : '' }}>Mañana (8:00 – 13:00)</option>
                                <option value="mediodia" {{ old('horario_preferido') === 'mediodia' ? 'selected' : '' }}>Mediodía (13:00 – 16:00)</option>
                                <option value="tarde" {{ old('horario_preferido') === 'tarde' ? 'selected' : '' }}>Tarde (16:00 – 21:00)</option>
                                <option value="sin_preferencia" {{ old('horario_preferido') === 'sin_preferencia' ? 'selected' : '' }}>Sin preferencia</option>
                            </select>
                        </div>
                    </div>

                    <script>
                        function openAddressEdit() {
                            document.getElementById('address-display').classList.add('hidden');
                            document.getElementById('address-edit').classList.remove('hidden');
                            document.getElementById('direccion_input').focus();
                        }

                        function confirmAddressEdit() {
                            const val = document.getElementById('direccion_input').value.trim();
                            document.getElementById('direccion_servicio').value = val;
                            document.getElementById('address-text').textContent = val || 'Sin dirección registrada';
                            document.getElementById('address-edit').classList.add('hidden');
                            document.getElementById('address-display').classList.remove('hidden');
                        }

                        function cancelAddressEdit() {
                            document.getElementById('address-edit').classList.add('hidden');
                            document.getElementById('address-display').classList.remove('hidden');
                        }
                    </script>

                    <div class="mt-8 flex items-center justify-between">
                        <p class="text-xs text-gray-400">
                            Solicitudes usadas hoy: <span class="font-semibold {{ $hoyCount >= 3 ? 'text-red-500' : 'text-[#1E3A5F]' }}">{{ $hoyCount }} / 3</span>
                        </p>
                        <button type="submit" {{ $hoyCount >= 3 ? 'disabled' : '' }}
                            class="{{ $hoyCount >= 3 ? 'bg-gray-300 cursor-not-allowed text-gray-500' : 'bg-[#10B981] hover:bg-[#059669] text-white shadow-[0px_2px_8px_rgba(16,185,129,0.25)]' }} px-8 py-4 rounded-xl transition-all font-medium text-base">
                            Enviar Solicitud
                        </button>
                    </div>
                </form>
            </div>

            </div>
        </main>
    </div>
</div>
@endsection
