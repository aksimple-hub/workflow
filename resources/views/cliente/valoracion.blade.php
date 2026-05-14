@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center gap-3 flex-shrink-0 flex-wrap">
            <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-brand-dark hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl md:text-3xl font-medium text-brand-dark">
                    Valorar Servicio #OT-{{ str_pad($orden->id, 3, '0', STR_PAD_LEFT) }}
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $orden->titulo }}</p>
            </div>
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 text-sm font-medium text-brand-dark border border-gray-200 hover:border-brand-dark px-4 py-2 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </header>

        {{-- Main --}}
        <main class="flex-1 overflow-y-auto p-6">

            {{-- Banner de servicio finalizado --}}
            <div class="max-w-2xl mx-auto mb-6 bg-[#D1FAE5] border border-[#6EE7B7] rounded-2xl px-6 py-4 flex items-start gap-3">
                <svg class="w-6 h-6 text-brand-green flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-[#065F46]">¡El técnico ha finalizado el servicio!</p>
                    <p class="text-xs text-[#065F46] mt-0.5">Por favor, valora la atención recibida y firma la conformidad para cerrar la orden.</p>
                </div>
            </div>

            {{-- Resumen del servicio --}}
            @if($orden->observaciones || $orden->recomendaciones)
            <div class="max-w-2xl mx-auto mb-6 bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-5 space-y-4">
                <h2 class="text-sm font-semibold text-brand-dark">Informe del Técnico</h2>
                @if($orden->observaciones)
                <div>
                    <p class="text-xs text-gray-400 mb-1">Descripción del trabajo realizado</p>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $orden->observaciones }}</p>
                </div>
                @endif
                @if($orden->recomendaciones)
                <div class="border-t border-gray-100 pt-3">
                    <p class="text-xs text-gray-400 mb-1">Recomendaciones</p>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $orden->recomendaciones }}</p>
                </div>
                @endif
                @if($orden->tecnico)
                <div class="border-t border-gray-100 pt-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <p class="text-xs text-gray-500">Técnico: <span class="font-medium text-brand-dark">{{ $orden->tecnico->name }}</span></p>
                </div>
                @endif
            </div>
            @endif

            {{-- Formulario de valoración --}}
            <form action="{{ route('cliente.orden.valorar.submit', $orden) }}" method="POST" id="form-valoracion"
                  class="max-w-2xl mx-auto space-y-5">
                @csrf

                <input type="hidden" name="firma_base64" id="firma_base64">

                @if ($errors->any())
                <div class="p-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg text-sm">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Satisfacción --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6">
                    <h2 class="text-base font-semibold text-brand-dark mb-1">Satisfacción con el Servicio</h2>
                    <p class="text-xs text-gray-400 mb-5">¿Cómo valorarías la atención recibida?</p>

                    <div class="flex items-center justify-center gap-3 mb-3" id="star-rating">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" data-value="{{ $i }}"
                            class="star-btn transition-transform hover:scale-110 focus:outline-none"
                            onclick="setStars({{ $i }})">
                            <svg class="w-12 h-12 star-icon text-gray-200 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                        @endfor
                    </div>

                    <p id="star-label" class="text-center text-sm font-medium text-gray-400 mb-1">Toca una estrella para valorar</p>
                    <p id="star-sublabel" class="text-center text-xs text-gray-300"></p>
                    <input type="hidden" name="satisfaccion" id="satisfaccion-input" required>
                    @error('satisfaccion')
                        <p class="text-xs text-red-500 text-center mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Comentario del cliente --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6">
                    <h2 class="text-base font-semibold text-brand-dark mb-1">Comentario (opcional)</h2>
                    <p class="text-xs text-gray-400 mb-3">Cuéntanos tu experiencia con el servicio recibido</p>
                    <textarea name="comentario_cliente" rows="4"
                        class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-brand-green rounded-xl p-3 resize-none outline-none transition-colors text-sm text-gray-700"
                        placeholder="Ej: El técnico llegó puntual y resolvió el problema rápidamente...">{{ old('comentario_cliente') }}</textarea>
                </div>

                {{-- Firma del cliente --}}
                <div class="bg-[#EFF6FF] rounded-xl border border-[#BFDBFE] p-6">
                    <h2 class="text-base font-semibold text-brand-dark mb-1">Firma de Conformidad</h2>
                    <p class="text-xs text-[#1D4ED8] mb-4">Firma en el área de abajo para confirmar que el servicio se ha realizado correctamente</p>

                    <div class="bg-white rounded-xl border-2 border-dashed border-[#BFDBFE] relative overflow-hidden" style="height:140px">
                        <canvas id="firma-canvas" class="w-full h-full cursor-crosshair touch-none"></canvas>
                        <span id="firma-placeholder" class="absolute inset-0 flex items-center justify-center text-xs text-gray-400 pointer-events-none">
                            Firma aquí para confirmar la conformidad
                        </span>
                    </div>

                    <button type="button" onclick="limpiarFirma()"
                        class="mt-3 text-xs text-gray-500 border border-gray-200 hover:bg-gray-50 rounded-lg px-4 py-2 transition-colors">
                        Limpiar firma
                    </button>
                </div>

                {{-- Botón submit --}}
                <button type="submit" id="btn-submit"
                    class="w-full bg-brand-green hover:bg-brand-green-dark text-white py-4 rounded-xl font-semibold text-base shadow-[0px_4px_12px_rgba(16,185,129,0.3)] transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Enviar Valoración
                </button>
            </form>
        </main>
    </div>
