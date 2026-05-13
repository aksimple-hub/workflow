@php use Illuminate\Support\Facades\Storage; @endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Perfil - Workflow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F7FA] font-sans antialiased text-gray-900">

    <!-- Top Navigation -->
    <nav class="bg-white border-b border-gray-200 py-4 px-8 flex justify-between items-center">
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard') }}" class="bg-[#214371] px-5 py-2 rounded-xl hover:bg-[#1E3A5F] transition-colors">
                <span class="text-white text-xl font-bold tracking-tight">Workflow</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#1E3A5F]">Mi Perfil</h1>
                <p class="text-xs text-gray-500">Configuración de cuenta</p>
            </div>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 bg-white border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                Volver al Dashboard
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-6">
        
        <!-- Left Sidebar (Profile Menu) -->
        <div class="w-full md:w-1/4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center">

                <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data" id="form-foto">
                    @csrf
                    <input type="file" id="input-foto" name="foto_perfil" accept="image/*" class="hidden"
                           onchange="document.getElementById('form-foto').submit()">
                </form>

                <div class="w-24 h-24 rounded-full overflow-hidden mb-4 bg-gray-200 flex items-center justify-center text-gray-400 cursor-pointer"
                     onclick="document.getElementById('input-foto').click()">
                    @if(auth()->user()->foto_perfil)
                        <img src="{{ Storage::url(auth()->user()->foto_perfil) }}" alt="Foto de perfil" class="w-full h-full object-cover">
                    @else
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    @endif
                </div>

                @error('foto_perfil')
                    <p class="text-xs text-red-500 mb-2">{{ $message }}</p>
                @enderror

                @if(session('status') === 'photo-updated')
                    <p class="text-xs text-[#10B981] mb-2">Foto actualizada.</p>
                @endif

                <button type="button" onclick="document.getElementById('input-foto').click()"
                        class="text-sm font-medium text-[#10B981] hover:text-[#059669] mb-6 transition-colors">
                    Cambiar foto
                </button>

                <div class="w-full space-y-2">
                    <button id="btn-tab-info" onclick="switchTab('tab-info')" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-[#10B981] text-white font-medium text-sm transition-colors border border-transparent">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Información Personal
                    </button>
                    <button id="btn-tab-security" onclick="switchTab('tab-security')" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium text-sm transition-colors border border-transparent hover:border-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        Seguridad
                    </button>
                    <button id="btn-tab-notifications" onclick="switchTab('tab-notifications')" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium text-sm transition-colors border border-transparent hover:border-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        Notificaciones
                    </button>
                </div>

                <div class="w-full mt-8 pt-6 border-t border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 text-sm font-medium text-red-500 hover:text-red-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Content Area -->
        <div class="w-full md:w-3/4">
            
            <!-- TAB: Información -->
            <div id="tab-info" class="tab-content space-y-6 block">
                <!-- Información Personal -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-[#1E3A5F]">Información Personal</h3>
                        <button onclick="toggleSection('edit-profile')" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Editar</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nombre completo</p>
                            <div class="flex items-center gap-3 bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 text-gray-700 font-medium">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                {{ auth()->user()->name }}
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Rol en el sistema</p>
                            <div class="flex items-center gap-3 bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 text-gray-700 font-medium">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                {{ ucfirst(auth()->user()->role) }}
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Correo electrónico</p>
                            <div class="flex items-center gap-3 bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 text-gray-700 font-medium">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Teléfono</p>
                            <div class="flex items-center gap-3 bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 text-gray-700 font-medium">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                @php
                                    $telefono = '+34 No asignado';
                                    if(auth()->user()->role === 'tecnico') {
                                        $tecnico = \App\Models\Tecnico::find(auth()->id());
                                        if($tecnico) $telefono = $tecnico->telefono;
                                    } elseif(auth()->user()->role === 'cliente') {
                                        $cliente = \App\Models\Cliente::find(auth()->id());
                                        if($cliente) $telefono = $cliente->telefono;
                                    }
                                @endphp
                                {{ $telefono }}
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Edit Form -->
                    <div id="edit-profile" class="hidden mt-8 pt-8 border-t border-gray-100">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- Información de la Cuenta -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-xl font-bold text-[#1E3A5F] mb-6">Información de la Cuenta</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-100">
                            <div class="text-3xl font-bold text-[#1E3A5F] mb-1">
                                @php
                                    $count = 0;
                                    if(auth()->user()->role === 'tecnico') {
                                        $count = \App\Models\OrdenTrabajo::where('usuario_id', auth()->id())->count();
                                    } elseif(auth()->user()->role === 'cliente') {
                                        $count = \App\Models\OrdenTrabajo::where('cliente_id', auth()->id())->count();
                                    } elseif(auth()->user()->role === 'admin') {
                                        $count = \App\Models\OrdenTrabajo::count();
                                    }
                                @endphp
                                {{ $count }}
                            </div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide font-medium">Órdenes Gestionadas</div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-100">
                            <div class="text-3xl font-bold text-[#1E3A5F] mb-1">
                                @php
                                    $iv = auth()->user()->created_at->toDateTime()->diff(new \DateTime());
                                    $td = (int) $iv->days;
                                    if ($td < 1)        $display = 'hoy';
                                    elseif ($td < 30)   $display = $td . ($td === 1 ? ' día' : ' días');
                                    elseif ($iv->y === 0) { $m = $iv->m; $display = $m . ($m === 1 ? ' mes' : ' meses'); }
                                    else                { $a = $iv->y; $display = $a . ($a === 1 ? ' año' : ' años'); }
                                @endphp
                                {{ $display }}
                            </div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide font-medium">Miembro desde</div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-100">
                            <div class="text-3xl font-bold text-[#10B981] mb-1 flex items-center justify-center gap-1">
                                @php
                                    $u = auth()->user();
                                    $avgRaw = match($u->role) {
                                        'tecnico'  => \App\Models\OrdenTrabajo::where('usuario_id', $u->id)->whereNotNull('satisfaccion')->avg('satisfaccion'),
                                        'cliente'  => \App\Models\OrdenTrabajo::where('cliente_id', $u->cliente_id)->whereNotNull('satisfaccion')->avg('satisfaccion'),
                                        default    => \App\Models\OrdenTrabajo::whereNotNull('satisfaccion')->avg('satisfaccion'),
                                    };
                                    $rating = $avgRaw ? round($avgRaw, 1) : null;
                                @endphp
                                @if($rating)
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    {{ $rating }}
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide font-medium">Valoración</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB: Seguridad -->
            <div id="tab-security" class="tab-content hidden space-y-6">
                <!-- Seguridad y Contraseña -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-xl font-bold text-[#1E3A5F] mb-6">Seguridad y Contraseña</h3>
                    
                    <div class="space-y-4">
                        <!-- Password Option -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex items-center gap-4">
                                <div class="text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#1E3A5F]">Contraseña</p>
                                    <p class="text-xs text-gray-500">Actualiza la contraseña de tu cuenta</p>
                                </div>
                            </div>
                            <button onclick="toggleSection('edit-password')" class="bg-[#214371] hover:bg-[#152e50] text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                                Cambiar Contraseña
                            </button>
                        </div>
                        
                        <!-- Hidden Password Form -->
                        <div id="edit-password" class="hidden my-6 p-6 border border-gray-100 rounded-xl">
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>

                        <!-- 2FA Option -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 opacity-70">
                            <div class="flex items-center gap-4">
                                <div class="text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#1E3A5F]">Autenticación de dos factores</p>
                                    <p class="text-xs text-gray-500">Añade una capa extra de seguridad (Próximamente)</p>
                                </div>
                            </div>
                            <button class="bg-white border border-gray-200 text-gray-400 px-5 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                                Activar
                            </button>
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="mt-12 pt-8 border-t border-red-100">
                        <h3 class="text-lg font-bold text-red-600 mb-4">Zona de Peligro</h3>
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB: Notificaciones -->
            <div id="tab-notifications" class="tab-content hidden space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="w-20 h-20 text-gray-200 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    <h3 class="text-2xl font-bold text-[#1E3A5F] mb-3">Notificaciones</h3>
                    <p class="text-gray-500 max-w-md mx-auto">No tienes notificaciones pendientes en este momento. Cuando haya actualizaciones sobre tus servicios aparecerán aquí.</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Ocultar todos los contenidos
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            
            // Mostrar el seleccionado
            document.getElementById(tabId).classList.remove('hidden');
            document.getElementById(tabId).classList.add('block');

            // Resetear estilos de todos los botones
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('bg-[#10B981]', 'text-white');
                el.classList.add('text-gray-600', 'hover:bg-gray-50', 'hover:border-gray-100');
            });

            // Activar estilo del botón seleccionado
            const activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.classList.remove('text-gray-600', 'hover:bg-gray-50', 'hover:border-gray-100');
            activeBtn.classList.add('bg-[#10B981]', 'text-white');
        }

        function toggleSection(sectionId) {
            const el = document.getElementById(sectionId);
            if(el.classList.contains('hidden')) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        }
        
        // Si hay errores en las validaciones, asegurar que las pestañas correctas se abran.
        @if ($errors->hasBag('updatePassword'))
            switchTab('tab-security');
            toggleSection('edit-password');
        @elseif ($errors->hasBag('userDeletion'))
            switchTab('tab-security');
        @elseif ($errors->any())
            switchTab('tab-info');
            toggleSection('edit-profile');
        @endif

        // Toast de notificación
        function showToast(message, type) {
            const colors = type === 'success'
                ? 'bg-[#10B981] text-white'
                : 'bg-red-500 text-white';
            const icon = type === 'success'
                ? '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                : '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';

            const toast = document.createElement('div');
            toast.className = `fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-4 rounded-2xl shadow-lg ${colors} transition-all duration-300 translate-y-0 opacity-100`;
            toast.innerHTML = `${icon}<span class="text-sm font-medium">${message}</span>`;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-1rem)';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        @if(session('status') === 'password-updated')
            showToast('Contraseña actualizada correctamente.', 'success');
        @elseif(session('status') === 'profile-updated')
            showToast('Perfil actualizado correctamente.', 'success');
        @elseif(session('status') === 'photo-updated')
            showToast('Foto de perfil actualizada.', 'success');
        @endif
    </script>
</body>
</html>
