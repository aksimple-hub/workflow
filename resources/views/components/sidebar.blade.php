@php
    $notificaciones = auth()->user()->notifications()->latest()->take(20)->get();
    $unreadCount    = auth()->user()->unreadNotifications()->count();
@endphp

<!-- Backdrop (mobile sidebar) -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-30 md:hidden hidden" onclick="closeSidebar()"></div>

<aside id="app-sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-brand-dark h-full flex flex-col text-white flex-shrink-0
           -translate-x-full md:translate-x-0 md:relative md:inset-auto transition-transform duration-300 ease-in-out">
    <!-- Logo Area -->
    <div class="h-20 flex items-center justify-center border-b border-brand-dark-mid">
        <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-white tracking-wide hover:opacity-80 transition-opacity">WorkFlow</a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-6 px-4 space-y-2">

        <!-- Link Dashboard / Agenda / Mis Servicios -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-brand-green text-white' : 'text-gray-300 hover:bg-brand-dark-mid hover:text-white' }}">
            @if(auth()->user()->role === 'tecnico')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="font-medium text-sm">Mi Agenda</span>
            @elseif(auth()->user()->role === 'cliente')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                <span class="font-medium text-sm">Mis Servicios</span>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span class="font-medium text-sm">Dashboard</span>
            @endif
        </a>

        @if(auth()->user()->role === 'admin')
        <a href="{{ route('ordenes.create') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('ordenes.create') ? 'bg-brand-green text-white' : 'text-gray-300 hover:bg-brand-dark-mid hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="font-medium text-sm">Crear Orden</span>
        </a>

        <a href="{{ route('admin.tecnicos') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.tecnicos') ? 'bg-brand-green text-white' : 'text-gray-300 hover:bg-brand-dark-mid hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            <span class="font-medium text-sm">Técnicos</span>
        </a>

        <a href="{{ route('admin.clientes') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.clientes') ? 'bg-brand-green text-white' : 'text-gray-300 hover:bg-brand-dark-mid hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            <span class="font-medium text-sm">Clientes</span>
        </a>

        <a href="{{ route('admin.historial') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.historial') ? 'bg-brand-green text-white' : 'text-gray-300 hover:bg-brand-dark-mid hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-medium text-sm">Historial</span>
        </a>

        <a href="{{ route('admin.configuracion') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.configuracion') ? 'bg-brand-green text-white' : 'text-gray-300 hover:bg-brand-dark-mid hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            <span class="font-medium text-sm">Configuración</span>
        </a>
        @endif

        @if(auth()->user()->role === 'tecnico')
        <a href="{{ route('tecnico.historial') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('tecnico.historial') ? 'bg-brand-green text-white' : 'text-gray-300 hover:bg-brand-dark-mid hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium text-sm">Mi Historial</span>
        </a>
        @endif

        @if(auth()->user()->role === 'cliente')
        <a href="{{ route('solicitud.nueva') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('solicitud.nueva') ? 'bg-brand-green text-white' : 'text-gray-300 hover:bg-brand-dark-mid hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span class="font-medium text-sm">Nueva Solicitud</span>
        </a>
        <a href="{{ route('dashboard') }}#historial"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-300 hover:bg-brand-dark-mid hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium text-sm">Historial</span>
        </a>
        @endif

        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('profile.edit') ? 'bg-brand-green text-white' : 'text-gray-300 hover:bg-brand-dark-mid hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            <span class="font-medium text-sm">Mi Perfil</span>
        </a>

        <!-- Notificaciones -->
        <button onclick="toggleNotifPanel()"
            class="relative flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-300 hover:bg-brand-dark-mid hover:text-white w-full text-left">
            <div class="relative flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($unreadCount > 0)
                <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-0.5 leading-none">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
                @endif
            </div>
            <span class="font-medium text-sm">Notificaciones</span>
            @if($unreadCount > 0)
            <span class="ml-auto text-[10px] font-bold bg-red-500 text-white px-1.5 py-0.5 rounded-full leading-none">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
            @endif
        </button>

    </nav>

    <!-- Logout / Perfil Area -->
    <div class="p-4 border-t border-brand-dark-mid">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 mb-4 p-2 rounded-lg hover:bg-brand-dark-mid transition-colors cursor-pointer">
            <div class="w-10 h-10 rounded-full bg-gray-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center justify-center gap-2 w-full py-2 rounded-lg text-gray-300 hover:bg-red-500 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span class="font-medium text-xs uppercase tracking-wider">Cerrar Sesión</span>
            </button>
        </form>
    </div>
