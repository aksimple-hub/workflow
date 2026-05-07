@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('ordenes.show', $orden) }}" class="text-gray-400 hover:text-[#1E3A5F] transition-all duration-200 ease-in-out">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-4xl font-medium text-[#1E3A5F]">Cierre de Orden</h1>
                </div>
                <p class="text-base text-gray-500 mt-1 ml-9">ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="grid grid-cols-2 gap-6 max-w-7xl mx-auto h-full">
                
                <!-- Columna Izquierda: Readonly -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)] h-fit">
                    <h2 class="text-xl font-medium text-[#1E3A5F] mb-6 pb-2 border-b flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Resumen del Servicio
                    </h2>
                    <h3 class="text-lg font-medium text-[#1E3A5F] mb-2">{{ $orden->titulo }}</h3>
                    <div class="bg-[#F5F7FA] p-4 rounded-xl mt-2 text-sm text-gray-700 leading-relaxed mb-4">
                        {{ $orden->descripcion }}
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Cliente:</span>
                        <span class="text-sm font-medium text-[#1E3A5F]">{{ $orden->cliente->nombre ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Columna Derecha: Formulario de Cierre -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-[0px_1px_3px_rgba(0,0,0,0.05)]">
                    <form action="{{ route('ordenes.cerrar', $orden) }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-full">
                        @csrf
                        <h2 class="text-xl font-medium text-[#1E3A5F] mb-6 pb-2 border-b flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            Reporte Técnico Final
                        </h2>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-[#1E3A5F] mb-2">Observaciones y Repuestos *</label>
                            <!-- Textarea con altura de 192px (h-48) exigida -->
                            <textarea name="observaciones" required 
                                class="w-full h-48 bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl p-4 resize-none outline-none transition-all duration-200 ease-in-out text-sm"
                                placeholder="Describe el trabajo realizado de forma detallada..."></textarea>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-medium text-[#1E3A5F] mb-2">Evidencia Fotográfica / Firma</label>
                            <!-- Zona dashed con #E5E7EB y #F5F7FA -->
                            <div class="border-2 border-dashed border-[#E5E7EB] hover:border-[#10B981] rounded-xl bg-[#F5F7FA] flex flex-col items-center justify-center py-10 relative cursor-pointer transition-all duration-200 ease-in-out group">
                                <svg class="w-10 h-10 text-gray-400 group-hover:text-[#10B981] transition-colors duration-200 ease-in-out mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm font-medium text-[#1E3A5F]">Haz clic para subir evidencia</p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF hasta 2MB</p>
                                <input type="file" name="firma" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                            </div>
                        </div>

                        <div class="mt-auto pt-4">
                            <button type="submit" class="w-full bg-[#10B981] hover:bg-[#059669] text-white px-8 py-4 rounded-xl shadow-[0px_2px_8px_rgba(16,185,129,0.25)] font-medium transition-all duration-200 ease-in-out flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Finalizar y Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
