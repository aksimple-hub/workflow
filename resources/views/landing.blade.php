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
                rgba(0,0,0,0.45) 0%,
                rgba(0,0,0,0.25) 40%,
                rgba(0,0,0,0.65) 100%
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

        {{-- ── Slide 5: Oficina moderna / tecnología ── --}}
        <div class="slide">
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=1920&q=80');"></div>
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

        {{-- Navigation dots --}}
        <div class="absolute bottom-44 left-0 right-0 flex justify-center items-center gap-2 z-20">
            <div class="dot active" data-slide="0" onclick="goToSlide(0)"></div>
            <div class="dot" data-slide="1" onclick="goToSlide(1)"></div>
            <div class="dot" data-slide="2" onclick="goToSlide(2)"></div>
            <div class="dot" data-slide="3" onclick="goToSlide(3)"></div>
            <div class="dot" data-slide="4" onclick="goToSlide(4)"></div>
        </div>

        {{-- CTA panel --}}
        <div class="absolute bottom-0 left-0 right-0 z-20 px-4 pb-6">
            <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl p-5 max-w-sm mx-auto">
                <div class="flex items-center justify-center mb-3">
                    <div class="bg-[#214371] px-5 py-2 rounded-xl">
                        <span class="text-white text-2xl font-extrabold tracking-tight">Workflow</span>
                    </div>
                </div>
                <p class="text-center text-gray-500 text-xs mb-4 uppercase tracking-wide font-medium">Sistema de gestión de servicios</p>
                <a href="{{ route('login') }}"
                   class="flex items-center justify-center w-full py-3.5 bg-[#214371] hover:bg-[#1a3560] text-white text-base font-bold rounded-2xl shadow-lg transition-all duration-200 mb-3 gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Iniciar sesión
                </a>
                <div class="flex gap-2">
                    <a href="{{ route('register') }}"
                       class="flex-1 py-2.5 border-2 border-[#214371] text-[#214371] text-sm font-semibold rounded-xl hover:bg-blue-50 transition-all duration-200 text-center">
                        Registrarme como cliente
                    </a>
                    <a href="{{ route('register.tecnico') }}"
                       class="flex-1 py-2.5 border-2 border-gray-300 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200 text-center">
                        Registrarme como técnico
                    </a>
                </div>
            </div>
        </div>
    </div>

<script>
    const slides = document.querySelectorAll('.slide');
    const dots   = document.querySelectorAll('.dot');
    let current  = 0;
    let timer;

    function goToSlide(n) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
        clearInterval(timer);
        timer = setInterval(nextSlide, 5000);
    }

    function nextSlide() { goToSlide(current + 1); }

    // Swipe support for mobile
    let touchStartX = 0;
    document.getElementById('slideshow').addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; });
    document.getElementById('slideshow').addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) goToSlide(diff > 0 ? current + 1 : current - 1);
    });

    timer = setInterval(nextSlide, 5000);
</script>
</body>
</html>
