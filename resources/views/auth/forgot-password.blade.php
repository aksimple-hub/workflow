<x-guest-layout>
    <div class="flex h-screen overflow-hidden bg-white">

        {{-- Panel izquierdo --}}
        <div class="hidden lg:flex lg:w-7/12 bg-[#214371] p-10 flex-col justify-center items-center text-white">
            <div class="bg-white px-6 py-3 rounded-xl mb-6 shadow-lg">
                <span class="text-[#214371] text-4xl font-bold tracking-tight">Workflow</span>
            </div>
            <div class="text-center max-w-xl">
                <h1 class="text-4xl font-bold mb-3">¿Olvidaste tu contraseña?</h1>
                <p class="text-lg text-blue-100 leading-relaxed px-10">
                    No te preocupes. Introduce tu correo y te enviaremos un enlace para restablecerla en segundos.
                </p>
            </div>
            <div class="mt-10 flex items-center gap-4 text-blue-200 text-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Recibirás el enlace en tu bandeja de entrada
            </div>
        </div>

        {{-- Panel derecho --}}
        <div class="w-full lg:w-5/12 flex flex-col justify-center items-center px-10 py-6 bg-gray-50 overflow-y-auto">
            <div class="w-full max-w-md">

                {{-- Logo móvil --}}
                <div class="lg:hidden flex justify-center mb-6">
                    <div class="bg-[#214371] px-6 py-3 rounded-xl shadow-lg">
                        <span class="text-white text-2xl font-bold tracking-tight">Workflow</span>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-1">Recuperar contraseña</h2>
                <p class="text-gray-500 mb-6">Introduce tu correo y te enviamos el enlace</p>

                {{-- Estado de sesión (enlace enviado) --}}
                @if (session('status'))
                <div class="mb-5 p-4 bg-[#D1FAE5] border border-[#6EE7B7] text-[#065F46] rounded-xl text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('status') }}
                </div>
                @endif

                {{-- Errores --}}
                @if ($errors->any())
                <div class="mb-5 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg text-sm">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="font-medium">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="bg-white p-7 rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Correo electrónico</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="w-full h-12 pl-12 bg-gray-50 border @error('email') border-red-500 @else border-gray-200 @enderror rounded-2xl focus:ring-2 focus:ring-[#10b981] focus:outline-none"
                                       placeholder="usuario@empresa.com" required autofocus />
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full h-13 py-3 bg-[#10b981] hover:bg-[#059669] text-white text-lg font-bold rounded-2xl shadow-lg transition-all">
                            Enviar enlace de recuperación
                        </button>
                    </form>
                </div>

                <p class="mt-4 text-center text-gray-600 text-sm">
                    ¿Recuerdas tu contraseña?
                    <a href="{{ route('login') }}" class="text-[#10b981] font-bold hover:underline">Volver al inicio de sesión</a>
                </p>

                <p class="mt-4 text-center text-xs text-gray-400 tracking-wide uppercase">
                    Workflow v2.0 © 2026 - Sistema de Gestión de Servicios
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
