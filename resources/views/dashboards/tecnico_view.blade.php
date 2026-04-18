<div class="space-y-4">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wider">Mi Agenda Diaria</h3>
        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Ruta Activa</span>
    </div>

    @forelse($ordenes as $orden)
        <div class="bg-white p-5 rounded-2xl shadow-sm border-l-8 @if($orden->prioridad == 'alta') border-red-500 @else border-green-500 @endif transition hover:shadow-md">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-bold text-gray-400 uppercase">#{{ strtoupper(substr($orden->uuid, 0, 5)) }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-extrabold uppercase @if($orden->prioridad == 'alta') bg-red-100 text-red-600 @else bg-green-100 text-green-600 @endif">
                            {{ $orden->prioridad }}
                        </span>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 leading-tight">{{ $orden->cliente->nombre ?? 'Cliente sin nombre' }}</h4>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                        📍 {{ $orden->cliente->direccion ?? 'Dirección no disponible' }}
                    </p>
                    <p class="text-sm text-blue-600 font-medium mt-2 italic">
                        "{{ $orden->titulo }}"
                    </p>
                </div>

                <div class="text-right">
                    <span class="text-xs font-bold px-3 py-1 rounded-lg bg-gray-100 text-gray-600 uppercase">
                        {{ $orden->estado }}
                    </span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <a href="tel:{{ $orden->cliente->telefono ?? '#' }}" class="flex items-center justify-center gap-2 bg-gray-100 text-gray-700 py-3 rounded-xl text-sm font-bold active:scale-95 transition">
                    📞 Llamar
                </a>
                <a href="{{ route('ordenes.show', $orden) }}" class="flex items-center justify-center gap-2 bg-[#10b981] text-white py-3 rounded-xl text-sm font-bold shadow-lg shadow-green-100 active:scale-95 transition">
                    🚀 Iniciar
                </a>
            </div>
        </div>
    @empty
        <div class="bg-white p-12 rounded-3xl border-2 border-dashed border-gray-200 text-center">
            <span class="text-4xl block mb-4">☕</span>
            <p class="text-gray-500 font-medium">No tienes servicios asignados para hoy.</p>
            <p class="text-xs text-gray-400 mt-1">¡Buen trabajo!</p>
        </div>
    @endforelse
</div>
