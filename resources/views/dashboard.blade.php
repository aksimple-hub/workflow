<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if(auth()->user()->role === 'admin')
                    {{ __('Panel de Control - Administración') }}
                @elseif(auth()->user()->role === 'tecnico')
                    {{ __('Mi Agenda Diaria') }}
                @else
                    {{ __('Mis Servicios') }}
                @endif
            </h2>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('ordenes.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-sm">
                    + Nueva Orden
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->role === 'admin')
                {{-- MOCKUP M-03: DASHBOARD ADMINISTRADOR --}}
                @include('dashboards.admin_view')
            @elseif(auth()->user()->role === 'tecnico')
                {{-- MOCKUP M-05: MI AGENDA (TÉCNICO) --}}
                @include('dashboards.tecnico_view')
            @else
                {{-- MOCKUP M-08: PORTAL DEL CLIENTE --}}
                @include('dashboards.cliente_view')
            @endif
        </div>
    </div>
</x-app-layout>
