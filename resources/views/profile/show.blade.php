<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Sidebar Menu -->
                <div class="md:col-span-1">
                    <!-- Profile Avatar Section -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <div class="flex flex-col items-center">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <a href="#" class="text-green-600 font-semibold text-sm hover:underline mb-4">
                                {{ __('Cambiar foto') }}
                            </a>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <a href="#personal" onclick="switchTab('personal')" class="block w-full px-6 py-3 bg-green-600 text-white font-semibold hover:bg-green-700 flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Información Personal') }}
                        </a>
                        <a href="#security" onclick="switchTab('security')" class="block w-full px-6 py-3 text-gray-700 hover:bg-gray-50 border-b flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Seguridad') }}
                        </a>
                        <a href="#notifications" onclick="switchTab('notifications')" class="block w-full px-6 py-3 text-gray-700 hover:bg-gray-50 border-b flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 100 12 6 6 0 000-12zM0 10a10 10 0 1120 0 10 10 0 01-20 0z" />
                            </svg>
                            {{ __('Notificaciones') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="block w-full">
                            @csrf
                            <button type="submit" class="w-full px-6 py-3 text-red-600 hover:bg-red-50 flex items-center font-semibold">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm11 4.414l-4.293 4.293a1 1 0 01-1.414-1.414L12.586 7H10a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V7.414z" clip-rule="evenodd" />
                                </svg>
                                {{ __('Cerrar Sesión') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="md:col-span-3 space-y-6">
                    <!-- Personal Information Tab -->
                    <div id="personal-tab" class="tab-content">
                        <div class="bg-white rounded-lg shadow p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-2xl font-bold text-gray-900">{{ __('Información Personal') }}</h3>
                                <a href="{{ route('profile.edit') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                    {{ __('Editar') }}
                                </a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <div>
                                    <label class="text-gray-600 text-sm font-semibold">{{ __('Nombre completo') }}</label>
                                    <p class="text-gray-900 text-lg font-semibold mt-2">{{ $user->name }}</p>
                                </div>
                                <div>
                                    <label class="text-gray-600 text-sm font-semibold">{{ __('Rol en el sistema') }}</label>
                                    <div class="mt-2 flex items-center">
                                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-900 text-lg font-semibold capitalize">
                                            {{ ucfirst($user->role ?? 'usuario') }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-gray-600 text-sm font-semibold">{{ __('Correo electrónico') }}</label>
                                    <p class="text-gray-900 text-lg font-semibold mt-2">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <label class="text-gray-600 text-sm font-semibold">{{ __('Teléfono') }}</label>
                                    <p class="text-gray-900 text-lg font-semibold mt-2">{{ $user->phone ?? 'No disponible' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="bg-white rounded-lg shadow p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Información de la Cuenta') }}</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <p class="text-4xl font-bold text-gray-900">{{ $ordenesCount }}</p>
                                    <p class="text-gray-600 text-sm mt-2">{{ __('Órdenes Gestionadas') }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-4xl font-bold text-gray-900">{{ $memberSince }}</p>
                                    <p class="text-gray-600 text-sm mt-2">Miembro desde</p>
                                </div>
                                @if(auth()->user()->role !== 'admin')
                                <div class="text-center">
                                    @if($rating)
                                        <p class="text-4xl font-bold text-green-600">{{ $rating }}</p>
                                    @else
                                        <p class="text-4xl font-bold text-gray-300">—</p>
                                    @endif
                                    <p class="text-gray-600 text-sm mt-2">
                                        <svg class="inline w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Valoración media
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div id="security-tab" class="tab-content hidden">
                        <div class="bg-white rounded-lg shadow p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Seguridad y Contraseña') }}</h3>

                            <div class="space-y-6">
                                <!-- Password Section -->
                                <div class="border-b pb-6">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start">
                                            <svg class="w-6 h-6 text-gray-600 mr-4 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900">{{ __('Contraseña') }}</h4>
                                                <p class="text-gray-600 text-sm mt-1">{{ __('Última modificación hace 3 meses') }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('profile.edit') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                            {{ __('Cambiar Contraseña') }}
                                        </a>
                                    </div>
                                </div>

                                <!-- Two Factor Authentication -->
                                <div>
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start">
                                            <svg class="w-6 h-6 text-gray-600 mr-4 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M2.166 4.999a11.954 11.954 0 010 10.002 8 8 0 1015.668 0A11.954 11.954 0 012.166 5zm-1.084 0A10 10 0 0019.318 15h-2.205a6 6 0 00-11.226 0H1.082A10 10 0 001.082 5z" clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900">{{ __('Autenticación de dos factores') }}</h4>
                                                <p class="text-gray-600 text-sm mt-1">{{ __('Añade una capa extra de seguridad') }}</p>
                                            </div>
                                        </div>
                                        <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                            {{ __('Activar') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Tab -->
                    <div id="notifications-tab" class="tab-content hidden">
                        <div class="bg-white rounded-lg shadow p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Notificaciones') }}</h3>

                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="email-notifications" checked class="w-4 h-4 text-green-600 rounded">
                                    <label for="email-notifications" class="ml-3 text-gray-700 font-semibold">
                                        {{ __('Notificaciones por correo electrónico') }}
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="order-notifications" checked class="w-4 h-4 text-green-600 rounded">
                                    <label for="order-notifications" class="ml-3 text-gray-700 font-semibold">
                                        {{ __('Notificaciones de órdenes de trabajo') }}
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="system-notifications" checked class="w-4 h-4 text-green-600 rounded">
                                    <label for="system-notifications" class="ml-3 text-gray-700 font-semibold">
                                        {{ __('Notificaciones del sistema') }}
                                    </label>
                                </div>
                            </div>

                            <div class="mt-8">
                                <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold">
                                    {{ __('Guardar preferencias') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
            });

            // Show selected tab
            document.getElementById(tabName + '-tab').classList.remove('hidden');
        }
    </script>
</x-app-layout>
