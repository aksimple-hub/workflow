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

        // 1. Lógica para el Administrador (Mockup M-03) [cite: 73, 330]
        if ($user->role === 'admin') {
            $stats = [
                'total' => OrdenTrabajo::count(),
                'pendientes' => OrdenTrabajo::where('estado', 'abierta')->count(),
                'en_curso' => OrdenTrabajo::where('estado', 'en_curso')->count(),
                'finalizadas' => OrdenTrabajo::where('estado', 'finalizada')->count(),
            ];
            // Importante: Verifica si en tu modelo la relación se llama 'tecnico' o 'usuario'
            $ordenes = OrdenTrabajo::with(['cliente', 'tecnico'])->latest()->take(10)->get();

            return view('dashboard', compact('stats', 'ordenes'));
        }

        // 2. Lógica para el Técnico (Mockup M-05) [cite: 75, 341]
        if ($user->role === 'tecnico') {
            // CAMBIO: Usamos 'usuario_id' que es como aparece en tu diagrama de clases
            $ordenes = OrdenTrabajo::where('cliente_id', $user->id)
                ->whereIn('estado', ['asignada', 'en_curso'])
                ->orderBy('prioridad', 'desc')
                ->get();

            return view('dashboard', compact('ordenes'));
        }

        if ($user->role === 'cliente') {
        $ordenes = OrdenTrabajo::where('cliente_id', $user->id)
            ->latest()
            ->get();

        return view('dashboard', compact('ordenes'));
        }

    }

}
