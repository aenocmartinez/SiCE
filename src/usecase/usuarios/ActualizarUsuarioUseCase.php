<?php

namespace Src\usecase\usuarios;

use Illuminate\Support\Facades\Hash;
use Src\domain\Usuario;
use Src\view\dto\Response;
use Src\view\dto\UsuarioDto;

class ActualizarUsuarioUseCase
{
    public function Ejecutar(UsuarioDto $usuarioDto): Response
    {
        $response = new Response(200, 'Usuario actualizado con éxito.');

        $usuario = Usuario::BuscarPorId($usuarioDto->getId());

        if (!$usuario->Existe())
        {
            $response->code = 404;
            $response->message = 'El usuario no existe.';
            return $response;
        }

        $usuario->setNombre($usuarioDto->getNombre());
        $usuario->setRole($usuarioDto->getRole());
        $usuario->setEmail($usuarioDto->getEmail());
        $usuario->setEstado($usuarioDto->getEstado());
        $usuario->setOrientadorID($usuarioDto->getOrientadorID());
        $usuario->setPuedeCargarFirmas($usuarioDto->puedeCargarFirmas());
        
        if (strlen($usuarioDto->getPassword()) > 0)
        {
            $usuario->setPassword(Hash::make($usuarioDto->getPassword()));
        }

        $exito = $usuario->actualizar();
        if (!$exito)
        {
            $response->code = 500;
            $response->message = 'Ha ocurrido un error en el sistema';
            return $response;
        }

        return $response;
    }
}