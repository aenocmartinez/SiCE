<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Acepta correo, teléfono o DNI en el mismo input
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'Por favor, completa el reCAPTCHA para continuar.',
            'email.required' => 'Debe ingresar su correo, teléfono o DNI.',
        ];
    }

    /**
     * Normaliza el identificador para compararlo siempre contra users.email
     * - Correo: minúsculas
     * - Teléfono: solo dígitos
     * - DNI: sin espacios y MAYÚSCULAS
     */
    protected function normalizedIdentifier(): string
    {
        $raw = (string) $this->input('email', '');
        $val = trim($raw);

        if (filter_var($val, FILTER_VALIDATE_EMAIL)) {
            return mb_strtolower($val);
        }

        // Teléfono (dígitos y símbolos comunes) -> solo dígitos
        if (preg_match('/^[0-9\s\-\(\)\+]+$/', $val)) {
            return preg_replace('/\D+/', '', $val);
        }

        // DNI genérico
        $val = preg_replace('/\s+/', '', $val);
        return mb_strtoupper($val);
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        $identifier = $this->normalizedIdentifier();

        if (! Auth::attempt(['email' => $identifier, 'password' => $this->password], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey()
    {
        // Usa el identificador normalizado para que el throttling sea consistente
        return Str::lower($this->normalizedIdentifier()) . '|' . $this->ip();
    }
}
