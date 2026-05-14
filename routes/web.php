<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\SocialAuthController;

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
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('clientes', ClienteController::class);
    
    // Rutas para Admin (Gestión de órdenes) y Cliente (Nueva solicitud)
    Route::resource('ordenes', OrdenTrabajoController::class)->except(['show', 'edit']);
    
    // Rutas adicionales de Admin
    Route::get('/tecnicos', [DashboardController::class, 'tecnicos'])->name('admin.tecnicos');
    Route::get('/tecnicos/create', [DashboardController::class, 'createTecnico'])->name('admin.tecnicos.create');
    Route::post('/tecnicos', [DashboardController::class, 'storeTecnico'])->name('admin.tecnicos.store');
    Route::get('/tecnicos/{id}', [DashboardController::class, 'tecnicoShow'])->name('admin.tecnico.show');
    Route::patch('/tecnicos/{id}', [DashboardController::class, 'updateTecnico'])->name('admin.tecnico.update');
    Route::delete('/tecnicos/{id}', [DashboardController::class, 'destroyTecnico'])->name('admin.tecnico.destroy');
    Route::get('/clientes-lista', [DashboardController::class, 'clientes'])->name('admin.clientes');
    Route::get('/clientes-lista/create', [DashboardController::class, 'createCliente'])->name('admin.clientes.create');
    Route::post('/clientes-lista', [DashboardController::class, 'storeCliente'])->name('admin.clientes.store');
    Route::post('/usuarios/{id}/validate', [DashboardController::class, 'validateUser'])->name('admin.users.validate');
    Route::get('/clientes-lista/{id}', [DashboardController::class, 'clienteShow'])->name('admin.cliente.show');
    Route::patch('/clientes-lista/{id}', [DashboardController::class, 'updateCliente'])->name('admin.cliente.update');
    Route::delete('/clientes-lista/{id}', [DashboardController::class, 'destroyCliente'])->name('admin.cliente.destroy');
    Route::get('/historial', [DashboardController::class, 'historial'])->name('admin.historial');
    Route::get('/ordenes-detalle/{id}', [DashboardController::class, 'ordenShow'])->name('admin.orden.show');
    Route::get('/configuracion', [DashboardController::class, 'configuracion'])->name('admin.configuracion');
    
    // Rutas para Técnico (Estados y Cierre)
    Route::post('/ordenes/bulk-assign', [OrdenTrabajoController::class, 'bulkAssignTecnico'])->name('ordenes.bulk-assign');
    Route::get('/ordenes/{orden}', [OrdenTrabajoController::class, 'show'])->name('ordenes.show');
    Route::patch('/ordenes/{orden}/estado', [OrdenTrabajoController::class, 'updateEstado'])->name('ordenes.update-estado');
    Route::patch('/ordenes/{orden}/tecnico', [OrdenTrabajoController::class, 'assignTecnico'])->name('ordenes.assign-tecnico');
    Route::get('/ordenes/{orden}/cierre', [OrdenTrabajoController::class, 'showCierre'])->name('ordenes.cierre');
    Route::post('/ordenes/{orden}/cerrar', [OrdenTrabajoController::class, 'cerrar'])->name('ordenes.cerrar');

    // Rutas para Cliente (Nueva Solicitud + Detalle + Valoración)
    Route::get('/solicitud/nueva', [OrdenTrabajoController::class, 'nuevaSolicitud'])->name('solicitud.nueva');
    Route::get('/mis-servicios/{orden}/valorar', [OrdenTrabajoController::class, 'showValoracion'])->name('cliente.orden.valorar');
    Route::post('/mis-servicios/{orden}/valorar', [OrdenTrabajoController::class, 'submitValoracion'])->name('cliente.orden.valorar.submit');
    Route::get('/mis-servicios/{orden}', [OrdenTrabajoController::class, 'showCliente'])->name('cliente.orden.show');

    // Rutas para Técnico
    Route::get('/mi-historial', [DashboardController::class, 'historialTecnico'])->name('tecnico.historial');
});

// Google OAuth
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Rutas de registro de técnicos (públicas)
Route::middleware('guest')->group(function () {
    Route::get('/registro-tecnico', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'createTecnico'])->name('register.tecnico');
    Route::post('/registro-tecnico', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'storeTecnico'])->name('register.tecnico.store');
});

require __DIR__.'/auth.php';
