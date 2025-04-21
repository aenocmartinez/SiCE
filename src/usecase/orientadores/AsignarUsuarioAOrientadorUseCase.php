<?php

namespace Src\usecase\orientadores;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Src\domain\Orientador;

class AsignarUsuarioAOrientadorUseCase
{
    public function ejecutar(Orientador $orientador)
    {
        $nombre = Str::title(Str::lower($orientador->getNombre()));

        $usuario = User::where('email', $orientador->getEmailInstitucional())->first();
        if ($usuario)
        {
            $usuario->update([
                'name' => $nombre,
                'password' => Hash::make($orientador->getDocumento()),
            ]);
        }
        else
        {
            User::create([
                'name' => $nombre,
                'email' => $orientador->getEmailInstitucional(),
                'password' => Hash::make($orientador->getDocumento()),
                'orientador_id' => $orientador->getId(),
                'role' => 'orientador',
            ]);
        }
    }
}