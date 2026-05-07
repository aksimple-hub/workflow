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
            // Corregido: buscar por tecnico_id en lugar de cliente_id
            $ordenes = OrdenTrabajo::where('tecnico_id', $user->id)
                ->whereIn('estado', ['asignada', 'en_curso'])
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
}
