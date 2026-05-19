<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Workflow — Gestión de Servicios de Campo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .slide { position: absolute; inset: 0; opacity: 0; transition: opacity 1.2s ease-in-out; }
        .slide.active { opacity: 1; }
        .dot { transition: all 0.3s ease; }
        .dot.active { background-color: white; transform: scale(1.3); }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .float-anim { animation: float 4s ease-in-out infinite; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.9s ease-out forwards; }
        .fade-up-delay { animation: fadeUp 0.9s ease-out 0.3s forwards; opacity: 0; }
        .fade-up-delay2 { animation: fadeUp 0.9s ease-out 0.6s forwards; opacity: 0; }
    </style>
</head>
<body class="overflow-hidden h-screen w-screen">

    {{-- Slideshow --}}
    <div class="relative h-full w-full" id="slideshow">

        {{-- Slide 1: Hero --}}
        <div class="slide active" style="background: linear-gradient(135deg, #0f2349 0%, #214371 50%, #1a5276 100%);">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full opacity-10" style="background: radial-gradient(circle, #4fc3f7, transparent)"></div>
                <div class="absolute bottom-20 -left-10 w-64 h-64 rounded-full opacity-10" style="background: radial-gradient(circle, #81c784, transparent)"></div>
            </div>
            <div class="relative h-full flex flex-col items-center justify-center text-white text-center px-8">
                <div class="float-anim mb-8">
                    <svg width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="45" cy="45" r="44" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                        <circle cx="45" cy="45" r="35" fill="rgba(255,255,255,0.08)"/>
                        <path d="M30 45 Q45 25 60 45 Q45 65 30 45Z" fill="rgba(79,195,247,0.6)" stroke="rgba(255,255,255,0.4)" stroke-width="1.5"/>
                        <circle cx="45" cy="45" r="8" fill="white" opacity="0.9"/>
                        <circle cx="45" cy="45" r="4" fill="#214371"/>
                    </svg>
                </div>
                <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight mb-4 leading-tight">
                    Optimiza tu<br>flujo de trabajo
                </h1>
                <p class="text-xl text-blue-200 max-w-lg leading-relaxed">
                    Gestión inteligente de servicios de campo. Coordina equipos, asigna técnicos y mejora la satisfacción del cliente.
                </p>
            </div>
        </div>

        {{-- Slide 2: Técnicos --}}
        <div class="slide" style="background: linear-gradient(135deg, #0d5c2e 0%, #1a7a3a 50%, #2e7d32 100%);">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-10 right-10 w-80 h-80 rounded-full opacity-10" style="background: radial-gradient(circle, #a5d6a7, transparent)"></div>
                <div class="absolute -bottom-10 left-20 w-48 h-48 rounded-full opacity-10" style="background: radial-gradient(circle, #fff9c4, transparent)"></div>
            </div>
            <div class="relative h-full flex flex-col items-center justify-center text-white text-center px-8">
                <div class="float-anim mb-8">
                    <svg width="90" height="90" viewBox="0 0 90 90" fill="none">
                        <circle cx="45" cy="45" r="44" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                        <circle cx="45" cy="45" r="35" fill="rgba(255,255,255,0.08)"/>
                        <circle cx="45" cy="35" r="12" fill="rgba(255,255,255,0.85)"/>
                        <path d="M22 70 Q22 52 45 52 Q68 52 68 70" fill="rgba(255,255,255,0.6)" stroke="rgba(255,255,255,0.4)" stroke-width="1"/>
                        <rect x="55" y="50" width="14" height="14" rx="3" fill="#2e7d32" opacity="0.9"/>
                        <path d="M57 57 l3 3 5-5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>
                <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight mb-4 leading-tight">
                    Técnicos siempre<br>conectados
                </h1>
                <p class="text-xl text-green-200 max-w-lg leading-relaxed">
                    Asigna órdenes de trabajo al instante. Tus técnicos reciben notificaciones en tiempo real con toda la información del servicio.
                </p>
            </div>
        </div>

        {{-- Slide 3: Seguimiento --}}
        <div class="slide" style="background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #0d47a1 100%);">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-16 left-16 w-72 h-72 rounded-full opacity-10" style="background: radial-gradient(circle, #90caf9, transparent)"></div>
                <div class="absolute bottom-16 right-8 w-56 h-56 rounded-full opacity-10" style="background: radial-gradient(circle, #b39ddb, transparent)"></div>
            </div>
            <div class="relative h-full flex flex-col items-center justify-center text-white text-center px-8">
                <div class="float-anim mb-8">
                    <svg width="90" height="90" viewBox="0 0 90 90" fill="none">
                        <circle cx="45" cy="45" r="44" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                        <circle cx="45" cy="45" r="35" fill="rgba(255,255,255,0.08)"/>
                        <rect x="25" y="28" width="40" height="34" rx="4" fill="rgba(255,255,255,0.2)" stroke="rgba(255,255,255,0.5)" stroke-width="1.5"/>
                        <line x1="25" y1="37" x2="65" y2="37" stroke="rgba(255,255,255,0.5)" stroke-width="1.5"/>
                        <circle cx="33" cy="33" r="2" fill="rgba(255,100,100,0.8)"/>
                        <circle cx="40" cy="33" r="2" fill="rgba(255,200,50,0.8)"/>
                        <circle cx="47" cy="33" r="2" fill="rgba(100,220,100,0.8)"/>
                        <path d="M31 46 L38 51 L46 44 L55 54" stroke="rgba(79,195,247,0.9)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <circle cx="55" cy="54" r="3" fill="rgba(79,195,247,0.9)"/>
                    </svg>
                </div>
                <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight mb-4 leading-tight">
                    Seguimiento<br>en tiempo real
                </h1>
                <p class="text-xl text-blue-200 max-w-lg leading-relaxed">
                    Los clientes siguen el estado de su servicio paso a paso. Desde la asignación hasta la valoración final.
                </p>
            </div>
        </div>

        {{-- Slide 4: Reportes --}}
        <div class="slide" style="background: linear-gradient(135deg, #4a148c 0%, #6a1b9a 50%, #7b1fa2 100%);">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-20 right-20 w-64 h-64 rounded-full opacity-10" style="background: radial-gradient(circle, #e1bee7, transparent)"></div>
                <div class="absolute bottom-10 left-10 w-80 h-80 rounded-full opacity-10" style="background: radial-gradient(circle, #f8bbd0, transparent)"></div>
            </div>
            <div class="relative h-full flex flex-col items-center justify-center text-white text-center px-8">
                <div class="float-anim mb-8">
                    <svg width="90" height="90" viewBox="0 0 90 90" fill="none">
                        <circle cx="45" cy="45" r="44" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                        <circle cx="45" cy="45" r="35" fill="rgba(255,255,255,0.08)"/>
                        <rect x="28" y="55" width="8" height="16" rx="2" fill="rgba(255,255,255,0.5)"/>
                        <rect x="41" y="44" width="8" height="27" rx="2" fill="rgba(255,255,255,0.7)"/>
                        <rect x="54" y="33" width="8" height="38" rx="2" fill="rgba(255,255,255,0.9)"/>
                        <path d="M28 48 L45 35 L62 24" stroke="rgba(255,200,100,0.9)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" stroke-dasharray="3 2"/>
                    </svg>
                </div>
                <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight mb-4 leading-tight">
                    Reportes y<br>valoraciones
                </h1>
                <p class="text-xl text-purple-200 max-w-lg leading-relaxed">
                    Análisis detallados del rendimiento. Cada servicio cuenta con informe técnico y valoración del cliente.
                </p>
            </div>
        </div>

        {{-- Slide 5: CTA extra --}}
        <div class="slide" style="background: linear-gradient(135deg, #b34700 0%, #e65100 50%, #bf360c 100%);">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-10 right-10 w-96 h-96 rounded-full opacity-10" style="background: radial-gradient(circle, #ffe082, transparent)"></div>
                <div class="absolute bottom-20 -left-16 w-72 h-72 rounded-full opacity-10" style="background: radial-gradient(circle, #ffccbc, transparent)"></div>
            </div>
            <div class="relative h-full flex flex-col items-center justify-center text-white text-center px-8">
                <div class="float-anim mb-8">
                    <svg width="90" height="90" viewBox="0 0 90 90" fill="none">
                        <circle cx="45" cy="45" r="44" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                        <circle cx="45" cy="45" r="35" fill="rgba(255,255,255,0.08)"/>
                        <polygon points="45,20 52,38 72,38 57,50 63,68 45,57 27,68 33,50 18,38 38,38" fill="rgba(255,224,130,0.85)" stroke="rgba(255,255,255,0.4)" stroke-width="1"/>
                    </svg>
                </div>
                <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight mb-4 leading-tight">
                    Tu plataforma<br>de confianza
                </h1>
                <p class="text-xl text-orange-200 max-w-lg leading-relaxed">
                    Administradores, técnicos y clientes en una sola plataforma. Eficiencia, transparencia y calidad de servicio garantizada.
                </p>
            </div>
        </div>

        {{-- Overlay gradient at bottom --}}
        <div class="absolute bottom-0 left-0 right-0 h-64 pointer-events-none" style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent)"></div>

        {{-- Navigation dots --}}
        <div class="absolute bottom-36 left-0 right-0 flex justify-center gap-2 z-10">
            <button class="dot active w-3 h-3 rounded-full bg-white/40 border border-white/30" data-slide="0" onclick="goToSlide(0)"></button>
            <button class="dot w-3 h-3 rounded-full bg-white/40 border border-white/30" data-slide="1" onclick="goToSlide(1)"></button>
            <button class="dot w-3 h-3 rounded-full bg-white/40 border border-white/30" data-slide="2" onclick="goToSlide(2)"></button>
            <button class="dot w-3 h-3 rounded-full bg-white/40 border border-white/30" data-slide="3" onclick="goToSlide(3)"></button>
            <button class="dot w-3 h-3 rounded-full bg-white/40 border border-white/30" data-slide="4" onclick="goToSlide(4)"></button>
        </div>

        {{-- Bottom CTA panel --}}
        <div class="absolute bottom-0 left-0 right-0 z-10 flex flex-col items-center pb-8 px-6">
            <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm text-center">
                <div class="flex items-center justify-center gap-2 mb-3">
                    <div class="bg-[#214371] px-4 py-1.5 rounded-lg">
                        <span class="text-white text-xl font-extrabold tracking-tight">Workflow</span>
                    </div>
                </div>
                <p class="text-gray-500 text-sm mb-4">Sistema de gestión de servicios de campo</p>
                <a href="{{ route('login') }}"
                   class="block w-full py-3.5 bg-[#214371] hover:bg-[#1a3560] text-white text-base font-bold rounded-2xl shadow-lg transition-all duration-200 mb-3">
                    Iniciar sesión
                </a>
                <div class="flex gap-2">
                    <a href="{{ route('register') }}"
                       class="flex-1 py-2.5 border-2 border-[#214371] text-[#214371] text-sm font-semibold rounded-xl hover:bg-blue-50 transition-all duration-200">
                        Soy cliente
                    </a>
                    <a href="{{ route('register.tecnico') }}"
                       class="flex-1 py-2.5 border-2 border-gray-300 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200">
                        Soy técnico
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
        current = n;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
        clearInterval(timer);
        timer = setInterval(nextSlide, 5000);
    }

    function nextSlide() {
        goToSlide((current + 1) % slides.length);
    }

    timer = setInterval(nextSlide, 5000);
</script>
</body>
</html>
