@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center gap-3">
            <button onclick="toggleSidebar()" class="md:hidden p-1.5 rounded-lg text-brand-dark hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="text-2xl md:text-4xl font-medium text-brand-dark">Asignación de Rutas</h1>
                <p class="text-sm md:text-base text-gray-500 mt-0.5">Crear y asignar órdenes de trabajo a los técnicos</p>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <!-- Split View -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Mapa -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden relative group min-h-64 lg:min-h-0 lg:h-[calc(100vh-10rem)]">
                    <div class="absolute inset-0 bg-[#E5E7EB] flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-2 group-hover:scale-110 transition-transform duration-200 ease-in-out" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                            <p class="text-brand-dark font-medium text-lg">Mapa de Operaciones</p>
                            <p class="text-gray-500 text-sm">Vista en vivo de las ubicaciones</p>
                        </div>
                    </div>
                </div>

                <!-- Panel de Gestión -->
                <div class="bg-white rounded-xl shadow-[0px_1px_3px_rgba(0,0,0,0.05)] border border-gray-100 p-6 overflow-y-auto">
                    <h2 class="text-xl font-medium text-brand-dark mb-6 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Nueva Orden de Trabajo
                    </h2>
                    
                    <form action="{{ route('ordenes.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <!-- Título -->
                        <div>
                            <label for="titulo" class="block text-sm font-medium text-brand-dark mb-2">Título de la Orden *</label>
                            <input type="text" id="titulo" name="titulo" required
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-brand-green rounded-xl px-4 py-3 focus:outline-none transition-colors text-base">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Cliente -->
                            <div>
                                <label for="cliente_id" class="block text-sm font-medium text-brand-dark mb-2">Cliente *</label>
                                <select id="cliente_id" name="cliente_id" required
                                    class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-brand-green rounded-xl px-4 py-3 focus:outline-none transition-colors text-base appearance-none">
                                    <option value="" disabled selected>Seleccione cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Prioridad -->
                            <div>
                                <label for="prioridad" class="block text-sm font-medium text-brand-dark mb-2">Prioridad *</label>
                                <select id="prioridad" name="prioridad" required
                                    class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-brand-green rounded-xl px-4 py-3 focus:outline-none transition-colors text-base appearance-none">
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                        </div>

                        <!-- Técnico (Opcional) -->
                        <div>
                            <label for="usuario_id" class="block text-sm font-medium text-brand-dark mb-2">Asignar Técnico</label>
                            <select id="usuario_id" name="usuario_id"
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-brand-green rounded-xl px-4 py-3 focus:outline-none transition-colors text-base appearance-none">
                                <option value="" selected>Dejar abierta (Sin asignar)</option>
                                @foreach($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-brand-dark mb-2">Descripción Detallada *</label>
                            <textarea id="descripcion" name="descripcion" required rows="4"
                                class="w-full bg-[#F5F7FA] border-2 border-transparent focus:border-brand-green rounded-xl p-4 resize-none focus:outline-none transition-colors text-base"></textarea>
                        </div>

                        <!-- Botón de Envío -->
                        <div class="pt-4 border-t border-gray-100">
                            <button type="submit" 
                                class="w-full bg-brand-green hover:bg-brand-green-dark text-white px-8 py-4 rounded-xl shadow-[0px_2px_8px_rgba(16,185,129,0.25)] transition-all duration-200 ease-in-out font-medium text-base">
                                Crear y Asignar Orden
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
