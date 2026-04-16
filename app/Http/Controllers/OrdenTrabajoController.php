<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use App\Models\OrdenTrabajo;
use App\Http\Requests\StoreOrdenTrabajoRequest;
use App\Http\Requests\UpdateOrdenTrabajoRequest;

class OrdenTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();
        $tecnicos = User::where('role', 'tecnico')->get();

        return view('ordenes.create', compact('clientes', 'tecnicos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrdenTrabajoRequest $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tecnico_id' => 'nullable|exists:users,id',
            'titulo'     => 'required|string|max:255',
            'descripcion'=> 'nullable|string',
            'prioridad'  => 'required|in:baja,media,alta',
        ]);

        OrdenTrabajo::create([
            'uuid'         => (string) Str::uuid(), // Identificador único de seguridad [cite: 289]
            'cliente_id'   => $validated['cliente_id'],
            'tecnico_id'   => $validated['tecnico_id'],
            'titulo'       => $validated['titulo'],
            'descripcion'  => $validated['descripcion'],
            'prioridad'    => $validated['prioridad'],
            'estado'       => $request->tecnico_id ? 'asignada' : 'abierta', // Si hay técnico, nace asignada [cite: 295]
            'fecha_asignacion' => $request->tecnico_id ? now() : null,
        ]);


        return redirect()->route('dashboard')->with('success', 'Orden de trabajo creada y asignada correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(OrdenTrabajo $ordenTrabajo)
    {
        $orden->load('cliente');
        return view('ordenes.show', compact('orden'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrdenTrabajo $ordenTrabajo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrdenTrabajoRequest $request, OrdenTrabajo $ordenTrabajo)
    {
        $ordenTrabajo->update([
            'estado' => $request->estado,
            'fecha_inicio' => ($request->estado == 'en_curso') ? now() : $orden->fecha_inicio,
        ]);

        return back()->with('success', 'Estado de la orden actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrdenTrabajo $ordenTrabajo)
    {
        //
    }
}
