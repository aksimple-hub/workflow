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
            $counts = OrdenTrabajo::selectRaw("
                count(*) as total,
                count(case when estado = 'pendiente' then 1 end) as pendientes,
                count(case when estado in ('en_camino','en_proceso','asignada') then 1 end) as en_curso,
                count(case when estado = 'finalizada' then 1 end) as finalizadas,
                count(case when estado = 'cancelada' then 1 end) as canceladas
            ")->first();
            $stats = (array) $counts->getAttributes();

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
            $ordenes = OrdenTrabajo::with('cliente')
                ->where('usuario_id', $user->id)
                ->whereIn('estado', ['asignada', 'pendiente', 'en_camino', 'en_proceso'])
                ->orderBy('fecha_asignacion', 'asc')
                ->orderBy('prioridad', 'desc')
                ->get();

            $today        = \Carbon\Carbon::today();
            $startOfWeek  = $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            $weekDays     = collect(range(0, 4))->map(fn($i) => $startOfWeek->copy()->addDays($i));

            $completadosHoy = OrdenTrabajo::where('usuario_id', $user->id)
                ->whereIn('estado', ['finalizada', 'pendiente_valoracion'])
                ->whereDate('updated_at', $today)
                ->count();

            return view('tecnico.agenda', compact('ordenes', 'today', 'weekDays', 'completadosHoy'));
        }

        if ($user->role === 'cliente') {
            $clienteId = $user->cliente_id ?? $user->id;

            $todas = OrdenTrabajo::with(['tecnico.perfil', 'cliente'])
                ->where('cliente_id', $clienteId)
                ->latest()
                ->get();

            $ordenesActivas = $todas->whereNotIn('estado', ['finalizada', 'cancelada'])->values();
            $ordenActiva    = $ordenesActivas->first();
            $historial      = $todas->whereIn('estado', ['finalizada', 'cancelada'])->values();

            $stats = [
                'total'      => $todas->count(),
                'en_proceso' => $ordenesActivas->count(),
                'finalizadas' => $todas->where('estado', 'finalizada')->count(),
            ];

            return view('cliente.solicitudes', compact('todas', 'ordenActiva', 'ordenesActivas', 'historial', 'stats'));
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
            ->whereIn('estado', ['finalizada', 'cancelada', 'pendiente_valoracion'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $stats = [
            'finalizadas'          => $ordenes->where('estado', 'finalizada')->count(),
            'canceladas'           => $ordenes->where('estado', 'cancelada')->count(),
            'pendiente_valoracion' => $ordenes->where('estado', 'pendiente_valoracion')->count(),
            'total'                => $ordenes->count(),
        ];

        return view('tecnico.historial', compact('ordenes', 'stats'));
    }

    public function tecnicos()
    {
        $tecnicos = \App\Models\User::where('role', 'tecnico')->paginate(20)->withQueryString();
        return view('admin.tecnicos', compact('tecnicos'));
    }

    public function clientes()
    {
        $clientes = \App\Models\Cliente::with('usuario')->paginate(20)->withQueryString();
        return view('admin.clientes', compact('clientes'));
    }

    public function historial()
    {
        $ordenes = OrdenTrabajo::with(['cliente', 'tecnico'])->orderBy('updated_at', 'desc')->paginate(20)->withQueryString();
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

        // Valoraciones recibidas por el técnico (cliente → técnico)
        $valoraciones = OrdenTrabajo::with('cliente')
            ->where('usuario_id', $tecnico->id)
            ->whereNotNull('satisfaccion')
            ->orderByDesc('updated_at')
            ->get(['id', 'satisfaccion', 'comentario_cliente', 'cliente_id', 'titulo', 'updated_at']);
        $ratingAvg   = $valoraciones->count() ? round($valoraciones->avg('satisfaccion'), 1) : null;
        $ratingCount = $valoraciones->count();

        return view('admin.tecnico-show', compact('tecnico', 'perfil', 'ordenes', 'valoraciones', 'ratingAvg', 'ratingCount'));
    }

    public function updateTecnico(\Illuminate\Http\Request $request, $id)
    {
        $tecnico = \App\Models\User::where('role', 'tecnico')->findOrFail($id);
        $perfil  = \App\Models\Tecnico::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'apellidos'   => 'required|string|max:255',
            'email'       => 'required|string|lowercase|email|max:255|unique:users,email,' . $tecnico->id,
            'telefono'    => 'required|string|max:20',
            'direccion'   => 'required|string|max:255',
            'dni_nie'     => 'required|string|max:20|unique:tecnicos,dni_nie,' . $id,
            'experiencia' => 'nullable|string',
        ]);

        $tecnico->update(['name' => $validated['name'], 'email' => strtolower(trim($validated['email']))]);
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
        app()->terminating(function() use ($tecnico) {
            try { $tecnico->notify(new \App\Notifications\TecnicoDadoDeBaja()); } catch (\Throwable $e) { \Log::error('Email TecnicoDadoDeBaja: ' . $e->getMessage()); }
        });
        return redirect()->route('admin.tecnicos')->with('success', 'Técnico "' . $tecnico->name . '" dado de baja correctamente.');
    }

    public function clienteShow($id)
    {
        $cliente = \App\Models\Cliente::findOrFail($id);
        $user    = \App\Models\User::where('cliente_id', $cliente->id)->first();
        $ordenes = OrdenTrabajo::where('cliente_id', $cliente->id)->latest()->get();

        // Valoraciones recibidas por el cliente (técnico → cliente)
        $valoraciones = OrdenTrabajo::with('tecnico')
            ->where('cliente_id', $cliente->id)
            ->whereNotNull('satisfaccion_tecnico')
            ->orderByDesc('updated_at')
            ->get(['id', 'satisfaccion_tecnico', 'usuario_id', 'titulo', 'updated_at']);
        $ratingAvg   = $valoraciones->count() ? round($valoraciones->avg('satisfaccion_tecnico'), 1) : null;
        $ratingCount = $valoraciones->count();

        return view('admin.cliente-show', compact('cliente', 'user', 'ordenes', 'valoraciones', 'ratingAvg', 'ratingCount'));
    }

    public function updateCliente(\Illuminate\Http\Request $request, $id)
    {
        $cliente = \App\Models\Cliente::findOrFail($id);
        $user    = \App\Models\User::where('cliente_id', $cliente->id)->first();

        $validated = $request->validate([
            'nombre'    => 'required|string|max:255',
            'email'     => 'required|string|lowercase|email|max:255|unique:clientes,email,' . $cliente->id,
            'telefono'  => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'dni_cif'   => 'required|string|max:20|unique:clientes,dni_cif,' . $cliente->id,
        ]);

        $validated['email'] = strtolower(trim($validated['email']));

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
            app()->terminating(function() use ($user) {
                try { $user->notify(new \App\Notifications\ClienteDadoDeBaja()); } catch (\Throwable $e) { \Log::error('Email ClienteDadoDeBaja: ' . $e->getMessage()); }
            });
        }

        return redirect()->route('admin.clientes')->with('success', 'Cliente "' . $cliente->nombre . '" dado de baja correctamente.');
    }

    public function ordenShow($id)
    {
        $orden = OrdenTrabajo::with(['cliente', 'tecnico', 'fotos'])->findOrFail($id);
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
            'email' => strtolower(trim($request->email)),
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
            app()->terminating(function() use ($user) {
                try { $user->notify(new \App\Notifications\TecnicoAprobado()); } catch (\Throwable $e) { \Log::error('Email TecnicoAprobado: ' . $e->getMessage()); }
            });
            $msg = 'Técnico "' . $user->name . '" activado correctamente. Se le ha notificado.';
        } else {
            app()->terminating(function() use ($user) {
                try { $user->notify(new \App\Notifications\ClienteAprobado()); } catch (\Throwable $e) { \Log::error('Email ClienteAprobado: ' . $e->getMessage()); }
            });
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
            'email' => 'required|string|lowercase|email|max:255|unique:users|unique:clientes,email',
            'password' => 'required|string|min:8|confirmed',
            'dni_cif' => 'required|string|unique:clientes',
            'telefono' => 'required|string',
            'direccion' => 'nullable|string',
        ]);

        $emailCliente = strtolower(trim($request->email));

        $cliente = \App\Models\Cliente::create([
            'nombre' => $request->nombre,
            'dni_cif' => $request->dni_cif,
            'email' => $emailCliente,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        \App\Models\User::create([
            'name' => $request->nombre,
            'email' => $emailCliente,
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
        $tipo = $data['tipo'] ?? null;

        // Routing by tipo (new format)
        if ($tipo === 'nuevo_usuario') {
            if (isset($data['tecnico_id'])) return redirect()->route('admin.tecnicos');
            if (isset($data['cliente_id'])) return redirect()->route('admin.cliente.show', $data['cliente_id']);
        }
        if (in_array($tipo, ['orden_cancelada', 'orden_aplazada']) && isset($data['orden_id'])) {
            return redirect()->route('admin.orden.show', $data['orden_id']);
        }
        if ($tipo === 'nueva_orden' && isset($data['orden_id'])) {
            return redirect()->route('ordenes.show', $data['orden_id']);
        }
        if ($tipo === 'orden_estado' && isset($data['orden_id'])) {
            return redirect()->route('cliente.orden.show', $data['orden_id']);
        }
        if (in_array($tipo, ['aprobacion', 'baja'])) {
            return redirect()->route('dashboard');
        }

        // Fallback: legacy format without tipo
        if (isset($data['tecnico_id']) && !isset($data['orden_id'])) {
            return redirect()->route('admin.tecnicos');
        }
        if (isset($data['cliente_id']) && !isset($data['orden_id'])) {
            return redirect()->route('admin.cliente.show', $data['cliente_id']);
        }
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
