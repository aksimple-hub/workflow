@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3 min-w-0">
                <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-brand-dark hover:bg-gray-100 transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-2xl md:text-4xl font-medium text-brand-dark">Nueva Solicitud</h1>
                    <p class="text-sm md:text-base text-gray-500 mt-0.5 hidden sm:block">Registra una nueva petición de servicio</p>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="flex-shrink-0 text-brand-dark hover:underline font-medium text-sm">
                Volver
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
                    <div class="w-8 h-8 rounded-full bg-brand-green flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-brand-dark">Paso 1: Confirmar Dirección de Domicilio</h2>
                </div>

                {{-- Vista lectura --}}
                <div id="address-display" class="bg-[#F5F7FA] rounded-xl px-5 py-4 flex items-center justify-between">
                    <div>
                        <p id="address-text" class="text-sm font-medium text-brand-dark">
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
                        class="w-full bg-[#F5F7FA] border-2 border-brand-green rounded-xl px-4 py-3 focus:outline-none text-sm">
                    <div class="flex gap-2">
                        <button type="button" onclick="confirmAddressEdit()"
                            class="text-sm bg-brand-green hover:bg-brand-green-dark text-white px-4 py-2 rounded-lg transition-colors font-medium">
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
                    <p class="text-sm font-semibold text-brand-dark">Te quedan {{ $restantes }} solicitudes para hoy</p>
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
                <form id="solicitud-form" action="{{ route('ordenes.store') }}" method="POST" enctype="multipart/form-data" {{ $hoyCount >= 3 ? 'onsubmit=return false' : '' }}>
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Título (Ocupa 2 columnas) -->
                        <div class="col-span-2">
                            <label for="titulo" class="block text-sm font-medium text-brand-dark mb-2">Asunto de la solicitud *</label>
                            <input type="text" id="titulo" name="titulo" required
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-brand-green rounded-xl px-4 py-3 focus:outline-none transition-colors text-base"
                                placeholder="Ej: Falla en el sistema de refrigeración">
                        </div>

                        <!-- Prioridad -->
                        <div>
                            <label for="prioridad" class="block text-sm font-medium text-brand-dark mb-2">Prioridad Estimada *</label>
                            <select id="prioridad" name="prioridad" required
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-brand-green rounded-xl px-4 py-3 focus:outline-none transition-colors text-base appearance-none">
                                <option value="" disabled selected>Selecciona una opción...</option>
                                <option value="baja">Baja - Puede esperar</option>
                                <option value="media">Media - Necesita atención pronta</option>
                                <option value="alta">Alta - Urgente</option>
                            </select>
                        </div>

                        <!-- Tipo de Servicio (Simulado, no está en la BD actual pero es común) -->
                        <div>
                            <label for="tipo_servicio" class="block text-sm font-medium text-brand-dark mb-2">Tipo de Servicio</label>
                            <select id="tipo_servicio" name="tipo_servicio"
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-brand-green rounded-xl px-4 py-3 focus:outline-none transition-colors text-base appearance-none">
                                <option value="mantenimiento">Mantenimiento Preventivo</option>
                                <option value="reparacion">Reparación de Avería</option>
                                <option value="instalacion">Nueva Instalación</option>
                            </select>
                        </div>

                        <!-- Descripción (Ocupa 2 columnas) -->
                        <div class="col-span-2">
                            <label for="descripcion" class="block text-sm font-medium text-brand-dark mb-2">Descripción detallada *</label>
                            <textarea id="descripcion" name="descripcion" required rows="6"
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-brand-green rounded-xl p-4 resize-none focus:outline-none transition-colors text-base"
                                placeholder="Por favor, describe con detalle el problema o la necesidad..."></textarea>
                        </div>

                        <!-- Fotos de la avería -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-brand-dark mb-2">
                                Fotos de la avería <span class="text-gray-400 font-normal">(opcional, máx. 5)</span>
                            </label>

                            {{-- Zona de drop --}}
                            <div id="drop-zone"
                                onclick="document.getElementById('fotos-input').click()"
                                class="border-2 border-dashed border-gray-200 hover:border-brand-green rounded-xl p-6 text-center cursor-pointer transition-colors group">
                                <svg class="w-8 h-8 text-gray-300 group-hover:text-brand-green mx-auto mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-gray-400 group-hover:text-brand-green transition-colors">Haz clic o arrastra fotos aquí</p>
                                <p class="text-xs text-gray-300 mt-1">JPG, PNG o WEBP · Máx. 5 MB por foto</p>
                            </div>
                            <input type="file" id="fotos-input" name="fotos[]"
                                accept="image/jpeg,image/png,image/webp"
                                multiple class="hidden">

                            {{-- Contador --}}
                            <p id="fotos-counter" class="text-xs text-gray-400 mt-2"></p>

                            {{-- Preview grid --}}
                            <div id="fotos-preview" class="grid grid-cols-3 sm:grid-cols-5 gap-2 mt-2 hidden"></div>

                            @error('fotos')   <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            @error('fotos.*') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Separador cita -->
                        <div class="col-span-2 border-t border-gray-100 pt-4">
                            <p class="text-sm font-semibold text-brand-dark mb-1">Preferencia de horario</p>
                            <p class="text-xs text-gray-500">El administrador asignará la fecha definitiva de la visita</p>
                        </div>

                        <!-- Horario de preferencia -->
                        <div class="col-span-2">
                            <label for="horario_preferido" class="block text-sm font-medium text-brand-dark mb-2">Horario de preferencia *</label>
                            <select id="horario_preferido" name="horario_preferido" required
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-brand-green rounded-xl px-4 py-3 focus:outline-none transition-colors text-base appearance-none">
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

                    <script>
                    const MAX_FOTOS = 5;
                    let allFiles = []; // acumula los File objects seleccionados

                    function renderPreviews() {
                        const preview = document.getElementById('fotos-preview');
                        const dropZone = document.getElementById('drop-zone');
                        const counter = document.getElementById('fotos-counter');

                        preview.innerHTML = '';

                        if (!allFiles.length) {
                            preview.classList.add('hidden');
                            dropZone.classList.remove('hidden');
                            counter.textContent = '';
                            return;
                        }

                        preview.classList.remove('hidden');
                        counter.textContent = `${allFiles.length} / ${MAX_FOTOS} fotos seleccionadas`;

                        // Ocultar drop zone si ya hay 5
                        if (allFiles.length >= MAX_FOTOS) {
                            dropZone.classList.add('hidden');
                        } else {
                            dropZone.classList.remove('hidden');
                        }

                        allFiles.forEach((file, index) => {
                            const reader = new FileReader();
                            reader.onload = e => {
                                const div = document.createElement('div');
                                div.className = 'relative aspect-square rounded-xl overflow-hidden border border-gray-100 group';
                                div.innerHTML = `
                                    <img src="${e.target.result}" class="w-full h-full object-cover">
                                    <button type="button" onclick="removeFile(${index})"
                                        class="absolute top-1 right-1 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>`;
                                preview.appendChild(div);
                            };
                            reader.readAsDataURL(file);
                        });
                    }

                    function addFiles(newFiles) {
                        const disponibles = MAX_FOTOS - allFiles.length;
                        if (disponibles <= 0) return;

                        Array.from(newFiles).slice(0, disponibles).forEach(file => {
                            // Evitar duplicados por nombre + tamaño
                            const existe = allFiles.some(f => f.name === file.name && f.size === file.size);
                            if (!existe) allFiles.push(file);
                        });

                        syncInput();
                        renderPreviews();
                    }

                    function removeFile(index) {
                        allFiles.splice(index, 1);
                        syncInput();
                        renderPreviews();
                    }

                    document.getElementById('fotos-input').addEventListener('change', function() {
                        addFiles(this.files);
                        this.value = '';
                    });

                    document.getElementById('solicitud-form').addEventListener('submit', function(e) {
                        if (allFiles.length === 0) return;
                        e.preventDefault();

                        const form = this;
                        const fd = new FormData(form);
                        fd.delete('fotos[]');
                        allFiles.forEach(f => fd.append('fotos[]', f));

                        const btn = form.querySelector('button[type=submit]');
                        if (btn) { btn.disabled = true; btn.textContent = 'Enviando...'; }

                        fetch(form.action, { method: 'POST', body: fd })
                            .then(r => { window.location.href = r.url; })
                            .catch(() => { if (btn) { btn.disabled = false; btn.textContent = 'Enviar Solicitud'; } });
                    });

                    const dropZone = document.getElementById('drop-zone');
                    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-brand-green','bg-[#F0FDF4]'); });
                    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-brand-green','bg-[#F0FDF4]'));
                    dropZone.addEventListener('drop', e => {
                        e.preventDefault();
                        dropZone.classList.remove('border-brand-green','bg-[#F0FDF4]');
                        addFiles(e.dataTransfer.files);
                    });
                    </script>

                    <div class="mt-8 flex items-center justify-between">
                        <p class="text-xs text-gray-400">
                            Solicitudes usadas hoy: <span class="font-semibold {{ $hoyCount >= 3 ? 'text-red-500' : 'text-brand-dark' }}">{{ $hoyCount }} / 3</span>
                        </p>
                        <button type="submit" {{ $hoyCount >= 3 ? 'disabled' : '' }}
                            class="{{ $hoyCount >= 3 ? 'bg-gray-300 cursor-not-allowed text-gray-500' : 'bg-brand-green hover:bg-brand-green-dark text-white shadow-[0px_2px_8px_rgba(16,185,129,0.25)]' }} px-8 py-4 rounded-xl transition-all font-medium text-base">
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
