<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request; // Asegúrate de importar correctamente la clase Request de Laravel
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        // Verificación manual del reCAPTCHA usando Guzzle
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = env('RECAPTCHA_SECRET_KEY_V2');
        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

        // Verificación del captcha
        $response = Http::asForm()->post($verifyUrl, [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
        ]);

        $responseKeys = $response->json();

        // Si la verificación falla, mostrar el mensaje de error
        if (!$responseKeys["success"]) {
            
            return back()->withErrors(['g-recaptcha-response' => 'Verificación de reCAPTCHA fallida. Inténtelo de nuevo.'])->withInput();
        }

        // Intento de autenticación
        if (!Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            
            return back()->withErrors(['email' => __('auth.failed')])->withInput();
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->estaActivo())
        {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'El usuario se encuentra inactivo.'])->withInput();
        }

        // Log::info('Autenticación exitosa para ' . $request->input('email'));

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
