<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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

        // Contar órdenes totales para admins y técnicos
        $ordenesCount = ($user->role === 'admin' || $user->role === 'tecnico')
            ? OrdenTrabajo::count()
            : 0;

        // Calcular tiempo como miembro en años
        $memberSinceYears = \Carbon\Carbon::parse($user->created_at)->diffInYears(now());
        if ($memberSinceYears === 0) {
            $memberSinceYears = 1;
        }

        return view('profile.show', [
            'user' => $user,
            'ordenesCount' => $ordenesCount,
            'memberSinceYears' => $memberSinceYears,
            'rating' => 4.9, // Placeholder - puede venir de una tabla de ratings
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
