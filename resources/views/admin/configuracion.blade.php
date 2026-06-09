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
                    <h1 class="text-lg sm:text-2xl font-medium text-brand-dark">Configuración del Sistema</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Ajustes generales y preferencias</p>
                </div>
            </div>
            <button class="flex-shrink-0 bg-brand-green hover:bg-brand-green-dark text-white px-4 sm:px-6 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm whitespace-nowrap">
                Guardar Cambios
            </button>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-4xl mx-auto w-full space-y-6">
                <!-- Tarjeta Perfil Empresa -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                    <h2 class="text-xl font-medium text-brand-dark mb-4 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        Datos de la Empresa
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Empresa</label>
                            <input type="text" value="WorkFlow S.L." class="w-full bg-[#F5F7FA] border border-transparent focus:border-brand-green rounded-lg px-4 py-2 focus:outline-none transition-colors text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email de Contacto</label>
                            <input type="email" value="contacto@workflow.com" class="w-full bg-[#F5F7FA] border border-transparent focus:border-brand-green rounded-lg px-4 py-2 focus:outline-none transition-colors text-sm">
                        </div>
                        <div class="col-span-1 sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notificaciones</label>
                            <div class="flex items-center gap-2 mt-2">
                                <input type="checkbox" id="notif1" checked class="rounded text-brand-green focus:ring-brand-green">
                                <label for="notif1" class="text-sm text-gray-600">Enviar email cuando se complete una orden</label>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <input type="checkbox" id="notif2" checked class="rounded text-brand-green focus:ring-brand-green">
                                <label for="notif2" class="text-sm text-gray-600">Avisar a clientes por nueva asignación</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Preferencias Visuales -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                    <h2 class="text-xl font-medium text-brand-dark mb-4 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
                        Apariencia
                    </h2>
                    <div class="flex flex-wrap gap-3">
                        <button data-theme-btn="light" onclick="setTheme('light')"
                            class="flex items-center gap-2 border-2 border-brand-green bg-[#F5F7FA] px-6 py-3 rounded-xl font-medium text-brand-dark transition-colors">
                            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
                            </svg>
                            Tema Claro
                        </button>
                        <button data-theme-btn="dark" onclick="setTheme('dark')"
                            class="flex items-center gap-2 border border-gray-200 hover:border-gray-300 bg-white px-6 py-3 rounded-xl font-medium text-gray-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                            </svg>
                            Tema Oscuro
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
