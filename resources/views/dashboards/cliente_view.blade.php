<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">¡Hola, {{ auth()->user()->name }}!</h3>
            <p class="text-gray-500">Consulta el estado de tus averías en tiempo real.</p>
        </div>
        <a href="{{ route('ordenes.create') }}" class="bg-[#10b981] hover:bg-[#059669] text-white font-bold py-3 px-6 rounded-xl shadow-lg transition">
            + Nueva Solicitud
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($ordenes as $orden)
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="flex justify-between mb-4">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">#{{ strtoupper(substr($orden->uuid, 0, 8)) }}</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                        @if($orden->estado == 'finalizada') bg-green-100 text-green-700 @else bg-blue-100 text-blue-700 @endif">
                        {{ $orden->estado }} [cite: 295]
                    </span>
                </div>

                <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $orden->titulo }}</h4>
                <p class="text-gray-500 text-sm line-clamp-2 mb-6">{{ $orden->descripcion }}</p>

                <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                    <div class="flex text-yellow-400 text-sm">★★★★★</div>
                    <a href="{{ route('ordenes.show', $orden) }}" class="text-[#214371] font-bold text-sm hover:underline">Ver detalles →</a>
                </div>
            </div>
        @empty
            <div class="col-span-2 py-20 text-center bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200">
                <p class="text-gray-400 font-medium">No tienes solicitudes registradas aún.</p>
            </div>
        @endforelse
    </div>
</div>
