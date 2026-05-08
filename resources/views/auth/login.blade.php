<x-guest-layout>
    <div class="flex min-h-screen bg-white relative">

        <div class="hidden lg:flex lg:w-7/12 bg-[#214371] p-16 flex-col justify-center items-center text-white relative">
            <div class="bg-white px-8 py-4 rounded-xl mb-12 shadow-lg">
                <span class="text-[#214371] text-5xl font-bold tracking-tight">Workflow</span>
            </div>

            <div class="text-center max-w-xl">
                <h1 class="text-5xl font-bold mb-6">Optimiza tu flujo de trabajo</h1>
                <p class="text-xl text-blue-100 leading-relaxed mb-16 px-10">
                    Sistema inteligente de gestión de servicios de campo. Coordina equipos, asigna rutas y mejora la satisfacción del cliente. [cite: 33, 37]
                </p>
            </div>

            <div class="grid grid-cols-2 gap-6 w-full max-w-2xl">
                <div class="bg-[#2c5282]/50 p-8 rounded-2xl border border-blue-400/30 text-center">
                    <span class="text-3xl mb-4 block">📍</span>
                    <h4 class="text-xl font-bold">Gestión de Rutas</h4>
                    <p class="text-sm text-blue-200">Optimización automática</p>
                </div>
                <div class="bg-[#2c5282]/50 p-8 rounded-2xl border border-blue-400/30 text-center">
                    <span class="text-3xl mb-4 block">⚡</span>
                    <h4 class="text-xl font-bold">Tiempo Real</h4>
                    <p class="text-sm text-blue-200">Seguimiento en vivo</p>
                </div>
                <div class="bg-[#2c5282]/50 p-8 rounded-2xl border border-blue-400/30 text-center">
                    <span class="text-3xl mb-4 block">📊</span>
                    <h4 class="text-xl font-bold">Reportes</h4>
                    <p class="text-sm text-blue-200">Análisis detallados</p>
                </div>
                <div class="bg-[#2c5282]/50 p-8 rounded-2xl border border-blue-400/30 text-center">
                    <span class="text-3xl mb-4 block">👥</span>
                    <h4 class="text-xl font-bold">Colaboración</h4>
                    <p class="text-sm text-blue-200">Equipo conectado</p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-5/12 flex flex-col justify-center items-center p-10 bg-gray-50">

            <div class="w-full max-w-md">
                <h2 class="text-4xl font-bold text-gray-900 mb-2">Bienvenido de nuevo</h2>
                <p class="text-gray-500 mb-8">Ingresa tus credenciales para continuar</p>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                        <ul class="text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="font-medium">• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white p-10 rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100">

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Correo electrónico</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center @error('email') text-red-400 @else text-gray-400 @enderror">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="w-full h-14 pl-14 bg-gray-50 border @error('email') border-red-500 @else border-gray-200 @enderror rounded-2xl focus:ring-2 focus:ring-[#10b981]"
                                       placeholder="usuario@empresa.com" required />
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Contraseña</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center @error('password') text-red-400 @else text-gray-400 @enderror">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </span>
                                <input type="password" name="password"
                                       class="w-full h-14 pl-14 bg-gray-50 border @error('password') border-red-500 @else border-gray-200 @enderror rounded-2xl focus:ring-2 focus:ring-[#10b981]"
                                       placeholder="••••••••" required />
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-10">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="w-5 h-5 rounded border-gray-300 text-[#10b981] focus:ring-[#10b981]">
                                <span class="ml-2 text-sm text-gray-600 font-medium">Recordarme</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm font-bold text-blue-600 hover:underline">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" class="w-full h-16 bg-[#10b981] hover:bg-[#059669] text-white text-xl font-bold rounded-2xl shadow-lg transition-all">
                            Iniciar Sesión
                        </button>
                    </form>
                </div>

                <p class="mt-8 text-center text-gray-600">
                    ¿No tienes una cuenta? <a href="{{ route('register') }}" class="text-[#10b981] font-bold hover:underline">Regístrate como cliente</a> o <a href="{{ route('register.tecnico') }}" class="text-[#214371] font-bold hover:underline">como técnico</a>
                </p>
                <p class="mt-4 text-center text-gray-600">
                    ¿Problemas para acceder? <a href="#" class="text-blue-700 font-bold hover:underline">Contacta con soporte</a>
                </p>
                <p class="mt-12 text-center text-xs text-gray-400 tracking-wide uppercase">
                    Workflow v2.0 © 2026 - Sistema de Gestión de Servicios [cite: 322]
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
