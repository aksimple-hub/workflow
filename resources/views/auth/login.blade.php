<x-guest-layout>
    <div class="flex h-screen overflow-hidden bg-white">

        <div class="hidden lg:flex lg:w-7/12 relative flex-col justify-center items-center text-white overflow-hidden"
             style="background: linear-gradient(145deg, #0f2349 0%, #214371 55%, #1a5c8a 100%);">

            {{-- Back button --}}
            <a href="{{ route('landing') }}"
               class="absolute top-6 left-6 flex items-center gap-1.5 text-blue-200 hover:text-white text-sm font-medium transition-colors duration-150 group">
                <svg class="w-4 h-4 transition-transform duration-150 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver al inicio
            </a>

            {{-- Decorative blobs --}}
            <div class="absolute -top-24 -left-24 w-80 h-80 rounded-full opacity-10 pointer-events-none" style="background: radial-gradient(circle, #60a5fa, transparent)"></div>
            <div class="absolute -bottom-20 -right-20 w-96 h-96 rounded-full opacity-10 pointer-events-none" style="background: radial-gradient(circle, #34d399, transparent)"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full opacity-5 pointer-events-none" style="background: radial-gradient(circle, #93c5fd, transparent)"></div>

            <div class="relative z-10 flex flex-col items-center px-14 w-full max-w-2xl">

                {{-- Logo --}}
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-white/20 border border-white/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="text-3xl font-extrabold tracking-tight">Workflow</span>
                </div>

                {{-- Headline --}}
                <div class="text-center mb-10">
                    <h1 class="text-4xl font-extrabold leading-tight mb-3">
                        Gestión de servicios<br>
                        <span class="text-blue-300">sin complicaciones</span>
                    </h1>
                    <p class="text-blue-200 text-base leading-relaxed max-w-sm mx-auto">
                        Conecta administradores, técnicos y clientes en una sola plataforma inteligente.
                    </p>
                </div>

                {{-- Feature cards --}}
                <div class="grid grid-cols-2 gap-3 w-full">

                    <div class="bg-white/8 hover:bg-white/12 border border-white/10 rounded-2xl p-5 flex items-start gap-4 transition-all duration-200" style="background:rgba(255,255,255,0.07)">
                        <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center" style="background:rgba(96,165,250,0.25)">
                            <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white">Órdenes de trabajo</h4>
                            <p class="text-xs text-blue-200 mt-0.5 leading-snug">Crea, asigna y cierra servicios al instante</p>
                        </div>
                    </div>

                    <div class="border border-white/10 rounded-2xl p-5 flex items-start gap-4 transition-all duration-200" style="background:rgba(255,255,255,0.07)">
                        <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center" style="background:rgba(52,211,153,0.25)">
                            <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white">Seguimiento en vivo</h4>
                            <p class="text-xs text-blue-200 mt-0.5 leading-snug">El cliente sigue su servicio paso a paso</p>
                        </div>
                    </div>

                    <div class="border border-white/10 rounded-2xl p-5 flex items-start gap-4 transition-all duration-200" style="background:rgba(255,255,255,0.07)">
                        <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center" style="background:rgba(251,146,60,0.25)">
                            <svg class="w-5 h-5 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white">Informes y reportes</h4>
                            <p class="text-xs text-blue-200 mt-0.5 leading-snug">Historial completo con fotos y firma digital</p>
                        </div>
                    </div>

                    <div class="border border-white/10 rounded-2xl p-5 flex items-start gap-4 transition-all duration-200" style="background:rgba(255,255,255,0.07)">
                        <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center" style="background:rgba(251,191,36,0.25)">
                            <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white">Valoraciones</h4>
                            <p class="text-xs text-blue-200 mt-0.5 leading-snug">Calidad medible en cada intervención</p>
                        </div>
                    </div>
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

                {{-- Google OAuth --}}
                <a href="{{ route('auth.google') }}"
                   class="flex items-center justify-center gap-3 w-full py-3 bg-white border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 rounded-2xl shadow-sm transition-all mb-4 font-semibold text-gray-700 text-sm">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continuar con Google
                </a>

                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs text-gray-400 font-medium">o con tu cuenta</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

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
                    ¿No tienes una cuenta? <a href="{{ route('register') }}" class="text-brand-green font-bold hover:underline">Regístrate como cliente</a> o <a href="{{ route('register.tecnico') }}" class="text-[#214371] font-bold hover:underline">regístrate como técnico</a>
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
