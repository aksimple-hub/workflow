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
                    <h1 class="text-2xl md:text-4xl font-medium text-brand-dark">Clientes Registrados</h1>
                    <p class="text-sm md:text-base text-gray-500 mt-0.5">Directorio de clientes y empresas</p>
                </div>
            </div>
            <a href="{{ route('admin.clientes.create') }}" class="flex-shrink-0 bg-brand-green hover:bg-brand-green-dark text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                + Nuevo Cliente
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-[#F5F7FA] text-gray-500 text-sm border-b border-gray-200">
                            <th class="px-6 py-4 font-medium">Empresa / Nombre</th>
                            <th class="px-6 py-4 font-medium">DNI / CIF</th>
                            <th class="px-6 py-4 font-medium">Contacto</th>
                            <th class="px-6 py-4 font-medium">Dirección</th>
                            <th class="px-6 py-4 font-medium">Estado</th>
                            <th class="px-6 py-4 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($clientes as $cliente)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-brand-dark">{{ $cliente->nombre }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $cliente->dni_cif }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                <div>{{ $cliente->telefono }}</div>
                                <div class="text-xs text-gray-400">{{ $cliente->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $cliente->direccion ?? 'No especificada' }}</td>
                            @php $user = $cliente->usuario; @endphp
                            <td class="px-6 py-4">
                                @if($user && $user->is_approved)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#D1FAE5] text-[#065F46]">Aprobado</span>
                                @elseif($user && !$user->is_approved)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#FEF3C7] text-[#92400E]">Pendiente</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">Sin cuenta</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.cliente.show', $cliente->id) }}" class="text-[#1D4ED8] hover:underline font-medium">Ver detalles</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No hay clientes registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($clientes->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $clientes->links() }}</div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection
