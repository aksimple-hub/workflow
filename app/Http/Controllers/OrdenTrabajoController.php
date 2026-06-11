<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Material;
use App\Models\OrdenFoto;
use App\Models\User;
use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AsignacionOrdenTecnico;
use App\Notifications\OrdenCanceladaAdmin;
use App\Notifications\OrdenAplazadaAdmin;
use Illuminate\Support\Facades\Notification;

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
        if (!$user->is_approved) {
            return redirect()->route('dashboard')->with('pendiente', 'Tu cuenta está pendiente de validación. No puedes realizar solicitudes hasta que un administrador la active.');
        }

        $hoyCount = OrdenTrabajo::where('cliente_id', $user->cliente_id)
            ->whereDate('created_at', today())
            ->where('estado', '!=', 'cancelada')
            ->count();

        $cliente = Cliente::find($user->cliente_id ?? $user->id);

        return view('cliente.nueva-solicitud', compact('hoyCount', 'cliente'));
    }

    public function create()
    {
        // Solo Admin puede crear y asignar órdenes
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No autorizado');
        }

        $clientes = Cliente::all();
        $tecnicos = User::where('role', 'tecnico')->where('is_approved', true)->get();

        return view('admin.rutas', compact('clientes', 'tecnicos'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Permitir acceso a admin y cliente
        if ($user->role !== 'admin' && $user->role !== 'cliente') {
            abort(403, 'No autorizado');
        }
        if ($user->role === 'cliente' && !$user->is_approved) {
            return back()->with('pendiente', 'Tu cuenta está pendiente de validación. No puedes realizar solicitudes hasta que un administrador la active.');
        }

        // Para clientes, asignar automáticamente su cliente_id
        if ($user->role === 'cliente') {
            $validated = $request->validate([
                'titulo'             => 'required|string|max:255',
                'descripcion'        => 'nullable|string',
                'prioridad'          => 'required|in:baja,media,alta',
                'horario_preferido'  => 'required|in:mañana,mediodia,tarde,sin_preferencia',
                'direccion_servicio' => 'nullable|string|max:500',
                'fotos'              => 'nullable|array|max:5',
                'fotos.*'            => 'image|mimes:jpg,jpeg,png,webp|max:20480',
            ]);

            // Límite de 3 solicitudes por día
            $hoyCount = OrdenTrabajo::where('cliente_id', $user->cliente_id)
                ->whereDate('created_at', today())
                ->where('estado', '!=', 'cancelada')
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

            // Actualizar dirección del cliente si fue modificada
            if (!empty($validated['direccion_servicio'])) {
                $clienteModel = Cliente::find($cliente_id);
                if ($clienteModel && $clienteModel->direccion !== $validated['direccion_servicio']) {
                    $clienteModel->update(['direccion' => $validated['direccion_servicio']]);
                }
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

        $orden = OrdenTrabajo::create([
            'uuid'             => (string) Str::uuid(),
            'cliente_id'       => $cliente_id,
            'usuario_id'       => $usuario_id,
            'titulo'           => $validated['titulo'],
            'descripcion'      => $validated['descripcion'],
            'prioridad'        => $validated['prioridad'],
            'estado'           => $usuario_id ? 'asignada' : 'pendiente',
            'fecha_asignacion' => $usuario_id ? now() : null,
            'observaciones'    => $horarioLabel ? 'Horario preferido: ' . $horarioLabel : null,
        ]);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                try {
                    $path = $foto->store('orden_fotos');
                    if ($path) {
                        OrdenFoto::create(['orden_trabajo_id' => $orden->id, 'path' => $path]);
                    } else {
                        \Log::error('Foto upload devolvió false para orden ' . $orden->id . ' - disco: ' . config('filesystems.default'));
                    }
                } catch (\Throwable $e) {
                    \Log::error('Foto upload excepción orden ' . $orden->id . ': ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'Orden de trabajo creada correctamente.');
    }

    public function updateEstado(Request $request, OrdenTrabajo $orden)
    {
        // Pendiente -> En Proceso -> Finalizada
        $estadosValidos = ['pendiente', 'en_proceso', 'finalizada'];
        
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

    // Detalle de orden para el cliente
    public function showCliente(OrdenTrabajo $orden)
    {
        $user = Auth::user();
        if ($user->role !== 'cliente' || $orden->cliente_id !== $user->cliente_id) {
            abort(403);
        }

        $orden->load(['tecnico.perfil', 'cliente', 'Material', 'fotos']);
        return view('cliente.orden-detalle', compact('orden'));
    }

    // Muestra el detalle y timeline de la orden (Pantalla 6)
    public function show(OrdenTrabajo $orden)
    {
        if (Auth::user()->role === 'tecnico' && $orden->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta orden.');
        }

        $orden->load(['cliente', 'fotos']);
        return view('tecnico.detalle', compact('orden'));
    }

    // Procesa el cierre de la orden
    public function cerrar(Request $request, OrdenTrabajo $orden)
    {
        if (Auth::user()->role === 'tecnico' && $orden->usuario_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        if (in_array($orden->estado, ['pendiente_valoracion', 'finalizada', 'cancelada'])) {
            return redirect()->route('dashboard')->with('info', 'Esta orden ya fue procesada.');
        }

        $request->validate([
            'observaciones'       => 'required|string',
            'recomendaciones'     => 'nullable|string',
            'satisfaccion_tecnico'=> 'nullable|integer|min:1|max:5',
            'hora_inicio'         => 'required|date_format:H:i',
            'hora_fin'            => 'required|date_format:H:i|after:hora_inicio',
            'materiales'          => 'nullable|array',
            'materiales.*.nombre'   => 'required_with:materiales|string|max:255',
            'materiales.*.cantidad' => 'required_with:materiales|integer|min:1',
        ]);

        // Prefixar las tareas realizadas en las observaciones
        $observaciones = $request->observaciones;
        if ($request->filled('tareas_json')) {
            $tareas = json_decode($request->tareas_json, true) ?? [];
            $hechas = array_filter($tareas, fn($t) => $t['done']);
            if (count($hechas)) {
                $lista = implode(', ', array_column($hechas, 'nombre'));
                $observaciones = "Tareas: {$lista}.\n\n" . $observaciones;
            }
        }

        $orden->update([
            'estado'               => 'pendiente_valoracion',
            'observaciones'        => $observaciones,
            'recomendaciones'      => $request->recomendaciones,
            'satisfaccion_tecnico' => $request->satisfaccion_tecnico ?: null,
            'hora_inicio'          => $request->hora_inicio ?: null,
            'hora_fin'             => $request->hora_fin ?: null,
        ]);

        $clienteUser = \App\Models\User::where('cliente_id', $orden->cliente_id)->first();
        if ($clienteUser) {
            app()->terminating(function() use ($clienteUser, $orden) {
                try { $clienteUser->notify(new \App\Notifications\OrdenEstadoCambiada($orden, 'pendiente_valoracion')); } catch (\Throwable $e) { \Log::error('Notif OrdenEstadoCambiada: ' . $e->getMessage()); }
            });
        }

        // Guardar materiales
        if ($request->has('materiales')) {
            foreach ($request->materiales as $mat) {
                if (!empty($mat['nombre'])) {
                    Material::create([
                        'orden_trabajo_id' => $orden->id,
                        'nombre'           => $mat['nombre'],
                        'cantidad'         => (int) $mat['cantidad'],
                        'precio_unitario'  => 0,
                    ]);
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'Informe enviado. El cliente recibirá la solicitud de valoración.');
    }

    // Muestra el formulario de valoración del cliente
    public function showValoracion(OrdenTrabajo $orden)
    {
        $user = Auth::user();
        if ($user->role !== 'cliente' || $orden->cliente_id !== $user->cliente_id) {
            abort(403);
        }
        if ($orden->estado !== 'pendiente_valoracion') {
            return redirect()->route('dashboard');
        }
        $orden->load(['tecnico', 'cliente']);
        return view('cliente.valoracion', compact('orden'));
    }

    // Procesa la valoración del cliente y finaliza la orden
    public function submitValoracion(Request $request, OrdenTrabajo $orden)
    {
        $user = Auth::user();
        if ($user->role !== 'cliente' || $orden->cliente_id !== $user->cliente_id) {
            abort(403);
        }
        if ($orden->estado !== 'pendiente_valoracion') {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'satisfaccion'       => 'required|integer|min:1|max:5',
            'comentario_cliente' => 'nullable|string|max:1000',
            'firma_base64'       => 'nullable|string',
        ]);

        $pathFirma = $orden->firma_path;
        if ($request->filled('firma_base64')) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->firma_base64));
            $filename = 'firmas/' . Str::uuid() . '.png';
            Storage::put($filename, $imageData);
            $pathFirma = $filename;
        }

        $orden->update([
            'estado'             => 'finalizada',
            'satisfaccion'       => $request->satisfaccion,
            'comentario_cliente' => $request->comentario_cliente,
            'firma_path'         => $pathFirma,
        ]);

        return redirect()->route('dashboard')->with('success', '¡Gracias por tu valoración! El servicio ha quedado finalizado.');
    }

    // Cancela la orden por parte del técnico con un motivo
    public function cancelarPorTecnico(Request $request, OrdenTrabajo $orden)
    {
        if (Auth::user()->role !== 'tecnico' || $orden->usuario_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        if (in_array($orden->estado, ['finalizada', 'cancelada', 'pendiente_valoracion'])) {
            return redirect()->route('dashboard')->with('info', 'Esta orden ya fue procesada.');
        }

        $request->validate([
            'motivo' => 'required|string|max:1000',
        ]);

        $orden->update([
            'estado'        => 'cancelada',
            'observaciones' => '[Cancelado por técnico ' . now()->format('d/m/Y H:i') . ']: ' . $request->motivo,
        ]);

        $admins = User::where('role', 'admin')->get();
        app()->terminating(function() use ($orden, $admins, $request) {
            try { Notification::send($admins, new OrdenCanceladaAdmin($orden->fresh(), 'tecnico', $request->motivo)); } catch (\Throwable $e) { \Log::error('Email OrdenCancelada tecnico: ' . $e->getMessage()); }
        });

        return redirect()->route('dashboard')->with('success', 'Servicio cancelado. El administrador ha sido notificado.');
    }

    // Aplazar la orden por parte del técnico (no cancela, queda pendiente de reagendar)
    public function aplazarOrden(Request $request, OrdenTrabajo $orden)
    {
        if (Auth::user()->role !== 'tecnico' || $orden->usuario_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        if (in_array($orden->estado, ['finalizada', 'cancelada', 'pendiente_valoracion', 'pendiente_reprogramacion'])) {
            return redirect()->route('dashboard')->with('info', 'Esta orden ya fue procesada o está pendiente de reagendar.');
        }

        $request->validate([
            'motivo' => 'required|string|max:500',
            'nota'   => 'nullable|string|max:1000',
        ]);

        $observacion = '[Aplazado por técnico ' . now()->format('d/m/Y H:i') . '] Motivo: ' . $request->motivo;
        if ($request->filled('nota')) {
            $observacion .= ' - ' . $request->nota;
        }

        $orden->update([
            'estado'        => 'pendiente_reprogramacion',
            'observaciones' => $observacion,
        ]);

        $admins = User::where('role', 'admin')->get();
        app()->terminating(function() use ($orden, $admins, $request) {
            try { Notification::send($admins, new OrdenAplazadaAdmin($orden->fresh(), $request->motivo, $request->nota)); } catch (\Throwable $e) { \Log::error('Email OrdenAplazada: ' . $e->getMessage()); }
        });

        return redirect()->route('dashboard')->with('success', 'Servicio aplazado. El administrador ha sido notificado para reagendarlo.');
    }

    // Reagendar una orden aplazada (solo admin)
    public function reagendarOrden(Request $request, OrdenTrabajo $orden)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No autorizado');
        }

        if ($orden->estado !== 'pendiente_reprogramacion') {
            return back()->with('info', 'Esta orden no está pendiente de reagendación.');
        }

        $request->validate([
            'usuario_id'           => 'required|exists:users,id',
            'fecha_entrega_prevista' => 'required|date|after_or_equal:today',
        ]);

        $tecnico = User::findOrFail($request->usuario_id);
        if ($tecnico->role !== 'tecnico') {
            return back()->withErrors(['usuario_id' => 'El usuario seleccionado no es un técnico.']);
        }

        $orden->update([
            'estado'                 => 'asignada',
            'usuario_id'             => $tecnico->id,
            'fecha_entrega_prevista' => $request->fecha_entrega_prevista,
            'fecha_asignacion'       => now(),
        ]);

        app()->terminating(function() use ($tecnico, $orden) {
            try { $tecnico->notify(new AsignacionOrdenTecnico($orden->fresh())); } catch (\Throwable $e) { \Log::error('Email AsignacionOrden reagendar: ' . $e->getMessage()); }
        });

        return redirect()->route('admin.orden.show', $orden->id)
            ->with('success', 'Orden reagendada correctamente. Se ha notificado al técnico.');
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
        if ($orden->estado === 'en_proceso' || $orden->estado === 'finalizada') {
            abort(403, 'No puedes cancelar una orden en progreso o finalizada.');
        }

        $orden->update(['estado' => 'cancelada']);

        if ($user->role === 'cliente') {
            $admins = User::where('role', 'admin')->get();
            app()->terminating(function() use ($orden, $admins) {
                try { Notification::send($admins, new OrdenCanceladaAdmin($orden->fresh(), 'cliente')); } catch (\Throwable $e) { \Log::error('Email OrdenCancelada cliente: ' . $e->getMessage()); }
            });
        }

        return redirect()->route('dashboard')->with('success', 'Solicitud cancelada correctamente.');
    }

    // Asignar o reasignar técnico a una orden existente
    public function assignTecnico(Request $request, OrdenTrabajo $orden)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No autorizado');
        }

        $esReasignacion = $orden->usuario_id !== null;

        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'motivo'     => $esReasignacion ? 'required|string|max:500' : 'nullable|string|max:500',
        ]);

        $tecnico = User::findOrFail($validated['usuario_id']);
        if ($tecnico->role !== 'tecnico') {
            return back()->withErrors(['usuario_id' => 'El usuario seleccionado no es un técnico.']);
        }

        // Si es reasignación, registrar el motivo en observaciones
        if ($esReasignacion && !empty($validated['motivo'])) {
            $tecnicoAnterior = $orden->tecnico?->name ?? 'anterior';
            $nota = "\n[Reasignación " . now()->format('d/m/Y H:i') . "] "
                  . "Técnico anterior: {$tecnicoAnterior}. Motivo: " . $validated['motivo'];
            $orden->observaciones = ($orden->observaciones ?? '') . $nota;
        }

        $orden->usuario_id      = $tecnico->id;
        $orden->estado          = 'asignada';
        $orden->fecha_asignacion = now();
        $orden->save();

        $orden->load('cliente');
        app()->terminating(function() use ($tecnico, $orden) {
            try { $tecnico->notify(new AsignacionOrdenTecnico($orden)); } catch (\Throwable $e) { \Log::error('Email AsignacionOrden: ' . $e->getMessage()); }
        });

        $msg = $esReasignacion
            ? 'Orden reasignada a ' . $tecnico->name . ' correctamente.'
            : 'Técnico ' . $tecnico->name . ' asignado correctamente.';

        return back()->with('success', $msg);
    }

    // Asignar técnico a múltiples órdenes a la vez
    public function bulkAssignTecnico(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No autorizado');
        }

        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'orden_ids'  => 'required|array|min:1',
            'orden_ids.*'=> 'integer|exists:orden_trabajos,id',
        ]);

        $tecnico = User::findOrFail($validated['usuario_id']);
        if ($tecnico->role !== 'tecnico') {
            return back()->withErrors(['usuario_id' => 'El usuario seleccionado no es un técnico.']);
        }

        $ordenes = OrdenTrabajo::with('cliente')
            ->whereIn('id', $validated['orden_ids'])
            ->whereNotIn('estado', ['finalizada', 'cancelada'])
            ->get();

        $count = $ordenes->count();

        OrdenTrabajo::whereIn('id', $ordenes->pluck('id'))
            ->update([
                'usuario_id'       => $tecnico->id,
                'estado'           => 'asignada',
                'fecha_asignacion' => now(),
            ]);

        app()->terminating(function() use ($tecnico, $ordenes) {
            foreach ($ordenes as $orden) {
                try { $tecnico->notify(new AsignacionOrdenTecnico($orden)); } catch (\Throwable $e) { \Log::error('Email BulkAsignacion: ' . $e->getMessage()); }
            }
        });

        return back()->with('success', $count . ' orden(es) asignada(s) a ' . $tecnico->name . '.');
    }
}
