<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\OrdenTrabajo;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(Request $request): View
    {
        $user = $request->user();

        // Órdenes según rol
        if ($user->role === 'tecnico') {
            $ordenesCount = OrdenTrabajo::where('usuario_id', $user->id)->count();
        } elseif ($user->role === 'cliente') {
            $ordenesCount = OrdenTrabajo::where('cliente_id', $user->cliente_id)->count();
        } else {
            $ordenesCount = OrdenTrabajo::count();
        }

        // Tiempo como miembro legible (días / meses / años)
        $interval = $user->created_at->toDateTime()->diff(new \DateTime());
        $totalDays = (int) $interval->days; // ->days = total de días (entero puro de PHP)

        if ($totalDays < 1) {
            $memberSince = 'hoy';
        } elseif ($totalDays < 30) {
            $memberSince = $totalDays . ($totalDays === 1 ? ' día' : ' días');
        } elseif ($interval->y === 0) {
            $meses = $interval->m;
            $memberSince = $meses . ($meses === 1 ? ' mes' : ' meses');
        } else {
            $anios = $interval->y;
            $memberSince = $anios . ($anios === 1 ? ' año' : ' años');
        }

        // Valoración real desde el campo satisfaccion (1-5) — el admin no tiene valoración
        if ($user->role === 'tecnico') {
            $avgRaw = OrdenTrabajo::where('usuario_id', $user->id)
                ->whereNotNull('satisfaccion')->avg('satisfaccion');
        } elseif ($user->role === 'cliente') {
            $avgRaw = OrdenTrabajo::where('cliente_id', $user->cliente_id)
                ->whereNotNull('satisfaccion')->avg('satisfaccion');
        } else {
            $avgRaw = null; // admin no es valorado por nadie
        }
        $rating = $avgRaw ? round($avgRaw, 1) : null;

        return view('profile.show', [
            'user'         => $user,
            'ordenesCount' => $ordenesCount,
            'memberSince'  => $memberSince,
            'rating'       => $rating,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'foto_perfil' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();

        if ($user->foto_perfil) {
            Storage::disk('public')->delete($user->foto_perfil);
        }

        $path = $request->file('foto_perfil')->store('fotos-perfil', 'public');
        $user->foto_perfil = $path;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'photo-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
