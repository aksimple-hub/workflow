@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center gap-4">
            <a href="{{ route('admin.tecnicos') }}" class="text-gray-400 hover:text-[#10B981] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-medium text-[#1E3A5F]">Detalle del Técnico</h1>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-5xl mx-auto space-y-6">
                <!-- Info Técnico -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-8 flex items-start gap-6">
                    <div class="w-20 h-20 rounded-full bg-[#1E3A5F] text-white flex items-center justify-center font-bold text-3xl">
                        {{ substr($tecnico->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-3xl font-medium text-[#1E3A5F] mb-1">{{ $tecnico->name }}</h2>
                        <p class="text-gray-500 mb-4">{{ $tecnico->email }}</p>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#D1FAE5] text-[#065F46]">Activo</span>
                    </div>
                </div>

                <!-- Últimas órdenes asignadas -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                    <h3 class="text-xl font-medium text-[#1E3A5F] mb-4">Últimas órdenes asignadas</h3>
                    <div class="space-y-4">
                        @forelse($ordenes as $orden)
                        <div class="border border-gray-100 rounded-lg p-4 flex justify-between items-center hover:border-[#10B981] transition-colors">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase">ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <h4 class="text-lg font-medium text-[#1E3A5F]">{{ $orden->titulo }}</h4>
                                <p class="text-sm text-gray-500">{{ $orden->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <a href="{{ route('admin.orden.show', $orden->id) }}" class="text-[#1D4ED8] hover:underline text-sm font-medium">Ver orden</a>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">No tiene órdenes asignadas.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
