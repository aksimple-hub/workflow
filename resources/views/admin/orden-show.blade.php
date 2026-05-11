@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center gap-4">
            <a href="javascript:history.back()" class="text-gray-400 hover:text-[#10B981] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-medium text-[#1E3A5F]">Orden de Trabajo #ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</h1>
            </div>
            <div class="ml-auto">
                <span class="px-4 py-2 text-sm font-semibold rounded-full bg-[#DBEAFE] text-[#1D4ED8]">{{ ucfirst(str_replace('_', ' ', $orden->estado)) }}</span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-4xl mx-auto grid grid-cols-3 gap-6">
                <!-- Detalles Principales -->
                <div class="col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                        <h2 class="text-xl font-medium text-[#1E3A5F] mb-4">{{ $orden->titulo }}</h2>
                        <div class="prose max-w-none text-gray-600">
                            <p>{{ $orden->descripcion }}</p>
                        </div>
                    </div>

                    @if($orden->estado === 'finalizada' && file_exists(public_path('firmas/firma_' . $orden->id . '.png')))
                    <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                        <h3 class="text-lg font-medium text-[#1E3A5F] mb-4">Evidencia y Firma de Cierre</h3>
                        <img src="{{ asset('firmas/firma_' . $orden->id . '.png') }}" alt="Firma del cliente" class="w-full max-w-sm border rounded-lg shadow-sm">
                    </div>
                    @endif
                </div>

                <!-- Barra Lateral Info -->
                <div class="col-span-1 space-y-6">
                    <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Información del Cliente</h3>
                        @if($orden->cliente)
                            <p class="font-medium text-[#1E3A5F] mb-1">{{ $orden->cliente->nombre }}</p>
                            <p class="text-sm text-gray-500 mb-1">{{ $orden->cliente->telefono }}</p>
                            <p class="text-sm text-gray-500">{{ $orden->cliente->direccion }}</p>
                        @else
                            <p class="text-sm text-gray-500">Cliente no asignado</p>
                        @endif
                    </div>

                    <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Asignación de Técnico</h3>
                        @if($orden->tecnico)
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-[#1E3A5F] text-white flex items-center justify-center font-bold">
                                    {{ substr($orden->tecnico->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-[#1E3A5F]">{{ $orden->tecnico->name }}</p>
                                    <p class="text-xs text-gray-500">Técnico de Campo</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mb-4">Asignado el {{ $orden->fecha_asignacion?->translatedFormat('d M Y H:i') }}</p>
                        @else
                            <p class="text-sm text-gray-500 mb-4">Sin técnico asignado</p>
                        @endif
                        
                        <!-- Formulario para asignar/cambiar técnico -->
                        @php
                            $tecnicos = \App\Models\User::where('role', 'tecnico')->get();
                        @endphp
                        
                        @if($tecnicos->count() > 0)
                        <form action="{{ route('ordenes.assign-tecnico', $orden) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label for="usuario_id" class="block text-sm font-medium text-[#1E3A5F] mb-2">Seleccionar Técnico</label>
                                <select id="usuario_id" name="usuario_id" required
                                    class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl px-4 py-2 focus:outline-none transition-colors text-sm appearance-none">
                                    <option value="" disabled {{ !$orden->tecnico ? 'selected' : '' }}>Elige un técnico...</option>
                                    @foreach($tecnicos as $tecnico)
                                        <option value="{{ $tecnico->id }}" {{ $orden->usuario_id === $tecnico->id ? 'selected' : '' }}>
                                            {{ $tecnico->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <button type="submit" class="w-full bg-[#10B981] hover:bg-[#059669] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                {{ $orden->tecnico ? 'Cambiar Técnico' : 'Asignar Técnico' }}
                            </button>
                        </form>
                        @else
                        <p class="text-sm text-red-600">No hay técnicos disponibles para asignar.</p>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
