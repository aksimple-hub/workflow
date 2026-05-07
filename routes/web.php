<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('clientes', ClienteController::class);
    
    // Rutas para Admin (Gestión de órdenes)
    Route::resource('ordenes', OrdenTrabajoController::class)->except(['show', 'edit', 'destroy']);
    
    // Rutas para Técnico (Estados y Cierre)
    Route::patch('/ordenes/{orden}/estado', [OrdenTrabajoController::class, 'updateEstado'])->name('ordenes.update-estado');
    Route::get('/ordenes/{orden}/cierre', [OrdenTrabajoController::class, 'showCierre'])->name('ordenes.cierre');
    Route::post('/ordenes/{orden}/cerrar', [OrdenTrabajoController::class, 'cerrar'])->name('ordenes.cerrar');

    // Rutas para Cliente (Nueva Solicitud)
    Route::view('/solicitud/nueva', 'cliente.nueva-solicitud')->name('solicitud.nueva');
});

require __DIR__.'/auth.php';
