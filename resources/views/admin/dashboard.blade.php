@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-medium text-[#1E3A5F]">Dashboard Admin</h1>
                <p class="text-base text-gray-500 mt-1">Resumen general de órdenes de trabajo</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 pb-32">
            @if(session('success'))
            <div class="mb-6 px-4 py-3 bg-[#D1FAE5] border border-[#6EE7B7] text-[#065F46] rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Órdenes</p>
                    <p class="text-3xl font-bold text-[#1E3A5F]">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Pendientes</p>
                    <p class="text-3xl font-bold text-[#1D4ED8]">{{ $stats['pendientes'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">En Curso</p>
                    <p class="text-3xl font-bold text-[#D97706]">{{ $stats['en_curso'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Finalizadas</p>
                    <p class="text-3xl font-bold text-[#10B981]">{{ $stats['finalizadas'] ?? 0 }}</p>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-medium text-[#1E3A5F]">Órdenes de Trabajo Activas</h2>
                    <a href="{{ route('ordenes.create') }}" class="bg-[#10B981] hover:bg-[#059669] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Nueva Orden</a>
                </div>

                <form id="bulkForm" action="{{ route('ordenes.bulk-assign') }}" method="POST">
                    @csrf
                    <input type="hidden" name="usuario_id" id="bulkTecnicoId">

                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#F5F7FA] text-gray-500 text-sm border-b border-gray-200">
                                    <th class="px-4 py-3 w-10">
                                        <input type="checkbox" id="selectAll"
                                            class="w-4 h-4 rounded border-gray-300 text-[#10B981] focus:ring-[#10B981] cursor-pointer">
                                    </th>
                                    <th class="px-6 py-3 font-medium whitespace-nowrap">ID Orden</th>
                                    <th class="px-6 py-3 font-medium">Cliente</th>
                                    <th class="px-6 py-3 font-medium">Dirección del Servicio</th>
                                    <th class="px-6 py-3 font-medium">Técnico Asignado</th>
                                    <th class="px-6 py-3 font-medium">Fecha</th>
                                    <th class="px-6 py-3 font-medium">Estado</th>
                                    <th class="px-6 py-3 font-medium text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">
                                @forelse($ordenes as $orden)
                                @php
                                    $asignable = $orden->estado === 'pendiente';
                                    $bg = 'bg-gray-100 text-gray-600';
                                    if(in_array($orden->estado, ['en_camino', 'en_curso', 'en_proceso'])) $bg = 'bg-blue-500 text-white';
                                    if($orden->estado == 'finalizada') $bg = 'bg-[#10B981] text-white';
                                    if(in_array($orden->estado, ['pendiente', 'asignada'])) $bg = 'bg-gray-400 text-white';
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4">
                                        @if($asignable)
                                        <input type="checkbox" name="orden_ids[]" value="{{ $orden->id }}"
                                            class="orden-checkbox w-4 h-4 rounded border-gray-300 text-[#10B981] focus:ring-[#10B981] cursor-pointer">
                                        @else
                                        <span class="w-4 h-4 block"></span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-medium text-[#1E3A5F]">#OT-{{ $orden->id }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $orden->cliente->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-gray-500 text-sm max-w-xs truncate" title="{{ $orden->cliente->direccion ?? 'N/A' }}">{{ $orden->cliente->direccion ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $orden->tecnico->name ?? 'No asignado' }}</td>
                                    <td class="px-6 py-4 text-gray-500 text-sm whitespace-nowrap">{{ $orden->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $bg }}">
                                            {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.orden.show', $orden->id) }}" class="text-[#10B981] hover:text-[#059669] font-medium text-sm">Ver detalles</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">No hay órdenes registradas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

{{-- ── BARRA FLOTANTE DE ASIGNACIÓN MASIVA ── --}}
<div id="bulkBar" class="fixed bottom-0 left-0 right-0 z-50 hidden">
    <div class="mx-auto max-w-4xl mb-6 px-6">
        <div class="bg-[#1E3A5F] rounded-2xl shadow-2xl px-6 py-4 flex items-center gap-4 flex-wrap">
            <div class="flex items-center gap-2 text-white flex-shrink-0">
                <div class="w-7 h-7 bg-[#10B981] rounded-full flex items-center justify-center font-bold text-sm" id="selectedCount">0</div>
                <span class="font-medium text-sm">orden(es) seleccionada(s)</span>
            </div>

            <div class="flex-1 min-w-48">
                <select id="bulkTecnicoSelect"
                    class="w-full bg-white/10 border border-white/20 text-white rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-[#10B981] appearance-none">
                    <option value="" class="text-gray-900">Seleccionar técnico...</option>
                    @foreach(\App\Models\User::where('role','tecnico')->get() as $tec)
                    <option value="{{ $tec->id }}" class="text-gray-900">{{ $tec->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="button" id="bulkAssignBtn"
                class="bg-[#10B981] hover:bg-[#059669] text-white px-5 py-2 rounded-xl text-sm font-semibold transition-colors flex-shrink-0">
                Asignar técnico
            </button>

            <button type="button" id="clearSelection"
                class="text-white/60 hover:text-white text-sm flex-shrink-0 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancelar
            </button>
        </div>
    </div>
</div>

<script>
const checkboxes  = () => document.querySelectorAll('.orden-checkbox');
const bulkBar     = document.getElementById('bulkBar');
const countBadge  = document.getElementById('selectedCount');
const selectAll   = document.getElementById('selectAll');
const bulkSelect  = document.getElementById('bulkTecnicoSelect');
const bulkInput   = document.getElementById('bulkTecnicoId');
const bulkForm    = document.getElementById('bulkForm');

function updateBar() {
    const checked = [...checkboxes()].filter(c => c.checked);
    countBadge.textContent = checked.length;
    bulkBar.classList.toggle('hidden', checked.length === 0);
}

document.addEventListener('change', e => {
    if (e.target.classList.contains('orden-checkbox')) updateBar();
});

selectAll.addEventListener('change', () => {
    checkboxes().forEach(c => c.checked = selectAll.checked);
    updateBar();
});

document.getElementById('clearSelection').addEventListener('click', () => {
    checkboxes().forEach(c => c.checked = false);
    selectAll.checked = false;
    updateBar();
});

document.getElementById('bulkAssignBtn').addEventListener('click', () => {
    if (!bulkSelect.value) {
        bulkSelect.style.borderColor = '#EF4444';
        setTimeout(() => bulkSelect.style.borderColor = '', 1500);
        return;
    }
    bulkInput.value = bulkSelect.value;
    bulkForm.submit();
});
</script>
@endsection
