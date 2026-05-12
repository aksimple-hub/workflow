@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center flex-shrink-0">
            <div>
                <h1 class="text-3xl font-medium text-[#1E3A5F]">
                    Finalizar Servicio #OT-{{ str_pad($orden->id, 3, '0', STR_PAD_LEFT) }}
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $orden->cliente->nombre ?? 'Cliente' }}
                    @if($orden->cliente->direccion && $orden->cliente->direccion !== 'N/A')
                        · {{ $orden->cliente->direccion }}
                    @endif
                </p>
            </div>
            <a href="{{ route('ordenes.show', $orden) }}"
               class="flex items-center gap-2 text-sm font-medium text-[#1E3A5F] border border-gray-200 hover:border-[#1E3A5F] px-4 py-2 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </header>

        {{-- Main --}}
        <main class="flex-1 overflow-y-auto p-6">
            <form action="{{ route('ordenes.cerrar', $orden) }}" method="POST" id="form-cierre">
                @csrf

                {{-- Inputs ocultos para firma y tareas --}}
                <input type="hidden" name="firma_base64" id="firma_base64">
                <input type="hidden" name="tareas_json" id="tareas_json">

                <div class="grid grid-cols-5 gap-5 max-w-7xl mx-auto">

                    {{-- ====== COLUMNA IZQUIERDA (3/5) ====== --}}
                    <div class="col-span-3 space-y-5">

                        {{-- Tareas Realizadas --}}
                        <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                            <h2 class="text-base font-semibold text-[#1E3A5F] mb-1">Tareas Realizadas</h2>
                            <p class="text-xs text-gray-400 mb-4">Marca las tareas completadas durante el servicio</p>

                            <div id="tareas-list" class="space-y-2">
                                @foreach(['Revisión visual del sistema completo','Prueba de funcionamiento','Verificación de presión'] as $tarea)
                                <label class="flex items-center gap-3 bg-[#F5F7FA] rounded-xl px-4 py-3 cursor-pointer group">
                                    <input type="checkbox" class="tarea-check w-4 h-4 accent-[#10B981]" data-nombre="{{ $tarea }}" checked>
                                    <span class="text-sm text-gray-700 group-hover:text-[#1E3A5F] transition-colors">{{ $tarea }}</span>
                                </label>
                                @endforeach
                            </div>

                            <button type="button" onclick="addTarea()"
                                class="mt-3 w-full border-2 border-dashed border-gray-200 hover:border-[#10B981] text-gray-400 hover:text-[#10B981] rounded-xl py-2.5 text-sm font-medium transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Añadir tarea adicional
                            </button>
                        </div>

                        {{-- Material Utilizado --}}
                        <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                            <h2 class="text-base font-semibold text-[#1E3A5F] mb-1">Material Utilizado</h2>
                            <p class="text-xs text-gray-400 mb-4">Registra los materiales empleados</p>

                            <div id="materiales-list" class="space-y-2"></div>

                            <button type="button" onclick="addMaterial()"
                                class="mt-3 w-full border-2 border-dashed border-gray-200 hover:border-[#10B981] text-gray-400 hover:text-[#10B981] rounded-xl py-2.5 text-sm font-medium transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Añadir material
                            </button>
                        </div>

                        {{-- Resumen --}}
                        <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                            <h2 class="text-base font-semibold text-[#1E3A5F] mb-4">Resumen</h2>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-500">Hora de inicio:</span>
                                    <input type="time" name="hora_inicio" id="hora_inicio"
                                        class="text-sm font-medium text-[#1E3A5F] bg-transparent border-none outline-none text-right"
                                        onchange="calcularTiempo()">
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-500">Hora de finalización:</span>
                                    <input type="time" name="hora_fin" id="hora_fin"
                                        class="text-sm font-medium text-[#1E3A5F] bg-transparent border-none outline-none text-right"
                                        onchange="calcularTiempo()">
                                </div>
                                <div class="flex items-center justify-between py-2 bg-[#D1FAE5] rounded-xl px-3">
                                    <span class="text-sm font-medium text-[#065F46]">Tiempo total:</span>
                                    <span id="tiempo-total" class="text-sm font-bold text-[#10B981]">—</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-t border-gray-100 mt-1">
                                    <span class="text-sm text-gray-500">Materiales usados:</span>
                                    <span id="resumen-materiales" class="text-sm font-medium text-[#1E3A5F]">0 unidades</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- ====== COLUMNA DERECHA (2/5) ====== --}}
                    <div class="col-span-2 space-y-5">

                        {{-- Comentarios del Técnico --}}
                        <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                            <h2 class="text-base font-semibold text-[#1E3A5F] mb-1">Comentarios del Técnico</h2>
                            <p class="text-xs text-gray-400 mb-3">Describe el trabajo realizado y cualquier observación relevante</p>
                            <textarea name="observaciones" required rows="5"
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl p-3 resize-none outline-none transition-colors text-sm text-gray-700"
                                placeholder="Escribe aquí los detalles del servicio, observaciones, recomendaciones para el cliente..."></textarea>
                            <p class="text-xs text-gray-400 mt-2">
                                Estos comentarios serán visibles para el <span class="text-[#10B981] font-medium">cliente</span> y el <span class="text-[#1D4ED8] font-medium">administrador</span>
                            </p>
                            @error('observaciones')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Recomendaciones para el Cliente --}}
                        <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                            <h2 class="text-base font-semibold text-[#1E3A5F] mb-1">Recomendaciones para el Cliente</h2>
                            <p class="text-xs text-gray-400 mb-3">Sugerencias de mantenimiento o acciones preventivas</p>
                            <textarea name="recomendaciones" rows="3"
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl p-3 resize-none outline-none transition-colors text-sm text-gray-700"
                                placeholder="Ej: Recomiendo realizar una revisión anual de la caldera..."></textarea>
                        </div>

                        {{-- Satisfacción del Cliente --}}
                        <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5">
                            <h2 class="text-base font-semibold text-[#1E3A5F] mb-1">Satisfacción del Cliente</h2>
                            <p class="text-xs text-gray-400 mb-4">¿El cliente está satisfecho con el servicio?</p>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach([['satisfecho','😊','Satisfecho','#10B981'],['neutral','😐','Neutral','#D97706'],['insatisfecho','😤','Insatisfecho','#EF4444']] as [$val,$emoji,$label,$color])
                                <label class="satisfaccion-option cursor-pointer" data-color="{{ $color }}">
                                    <input type="radio" name="satisfaccion" value="{{ $val }}" class="sr-only">
                                    <div class="flex flex-col items-center gap-1.5 border-2 border-gray-200 rounded-xl py-3 transition-all hover:border-gray-300 option-box">
                                        <span class="text-2xl">{{ $emoji }}</span>
                                        <span class="text-xs font-medium text-gray-600 option-label">{{ $label }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Firma del Cliente --}}
                        <div class="bg-[#EFF6FF] rounded-xl border border-[#BFDBFE] p-5">
                            <h2 class="text-base font-semibold text-[#1E3A5F] mb-1">Firma del Cliente</h2>
                            <p class="text-xs text-[#1D4ED8] mb-3">Solicita la firma digital del cliente para confirmar la finalización</p>
                            <div class="bg-white rounded-xl border-2 border-dashed border-[#BFDBFE] relative overflow-hidden" style="height:120px">
                                <canvas id="firma-canvas" class="w-full h-full cursor-crosshair touch-none"></canvas>
                                <span id="firma-placeholder" class="absolute inset-0 flex items-center justify-center text-xs text-gray-400 pointer-events-none">
                                    Área de firma digital
                                </span>
                            </div>
                            <div class="flex gap-2 mt-2">
                                <button type="button" onclick="limpiarFirma()"
                                    class="flex-1 text-xs text-gray-500 border border-gray-200 hover:bg-gray-50 rounded-lg py-2 transition-colors">
                                    Limpiar
                                </button>
                                <button type="button" onclick="solicitarFirma()"
                                    class="flex-1 text-xs font-medium text-[#1D4ED8] border border-[#BFDBFE] hover:bg-[#DBEAFE] rounded-lg py-2 transition-colors">
                                    Solicitar Firma
                                </button>
                            </div>
                        </div>

                        {{-- Botón final --}}
                        <button type="submit"
                            class="w-full bg-[#10B981] hover:bg-[#059669] text-white py-4 rounded-xl font-semibold text-base shadow-[0px_4px_12px_rgba(16,185,129,0.3)] transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Firmar y Cerrar Orden
                        </button>

                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
