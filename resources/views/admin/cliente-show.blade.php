@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center gap-4">
            <a href="{{ route('admin.clientes') }}" class="text-gray-400 hover:text-[#10B981] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-medium text-[#1E3A5F]">Perfil del Cliente</h1>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-5xl mx-auto space-y-6">
                <!-- Info Cliente -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-8 flex items-start gap-6">
                    <div class="w-20 h-20 rounded-xl bg-[#F5F7FA] border-2 border-gray-200 text-[#1E3A5F] flex items-center justify-center font-bold text-3xl">
                        {{ substr($cliente->nombre, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h2 class="text-3xl font-medium text-[#1E3A5F] mb-1">{{ $cliente->nombre }}</h2>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">DNI / CIF</p>
                                <p class="text-sm font-medium text-gray-700">{{ $cliente->dni_cif }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Email</p>
                                <p class="text-sm font-medium text-gray-700">{{ $cliente->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Teléfono</p>
                                <p class="text-sm font-medium text-gray-700">{{ $cliente->telefono }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Dirección</p>
                                <p class="text-sm font-medium text-gray-700">{{ $cliente->direccion ?? 'No especificada' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial del Cliente -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                    <h3 class="text-xl font-medium text-[#1E3A5F] mb-4">Órdenes recientes</h3>
                    <div class="space-y-4">
                        @forelse($ordenes as $orden)
                        <div class="border border-gray-100 rounded-lg p-4 flex justify-between items-center hover:border-[#10B981] transition-colors">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase">ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <h4 class="text-lg font-medium text-[#1E3A5F]">{{ $orden->titulo }}</h4>
                                <p class="text-sm text-gray-500">{{ $orden->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <a href="{{ route('admin.orden.show', $orden->id) }}" class="text-[#1D4ED8] hover:underline text-sm font-medium">Ver detalle de la orden</a>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">Este cliente no tiene órdenes asociadas.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
