<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class ActualizarUsuario extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->esSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'int|required',
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|exists:users',
            'password' => ['nullable', Rules\Password::defaults()],
            'role' => 'required|string',
            'estado' => 'nullable',
        ];        
    }
}