// ─── Tareas ───────────────────────────────────────────────────────────────────
function addTarea() {
    const nombre = prompt('Nombre de la tarea:');
    if (!nombre || !nombre.trim()) return;
    const list = document.getElementById('tareas-list');
    const label = document.createElement('label');
    label.className = 'flex items-center gap-3 bg-[#F5F7FA] rounded-xl px-4 py-3 cursor-pointer group';
    label.innerHTML = `
        <input type="checkbox" class="tarea-check w-4 h-4 accent-[#10B981]" data-nombre="${nombre.trim()}" checked>
        <span class="text-sm text-gray-700 flex-1">${nombre.trim()}</span>
        <button type="button" onclick="this.closest('label').remove()" class="text-gray-300 hover:text-red-400 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
    list.appendChild(label);
}

// ─── Materiales ───────────────────────────────────────────────────────────────
let matIndex = 0;
function addMaterial(nombre = '') {
    const list = document.getElementById('materiales-list');
    const idx = matIndex++;
    const div = document.createElement('div');
    div.className = 'material-row flex items-center gap-3 bg-[#F5F7FA] rounded-xl px-4 py-2.5';
    div.innerHTML = `
        <input type="text" name="materiales[${idx}][nombre]" placeholder="Nombre del material"
            value="${nombre}"
            class="flex-1 bg-transparent text-sm text-[#1E3A5F] outline-none placeholder-gray-400">
        <div class="flex items-center gap-2 flex-shrink-0">
            <button type="button" onclick="changeQty(this,-1)" class="w-6 h-6 rounded-full border border-gray-300 hover:border-red-400 hover:text-red-400 flex items-center justify-center text-gray-500 transition-colors text-sm font-bold">−</button>
            <input type="number" name="materiales[${idx}][cantidad]" value="1" min="1"
                class="w-8 text-center text-sm font-medium text-[#1E3A5F] bg-transparent outline-none qty-input">
            <button type="button" onclick="changeQty(this,1)" class="w-6 h-6 rounded-full border border-gray-300 hover:border-[#10B981] hover:text-[#10B981] flex items-center justify-center text-gray-500 transition-colors text-sm font-bold">+</button>
        </div>
        <button type="button" onclick="removeMaterial(this)" class="text-gray-300 hover:text-red-400 transition-colors ml-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
    list.appendChild(div);
    div.querySelector('input[type=text]').focus();
    actualizarResumenMateriales();
}

