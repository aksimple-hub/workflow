@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-medium text-[#1E3A5F]">Nueva Solicitud</h1>
                <p class="text-base text-gray-500 mt-1">Registra una nueva petición de servicio</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-[#1E3A5F] hover:underline font-medium text-sm">
                Volver a mis solicitudes
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100">
                <form action="{{ route('ordenes.store') }}" method="POST">
                    @csrf
                    <!-- El cliente_id se asigna automáticamente desde la sesión del usuario -->
                    
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Título (Ocupa 2 columnas) -->
                        <div class="col-span-2">
                            <label for="titulo" class="block text-sm font-medium text-[#1E3A5F] mb-2">Asunto de la solicitud *</label>
                            <input type="text" id="titulo" name="titulo" required
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl px-4 py-3 focus:outline-none transition-colors text-base"
                                placeholder="Ej: Falla en el sistema de refrigeración">
                        </div>

                        <!-- Prioridad -->
                        <div>
                            <label for="prioridad" class="block text-sm font-medium text-[#1E3A5F] mb-2">Prioridad Estimada *</label>
                            <select id="prioridad" name="prioridad" required
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl px-4 py-3 focus:outline-none transition-colors text-base appearance-none">
                                <option value="" disabled selected>Selecciona una opción...</option>
                                <option value="baja">Baja - Puede esperar</option>
                                <option value="media">Media - Necesita atención pronta</option>
                                <option value="alta">Alta - Urgente</option>
                            </select>
                        </div>

                        <!-- Tipo de Servicio (Simulado, no está en la BD actual pero es común) -->
                        <div>
                            <label for="tipo_servicio" class="block text-sm font-medium text-[#1E3A5F] mb-2">Tipo de Servicio</label>
                            <select id="tipo_servicio" name="tipo_servicio"
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl px-4 py-3 focus:outline-none transition-colors text-base appearance-none">
                                <option value="mantenimiento">Mantenimiento Preventivo</option>
                                <option value="reparacion">Reparación de Avería</option>
                                <option value="instalacion">Nueva Instalación</option>
                            </select>
                        </div>

                        <!-- Descripción (Ocupa 2 columnas) -->
                        <div class="col-span-2">
                            <label for="descripcion" class="block text-sm font-medium text-[#1E3A5F] mb-2">Descripción detallada *</label>
                            <textarea id="descripcion" name="descripcion" required rows="6"
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-[#10B981] rounded-xl p-4 resize-none focus:outline-none transition-colors text-base"
                                placeholder="Por favor, describe con detalle el problema o la necesidad..."></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                            class="bg-[#10B981] hover:bg-[#059669] text-white px-8 py-4 rounded-xl shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-all font-medium text-base">
                            Enviar Solicitud
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection
