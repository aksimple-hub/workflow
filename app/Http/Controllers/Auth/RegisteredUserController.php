<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NuevoTecnicoRegistrado;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'dni_cif' => ['required', 'string', 'max:20', 'unique:clientes,dni_cif'],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'cliente',
            'is_approved' => false,
        ]);

        \App\Models\Cliente::create([
            'id' => $user->id,
            'nombre' => $request->name,
            'dni_cif' => $request->dni_cif,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        $user->update(['cliente_id' => $user->id]);

        event(new Registered($user));

        // Auth::login($user); // No auto-login until approved

        return redirect(route('login', absolute: false))->with('status', 'Tu cuenta ha sido registrada y está pendiente de validación por un administrador.');
    }
    public function createTecnico(): View
    {
        return view('auth.register-tecnico');
    }

    public function storeTecnico(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'dni_nie' => ['required', 'string', 'max:20', 'unique:tecnicos,dni_nie'],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
            'experiencia' => ['nullable', 'string'],
            'cv_pdf'      => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'tecnico',
            'is_approved' => false,
        ]);

        $cvPath = null;
        if ($request->hasFile('cv_pdf')) {
            $cvPath = $request->file('cv_pdf')->store('cvs', 'public');
        }

        \App\Models\Tecnico::create([
            'id' => $user->id,
            'nombre' => $request->name,
            'apellidos' => $request->apellidos,
            'dni_nie' => $request->dni_nie,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'experiencia' => $request->experiencia,
            'cv_path' => $cvPath,
        ]);

        event(new Registered($user));

        // Notificar a todos los admins
        User::where('role', 'admin')->each(fn($admin) => $admin->notify(new NuevoTecnicoRegistrado($user)));

        return redirect(route('login', absolute: false))->with('status', 'Tu solicitud ha sido enviada. Un administrador revisará tus datos y activará tu cuenta. Te avisaremos cuando esté lista.');
    }
}