</aside>

<!-- ── Panel de notificaciones ── -->
<div id="notif-backdrop" class="hidden fixed inset-0 z-[55] bg-black/40" onclick="closeNotifPanel()"></div>

<div id="notif-panel" class="hidden fixed inset-y-0 right-0 z-[60] w-80 sm:w-96 bg-white shadow-2xl flex flex-col border-l border-gray-200">

    <!-- Header del panel -->
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-white flex-shrink-0">
        <div class="flex items-center gap-2">
            <h2 class="text-base font-bold text-gray-900">Notificaciones</h2>
            @if($unreadCount > 0)
            <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="text-xs font-medium text-brand-green hover:text-brand-green-dark transition-colors">
                    Marcar todas
                </button>
            </form>
            @endif
            <button onclick="closeNotifPanel()" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Lista de notificaciones -->
    <div class="flex-1 overflow-y-auto">
        @forelse($notificaciones as $notif)
        @php
            $data   = $notif->data;
            $tipo   = $data['tipo'] ?? 'default';
            $leida  = !is_null($notif->read_at);
            $msg    = $data['mensaje']
                ?? (isset($data['tecnico_nombre']) ? 'Técnico: ' . $data['tecnico_nombre']
                : (isset($data['cliente_nombre']) ? 'Cliente: ' . $data['cliente_nombre']
                : (isset($data['orden_titulo'])   ? 'Orden: '   . $data['orden_titulo']
                : 'Nueva notificación')));

            $iconMap = [
                'aprobacion'    => ['bg' => 'bg-green-100',  'text' => 'text-green-600',  'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                'baja'          => ['bg' => 'bg-red-100',    'text' => 'text-red-600',    'path' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'],
                'nuevo_usuario' => ['bg' => 'bg-blue-100',   'text' => 'text-blue-600',   'path' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
                'nueva_orden'   => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                'orden_estado'  => ['bg' => 'bg-cyan-100',   'text' => 'text-cyan-600',   'path' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                'orden_cancelada'=> ['bg' => 'bg-orange-100','text' => 'text-orange-600', 'path' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                'orden_aplazada'=> ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'path' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ];
            $icon = $iconMap[$tipo] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-500', 'path' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'];
        @endphp

        <form id="nf-{{ $notif->id }}" method="POST" action="{{ route('notifications.read', $notif->id) }}" class="hidden">@csrf</form>

        <button onclick="document.getElementById('nf-{{ $notif->id }}').submit()"
            class="w-full flex items-start gap-3 px-4 py-4 border-b border-gray-50 transition-colors text-left {{ $leida ? 'hover:bg-gray-50' : 'bg-blue-50/60 hover:bg-blue-50' }}">
            <div class="flex-shrink-0 w-9 h-9 rounded-full {{ $icon['bg'] }} {{ $icon['text'] }} flex items-center justify-center mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm {{ $leida ? 'text-gray-600' : 'text-gray-900 font-medium' }} leading-snug">{{ $msg }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
            </div>
            @if(!$leida)
            <div class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mt-2"></div>
            @endif
        </button>

        @empty
        <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
            <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Sin notificaciones</p>
            <p class="text-xs text-gray-400 mt-1">Aquí aparecerán los avisos importantes</p>
        </div>
        @endforelse
    </div>
</div>

<script>
function toggleSidebar() {
    const s = document.getElementById('app-sidebar');
    const b = document.getElementById('sidebar-backdrop');
    const isHidden = s.classList.contains('-translate-x-full');
    s.classList.toggle('-translate-x-full', !isHidden);
    b.classList.toggle('hidden', !isHidden);
}
function closeSidebar() {
    document.getElementById('app-sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebar-backdrop').classList.add('hidden');
}
function toggleNotifPanel() {
    const panel    = document.getElementById('notif-panel');
    const backdrop = document.getElementById('notif-backdrop');
    const isHidden = panel.classList.contains('hidden');
    panel.classList.toggle('hidden', !isHidden);
    backdrop.classList.toggle('hidden', !isHidden);
    if (isHidden) closeSidebar();
}
function closeNotifPanel() {
    document.getElementById('notif-panel').classList.add('hidden');
    document.getElementById('notif-backdrop').classList.add('hidden');
}
</script>
