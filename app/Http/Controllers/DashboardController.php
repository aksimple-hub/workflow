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
                'pendientes' => OrdenTrabajo::where('estado', 'abierta')->count(),
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
}
