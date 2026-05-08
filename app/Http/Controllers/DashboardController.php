<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $stats = [
                'total' => OrdenTrabajo::count(),
                'pendientes' => OrdenTrabajo::where('estado', 'pendiente')->count(),
                'en_curso' => OrdenTrabajo::where('estado', 'en_curso')->count(),
                'finalizadas' => OrdenTrabajo::where('estado', 'finalizada')->count(),
            ];
            
            $ordenes = OrdenTrabajo::with(['cliente', 'tecnico'])->latest()->take(10)->get();

            return view('admin.dashboard', compact('stats', 'ordenes'));
        }

        if ($user->role === 'tecnico') {
            // Filtrar por el usuario_id del técnico autenticado
            $ordenes = OrdenTrabajo::where('usuario_id', $user->id)
                ->whereIn('estado', ['asignada', 'pendiente', 'en_curso', 'en_camino'])
                ->orderBy('prioridad', 'desc')
                ->get();

            return view('tecnico.agenda', compact('ordenes'));
        }

        if ($user->role === 'cliente') {
            $ordenes = OrdenTrabajo::where('cliente_id', $user->id)
                ->latest()
                ->get();

            return view('cliente.solicitudes', compact('ordenes'));
        }
    }

    public function tecnicos()
    {
        $tecnicos = \App\Models\User::where('role', 'tecnico')->get();
        return view('admin.tecnicos', compact('tecnicos'));
    }

    public function clientes()
    {
        $clientes = \App\Models\Cliente::all();
        return view('admin.clientes', compact('clientes'));
    }

    public function historial()
    {
        $ordenes = OrdenTrabajo::with(['cliente', 'tecnico'])->orderBy('updated_at', 'desc')->get();
        return view('admin.historial', compact('ordenes'));
    }

    public function configuracion()
    {
        return view('admin.configuracion');
    }

    public function tecnicoShow($id)
    {
        $tecnico = \App\Models\User::where('role', 'tecnico')->findOrFail($id);
        $ordenes = OrdenTrabajo::where('usuario_id', $tecnico->id)->latest()->take(5)->get();
        return view('admin.tecnico-show', compact('tecnico', 'ordenes'));
    }

    public function clienteShow($id)
    {
        $cliente = \App\Models\Cliente::findOrFail($id);
        $ordenes = OrdenTrabajo::where('cliente_id', $cliente->id)->latest()->take(5)->get();
        return view('admin.cliente-show', compact('cliente', 'ordenes'));
    }

    public function ordenShow($id)
    {
        $orden = OrdenTrabajo::with(['cliente', 'tecnico'])->findOrFail($id);
        return view('admin.orden-show', compact('orden'));
    }

    public function createTecnico()
    {
        return view('admin.tecnicos-create');
    }

    public function storeTecnico(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'dni_nie' => ['required', 'string', 'max:20', 'unique:tecnicos,dni_nie'],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
            'experiencia' => ['nullable', 'string'],
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'tecnico',
            'is_approved' => true,
        ]);

        \App\Models\Tecnico::create([
            'id' => $user->id,
            'nombre' => $request->name,
            'apellidos' => $request->apellidos,
            'dni_nie' => $request->dni_nie,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'experiencia' => $request->experiencia,
        ]);

        return redirect()->route('admin.tecnicos')->with('success', 'Técnico creado correctamente.');
    }

    public function validateUser($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Usuario validado correctamente.');
    }

    public function createCliente()
    {
        return view('admin.clientes-create');
    }

    public function storeCliente(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'dni_cif' => 'required|string|unique:clientes',
            'telefono' => 'required|string',
            'direccion' => 'nullable|string',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'cliente',
            'is_approved' => true,
        ]);

        \App\Models\Cliente::create([
            'id' => $user->id,
            'nombre' => $request->nombre,
            'dni_cif' => $request->dni_cif,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        return redirect()->route('admin.clientes')->with('success', 'Cliente creado correctamente.');
    }
}
