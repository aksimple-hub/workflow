@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3 min-w-0">
                <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-[#1E3A5F] hover:bg-gray-100 transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-2xl md:text-4xl font-medium text-[#1E3A5F]">Configuración del Sistema</h1>
                    <p class="text-sm md:text-base text-gray-500 mt-0.5">Ajustes generales y preferencias</p>
                </div>
            </div>
            <button class="flex-shrink-0 bg-[#10B981] hover:bg-[#059669] text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                Guardar Cambios
            </button>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-4xl space-y-6">
                <!-- Tarjeta Perfil Empresa -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                    <h2 class="text-xl font-medium text-[#1E3A5F] mb-4 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        Datos de la Empresa
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Empresa</label>
                            <input type="text" value="WorkFlow S.L." class="w-full bg-[#F5F7FA] border border-transparent focus:border-[#10B981] rounded-lg px-4 py-2 focus:outline-none transition-colors text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email de Contacto</label>
                            <input type="email" value="contacto@workflow.com" class="w-full bg-[#F5F7FA] border border-transparent focus:border-[#10B981] rounded-lg px-4 py-2 focus:outline-none transition-colors text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notificaciones</label>
                            <div class="flex items-center gap-2 mt-2">
                                <input type="checkbox" id="notif1" checked class="rounded text-[#10B981] focus:ring-[#10B981]">
                                <label for="notif1" class="text-sm text-gray-600">Enviar email cuando se complete una orden</label>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <input type="checkbox" id="notif2" checked class="rounded text-[#10B981] focus:ring-[#10B981]">
                                <label for="notif2" class="text-sm text-gray-600">Avisar a clientes por nueva asignación</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Preferencias Visuales -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                    <h2 class="text-xl font-medium text-[#1E3A5F] mb-4 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
                        Apariencia
                    </h2>
                    <div class="flex gap-4">
                        <button class="border-2 border-[#10B981] bg-[#F5F7FA] px-6 py-3 rounded-xl font-medium text-[#1E3A5F]">
                            Tema Claro
                        </button>
                        <button class="border border-gray-200 hover:border-gray-300 bg-white px-6 py-3 rounded-xl font-medium text-gray-500 transition-colors">
                            Tema Oscuro
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
