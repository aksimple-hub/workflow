@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-medium text-[#1E3A5F]">Clientes Registrados</h1>
                <p class="text-base text-gray-500 mt-1">Directorio de clientes y empresas</p>
            </div>
            <a href="{{ route('admin.clientes.create') }}" class="bg-[#10B981] hover:bg-[#059669] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                + Nuevo Cliente
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
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
                            <td class="px-6 py-4 font-medium text-[#1E3A5F]">{{ $cliente->nombre }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $cliente->dni_cif }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                <div>{{ $cliente->telefono }}</div>
                                <div class="text-xs text-gray-400">{{ $cliente->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $cliente->direccion ?? 'No especificada' }}</td>
                            @php
                                $user = \App\Models\User::find($cliente->id);
                            @endphp
                            <td class="px-6 py-4">
                                @if($user && $user->is_approved)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#D1FAE5] text-[#065F46]">Aprobado</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#FEF3C7] text-[#92400E]">Pendiente</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right flex items-center justify-end gap-3">
                                @if($user && !$user->is_approved)
                                    <form method="POST" action="{{ route('admin.users.validate', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="text-white bg-[#10B981] hover:bg-[#059669] px-3 py-1 rounded text-xs font-medium">Validar</button>
                                    </form>
                                @endif
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
            </div>
        </main>
    </div>
</div>
@endsection
