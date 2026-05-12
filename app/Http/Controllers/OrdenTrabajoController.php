<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class OrdenTrabajoController extends Controller
{
    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function nuevaSolicitud()
    {
        $user = Auth::user();
        if ($user->role !== 'cliente') {
            abort(403);
        }

        $hoyCount = OrdenTrabajo::where('cliente_id', $user->cliente_id)
            ->whereDate('created_at', today())
            ->count();

        return view('cliente.nueva-solicitud', compact('hoyCount'));
    }

    public function create()
    {
        // Solo Admin puede crear y asignar órdenes
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No autorizado');
        }

        $clientes = Cliente::all();
        $tecnicos = User::where('role', 'tecnico')->get();

        return view('admin.rutas', compact('clientes', 'tecnicos'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Permitir acceso a admin y cliente
        if ($user->role !== 'admin' && $user->role !== 'cliente') {
            abort(403, 'No autorizado');
        }

        // Para clientes, asignar automáticamente su cliente_id
        if ($user->role === 'cliente') {
            $validated = $request->validate([
                'titulo'           => 'required|string|max:255',
                'descripcion'      => 'nullable|string',
                'prioridad'        => 'required|in:baja,media,alta',
                'fecha_preferida'  => 'required|date|after_or_equal:tomorrow',
                'horario_preferido'=> 'required|in:mañana,mediodia,tarde,sin_preferencia',
            ]);

            // Verificar que sea día laborable (lunes-viernes)
            $fecha = \Carbon\Carbon::parse($validated['fecha_preferida']);
            if ($fecha->isWeekend()) {
                return back()->withErrors([
                    'fecha_preferida' => 'La fecha debe ser un día laborable (lunes a viernes).',
                ])->withInput();
            }

            // Límite de 3 solicitudes por día
            $hoyCount = OrdenTrabajo::where('cliente_id', $user->cliente_id)
                ->whereDate('created_at', today())
                ->count();

            if ($hoyCount >= 3) {
                return back()->withErrors([
                    'limite' => 'Has alcanzado el límite de 3 solicitudes por día. Podrás crear nuevas solicitudes mañana.',
                ]);
            }

            // Si el cliente no tiene un cliente_id asociado, crear uno automáticamente
            if (!$user->cliente_id) {
                $cliente = Cliente::create([
                    'nombre'   => $user->name,
                    'email'    => $user->email,
                    'dni_cif'  => 'AUTO_' . $user->id, // Valor por defecto
                    'telefono' => 'N/A',
                    'direccion' => 'N/A',
                ]);
                $user->update(['cliente_id' => $cliente->id]);
                $cliente_id = $cliente->id;
            } else {
                $cliente_id = $user->cliente_id;
            }
            $usuario_id = null;
        } else {
            // Para admin, puede seleccionar cliente y técnico
            $validated = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'usuario_id' => 'nullable|exists:users,id',
                'titulo'     => 'required|string|max:255',
                'descripcion'=> 'nullable|string',
                'prioridad'  => 'required|in:baja,media,alta',
            ]);
            $cliente_id = $validated['cliente_id'];
            $usuario_id = $validated['usuario_id'];
        }

        $horarioLabel = match($validated['horario_preferido'] ?? null) {
            'mañana'          => 'Mañana (8:00 – 13:00)',
            'mediodia'        => 'Mediodía (13:00 – 16:00)',
            'tarde'           => 'Tarde (16:00 – 21:00)',
            'sin_preferencia' => 'Sin preferencia',
            default           => null,
        };

        OrdenTrabajo::create([
            'uuid'                   => (string) Str::uuid(),
            'cliente_id'             => $cliente_id,
            'usuario_id'             => $usuario_id,
            'titulo'                 => $validated['titulo'],
            'descripcion'            => $validated['descripcion'],
            'prioridad'              => $validated['prioridad'],
            'estado'                 => $usuario_id ? 'asignada' : 'pendiente',
            'fecha_asignacion'       => $usuario_id ? now() : null,
            'fecha_entrega_prevista' => $validated['fecha_preferida'] ?? null,
            'observaciones'          => $horarioLabel ? 'Horario preferido: ' . $horarioLabel : null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Orden de trabajo creada correctamente.');
    }

    public function updateEstado(Request $request, OrdenTrabajo $orden)
    {
        // Pendiente -> En Camino -> En Proceso -> Finalizada
        $estadosValidos = ['pendiente', 'en_camino', 'en_proceso', 'finalizada'];
        
        $request->validate([
            'estado' => 'required|in:' . implode(',', $estadosValidos),
        ]);

        // Verificar que el técnico solo modifique sus órdenes
        if (Auth::user()->role === 'tecnico' && $orden->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para actualizar esta orden.');
        }

        $orden->update([
            'estado' => $request->estado,
        ]);

        return back()->with('success', 'Estado de la orden actualizado a ' . ucfirst(str_replace('_', ' ', $request->estado)));
    }

    // Muestra el formulario de cierre (Pantalla 6/7)
    public function showCierre(OrdenTrabajo $orden)
    {
        if (Auth::user()->role === 'tecnico' && $orden->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para cerrar esta orden.');
        }

        return view('tecnico.cierre', compact('orden'));
    }

    // Muestra el detalle y timeline de la orden (Pantalla 6)
    public function show(OrdenTrabajo $orden)
    {
        if (Auth::user()->role === 'tecnico' && $orden->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta orden.');
        }

        $orden->load('cliente');
        return view('tecnico.detalle', compact('orden'));
    }

    // Procesa el cierre de la orden
    public function cerrar(Request $request, OrdenTrabajo $orden)
    {
        if (Auth::user()->role === 'tecnico' && $orden->usuario_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'observaciones' => 'required|string',
            'firma' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB Max
        ]);

        $pathFirma = null;
        if ($request->hasFile('firma')) {
            $pathFirma = $request->file('firma')->store('firmas', 'public');
        }

        $orden->update([
            'estado' => 'finalizada',
            'observaciones' => $request->observaciones,
            'firma_path' => $pathFirma,
        ]);

        return redirect()->route('dashboard')->with('success', 'Orden finalizada correctamente.');
    }

    // Cancelar/eliminar una orden
    public function destroy(OrdenTrabajo $orden)
    {
        $user = Auth::user();

        // Solo el cliente propietario o admin puede cancelar
        if ($user->role === 'cliente' && $orden->cliente_id !== $user->cliente_id) {
            abort(403, 'No tienes permiso para cancelar esta orden.');
        }

        if ($user->role !== 'admin' && $user->role !== 'cliente') {
            abort(403, 'No autorizado');
        }

        // Solo se pueden cancelar órdenes que no están en progreso
        if ($orden->estado === 'en_proceso' || $orden->estado === 'en_camino' || $orden->estado === 'finalizada') {
            abort(403, 'No puedes cancelar una orden en progreso o finalizada.');
        }

        $orden->update(['estado' => 'cancelada']);

        return redirect()->route('dashboard')->with('success', 'Solicitud cancelada correctamente.');
    }

    // Asignar o cambiar técnico a una orden existente
    public function assignTecnico(Request $request, OrdenTrabajo $orden)
    {
        // Solo admin puede asignar técnicos
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No autorizado');
        }

        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
        ]);

        // Verificar que el usuario seleccionado sea técnico
        $tecnico = User::findOrFail($validated['usuario_id']);
        if ($tecnico->role !== 'tecnico') {
            return back()->withErrors(['usuario_id' => 'El usuario seleccionado no es un técnico.']);
        }

        // Actualizar la orden
        $orden->update([
            'usuario_id' => $validated['usuario_id'],
            'estado' => 'asignada',
            'fecha_asignacion' => now(),
        ]);

        return back()->with('success', 'Técnico ' . $tecnico->name . ' asignado correctamente a la orden.');
    }
}
