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
                    <h1 class="text-2xl md:text-4xl font-medium text-brand-dark">Historial de Órdenes</h1>
                    <p class="text-sm md:text-base text-gray-500 mt-0.5 hidden sm:block">Registro completo de todos los trabajos realizados</p>
                </div>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-none">
                    <input type="text" id="searchInput" placeholder="Buscar orden..."
                        class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-brand-green w-full sm:w-64">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </header>

        @if(session('success'))
        <div class="mx-6 mt-4 px-4 py-3 bg-[#D1FAE5] border border-[#6EE7B7] text-[#065F46] rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
        @endif

        <main class="flex-1 overflow-y-auto p-6 pb-32">
            <form id="bulkForm" action="{{ route('ordenes.bulk-assign') }}" method="POST">
                @csrf
                <input type="hidden" name="usuario_id" id="bulkTecnicoId">

                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[700px]" id="ordenesTable">
                        <thead>
                            <tr class="bg-[#F5F7FA] text-gray-500 text-sm border-b border-gray-200">
                                <th class="px-4 py-4 w-10">
                                    <input type="checkbox" id="selectAll"
                                        class="w-4 h-4 rounded border-gray-300 text-brand-green focus:ring-brand-green cursor-pointer">
                                </th>
                                <th class="px-6 py-4 font-medium">ID Orden</th>
                                <th class="px-6 py-4 font-medium">Fecha</th>
                                <th class="px-6 py-4 font-medium">Cliente</th>
                                <th class="px-6 py-4 font-medium">Técnico</th>
                                <th class="px-6 py-4 font-medium">Prioridad</th>
                                <th class="px-6 py-4 font-medium">Estado</th>
                                <th class="px-6 py-4 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            @forelse($ordenes as $orden)
                            @php
                                $asignable = $orden->estado === 'pendiente';
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors orden-row" data-search="{{ strtolower($orden->titulo . ' ' . ($orden->cliente->nombre ?? '') . ' ' . ($orden->tecnico->name ?? '')) }}">
                                <td class="px-4 py-4">
                                    @if($asignable)
                                    <input type="checkbox" name="orden_ids[]" value="{{ $orden->id }}"
                                        class="orden-checkbox w-4 h-4 rounded border-gray-300 text-brand-green focus:ring-brand-green cursor-pointer">
                                    @else
                                    <span class="w-4 h-4 block"></span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-500">ORD-{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $orden->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-brand-dark font-medium">{{ $orden->cliente->nombre ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $orden->tecnico->name ?? 'No asignado' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $orden->prioridad === 'alta' ? 'bg-[#FEF3C7] text-[#D97706]' : 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($orden->prioridad) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($orden->estado === 'finalizada')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#D1FAE5] text-[#065F46]">Finalizada</span>
                                    @elseif($orden->estado === 'cancelada')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelada</span>
                                    @elseif($orden->estado === 'pendiente')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Pendiente</span>
                                    @elseif($orden->estado === 'asignada')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#FEF3C7] text-[#D97706]">Asignada</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#DBEAFE] text-[#1D4ED8]">{{ ucfirst(str_replace('_', ' ', $orden->estado)) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.orden.show', $orden->id) }}"
                                        class="text-[#1D4ED8] hover:underline font-medium">Ver detalles</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">No hay órdenes en el historial.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if($ordenes->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">{{ $ordenes->links() }}</div>
                    @endif
                </div>
            </form>
        </main>
    </div>
</div>

{{-- ── BARRA FLOTANTE DE ASIGNACIÓN MASIVA ── --}}
<div id="bulkBar"
    class="fixed bottom-0 left-0 right-0 z-50 hidden transition-all duration-300">
    <div class="mx-auto max-w-5xl mb-6 px-6">
        <div class="bg-brand-dark rounded-2xl shadow-2xl px-6 py-4 flex items-center gap-4 flex-wrap">
            <div class="flex items-center gap-2 text-white flex-shrink-0">
                <div class="w-7 h-7 bg-brand-green rounded-full flex items-center justify-center font-bold text-sm" id="selectedCount">0</div>
                <span class="font-medium text-sm">orden(es) seleccionada(s)</span>
            </div>

            <div class="flex-1 min-w-48">
                <select id="bulkTecnicoSelect"
                    class="w-full bg-white/10 border border-white/20 text-white rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-brand-green appearance-none">
                    <option value="" class="text-gray-900">Seleccionar técnico...</option>
                    @foreach(\App\Models\User::where('role','tecnico')->where('is_approved',true)->get() as $tec)
                    <option value="{{ $tec->id }}" class="text-gray-900">{{ $tec->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="button" id="bulkAssignBtn"
                class="bg-brand-green hover:bg-brand-green-dark text-white px-5 py-2 rounded-xl text-sm font-semibold transition-colors flex-shrink-0">
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
        bulkSelect.focus();
        bulkSelect.style.borderColor = '#EF4444';
        setTimeout(() => bulkSelect.style.borderColor = '', 1500);
        return;
    }
    bulkInput.value = bulkSelect.value;
    bulkForm.submit();
});

// Búsqueda en tiempo real
document.getElementById('searchInput').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.orden-row').forEach(row => {
        row.style.display = row.dataset.search.includes(q) ? '' : 'none';
    });
});
</script>
@endsection
