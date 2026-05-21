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
                'total'      => OrdenTrabajo::count(),
                'pendientes' => OrdenTrabajo::where('estado', 'pendiente')->count(),
                'en_curso'   => OrdenTrabajo::whereIn('estado', ['en_camino', 'en_proceso', 'asignada'])->count(),
                'finalizadas' => OrdenTrabajo::where('estado', 'finalizada')->count(),
                'canceladas'  => OrdenTrabajo::where('estado', 'cancelada')->count(),
            ];

            $search   = request('search', '');
            $estado   = request('estado', '');
            $prioridad = request('prioridad', '');

            $ordenes = OrdenTrabajo::with(['cliente', 'tecnico'])
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($q2) use ($search) {
                        $q2->whereHas('cliente', fn($c) => $c->where('nombre', 'like', "%{$search}%"))
                           ->orWhereHas('tecnico', fn($t) => $t->where('name', 'like', "%{$search}%"))
                           ->orWhere('id', is_numeric($search) ? (int)$search : 0);
                    });
                })
                ->when($estado, fn($q) => $q->where('estado', $estado))
                ->when($prioridad, fn($q) => $q->where('prioridad', $prioridad))
                ->latest()
                ->paginate(15)
                ->withQueryString();

            return view('admin.dashboard', compact('stats', 'ordenes', 'search', 'estado', 'prioridad'));
        }

        if ($user->role === 'tecnico') {
            // Filtrar por el usuario_id del técnico autenticado
            $ordenes = OrdenTrabajo::where('usuario_id', $user->id)
                ->whereIn('estado', ['asignada', 'pendiente', 'en_camino', 'en_proceso'])
                ->orderBy('prioridad', 'desc')
                ->get();

            return view('tecnico.agenda', compact('ordenes'));
        }

        if ($user->role === 'cliente') {
            $clienteId = $user->cliente_id ?? $user->id;

            $todas = OrdenTrabajo::with(['tecnico.perfil', 'cliente'])
                ->where('cliente_id', $clienteId)
                ->latest()
                ->get();

            $ordenActiva = $todas->whereNotIn('estado', ['finalizada', 'cancelada'])->first();
            $historial   = $todas->where('estado', 'finalizada')->values();

            $stats = [
                'total'      => $todas->count(),
                'en_proceso' => $todas->whereNotIn('estado', ['finalizada', 'cancelada'])->count(),
                'finalizadas' => $historial->count(),
            ];

            return view('cliente.solicitudes', compact('todas', 'ordenActiva', 'historial', 'stats'));
        }
    }

    public function historialTecnico()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role !== 'tecnico') {
            abort(403);
        }

        $ordenes = OrdenTrabajo::with('cliente')
            ->where('usuario_id', $user->id)
            ->whereIn('estado', ['finalizada', 'cancelada'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $stats = [
            'finalizadas' => $ordenes->where('estado', 'finalizada')->count(),
            'canceladas'  => $ordenes->where('estado', 'cancelada')->count(),
            'total'       => $ordenes->count(),
        ];

        return view('tecnico.historial', compact('ordenes', 'stats'));
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
        $perfil  = \App\Models\Tecnico::find($id);
        $ordenes = OrdenTrabajo::where('usuario_id', $tecnico->id)->latest()->get();
        return view('admin.tecnico-show', compact('tecnico', 'perfil', 'ordenes'));
    }

    public function updateTecnico(\Illuminate\Http\Request $request, $id)
    {
        $tecnico = \App\Models\User::where('role', 'tecnico')->findOrFail($id);
        $perfil  = \App\Models\Tecnico::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'apellidos'   => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email,' . $tecnico->id,
            'telefono'    => 'required|string|max:20',
            'direccion'   => 'required|string|max:255',
            'dni_nie'     => 'required|string|max:20|unique:tecnicos,dni_nie,' . $id,
            'experiencia' => 'nullable|string',
        ]);

        $tecnico->update(['name' => $validated['name'], 'email' => $validated['email']]);
        $perfil->update([
            'nombre'      => $validated['name'],
            'apellidos'   => $validated['apellidos'],
            'dni_nie'     => $validated['dni_nie'],
            'telefono'    => $validated['telefono'],
            'direccion'   => $validated['direccion'],
            'experiencia' => $validated['experiencia'],
        ]);

        return redirect()->route('admin.tecnico.show', $id)->with('success', 'Datos del técnico actualizados correctamente.');
    }

    public function destroyTecnico($id)
    {
        $tecnico = \App\Models\User::where('role', 'tecnico')->findOrFail($id);
        $tecnico->update(['is_approved' => false]);
        return redirect()->route('admin.tecnicos')->with('success', 'Técnico "' . $tecnico->name . '" dado de baja correctamente.');
    }

    public function clienteShow($id)
    {
        $cliente = \App\Models\Cliente::findOrFail($id);
        $user    = \App\Models\User::where('cliente_id', $cliente->id)->first();
        $ordenes = OrdenTrabajo::where('cliente_id', $cliente->id)->latest()->get();
        return view('admin.cliente-show', compact('cliente', 'user', 'ordenes'));
    }

    public function updateCliente(\Illuminate\Http\Request $request, $id)
    {
        $cliente = \App\Models\Cliente::findOrFail($id);
        $user    = \App\Models\User::where('cliente_id', $cliente->id)->first();

        $validated = $request->validate([
            'nombre'    => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:clientes,email,' . $cliente->id,
            'telefono'  => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'dni_cif'   => 'required|string|max:20|unique:clientes,dni_cif,' . $cliente->id,
        ]);

        $cliente->update($validated);

        if ($user) {
            $user->update(['name' => $validated['nombre'], 'email' => $validated['email']]);
        }

        return redirect()->route('admin.cliente.show', $id)->with('success', 'Datos del cliente actualizados correctamente.');
    }

    public function destroyCliente($id)
    {
        $cliente = \App\Models\Cliente::findOrFail($id);
        $user    = \App\Models\User::where('cliente_id', $cliente->id)->first();

        if ($user) {
            $user->update(['is_approved' => false]);
        }

        return redirect()->route('admin.clientes')->with('success', 'Cliente "' . $cliente->nombre . '" dado de baja correctamente.');
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

        if ($user->role === 'tecnico') {
            $user->notify(new \App\Notifications\TecnicoAprobado());
            $msg = 'Técnico "' . $user->name . '" activado correctamente. Se le ha notificado.';
        } else {
            $user->notify(new \App\Notifications\ClienteAprobado());
            $msg = 'Cliente "' . $user->name . '" activado correctamente. Se le ha notificado.';
        }

        return redirect()->back()->with('success', $msg);
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

        $cliente = \App\Models\Cliente::create([
            'nombre' => $request->nombre,
            'dni_cif' => $request->dni_cif,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        \App\Models\User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'cliente',
            'is_approved' => true,
            'cliente_id' => $cliente->id,
        ]);

        return redirect()->route('admin.clientes')->with('success', 'Cliente creado correctamente.');
    }

    public function markNotificationRead(Request $request, string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $data = $notification->data;

        // Nuevo técnico registrado → ir a lista de técnicos pendientes
        if (isset($data['tecnico_id']) && !isset($data['orden_id']) && !isset($data['mensaje'])) {
            return redirect()->route('admin.tecnicos');
        }

        // Nuevo cliente registrado → ir al detalle del cliente
        if (isset($data['cliente_id']) && !isset($data['orden_id']) && !isset($data['mensaje'])) {
            return redirect()->route('admin.cliente.show', $data['cliente_id']);
        }

        // Técnico aprobado → ir al dashboard del técnico
        if (isset($data['mensaje'])) {
            return redirect()->route('dashboard');
        }

        // Cancelación o aplazamiento de orden
        if (isset($data['orden_id'])) {
            return redirect()->route('admin.orden.show', $data['orden_id']);
        }

        return redirect()->route('dashboard');
    }

    public function markAllNotificationsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
}
