<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-red-600">Eliminar Cuenta</h2>
        <p class="mt-1 text-sm text-gray-600">
            Una vez eliminada tu cuenta, todos los datos serán borrados permanentemente. Descarga cualquier información que desees conservar antes de continuar.
        </p>
    </header>

    <button type="button" onclick="document.getElementById('modal-eliminar').classList.remove('hidden')"
            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition ease-in-out duration-150">
        Eliminar Cuenta
    </button>
</section>

<!-- Modal de confirmación -->
<div id="modal-eliminar" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <!-- Fondo oscuro -->
    <div class="absolute inset-0 bg-gray-900/60" onclick="document.getElementById('modal-eliminar').classList.add('hidden')"></div>

    <!-- Contenido del modal -->
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">¿Eliminar tu cuenta?</h2>
                <p class="text-sm text-gray-500">Esta acción no se puede deshacer.</p>
            </div>
        </div>

        <p class="text-sm text-gray-600 mb-6">
            Todos tus datos, órdenes e historial serán borrados permanentemente. Introduce tu contraseña para confirmar.
        </p>

        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="mb-6">
                <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                <input type="password" id="delete_password" name="password"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                       placeholder="Introduce tu contraseña" required />
                @if($errors->userDeletion->get('password'))
                    <p class="mt-2 text-sm text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="flex gap-3 justify-end">
                <button type="button"
                        onclick="document.getElementById('modal-eliminar').classList.add('hidden')"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors">
                    Sí, eliminar cuenta
                </button>
            </div>
        </form>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
<script>document.getElementById('modal-eliminar').classList.remove('hidden');</script>
@endif