</div>

<script>
// ─── Estrellas ────────────────────────────────────────────────────────────────
const starLabels    = ['', 'Muy insatisfecho', 'Insatisfecho', 'Neutral', 'Satisfecho', 'Muy satisfecho'];
const starSubLabels = ['', '😞 Muy mal servicio', '😕 Necesita mejorar', '😐 Correcto', '😊 Buena atención', '🌟 Excelente servicio'];

function setStars(value) {
    document.getElementById('satisfaccion-input').value = value;
    document.getElementById('star-label').textContent    = starLabels[value];
    document.getElementById('star-sublabel').textContent = starSubLabels[value];
    document.getElementById('star-label').className = 'text-center text-sm font-medium ' + (value >= 4 ? 'text-brand-green' : value >= 3 ? 'text-[#D97706]' : 'text-red-500') + ' mb-1';
    paintStars(value);
}

function paintStars(upTo) {
    document.querySelectorAll('.star-btn .star-icon').forEach((s, i) => {
        s.classList.toggle('text-[#F59E0B]', i < upTo);
        s.classList.toggle('text-gray-200',  i >= upTo);
    });
}

document.querySelectorAll('.star-btn').forEach(btn => {
    btn.addEventListener('mouseenter', () => paintStars(parseInt(btn.dataset.value)));
    btn.addEventListener('mouseleave', () => paintStars(parseInt(document.getElementById('satisfaccion-input').value || 0)));
});

// ─── Firma Canvas ─────────────────────────────────────────────────────────────
const canvas = document.getElementById('firma-canvas');
const ctx    = canvas.getContext('2d');
let drawing = false, hasFirma = false;

function resizeCanvas() {
    const rect = canvas.parentElement.getBoundingClientRect();
    canvas.width  = rect.width;
    canvas.height = rect.height;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

function getPos(e) {
    const r   = canvas.getBoundingClientRect();
    const src = e.touches ? e.touches[0] : e;
    return { x: src.clientX - r.left, y: src.clientY - r.top };
}

function startDraw(e) {
    drawing = true;
    ctx.beginPath();
    const p = getPos(e);
    ctx.moveTo(p.x, p.y);
}
function draw(e) {
    if (!drawing) return;
    const p = getPos(e);
    ctx.lineTo(p.x, p.y);
    ctx.strokeStyle = '#1E3A5F';
    ctx.lineWidth   = 2;
    ctx.lineCap     = 'round';
    ctx.stroke();
    hasFirma = true;
    document.getElementById('firma-placeholder').style.display = 'none';
}
function stopDraw() { drawing = false; }

canvas.addEventListener('mousedown',  startDraw);
canvas.addEventListener('mousemove',  draw);
canvas.addEventListener('mouseup',    stopDraw);
canvas.addEventListener('mouseleave', stopDraw);
canvas.addEventListener('touchstart', e => { e.preventDefault(); startDraw(e); }, { passive: false });
canvas.addEventListener('touchmove',  e => { e.preventDefault(); draw(e); },      { passive: false });
canvas.addEventListener('touchend',   stopDraw);

function limpiarFirma() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    hasFirma = false;
    document.getElementById('firma-placeholder').style.display = 'flex';
    document.getElementById('firma_base64').value = '';
}

// ─── Submit ───────────────────────────────────────────────────────────────────
document.getElementById('form-valoracion').addEventListener('submit', function(e) {
    const stars = document.getElementById('satisfaccion-input').value;
    if (!stars) {
        e.preventDefault();
        document.getElementById('star-label').textContent = '⚠ Por favor selecciona una valoración';
        document.getElementById('star-label').className = 'text-center text-sm font-medium text-red-500 mb-1';
        document.getElementById('star-rating').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    if (hasFirma) {
        document.getElementById('firma_base64').value = canvas.toDataURL('image/png');
    }
});
</script>
@endsection
