<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('profile.show')" :active="request()->routeIs('profile.show')">
                        {{ __('Mi Perfil') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Campana de notificaciones (solo admin) -->
            @if(Auth::check() && Auth::user()->role === 'admin')
            @php
                $notificacionesSinLeer = Auth::user()->unreadNotifications->whereIn('type', [
                    'App\Notifications\OrdenCanceladaAdmin',
                    'App\Notifications\OrdenAplazadaAdmin',
                ]);
                $totalSinLeer = $notificacionesSinLeer->count();
            @endphp
            <div class="hidden sm:flex sm:items-center sm:ms-4" x-data="{ notifOpen: false }">
                <div class="relative">
                    <button @click="notifOpen = !notifOpen" @click.outside="notifOpen = false"
                        class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($totalSinLeer > 0)
                        <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                            {{ $totalSinLeer > 9 ? '9+' : $totalSinLeer }}
                        </span>
                        @endif
                    </button>

                    <!-- Panel desplegable de notificaciones -->
                    <div x-show="notifOpen" x-transition
                        class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                        style="display: none;">

                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-800">Incidencias pendientes</h3>
                            @if($totalSinLeer > 0)
                            <form method="POST" action="{{ route('notifications.read-all') }}">
                                @csrf
                                <button type="submit" class="text-xs text-blue-600 hover:underline">Marcar todas como leídas</button>
                            </form>
                            @endif
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
                            @forelse($notificacionesSinLeer->take(10) as $notif)
                            @php
                                $data = $notif->data;
                                $tipo = $data['tipo'] ?? '';

                                if (!empty($data['tecnico_id']) && !empty($data['tecnico_nombre']) && empty($data['orden_id']) && empty($data['mensaje'])) {
                                    // NuevoTecnicoRegistrado
                                    $titulo   = 'Nuevo técnico registrado';
                                    $etiqueta = $data['tecnico_nombre'] . ' · ' . ($data['tecnico_email'] ?? '');
                                    $dotColor = 'bg-blue-500';
                                    $accion   = route('admin.tecnicos');
                                } elseif (!empty($data['mensaje'])) {
                                    // TecnicoAprobado
                                    $titulo   = 'Cuenta activada';
                                    $etiqueta = $data['mensaje'];
                                    $dotColor = 'bg-green-500';
                                    $accion   = route('dashboard');
                                } elseif ($tipo === 'aplazamiento') {
                                    $titulo   = $data['orden_titulo'] ?? 'Orden aplazada';
                                    $etiqueta = 'Técnico aplazó · ' . ($data['tecnico_nombre'] ?? 'Desconocido');
                                    $dotColor = 'bg-amber-400';
                                    $accion   = route('admin.orden.show', $data['orden_id'] ?? 0);
                                } else {
                                    $canceladoPor = $data['cancelado_por'] ?? 'tecnico';
                                    $titulo   = $data['orden_titulo'] ?? 'Orden #' . ($data['orden_id'] ?? '');
                                    $etiqueta = $canceladoPor === 'tecnico'
                                        ? 'Técnico canceló · ' . ($data['tecnico_nombre'] ?? 'Desconocido')
                                        : 'Cliente canceló · ' . ($data['cliente_nombre'] ?? 'Desconocido');
                                    $dotColor = 'bg-red-500';
                                    $accion   = route('admin.orden.show', $data['orden_id'] ?? 0);
                                }
                            @endphp
                            <div class="px-4 py-3 hover:bg-gray-50">
                                <div class="flex items-start gap-3">
                                    <span class="mt-1.5 flex-shrink-0 w-2 h-2 rounded-full {{ $dotColor }}"></span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $titulo }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $etiqueta }}</p>
                                        @if(!empty($data['motivo']))
                                        <p class="text-xs text-gray-400 truncate mt-0.5">{{ str($data['motivo'])->limit(60) }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                                        @csrf
                                        <input type="hidden" name="redirect" value="{{ $accion }}">
                                        <button type="submit" class="flex-shrink-0 text-xs text-blue-600 hover:text-blue-800 font-medium whitespace-nowrap">
                                            Ver →
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <div class="px-4 py-6 text-center text-sm text-gray-400">
                                Sin notificaciones pendientes
                            </div>
                            @endforelse
                        </div>

                        @if($totalSinLeer > 10)
                        <div class="px-4 py-2 border-t border-gray-100 text-center">
                            <p class="text-xs text-gray-400">+{{ $totalSinLeer - 10 }} más sin leer</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.show')">
                            {{ __('Mi Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile.show')" :active="request()->routeIs('profile.show')">
                {{ __('Mi Perfil') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.show')">
                    {{ __('Mi Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
