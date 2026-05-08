@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F5F7FA]">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.tecnicos') }}" class="text-gray-400 hover:text-[#10B981] transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-[#1E3A5F]">Añadir Nuevo Técnico</h1>
                    <p class="text-sm text-gray-500 mt-1">Registra a un miembro del equipo técnico</p>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 flex justify-center">
            <div class="w-full max-w-3xl bg-white p-10 rounded-[2rem] shadow-[0_10px_40px_-15px_rgba(0,0,0,0.05)] border border-gray-100 h-fit">
                
                <div class="mb-8 flex items-center gap-4">
                    <div class="bg-[#214371] px-6 py-2 rounded-xl">
                        <span class="text-white text-xl font-bold tracking-tight">Workflow</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-[#1E3A5F]">Ficha de Técnico</h2>
                        <p class="text-gray-500 text-sm">Completa los datos del técnico</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                        <ul class="text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.tecnicos.store') }}">
                    @csrf
                    
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-[#1E3A5F] mb-4 border-b pb-2">1. Datos Personales</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-sm font-bold text-[#1E3A5F] mb-2">Nombre *</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#10b981]" required />
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#1E3A5F] mb-2">Apellidos *</label>
                                <input type="text" name="apellidos" value="{{ old('apellidos') }}" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#10b981]" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-sm font-bold text-[#1E3A5F] mb-2">DNI / NIE *</label>
                                <input type="text" name="dni_nie" value="{{ old('dni_nie') }}" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#10b981]" required />
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#1E3A5F] mb-2">Teléfono *</label>
                                <input type="text" name="telefono" value="{{ old('telefono') }}" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#10b981]" required />
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-[#1E3A5F] mb-2">Dirección *</label>
                            <input type="text" name="direccion" value="{{ old('direccion') }}" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#10b981]" required />
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-[#1E3A5F] mb-4 border-b pb-2">2. Credenciales y Perfil</h3>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-[#1E3A5F] mb-2">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#10b981]" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-sm font-bold text-[#1E3A5F] mb-2">Contraseña *</label>
                                <input type="password" name="password" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#10b981]" required />
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#1E3A5F] mb-2">Confirmar Contraseña *</label>
                                <input type="password" name="password_confirmation" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#10b981]" required />
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-[#1E3A5F] mb-2">Experiencia (Opcional)</label>
                            <textarea name="experiencia" class="w-full h-24 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#10b981]">{{ old('experiencia') }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                        <a href="{{ route('admin.tecnicos') }}" class="text-sm font-medium text-gray-600 hover:text-[#1E3A5F]">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-[#214371] hover:bg-[#152e50] text-white px-8 py-3 rounded-xl font-bold transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                            Crear Técnico
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection
