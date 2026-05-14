<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            \Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['email' => 'No se pudo autenticar con Google: ' . $e->getMessage()]);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Usuario ya existe → iniciar sesión directamente
            Auth::login($user, true);
            return redirect()->intended(route('dashboard'));
        }

        // Usuario nuevo → crear como cliente
        $user = User::create([
            'name'              => $googleUser->getName(),
            'email'             => $googleUser->getEmail(),
            'password'          => bcrypt(Str::random(24)),
            'role'              => 'cliente',
            'foto_perfil'       => $googleUser->getAvatar(),
            'email_verified_at' => now(),
        ]);

        Auth::login($user, true);
        return redirect()->route('dashboard');
    }
}
