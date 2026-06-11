<x-guest-layout>
    <div class="flex min-h-screen bg-white relative">
        <a href="{{ route('landing') }}" class="absolute top-6 left-6 flex items-center gap-2 text-gray-600 hover:text-brand-green font-medium transition-colors z-50 bg-white/80 backdrop-blur-md px-4 py-2 rounded-xl shadow-sm border border-gray-100 lg:bg-transparent lg:border-none lg:shadow-none lg:text-white lg:hover:text-blue-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>

        <!-- Left Column -->
        <div class="hidden lg:flex lg:w-5/12 bg-[#214371] p-16 flex-col justify-center items-center text-white relative">
            <div class="bg-white px-8 py-3 rounded-xl mb-12 shadow-lg">
                <span class="text-[#214371] text-4xl font-bold tracking-tight">Workflow</span>
            </div>

            <div class="text-center w-full max-w-sm mx-auto">
                <h1 class="text-4xl font-bold mb-4">Área de Clientes</h1>
                <p class="text-blue-100 leading-relaxed mb-12">
                    Crea tu cuenta de cliente para solicitar y gestionar tus servicios de forma eficiente.
                </p>

                <div class="space-y-4 text-left w-full">
                    <!-- Step 1 Nav -->
                    <div id="container-1" onclick="goToStep(1)" class="bg-white/10 rounded-xl p-4 flex items-center gap-4 border border-white/20 cursor-pointer">
                        <div id="indicator-1" class="w-10 h-10 bg-white text-[#214371] rounded-full flex items-center justify-center font-bold">1</div>
                        <div>
                            <h4 class="font-bold">Datos Personales</h4>
                            <p class="text-xs text-blue-200">Información básica y contacto</p>
                        </div>
                    </div>
                    <!-- Step 2 Nav -->
                    <div id="container-2" onclick="goToStep(2)" class="bg-white/5 hover:bg-white/10 rounded-xl p-4 flex items-center gap-4 border border-transparent cursor-pointer transition-colors">
                        <div id="indicator-2" class="w-10 h-10 bg-white/20 text-white rounded-full flex items-center justify-center font-bold transition-colors">2</div>
                        <div>
                            <h4 class="font-bold">Credenciales</h4>
                            <p class="text-xs text-blue-200">Acceso y seguridad</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="w-full lg:w-7/12 flex flex-col justify-center items-center p-4 md:p-10 bg-gray-50">

            <div class="w-full max-w-2xl bg-white p-6 md:p-10 rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100">
                <div class="mb-8">
                    <h2 id="step-title" class="text-3xl font-bold text-brand-dark">Datos Personales</h2>
                    <p id="step-subtitle" class="text-gray-500">Ingresa tu información básica y de contacto</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                        <ul class="text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div id="js-error-box" class="hidden mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-medium"></span>
                </div>

                <form id="registro-form" method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- STEP 1: Datos Personales -->
                    <div id="step-1" class="step-content">
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-brand-dark mb-2">Nombre / Empresa *</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-green" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Tipo y Documento -->
                            <div>
                                <label class="block text-sm font-bold text-brand-dark mb-2">Documento *</label>
                                <div class="flex">
                                    <select name="tipo_documento" class="w-24 h-12 px-2 bg-gray-50 border border-gray-200 rounded-l-xl focus:ring-2 focus:ring-brand-green border-r-0 text-sm font-medium text-gray-700 focus:outline-none">
                                        <option value="DNI">DNI</option>
                                        <option value="NIE">NIE</option>
                                        <option value="CIF">CIF</option>
                                    </select>
                                    <input type="text" name="dni_cif" value="{{ old('dni_cif') }}" class="flex-1 w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-r-xl focus:ring-2 focus:ring-brand-green" placeholder="Número..." required />
                                </div>
                            </div>
                            <!-- Teléfono -->
                            <div>
                                <label class="block text-sm font-bold text-brand-dark mb-2">Teléfono *</label>
                                <input type="text" name="telefono" value="{{ old('telefono') }}" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-green" required />
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-brand-dark mb-2">Dirección *</label>
                            <input type="text" name="direccion" value="{{ old('direccion') }}" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-green" required />
                        </div>

                        <div class="flex flex-col-reverse gap-3 md:flex-row md:items-center md:justify-between mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-900 font-medium text-center md:text-left">
                                ¿Ya tienes cuenta? <span class="font-bold text-brand-green">Inicia sesión</span>
                            </a>
                            <button type="button" onclick="goToStep(2)" class="w-full md:w-auto bg-brand-green hover:bg-brand-green-dark text-white px-8 py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                                Siguiente <span class="text-xl leading-none">&rarr;</span>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Credenciales -->
                    <div id="step-2" class="step-content hidden">
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-brand-dark mb-2">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-green" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Contraseña -->
                            <div>
                                <label class="block text-sm font-bold text-brand-dark mb-2">Contraseña *</label>
                                <input type="password" name="password" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-green" required />
                            </div>
                            <!-- Confirmar -->
                            <div>
                                <label class="block text-sm font-bold text-brand-dark mb-2">Confirmar Contraseña *</label>
                                <input type="password" name="password_confirmation" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-green" required />
                            </div>
                        </div>

                        <div class="flex flex-col-reverse gap-3 md:flex-row md:items-center md:justify-between mt-8 pt-6 border-t border-gray-100">
                            <button type="button" onclick="goToStep(1)" class="w-full md:w-auto flex items-center justify-center gap-2 py-3 px-6 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:text-brand-dark hover:border-gray-300 transition-all">
                                &larr; Volver
                            </button>
                            <button type="submit" class="w-full md:w-auto bg-[#214371] hover:bg-[#152e50] text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg hover:shadow-xl">
                                Registrar Cuenta
                            </button>
                        </div>
                    </div>
                </form>

            </div>
            
            <p class="mt-8 text-center text-xs text-gray-400">
                Workflow v2.0 © 2026 - Sistema de Gestión de Servicios
            </p>
        </div>
    </div>

    <script>
        const totalSteps = 2;

        function goToStep(step) {
            // Hide error box when switching steps manually
            document.getElementById('js-error-box').classList.add('hidden');

            for (let i = 1; i <= totalSteps; i++) {
                document.getElementById('step-' + i).classList.add('hidden');
                
                const indicator = document.getElementById('indicator-' + i);
                const container = document.getElementById('container-' + i);
                
                if (i === step) {
                    indicator.className = 'w-10 h-10 bg-white text-[#214371] rounded-full flex items-center justify-center font-bold';
                    container.className = 'bg-white/10 rounded-xl p-4 flex items-center gap-4 border border-white/20 cursor-pointer';
                } else {
                    indicator.className = 'w-10 h-10 bg-white/20 text-white rounded-full flex items-center justify-center font-bold transition-colors';
                    container.className = 'bg-white/5 hover:bg-white/10 rounded-xl p-4 flex items-center gap-4 border border-transparent cursor-pointer transition-colors';
                }
            }
            
            document.getElementById('step-' + step).classList.remove('hidden');
            
            const titles = {
                1: ['Datos Personales', 'Ingresa tu información básica y de contacto'],
                2: ['Credenciales', 'Crea tu acceso seguro']
            };
            document.getElementById('step-title').innerText = titles[step][0];
            document.getElementById('step-subtitle').innerText = titles[step][1];
        }

        document.getElementById('registro-form').addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                
                const invalidElements = this.querySelectorAll(':invalid');
                if (invalidElements.length > 0) {
                    const firstInvalid = invalidElements[0];
                    const stepParent = firstInvalid.closest('.step-content');
                    
                    if (stepParent) {
                        const stepNum = parseInt(stepParent.id.split('-')[1]);
                        goToStep(stepNum);
                        
                        const errorBox = document.getElementById('js-error-box');
                        errorBox.classList.remove('hidden');
                        errorBox.querySelector('span').innerText = 'Por favor, rellena los campos marcados en rojo en este paso.';
                        
                        invalidElements.forEach(el => {
                            if(el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                                el.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                                el.addEventListener('input', function() {
                                    if (this.checkValidity()) {
                                        this.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                                    }
                                }, { once: true });
                            }
                        });
                        
                        setTimeout(() => firstInvalid.reportValidity(), 50);
                    }
                }
            }
        });

        goToStep(1);
    </script>
</x-guest-layout>
