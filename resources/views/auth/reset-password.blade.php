<x-guest-layout>
    <div class="flex h-screen overflow-hidden bg-white">

        {{-- Panel izquierdo --}}
        <div class="hidden lg:flex lg:w-7/12 bg-[#214371] p-10 flex-col justify-center items-center text-white">
            <div class="bg-white px-6 py-3 rounded-xl mb-6 shadow-lg">
                <span class="text-[#214371] text-4xl font-bold tracking-tight">Workflow</span>
            </div>
            <div class="text-center max-w-xl">
                <h1 class="text-4xl font-bold mb-3">Nueva contraseña</h1>
                <p class="text-lg text-blue-100 leading-relaxed px-10">
                    Elige una contraseña segura para proteger tu cuenta. Recuerda no compartirla con nadie.
                </p>
            </div>
            <div class="mt-10 grid grid-cols-1 gap-3 w-full max-w-xs text-blue-200 text-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Mínimo 8 caracteres
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Ambas contraseñas deben coincidir
                </div>
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

                <h2 class="text-3xl font-bold text-gray-900 mb-1">Restablecer contraseña</h2>
                <p class="text-gray-500 mb-6">Introduce y confirma tu nueva contraseña</p>

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
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        {{-- Email --}}
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Correo electrónico</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input type="email" name="email" value="{{ old('email', $request->email) }}"
                                       class="w-full h-12 pl-12 bg-gray-50 border @error('email') border-red-500 @else border-gray-200 @enderror rounded-2xl focus:ring-2 focus:ring-[#10b981] focus:outline-none"
                                       placeholder="usuario@empresa.com" required autofocus autocomplete="username" />
                            </div>
                        </div>

                        {{-- Nueva contraseña --}}
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nueva contraseña</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <input type="password" id="password" name="password"
                                       class="w-full h-12 pl-12 pr-12 bg-gray-50 border @error('password') border-red-500 @else border-gray-200 @enderror rounded-2xl focus:ring-2 focus:ring-[#10b981] focus:outline-none"
                                       placeholder="••••••••" required autocomplete="new-password" />
                                <button type="button" onclick="togglePassword('password', 'eye-password')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg id="eye-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Confirmar contraseña --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Confirmar contraseña</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full h-12 pl-12 pr-12 bg-gray-50 border @error('password_confirmation') border-red-500 @else border-gray-200 @enderror rounded-2xl focus:ring-2 focus:ring-[#10b981] focus:outline-none"
                                       placeholder="••••••••" required autocomplete="new-password" />
                                <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg id="eye-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-3 bg-[#10b981] hover:bg-[#059669] text-white text-lg font-bold rounded-2xl shadow-lg transition-all">
                            Restablecer contraseña
                        </button>
                    </form>
                </div>

                <p class="mt-4 text-center text-gray-600 text-sm">
                    <a href="{{ route('login') }}" class="text-[#10b981] font-bold hover:underline">Volver al inicio de sesión</a>
                </p>

                <p class="mt-4 text-center text-xs text-gray-400 tracking-wide uppercase">
                    Workflow v2.0 © 2026 - Sistema de Gestión de Servicios
                </p>
            </div>
        </div>
    </div>

    <script>
    function togglePassword(inputId, eyeId) {
        const input = document.getElementById(inputId);
        const eye   = document.getElementById(eyeId);
        const show  = input.type === 'password';
        input.type  = show ? 'text' : 'password';
        eye.innerHTML = show
            ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
            : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
    </script>
</x-guest-layout>
