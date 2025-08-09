<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule; 

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
            'id'       => 'int|required',
            'nombre'   => 'required|string|max:255',
            // ✅ Sin 'email' (formato) y sin 'exists'.
            // ✅ Evita duplicados en la columna users.email, ignorando el propio registro.
            'email'    => ['required','string','max:255', Rule::unique('users','email')->ignore($this->id)],
            'password' => ['nullable', Rules\Password::defaults()],
            'role'     => 'required|string',
            'estado'   => 'nullable',
            'puede_cargar_firmas' => 'nullable',
            'orientador_id' => 'nullable',
        ];
    }
}
