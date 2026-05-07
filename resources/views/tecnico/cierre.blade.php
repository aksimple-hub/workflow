@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-medium text-[#1E3A5F]">Cierre de Orden #{{ $orden->id }}</h1>
                <p class="text-base text-gray-500 mt-1">Reporte de trabajo finalizado</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-[#1E3A5F] hover:underline font-medium text-sm">
                Volver a la Agenda
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="grid grid-cols-2 gap-6 max-w-7xl mx-auto">
                
                <!-- Columna Izquierda: Info Readonly -->
                <div class="bg-white p-6 rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100">
                    <h2 class="text-xl font-medium text-[#1E3A5F] mb-4 border-b pb-2">Información de la Orden</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-500">Título</label>
                            <p class="text-base text-[#1E3A5F] font-medium">{{ $orden->titulo }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Descripción Original</label>
                            <div class="bg-[#F5F7FA] p-4 rounded-xl mt-1 text-sm text-gray-700">
                                {{ $orden->descripcion ?? 'Sin descripción' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-500">Prioridad</label>
                                <p class="text-base font-medium">{{ ucfirst($orden->prioridad) }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Asignado el</label>
                                <p class="text-base font-medium">{{ $orden->fecha_asignacion ? \Carbon\Carbon::parse($orden->fecha_asignacion)->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Inputs de Cierre -->
                <div class="bg-white p-6 rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100">
                    <form action="{{ route('ordenes.cerrar', $orden) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <h2 class="text-xl font-medium text-[#1E3A5F] mb-4 border-b pb-2">Reporte del Técnico</h2>
                        
                        <!-- Observaciones: Textarea h-48 -->
                        <div class="mb-6">
                            <label for="observaciones" class="block text-sm font-medium text-[#1E3A5F] mb-2">Observaciones del Trabajo Realizado *</label>
                            <textarea id="observaciones" name="observaciones" required
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl p-4 h-48 resize-none focus:outline-none transition-colors text-base"
                                placeholder="Describe el trabajo realizado, repuestos utilizados o problemas encontrados..."></textarea>
                            @error('observaciones')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Zona de Firma/Upload: dashed border -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-[#1E3A5F] mb-2">Firma del Cliente o Comprobante</label>
                            <div class="border-2 border-dashed border-gray-300 hover:border-[#10B981] rounded-xl bg-[#F5F7FA] flex flex-col items-center justify-center py-8 px-4 transition-colors relative cursor-pointer group">
                                <svg class="w-10 h-10 text-gray-400 group-hover:text-[#10B981] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="text-sm text-gray-500 text-center"><span class="font-medium text-[#1E3A5F]">Haz clic para subir</span> o arrastra la imagen aquí</p>
                                <input type="file" name="firma" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                            </div>
                            @error('firma')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                class="bg-[#10B981] hover:bg-[#059669] text-white px-8 py-4 rounded-xl shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-all font-medium text-base w-full flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Finalizar Orden de Trabajo
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
