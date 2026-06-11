@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3 min-w-0">
                <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-brand-dark hover:bg-gray-100 transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-2xl md:text-4xl font-medium text-brand-dark">Técnicos Registrados</h1>
                    <p class="text-sm md:text-base text-gray-500 mt-0.5">Gestión del personal de campo</p>
                </div>
            </div>
            <a href="{{ route('admin.tecnicos.create') }}" class="flex-shrink-0 bg-brand-green hover:bg-brand-green-dark text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                + Nuevo Técnico
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-[#F5F7FA] text-gray-500 text-sm border-b border-gray-200">
                            <th class="px-6 py-4 font-medium">ID</th>
                            <th class="px-6 py-4 font-medium">Nombre Completo</th>
                            <th class="px-6 py-4 font-medium">Email</th>
                            <th class="px-6 py-4 font-medium">Estado</th>
                            <th class="px-6 py-4 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($tecnicos as $tecnico)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-brand-dark">#{{ $tecnico->id }}</td>
                            <td class="px-6 py-4 text-gray-700 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand-dark text-white flex items-center justify-center font-bold text-xs">
                                    {{ substr($tecnico->name, 0, 1) }}
                                </div>
                                {{ $tecnico->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $tecnico->email }}</td>
                            <td class="px-6 py-4">
                                @if($tecnico->is_approved)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#D1FAE5] text-[#065F46]">Activo</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#FEF3C7] text-[#92400E]">Pendiente</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.tecnico.show', $tecnico->id) }}" class="text-[#1D4ED8] hover:underline font-medium">Ver detalles</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No hay técnicos registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($tecnicos->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $tecnicos->links() }}</div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection
