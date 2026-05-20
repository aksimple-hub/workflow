@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3 min-w-0">
                <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-brand-dark hover:bg-gray-100 transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-2xl md:text-4xl font-medium text-brand-dark">Panel de Control</h1>
                    <p class="text-sm md:text-base text-gray-500 mt-0.5">Gestión de Órdenes de Trabajo</p>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <button type="button" onclick="toggleFiltros()"
                    class="flex items-center gap-2 px-4 py-2 border border-gray-200 bg-white rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors {{ ($estado || $prioridad) ? 'border-brand-green text-brand-green' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    Filtrar
                    @if($estado || $prioridad)
                        <span class="w-2 h-2 rounded-full bg-brand-green"></span>
                    @endif
                </button>
                <a href="{{ route('ordenes.create') }}"
                    class="flex items-center gap-2 bg-brand-green hover:bg-brand-green-dark text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nueva Orden
                </a>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 pb-32">
            @if(session('success'))
            <div class="mb-6 px-4 py-3 bg-[#D1FAE5] border border-[#6EE7B7] text-[#065F46] rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
            @endif

            <!-- Barra de búsqueda -->
            <form method="GET" action="{{ route('dashboard') }}" id="searchForm">
                <div class="mb-4 flex gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg>
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Buscar por cliente, ID de orden o técnico..."
                            class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition-colors"
                            oninput="debounceSearch(this)">
                        @if($search)
                        <a href="{{ route('dashboard', array_filter(['estado' => $estado, 'prioridad' => $prioridad])) }}"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                        @endif
                    </div>
                    @if($search || $estado || $prioridad)
                    <a href="{{ route('dashboard') }}" class="px-4 py-3 text-sm text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors whitespace-nowrap">
                        Limpiar filtros
                    </a>
                    @endif
                </div>

                <!-- Panel de filtros -->
                <div id="filtrosPanel" class="{{ ($estado || $prioridad) ? '' : 'hidden' }} mb-4 bg-white border border-gray-200 rounded-xl p-4 flex flex-wrap gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Estado</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['pendiente' => 'Pendiente', 'asignada' => 'Asignada', 'en_camino' => 'En camino', 'en_proceso' => 'En proceso', 'finalizada' => 'Finalizada', 'cancelada' => 'Cancelada'] as $val => $label)
                            <button type="button" onclick="setFiltro('estado', '{{ $val }}')"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium border transition-colors
                                {{ $estado === $val ? 'bg-[#214371] text-white border-[#214371]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#214371]' }}">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Prioridad</label>
                        <div class="flex gap-2">
                            @foreach(['baja' => 'Baja', 'media' => 'Media', 'alta' => 'Alta'] as $val => $label)
                            <button type="button" onclick="setFiltro('prioridad', '{{ $val }}')"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium border transition-colors
                                {{ $prioridad === $val ? 'bg-[#214371] text-white border-[#214371]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#214371]' }}">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="estado" id="inputEstado" value="{{ $estado }}">
                    <input type="hidden" name="prioridad" id="inputPrioridad" value="{{ $prioridad }}">
                </div>
            </form>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6 mb-8">
                <a href="{{ route('dashboard') }}"
                    class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border {{ !$estado ? 'border-brand-dark ring-2 ring-brand-dark/20' : 'border-gray-100 hover:border-gray-300' }} transition-colors group block">
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Órdenes</p>
                    <p class="text-3xl font-bold text-brand-dark">{{ $stats['total'] ?? 0 }}</p>
                </a>
                <button type="button" onclick="filtrarPorEstado('pendiente')"
                    class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border {{ $estado === 'pendiente' ? 'border-[#1D4ED8] ring-2 ring-[#1D4ED8]/20' : 'border-gray-100 hover:border-[#1D4ED8]/40' }} transition-colors text-left group">
                    <p class="text-sm font-medium text-gray-500 mb-1">Pendientes</p>
                    <p class="text-3xl font-bold text-[#1D4ED8]">{{ $stats['pendientes'] ?? 0 }}</p>
                </button>
                <button type="button" onclick="filtrarPorEstado('asignada')"
                    class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border {{ $estado === 'asignada' ? 'border-[#D97706] ring-2 ring-[#D97706]/20' : 'border-gray-100 hover:border-[#D97706]/40' }} transition-colors text-left group">
                    <p class="text-sm font-medium text-gray-500 mb-1">En Curso</p>
                    <p class="text-3xl font-bold text-[#D97706]">{{ $stats['en_curso'] ?? 0 }}</p>
                </button>
                <button type="button" onclick="filtrarPorEstado('finalizada')"
                    class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border {{ $estado === 'finalizada' ? 'border-brand-green ring-2 ring-brand-green/20' : 'border-gray-100 hover:border-brand-green/40' }} transition-colors text-left group">
                    <p class="text-sm font-medium text-gray-500 mb-1">Finalizadas</p>
                    <p class="text-3xl font-bold text-brand-green">{{ $stats['finalizadas'] ?? 0 }}</p>
                </button>
                <button type="button" onclick="filtrarPorEstado('cancelada')"
                    class="bg-red-50 rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] p-6 border {{ $estado === 'cancelada' ? 'border-red-500 ring-2 ring-red-500/20' : 'border-red-100 hover:border-red-300' }} transition-colors text-left group">
                    <p class="text-sm font-medium text-red-400 mb-1">Canceladas</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['canceladas'] ?? 0 }}</p>
                </button>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-medium text-brand-dark">
                        Órdenes de Trabajo
                        @if($search || $estado || $prioridad)
                            <span class="ml-2 text-sm font-normal text-gray-400">— {{ $ordenes->total() }} resultado(s)</span>
                        @endif
                    </h2>
                </div>

                <form id="bulkForm" action="{{ route('ordenes.bulk-assign') }}" method="POST">
                    @csrf
                    <input type="hidden" name="usuario_id" id="bulkTecnicoId">

                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr class="bg-[#F5F7FA] text-gray-500 text-sm border-b border-gray-200">
                                    <th class="px-4 py-3 w-10">
                                        <input type="checkbox" id="selectAll"
                                            class="w-4 h-4 rounded border-gray-300 text-brand-green focus:ring-brand-green cursor-pointer">
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
                                    if($orden->estado == 'finalizada') $bg = 'bg-brand-green text-white';
                                    if(in_array($orden->estado, ['pendiente', 'asignada'])) $bg = 'bg-gray-400 text-white';
                                    if($orden->estado === 'cancelada') $bg = 'bg-red-100 text-red-700';
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4">
                                        @if($asignable)
                                        <input type="checkbox" name="orden_ids[]" value="{{ $orden->id }}"
                                            class="orden-checkbox w-4 h-4 rounded border-gray-300 text-brand-green focus:ring-brand-green cursor-pointer">
                                        @else
                                        <span class="w-4 h-4 block"></span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-medium text-brand-dark">#OT-{{ $orden->id }}</td>
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
                                        <a href="{{ route('admin.orden.show', $orden->id) }}" class="text-brand-green hover:text-brand-green-dark font-medium text-sm">Ver detalles</a>
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

                @if($ordenes->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        Mostrando {{ $ordenes->firstItem() }}–{{ $ordenes->lastItem() }} de {{ $ordenes->total() }} órdenes
                    </p>
                    <div class="flex items-center gap-1">
                        @if($ordenes->onFirstPage())
                            <span class="px-3 py-1.5 text-sm text-gray-300 border border-gray-100 rounded-lg">Anterior</span>
                        @else
                            <a href="{{ $ordenes->previousPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Anterior</a>
                        @endif

                        @foreach($ordenes->getUrlRange(1, $ordenes->lastPage()) as $page => $url)
                            @if($page == $ordenes->currentPage())
                                <span class="px-3 py-1.5 text-sm font-medium text-white bg-[#214371] border border-[#214371] rounded-lg">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1.5 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($ordenes->hasMorePages())
                            <a href="{{ $ordenes->nextPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Siguiente</a>
                        @else
                            <span class="px-3 py-1.5 text-sm text-gray-300 border border-gray-100 rounded-lg">Siguiente</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </main>
    </div>
</div>

{{-- ── BARRA FLOTANTE DE ASIGNACIÓN MASIVA ── --}}
<div id="bulkBar" class="fixed bottom-0 left-0 right-0 z-50 hidden">
    <div class="mx-auto max-w-4xl mb-6 px-6">
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
// Búsqueda con debounce
let searchTimer;
function debounceSearch(input) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 400);
}

// Toggle panel de filtros
function toggleFiltros() {
    const panel = document.getElementById('filtrosPanel');
    panel.classList.toggle('hidden');
}

// Aplicar filtro y enviar formulario
function setFiltro(campo, valor) {
    const input = document.getElementById('input' + campo.charAt(0).toUpperCase() + campo.slice(1));
    input.value = input.value === valor ? '' : valor;
    document.getElementById('searchForm').submit();
}

// Filtrar desde las tarjetas de stats
function filtrarPorEstado(valor) {
    document.getElementById('inputEstado').value = valor;
    document.getElementById('inputPrioridad').value = '';
    document.getElementById('searchForm').submit();
}

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
