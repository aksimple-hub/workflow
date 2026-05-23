<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Workflow — Gestión de Servicios de Campo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body { margin: 0; padding: 0; height: 100%; overflow: hidden; }

        .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.4s ease-in-out;
            background-size: cover;
            background-position: center;
        }
        .slide.active { opacity: 1; }

        .slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(0,0,0,0.55) 0%,
                rgba(0,0,0,0.50) 40%,
                rgba(0,0,0,0.72) 100%
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
        .dot.active {
            background: white;
            width: 24px;
            border-radius: 4px;
        }
        .play-pause-btn {
            width: 28px; height: 28px;
            border-radius: 9999px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.4);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: background 0.2s ease;
            backdrop-filter: blur(4px);
        }
        .play-pause-btn:hover { background: rgba(255,255,255,0.3); }

        @keyframes kenBurns {
            from { transform: scale(1.0); }
            to   { transform: scale(1.08); }
        }
        .slide.active .slide-bg {
            animation: kenBurns 8s ease-out forwards;
        }
        .slide-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
        }

        .slide-content h1,
        .slide-content p,
        .slide-content span {
            text-shadow: 0 2px 8px rgba(0,0,0,0.85), 0 1px 3px rgba(0,0,0,0.9);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide.active .slide-content > * {
            animation: fadeUp 0.8s ease-out forwards;
        }
        .slide.active .slide-content > *:nth-child(2) { animation-delay: 0.15s; opacity: 0; }
        .slide.active .slide-content > *:nth-child(3) { animation-delay: 0.3s;  opacity: 0; }
    </style>
</head>
<body class="h-screen w-screen">

    <div class="relative h-full w-full select-none" id="slideshow">

        {{-- ── Slide 1: Técnico trabajando ── --}}
        <div class="slide active">
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1581092921461-eab62e97a780?auto=format&fit=crop&w=1920&q=80');"></div>
            <div class="slide-overlay"></div>
            <div class="slide-content relative h-full flex flex-col items-center justify-center text-white text-center px-8 pb-48 pointer-events-none">
                <span class="text-sm font-semibold uppercase tracking-widest text-blue-300 mb-4">Gestión inteligente</span>
                <h1 class="text-5xl lg:text-6xl font-extrabold leading-tight mb-5 drop-shadow-lg">
                    Optimiza tu<br>flujo de trabajo
                </h1>
                <p class="text-lg lg:text-xl text-white/80 max-w-md leading-relaxed drop-shadow">
                    Coordinación de técnicos, asignación de órdenes y seguimiento en tiempo real, todo en una plataforma.
                </p>
            </div>
        </div>

        {{-- ── Slide 2: Equipo en campo con tablet ── --}}
        <div class="slide">
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1531973576160-7125cd663d86?auto=format&fit=crop&w=1920&q=80');"></div>
            <div class="slide-overlay"></div>
            <div class="slide-content relative h-full flex flex-col items-center justify-center text-white text-center px-8 pb-48 pointer-events-none">
                <span class="text-sm font-semibold uppercase tracking-widest text-green-300 mb-4">Siempre conectados</span>
                <h1 class="text-5xl lg:text-6xl font-extrabold leading-tight mb-5 drop-shadow-lg">
                    Técnicos siempre<br>coordinados
                </h1>
                <p class="text-lg lg:text-xl text-white/80 max-w-md leading-relaxed drop-shadow">
                    Tus técnicos reciben las órdenes al instante con toda la información del cliente y la avería.
                </p>
            </div>
        </div>

        {{-- ── Slide 3: Satisfacción del cliente ── --}}
        <div class="slide">
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=1920&q=80');"></div>
            <div class="slide-overlay"></div>
            <div class="slide-content relative h-full flex flex-col items-center justify-center text-white text-center px-8 pb-48 pointer-events-none">
                <span class="text-sm font-semibold uppercase tracking-widest text-yellow-300 mb-4">Calidad garantizada</span>
                <h1 class="text-5xl lg:text-6xl font-extrabold leading-tight mb-5 drop-shadow-lg">
                    Clientes<br>satisfechos
                </h1>
                <p class="text-lg lg:text-xl text-white/80 max-w-md leading-relaxed drop-shadow">
                    Cada servicio finaliza con valoración del cliente y firma digital. Transparencia total en cada trabajo.
                </p>
            </div>
        </div>

        {{-- ── Slide 4: Herramientas profesionales ── --}}
        <div class="slide">
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1504148455328-c376907d081c?auto=format&fit=crop&w=1920&q=80');"></div>
            <div class="slide-overlay"></div>
            <div class="slide-content relative h-full flex flex-col items-center justify-center text-white text-center px-8 pb-48 pointer-events-none">
                <span class="text-sm font-semibold uppercase tracking-widest text-orange-300 mb-4">Profesionalidad</span>
                <h1 class="text-5xl lg:text-6xl font-extrabold leading-tight mb-5 drop-shadow-lg">
                    Servicios de<br>alta calidad
                </h1>
                <p class="text-lg lg:text-xl text-white/80 max-w-md leading-relaxed drop-shadow">
                    Gestiona informes técnicos, fotos de avería y reportes detallados de cada intervención realizada.
                </p>
            </div>
        </div>

        {{-- ── Slide 5: Técnico reparando instalación ── --}}
        <div class="slide">
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=1920&q=80');"></div>
            <div class="slide-overlay"></div>
            <div class="slide-content relative h-full flex flex-col items-center justify-center text-white text-center px-8 pb-48 pointer-events-none">
                <span class="text-sm font-semibold uppercase tracking-widest text-purple-300 mb-4">Tu plataforma</span>
                <h1 class="text-5xl lg:text-6xl font-extrabold leading-tight mb-5 drop-shadow-lg">
                    Todo bajo<br>control
                </h1>
                <p class="text-lg lg:text-xl text-white/80 max-w-md leading-relaxed drop-shadow">
                    Panel de administración en tiempo real. Historial completo de órdenes, técnicos y clientes.
                </p>
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

        {{-- Navigation dots + play/pause --}}
        <div class="absolute bottom-44 left-0 right-0 flex justify-center items-center gap-3 z-20">
            <div class="dot active" data-slide="0" onclick="userGoTo(0)"></div>
            <div class="dot" data-slide="1" onclick="userGoTo(1)"></div>
            <div class="dot" data-slide="2" onclick="userGoTo(2)"></div>
            <div class="dot" data-slide="3" onclick="userGoTo(3)"></div>
            <div class="dot" data-slide="4" onclick="userGoTo(4)"></div>
            <button class="play-pause-btn ms-1" id="toggleBtn" onclick="togglePlayPause()" title="Pausar / Reanudar">
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
        <div class="absolute bottom-0 left-0 right-0 z-20 px-4 pb-6">
            <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl p-6 max-w-sm mx-auto">
                <div class="flex items-center justify-center mb-3">
                    <div class="bg-[#214371] px-6 py-2.5 rounded-xl">
                        <span class="text-white text-3xl font-extrabold tracking-tight">Workflow</span>
                    </div>
                </div>
                <p class="text-center text-gray-500 text-sm mb-5 uppercase tracking-wide font-medium">Sistema de gestión de servicios</p>
                <a href="{{ route('login') }}"
                   class="flex items-center justify-center w-full py-4 bg-[#214371] hover:bg-[#1a3560] text-white text-lg font-bold rounded-2xl shadow-lg transition-all duration-200 mb-4 gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Iniciar sesión
                </a>
                <div class="flex gap-3">
                    <a href="{{ route('register') }}"
                       class="flex-1 py-3.5 border-2 border-[#214371] text-[#214371] text-base font-semibold rounded-xl hover:bg-blue-50 transition-all duration-200 text-center leading-tight">
                        Registrarme como<br>cliente
                    </a>
                    <a href="{{ route('register.tecnico') }}"
                       class="flex-1 py-3.5 border-2 border-[#214371] text-[#214371] text-base font-semibold rounded-xl hover:bg-blue-50 transition-all duration-200 text-center leading-tight">
                        Registrarme como<br>técnico
                    </a>
                </div>
            </div>
        </div>
    </div>

<script>
    const slides    = document.querySelectorAll('.slide');
    const dots      = document.querySelectorAll('.dot');
    const iconPause = document.getElementById('iconPause');
    const iconPlay  = document.getElementById('iconPlay');
    let current = 0;
    let timer   = null;
    let paused  = false;
    const INTERVAL = 8000;

    // Cambia al slide n (sin tocar el estado paused/play)
    function goToSlide(n) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }

    // Avance automático (solo llamado por el timer)
    function autoNext() { goToSlide(current + 1); }

    // El usuario hace clic en punto o flecha → ir al slide y pausar
    function userGoTo(n) {
        goToSlide(n);
        pause();
    }

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

    function togglePlayPause() {
        paused ? resume() : pause();
    }

    // Swipe móvil → navegar y pausar
    let touchStartX = 0;
    document.getElementById('slideshow').addEventListener('touchstart', e => {
        touchStartX = e.touches[0].clientX;
    });
    document.getElementById('slideshow').addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) userGoTo(diff > 0 ? current + 1 : current - 1);
    });

    // Arrancar
    timer = setInterval(autoNext, INTERVAL);
</script>
</body>
</html>
