<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Workflow — Gestión de Servicios de Campo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Slideshow ── */
        .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.4s ease-in-out;
        }
        .slide.active { opacity: 1; }

        .slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(0,0,0,0.50) 0%,
                rgba(0,0,0,0.38) 45%,
                rgba(0,0,0,0.78) 100%
            );
        }

        .dot {
            width: 8px; height: 8px;
            border-radius: 9999px;
            background: rgba(255,255,255,0.4);
            border: 1px solid rgba(255,255,255,0.5);
            transition: all 0.35s ease;
            cursor: pointer;
        }
        .dot.active { background: white; width: 24px; border-radius: 4px; }

        @keyframes kenBurns {
            from { transform: scale(1.0); }
            to   { transform: scale(1.08); }
        }
        .slide-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
        }
        .slide.active .slide-bg { animation: kenBurns 8s ease-out forwards; }

        .slide-content h1,
        .slide-content p,
        .slide-content span {
            text-shadow: 0 2px 8px rgba(0,0,0,0.85), 0 1px 3px rgba(0,0,0,0.9);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide.active .slide-content > * { animation: fadeUp 0.8s ease-out forwards; }
        .slide.active .slide-content > *:nth-child(2) { animation-delay: 0.15s; opacity: 0; }
        .slide.active .slide-content > *:nth-child(3) { animation-delay: 0.30s; opacity: 0; }

        /* ── Navbar ── */
        #navbar {
            background: transparent;
            backdrop-filter: none;
        }
        #navbar.scrolled {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(12px);
            box-shadow: 0 1px 24px rgba(0,0,0,0.10);
        }
        #navbar .nav-logo  { color: white; }
        #navbar .nav-link  { color: rgba(255,255,255,0.88); }
        #navbar .nav-cta   { background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.35); backdrop-filter: blur(4px); }
        #navbar.scrolled .nav-logo { color: #214371; }
        #navbar.scrolled .nav-link { color: #374151; }
        #navbar.scrolled .nav-link:hover { color: #214371; }
        #navbar.scrolled .nav-cta  { background: #214371; color: white; border-color: #214371; }

        /* ── Scroll indicator ── */
        @keyframes bounce-slow {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50%       { transform: translateX(-50%) translateY(7px); }
        }
        .scroll-indicator { animation: bounce-slow 2s infinite; }

        /* ── Section fade-in on scroll ── */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-white antialiased">

    {{-- ══════════════════════════════════════
         NAVBAR
    ══════════════════════════════════════ --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">

            {{-- Logo --}}
            <a href="#inicio" class="flex items-center gap-2 font-extrabold text-xl tracking-tight nav-logo transition-colors duration-300">
                <div class="w-8 h-8 bg-[#214371] rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                Workflow
            </a>

            {{-- Links desktop --}}
            <div class="hidden sm:flex items-center gap-6">
                <a href="#como-funciona" class="nav-link text-sm font-medium transition-colors duration-300 hover:opacity-100">Cómo funciona</a>
                <a href="#roles"         class="nav-link text-sm font-medium transition-colors duration-300 hover:opacity-100">Para quién</a>
                <a href="#caracteristicas" class="nav-link text-sm font-medium transition-colors duration-300 hover:opacity-100">Características</a>
                <a href="{{ route('login') }}" class="nav-cta text-sm font-semibold px-4 py-2 rounded-xl transition-all duration-300">
                    Iniciar sesión
                </a>
            </div>

            {{-- Mobile login --}}
            <a href="{{ route('login') }}" class="sm:hidden nav-cta text-sm font-semibold px-4 py-2 rounded-xl transition-all duration-300">
                Entrar
            </a>
        </div>
    </nav>


    {{-- ══════════════════════════════════════
         HERO — SLIDESHOW
    ══════════════════════════════════════ --}}
    <section id="inicio" class="relative h-screen overflow-hidden select-none">

        <div id="slideshow" class="absolute inset-0">

            {{-- Slide 1 --}}
            <div class="slide active">
                <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1581092921461-eab62e97a780?auto=format&fit=crop&w=1920&q=80');"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content relative h-full flex flex-col items-center justify-center text-white text-center px-8 pb-72 sm:pb-64 pointer-events-none">
                    <span class="text-sm font-semibold uppercase tracking-widest text-blue-300 mb-4">Gestión inteligente</span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-4 drop-shadow-lg">Optimiza tu<br>flujo de trabajo</h1>
                    <p class="text-lg lg:text-xl text-white/80 max-w-md leading-relaxed drop-shadow">Coordinación de técnicos, asignación de órdenes y seguimiento en tiempo real.</p>
                </div>
            </div>

            {{-- Slide 2 --}}
            <div class="slide">
                <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1531973576160-7125cd663d86?auto=format&fit=crop&w=1920&q=80');"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content relative h-full flex flex-col items-center justify-center text-white text-center px-8 pb-72 sm:pb-64 pointer-events-none">
                    <span class="text-sm font-semibold uppercase tracking-widest text-green-300 mb-4">Siempre conectados</span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-4 drop-shadow-lg">Técnicos siempre<br>coordinados</h1>
                    <p class="text-lg lg:text-xl text-white/80 max-w-md leading-relaxed drop-shadow">Tus técnicos reciben las órdenes al instante con toda la información del cliente.</p>
                </div>
            </div>

            {{-- Slide 3 --}}
            <div class="slide">
                <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=1920&q=80');"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content relative h-full flex flex-col items-center justify-center text-white text-center px-8 pb-72 sm:pb-64 pointer-events-none">
                    <span class="text-sm font-semibold uppercase tracking-widest text-yellow-300 mb-4">Calidad garantizada</span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-4 drop-shadow-lg">Clientes<br>satisfechos</h1>
                    <p class="text-lg lg:text-xl text-white/80 max-w-md leading-relaxed drop-shadow">Cada servicio finaliza con valoración y firma digital. Transparencia total.</p>
                </div>
            </div>

            {{-- Slide 4 --}}
            <div class="slide">
                <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1504148455328-c376907d081c?auto=format&fit=crop&w=1920&q=80');"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content relative h-full flex flex-col items-center justify-center text-white text-center px-8 pb-72 sm:pb-64 pointer-events-none">
                    <span class="text-sm font-semibold uppercase tracking-widest text-orange-300 mb-4">Profesionalidad</span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-4 drop-shadow-lg">Servicios de<br>alta calidad</h1>
                    <p class="text-lg lg:text-xl text-white/80 max-w-md leading-relaxed drop-shadow">Gestiona informes técnicos, fotos de avería y reportes detallados de cada trabajo.</p>
                </div>
            </div>

            {{-- Slide 5 --}}
            <div class="slide">
                <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=1920&q=80');"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content relative h-full flex flex-col items-center justify-center text-white text-center px-8 pb-72 sm:pb-64 pointer-events-none">
                    <span class="text-sm font-semibold uppercase tracking-widest text-purple-300 mb-4">Tu plataforma</span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-4 drop-shadow-lg">Todo bajo<br>control</h1>
                    <p class="text-lg lg:text-xl text-white/80 max-w-md leading-relaxed drop-shadow">Panel de administración en tiempo real. Historial completo de órdenes, técnicos y clientes.</p>
                </div>
            </div>
        </div>

        {{-- Flecha izquierda --}}
        <button onclick="userGoTo(current - 1)"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-20 hidden sm:flex items-center justify-center w-11 h-11 rounded-full bg-black/25 hover:bg-black/45 backdrop-blur-sm border border-white/20 transition-colors">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Flecha derecha --}}
        <button onclick="userGoTo(current + 1)"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-20 hidden sm:flex items-center justify-center w-11 h-11 rounded-full bg-black/25 hover:bg-black/45 backdrop-blur-sm border border-white/20 transition-colors">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        {{-- Dots + play/pause --}}
        <div class="absolute bottom-52 sm:bottom-44 left-0 right-0 flex justify-center items-center gap-3 z-20">
            <div class="dot active" data-slide="0" onclick="userGoTo(0)"></div>
            <div class="dot"        data-slide="1" onclick="userGoTo(1)"></div>
            <div class="dot"        data-slide="2" onclick="userGoTo(2)"></div>
            <div class="dot"        data-slide="3" onclick="userGoTo(3)"></div>
            <div class="dot"        data-slide="4" onclick="userGoTo(4)"></div>
            <button class="ms-1 w-7 h-7 rounded-full bg-white/15 border border-white/40 flex items-center justify-center hover:bg-white/30 transition-colors backdrop-blur-sm" id="toggleBtn" onclick="togglePlayPause()" title="Pausar / Reanudar">
                <svg id="iconPause" class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <rect x="5" y="4" width="4" height="16" rx="1"/>
                    <rect x="15" y="4" width="4" height="16" rx="1"/>
                </svg>
                <svg id="iconPlay" class="w-3.5 h-3.5 text-white hidden" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </button>
        </div>

        {{-- CTA panel --}}
        <div class="absolute bottom-0 left-0 right-0 z-20 px-4 pb-5 sm:pb-6">
            <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl p-3 sm:p-5 max-w-sm mx-auto">
                <div class="flex items-center justify-center mb-2">
                    <div class="bg-[#214371] px-5 py-1.5 rounded-xl">
                        <span class="text-white text-xl font-extrabold tracking-tight">Workflow</span>
                    </div>
                </div>
                <p class="text-center text-gray-500 text-xs mb-3 uppercase tracking-wide font-medium">Sistema de gestión de servicios</p>
                <a href="{{ route('login') }}"
                   class="flex items-center justify-center w-full py-3 bg-[#214371] hover:bg-[#1a3560] text-white font-bold rounded-2xl shadow-lg transition-all duration-200 mb-2 gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Iniciar sesión
                </a>
                <div class="flex gap-2">
                    <a href="{{ route('register') }}"
                       class="flex-1 py-2 border-2 border-[#214371] text-[#214371] text-sm font-semibold rounded-xl hover:bg-blue-50 transition-all text-center leading-tight">
                        Registrarme como<br>cliente
                    </a>
                    <a href="{{ route('register.tecnico') }}"
                       class="flex-1 py-2 border-2 border-[#214371] text-[#214371] text-sm font-semibold rounded-xl hover:bg-blue-50 transition-all text-center leading-tight">
                        Registrarme como<br>técnico
                    </a>
                </div>
            </div>
        </div>

        {{-- Scroll indicator (desktop) --}}
        <a href="#como-funciona" class="scroll-indicator hidden sm:flex flex-col items-center gap-1 absolute bottom-6 left-1/2 z-20 text-white/50 hover:text-white/80 transition-colors">
            <span class="text-xs uppercase tracking-widest font-medium">Descubre más</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </a>
    </section>


    {{-- ══════════════════════════════════════
         CÓMO FUNCIONA
    ══════════════════════════════════════ --}}
    <section id="como-funciona" class="bg-slate-900 py-24 sm:py-32 px-4">
        <div class="max-w-5xl mx-auto text-center">
            <div class="reveal">
                <span class="text-blue-400 text-sm font-semibold uppercase tracking-widest">Simple y eficaz</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-3 mb-4">Cómo funciona</h2>
                <p class="text-slate-400 text-lg max-w-xl mx-auto mb-20">En tres pasos sencillos, de la solicitud al servicio completado.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-10 relative">
                {{-- Línea conectora desktop --}}
                <div class="hidden sm:block absolute top-8 left-[16.67%] right-[16.67%] h-px bg-gradient-to-r from-blue-800 via-blue-500 to-blue-800 z-0"></div>

                {{-- Paso 1 --}}
                <div class="reveal relative z-10 flex flex-col items-center" style="transition-delay:0.1s">
                    <div class="w-16 h-16 bg-[#214371] rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-blue-950/60 ring-4 ring-slate-900">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-blue-400 text-xs font-bold uppercase tracking-widest mb-2">Paso 1</div>
                    <h3 class="text-white text-xl font-bold mb-3">Solicita el servicio</h3>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-xs mx-auto">El cliente crea una solicitud con fotos, descripción detallada y preferencia de horario.</p>
                </div>

                {{-- Paso 2 --}}
                <div class="reveal relative z-10 flex flex-col items-center" style="transition-delay:0.25s">
                    <div class="w-16 h-16 bg-[#214371] rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-blue-950/60 ring-4 ring-slate-900">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="text-blue-400 text-xs font-bold uppercase tracking-widest mb-2">Paso 2</div>
                    <h3 class="text-white text-xl font-bold mb-3">Asignamos un técnico</h3>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-xs mx-auto">El administrador asigna al técnico adecuado. Ambos reciben notificación inmediata por email.</p>
                </div>

                {{-- Paso 3 --}}
                <div class="reveal relative z-10 flex flex-col items-center" style="transition-delay:0.4s">
                    <div class="w-16 h-16 bg-[#214371] rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-blue-950/60 ring-4 ring-slate-900">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-blue-400 text-xs font-bold uppercase tracking-widest mb-2">Paso 3</div>
                    <h3 class="text-white text-xl font-bold mb-3">Servicio completado</h3>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-xs mx-auto">El técnico cierra la orden con informe, materiales usados y firma digital del cliente.</p>
                </div>
            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════
         PARA QUIÉN ES
    ══════════════════════════════════════ --}}
    <section id="roles" class="bg-white py-24 sm:py-32 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16 reveal">
                <span class="text-[#214371] text-sm font-semibold uppercase tracking-widest">Tres roles, una plataforma</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3 mb-4">Diseñado para todos</h2>
                <p class="text-slate-500 text-lg max-w-xl mx-auto">Cada perfil tiene su propio panel adaptado exactamente a sus necesidades.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                {{-- Admin --}}
                <div class="reveal group rounded-2xl border border-slate-100 p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 hover:border-[#214371]/25 transition-all duration-300" style="transition-delay:0.1s">
                    <div class="w-12 h-12 bg-blue-50 group-hover:bg-[#214371] rounded-xl flex items-center justify-center mb-5 transition-colors duration-300">
                        <svg class="w-6 h-6 text-[#214371] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-bold text-lg mb-1">Administrador</h3>
                    <p class="text-slate-400 text-xs mb-4">Control total de la operativa</p>
                    <ul class="space-y-2.5 text-sm text-slate-500">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Dashboard con estadísticas en tiempo real
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Crea y asigna órdenes de trabajo
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Gestión de técnicos y clientes
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Aprobación de nuevas cuentas
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Historial completo con filtros
                        </li>
                    </ul>
                </div>

                {{-- Técnico --}}
                <div class="reveal group rounded-2xl border border-slate-100 p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 hover:border-[#214371]/25 transition-all duration-300" style="transition-delay:0.2s">
                    <div class="w-12 h-12 bg-blue-50 group-hover:bg-[#214371] rounded-xl flex items-center justify-center mb-5 transition-colors duration-300">
                        <svg class="w-6 h-6 text-[#214371] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-bold text-lg mb-1">Técnico</h3>
                    <p class="text-slate-400 text-xs mb-4">Tu agenda de trabajo digital</p>
                    <ul class="space-y-2.5 text-sm text-slate-500">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Agenda semanal de servicios
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Navegación GPS al cliente
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Cierre con informe + fotos
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Recoge la firma digital
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Historial y valoraciones recibidas
                        </li>
                    </ul>
                </div>

                {{-- Cliente --}}
                <div class="reveal group rounded-2xl border border-slate-100 p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 hover:border-[#214371]/25 transition-all duration-300" style="transition-delay:0.3s">
                    <div class="w-12 h-12 bg-blue-50 group-hover:bg-[#214371] rounded-xl flex items-center justify-center mb-5 transition-colors duration-300">
                        <svg class="w-6 h-6 text-[#214371] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-bold text-lg mb-1">Cliente</h3>
                    <p class="text-slate-400 text-xs mb-4">Seguimiento total de tu servicio</p>
                    <ul class="space-y-2.5 text-sm text-slate-500">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Solicita servicios con fotos
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Tracker de estado en tiempo real
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Historial de todos sus servicios
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Cancelación de solicitudes
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Valora el servicio recibido
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════
         CARACTERÍSTICAS
    ══════════════════════════════════════ --}}
    <section id="caracteristicas" class="bg-slate-50 py-24 sm:py-32 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16 reveal">
                <span class="text-[#214371] text-sm font-semibold uppercase tracking-widest">Todo incluido</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3 mb-4">Todo lo que necesitas</h2>
                <p class="text-slate-500 text-lg max-w-xl mx-auto">Una plataforma completa para gestionar cada aspecto de tus servicios de campo.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                <div class="reveal bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200" style="transition-delay:0.05s">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-[#214371]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-semibold mb-1.5">Notificaciones por email</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Alertas automáticas en cada cambio de estado. Técnicos y clientes siempre al día.</p>
                </div>

                <div class="reveal bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200" style="transition-delay:0.10s">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-[#214371]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-semibold mb-1.5">Firma digital</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">El cliente firma directamente desde su móvil al cerrar el servicio. Sin papel.</p>
                </div>

                <div class="reveal bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200" style="transition-delay:0.15s">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-[#214371]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-semibold mb-1.5">Fotos adjuntas</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Hasta 5 fotos por solicitud (máx. 20 MB/foto). El técnico también adjunta fotos al cerrar.</p>
                </div>

                <div class="reveal bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200" style="transition-delay:0.20s">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-[#214371]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-semibold mb-1.5">Valoraciones bidireccionales</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">El cliente valora al técnico y el técnico al cliente. Mejora continua en cada servicio.</p>
                </div>

                <div class="reveal bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200" style="transition-delay:0.25s">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-[#214371]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-semibold mb-1.5">Filtros y búsqueda avanzada</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Busca órdenes por estado, prioridad o cliente. Historial completo con paginación.</p>
                </div>

                <div class="reveal bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200" style="transition-delay:0.30s">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-[#214371]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-semibold mb-1.5">100% responsive</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Optimizado para móvil y escritorio. Los técnicos trabajan desde su smartphone en campo.</p>
                </div>
            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════
         CTA FINAL
    ══════════════════════════════════════ --}}
    <section class="relative bg-[#214371] py-24 sm:py-28 px-4 text-center overflow-hidden">
        {{-- Decoración fondo --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/5 rounded-full"></div>
            <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-white/5 rounded-full"></div>
        </div>
        <div class="relative max-w-2xl mx-auto reveal">
            <span class="text-blue-300 text-sm font-semibold uppercase tracking-widest">Empieza hoy</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-3 mb-4">¿Listo para empezar?</h2>
            <p class="text-blue-200 text-lg mb-10 max-w-lg mx-auto leading-relaxed">Accede ahora y gestiona tus servicios de campo de forma profesional.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-[#214371] font-bold text-base rounded-2xl hover:bg-blue-50 transition-all shadow-xl shadow-blue-950/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
                    </svg>
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 border-2 border-white/40 text-white font-bold text-base rounded-2xl hover:bg-white/10 transition-all">
                    Crear cuenta gratis
                </a>
            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════
         FOOTER
    ══════════════════════════════════════ --}}
    <footer class="bg-slate-950 py-10 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-5 pb-8 mb-8 border-b border-slate-800">
                <a href="#inicio" class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-[#214371] rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="text-white font-extrabold text-lg tracking-tight">Workflow</span>
                </a>
                <nav class="flex flex-wrap items-center justify-center gap-6">
                    <a href="#como-funciona"   class="text-slate-400 hover:text-white text-sm transition-colors">Cómo funciona</a>
                    <a href="#roles"            class="text-slate-400 hover:text-white text-sm transition-colors">Para quién</a>
                    <a href="#caracteristicas"  class="text-slate-400 hover:text-white text-sm transition-colors">Características</a>
                    <a href="{{ route('login') }}" class="text-slate-400 hover:text-white text-sm transition-colors">Acceder</a>
                </nav>
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-slate-500 text-sm">
                <span>© 2026 Workflow — Sistema de Gestión de Servicios de Campo</span>
                <span>Trabajo de Fin de Grado · Akram</span>
            </div>
        </div>
    </footer>


<script>
    // ── Slideshow ──────────────────────────────────────
    const slides    = document.querySelectorAll('.slide');
    const dots      = document.querySelectorAll('.dot');
    const iconPause = document.getElementById('iconPause');
    const iconPlay  = document.getElementById('iconPlay');
    let current = 0;
    let timer   = null;
    let paused  = false;
    const INTERVAL = 8000;

    function goToSlide(n) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }
    function autoNext() { goToSlide(current + 1); }
    function userGoTo(n) { goToSlide(n); pause(); }
    function pause() {
        paused = true;
        clearInterval(timer);
        timer = null;
        iconPause.classList.add('hidden');
        iconPlay.classList.remove('hidden');
    }
    function resume() {
        paused = false;
        iconPlay.classList.add('hidden');
        iconPause.classList.remove('hidden');
        timer = setInterval(autoNext, INTERVAL);
    }
    function togglePlayPause() { paused ? resume() : pause(); }

    // Swipe móvil
    let touchStartX = 0;
    document.getElementById('slideshow').addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; });
    document.getElementById('slideshow').addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) userGoTo(diff > 0 ? current + 1 : current - 1);
    });

    timer = setInterval(autoNext, INTERVAL);

    // ── Navbar scroll ──────────────────────────────────
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 60);
    }, { passive: true });

    // ── Reveal on scroll ──────────────────────────────
    const revealEls = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });
    revealEls.forEach(el => observer.observe(el));
</script>
</body>
</html>