function changeQty(btn, delta) {
    const input = btn.closest('div').querySelector('.qty-input');
    input.value = Math.max(1, parseInt(input.value || 1) + delta);
    actualizarResumenMateriales();
}

function removeMaterial(btn) {
    btn.closest('.material-row').remove();
    actualizarResumenMateriales();
}

function actualizarResumenMateriales() {
    const inputs = document.querySelectorAll('.qty-input');
    let total = 0;
    inputs.forEach(i => total += parseInt(i.value || 1));
    document.getElementById('resumen-materiales').textContent = total + ' unidad' + (total !== 1 ? 'es' : '');
}

document.addEventListener('input', e => { if (e.target.classList.contains('qty-input')) actualizarResumenMateriales(); });

// ─── Tiempo ───────────────────────────────────────────────────────────────────
function calcularTiempo() {
    const ini = document.getElementById('hora_inicio').value;
    const fin = document.getElementById('hora_fin').value;
    if (!ini || !fin) return;
    const [hi, mi] = ini.split(':').map(Number);
    const [hf, mf] = fin.split(':').map(Number);
    let mins = (hf * 60 + mf) - (hi * 60 + mi);
    if (mins <= 0) { document.getElementById('tiempo-total').textContent = '—'; return; }
    const h = Math.floor(mins / 60), m = mins % 60;
    document.getElementById('tiempo-total').textContent = (h ? h + 'h ' : '') + (m ? m + 'min' : '');
}

