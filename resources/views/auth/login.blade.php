<x-guest-layout>
    <div class="flex h-screen overflow-hidden bg-white">

        <div class="hidden lg:flex lg:w-7/12 bg-[#214371] p-10 flex-col justify-center items-center text-white">
            <div class="bg-white px-6 py-3 rounded-xl mb-6 shadow-lg">
                <span class="text-[#214371] text-4xl font-bold tracking-tight">Workflow</span>
            </div>

            <div class="text-center max-w-xl">
                <h1 class="text-4xl font-bold mb-3">Optimiza tu flujo de trabajo</h1>
                <p class="text-lg text-blue-100 leading-relaxed mb-8 px-10">
                    Sistema inteligente de gestión de servicios de campo. Coordina equipos, asigna rutas y mejora la satisfacción del cliente.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 w-full max-w-2xl">
                <div class="bg-brand-dark-mid/50 p-5 rounded-2xl border border-blue-400/30 text-center">
                    <span class="text-2xl mb-2 block">📍</span>
                    <h4 class="text-base font-bold">Gestión de Rutas</h4>
                    <p class="text-xs text-blue-200">Optimización automática</p>
                </div>
                <div class="bg-brand-dark-mid/50 p-5 rounded-2xl border border-blue-400/30 text-center">
                    <span class="text-2xl mb-2 block">⚡</span>
                    <h4 class="text-base font-bold">Tiempo Real</h4>
                    <p class="text-xs text-blue-200">Seguimiento en vivo</p>
                </div>
                <div class="bg-brand-dark-mid/50 p-5 rounded-2xl border border-blue-400/30 text-center">
                    <span class="text-2xl mb-2 block">📊</span>
                    <h4 class="text-base font-bold">Reportes</h4>
                    <p class="text-xs text-blue-200">Análisis detallados</p>
                </div>
                <div class="bg-brand-dark-mid/50 p-5 rounded-2xl border border-blue-400/30 text-center">
                    <span class="text-2xl mb-2 block">👥</span>
                    <h4 class="text-base font-bold">Colaboración</h4>
                    <p class="text-xs text-blue-200">Equipo conectado</p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-5/12 flex flex-col justify-center items-center px-10 py-6 bg-gray-50 overflow-y-auto">

            <div class="w-full max-w-md">
                <!-- Logo (solo móvil) -->
                <div class="lg:hidden flex justify-center mb-6">
                    <div class="bg-[#214371] px-6 py-3 rounded-xl shadow-lg">
                        <span class="text-white text-2xl font-bold tracking-tight">Workflow</span>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-1">Bienvenido de nuevo</h2>
                <p class="text-gray-500 mb-4">Ingresa tus credenciales para continuar</p>

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                        <ul class="text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="font-medium">• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white p-7 rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100">

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Correo electrónico</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center @error('email') text-red-400 @else text-gray-400 @enderror">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="w-full h-12 pl-12 bg-gray-50 border @error('email') border-red-500 @else border-gray-200 @enderror rounded-2xl focus:ring-2 focus:ring-brand-green"
                                       placeholder="usuario@empresa.com" required />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Contraseña</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center @error('password') text-red-400 @else text-gray-400 @enderror">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </span>
                                <input type="password" id="login-password" name="password"
                                       class="w-full h-12 pl-12 pr-12 bg-gray-50 border @error('password') border-red-500 @else border-gray-200 @enderror rounded-2xl focus:ring-2 focus:ring-brand-green focus:outline-none"
                                       placeholder="••••••••" required />
                                <button type="button" onclick="toggleLoginPassword()"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg id="eye-login" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-brand-green focus:ring-brand-green">
                                <span class="ml-2 text-sm text-gray-600 font-medium">Recordarme</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm font-bold text-blue-600 hover:underline">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" class="w-full h-13 py-3 bg-brand-green hover:bg-brand-green-dark text-white text-lg font-bold rounded-2xl shadow-lg transition-all">
                            Iniciar Sesión
                        </button>
                    </form>
                </div>

                <p class="mt-4 text-center text-gray-600 text-sm">
                    ¿No tienes una cuenta? <a href="{{ route('register') }}" class="text-brand-green font-bold hover:underline">Regístrate como cliente</a> o <a href="{{ route('register.tecnico') }}" class="text-[#214371] font-bold hover:underline">como técnico</a>
                </p>
                <p class="mt-2 text-center text-gray-600 text-sm">
                    ¿Problemas para acceder? <a href="#" class="text-blue-700 font-bold hover:underline">Contacta con soporte</a>
                </p>
                <p class="mt-4 text-center text-xs text-gray-400 tracking-wide uppercase">
                    Workflow v2.0 © 2026 - Sistema de Gestión de Servicios
                </p>
            </div>
        </div>
    </div>

<script>
function toggleLoginPassword() {
    const input = document.getElementById('login-password');
    const eye   = document.getElementById('eye-login');
    const show  = input.type === 'password';
    input.type  = show ? 'text' : 'password';
    eye.innerHTML = show
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
}
</script>
</x-guest-layout>
