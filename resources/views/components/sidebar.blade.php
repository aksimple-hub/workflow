<!-- Backdrop (mobile) -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-30 md:hidden hidden" onclick="closeSidebar()"></div>

<aside id="app-sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-[#1E3A5F] h-full flex flex-col text-white flex-shrink-0
           -translate-x-full md:translate-x-0 md:relative md:inset-auto transition-transform duration-300 ease-in-out">
    <!-- Logo Area -->
    <div class="h-20 flex items-center justify-center border-b border-[#2C5282]">
        <h2 class="text-2xl font-bold text-white tracking-wide">WorkFlow</h2>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-6 px-4 space-y-2">

        <!-- Link Dashboard / Agenda / Mis Servicios -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-[#10B981] text-white' : 'text-gray-300 hover:bg-[#2C5282] hover:text-white' }}">
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
        <!-- Link Asignar Rutas (Admin) -->
        <a href="{{ route('ordenes.create') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('ordenes.create') ? 'bg-[#10B981] text-white' : 'text-gray-300 hover:bg-[#2C5282] hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
            <span class="font-medium text-sm">Asignar Rutas</span>
        </a>

        <!-- Link Técnicos -->
        <a href="{{ route('admin.tecnicos') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.tecnicos') ? 'bg-[#10B981] text-white' : 'text-gray-300 hover:bg-[#2C5282] hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            <span class="font-medium text-sm">Técnicos</span>
        </a>

        <!-- Link Clientes -->
        <a href="{{ route('admin.clientes') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.clientes') ? 'bg-[#10B981] text-white' : 'text-gray-300 hover:bg-[#2C5282] hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            <span class="font-medium text-sm">Clientes</span>
        </a>

        <!-- Link Historial -->
        <a href="{{ route('admin.historial') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.historial') ? 'bg-[#10B981] text-white' : 'text-gray-300 hover:bg-[#2C5282] hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-medium text-sm">Historial</span>
        </a>

        <!-- Link Configuración -->
        <a href="{{ route('admin.configuracion') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.configuracion') ? 'bg-[#10B981] text-white' : 'text-gray-300 hover:bg-[#2C5282] hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            <span class="font-medium text-sm">Configuración</span>
        </a>
        @endif

        @if(auth()->user()->role === 'tecnico')
        <!-- Links Técnico -->
        <a href="{{ route('tecnico.historial') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('tecnico.historial') ? 'bg-[#10B981] text-white' : 'text-gray-300 hover:bg-[#2C5282] hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium text-sm">Mi Historial</span>
        </a>
        @endif

        @if(auth()->user()->role === 'cliente')
        <!-- Links Cliente -->
        <a href="{{ route('solicitud.nueva') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('solicitud.nueva') ? 'bg-[#10B981] text-white' : 'text-gray-300 hover:bg-[#2C5282] hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span class="font-medium text-sm">Nueva Solicitud</span>
        </a>
        <a href="{{ route('dashboard') }}#historial"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-300 hover:bg-[#2C5282] hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium text-sm">Historial</span>
        </a>
        @endif

        <!-- Link Mi Perfil (Para todos los usuarios) -->
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('profile.edit') ? 'bg-[#10B981] text-white' : 'text-gray-300 hover:bg-[#2C5282] hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            <span class="font-medium text-sm">Mi Perfil</span>
        </a>
    </nav>

    <!-- Logout / Perfil Area -->
    <div class="p-4 border-t border-[#2C5282]">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 mb-4 p-2 rounded-lg hover:bg-[#2C5282] transition-colors cursor-pointer">
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
</script>