// ─── Satisfacción ─────────────────────────────────────────────────────────────
document.querySelectorAll('.satisfaccion-option').forEach(opt => {
    opt.addEventListener('click', () => {
        document.querySelectorAll('.satisfaccion-option .option-box').forEach(b => {
            b.classList.remove('border-[#10B981]','border-[#D97706]','border-[#EF4444]','bg-[#D1FAE5]','bg-[#FEF3C7]','bg-red-50');
            b.classList.add('border-gray-200');
        });
        const color = opt.dataset.color;
        const box = opt.querySelector('.option-box');
        box.classList.remove('border-gray-200');
        if (color === '#10B981') box.classList.add('border-[#10B981]', 'bg-[#D1FAE5]');
        if (color === '#D97706') box.classList.add('border-[#D97706]', 'bg-[#FEF3C7]');
        if (color === '#EF4444') box.classList.add('border-[#EF4444]', 'bg-red-50');
    });
});

// ─── Firma Canvas ─────────────────────────────────────────────────────────────
const canvas = document.getElementById('firma-canvas');
const ctx = canvas.getContext('2d');
let drawing = false, hasFirma = false;

function resizeCanvas() {
    const rect = canvas.parentElement.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = rect.height;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

function getPos(e) {
    const r = canvas.getBoundingClientRect();
    const src = e.touches ? e.touches[0] : e;
    return { x: src.clientX - r.left, y: src.clientY - r.top };
}

canvas.addEventListener('mousedown',  e => { drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); });
canvas.addEventListener('mousemove',  e => { if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.strokeStyle = '#1E3A5F'; ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.stroke(); hasFirma = true; document.getElementById('firma-placeholder').style.display = 'none'; });
canvas.addEventListener('mouseup',    () => drawing = false);
canvas.addEventListener('mouseleave', () => drawing = false);
canvas.addEventListener('touchstart', e => { e.preventDefault(); drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); }, { passive: false });
canvas.addEventListener('touchmove',  e => { e.preventDefault(); if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.strokeStyle = '#1E3A5F'; ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.stroke(); hasFirma = true; document.getElementById('firma-placeholder').style.display = 'none'; }, { passive: false });
canvas.addEventListener('touchend',   () => drawing = false);

function limpiarFirma() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    hasFirma = false;
    document.getElementById('firma-placeholder').style.display = 'flex';
    document.getElementById('firma_base64').value = '';
}

function solicitarFirma() {
    alert('Entrega el dispositivo al cliente para que firme en el área de arriba.');
}

// ─── Submit: serializar tareas y firma ────────────────────────────────────────
document.getElementById('form-cierre').addEventListener('submit', function() {
    // Tareas
    const tareas = [];
    document.querySelectorAll('.tarea-check').forEach(ch => {
        tareas.push({ nombre: ch.dataset.nombre, done: ch.checked });
    });
    document.getElementById('tareas_json').value = JSON.stringify(tareas);

    // Firma
    if (hasFirma) {
        document.getElementById('firma_base64').value = canvas.toDataURL('image/png');
    }
});
</script>
@endsection
