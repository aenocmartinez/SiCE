<?php

namespace Src\usecase\usuarios;

use Src\domain\Usuario;
use Src\view\dto\Response;
use Src\view\dto\UsuarioDto;

class CrearUsuarioUseCase
{
    public function Ejecutar(UsuarioDto $usuarioDto): Response
    {
        $response = new Response("500", "Ha ocurrido un error en el sistema");

        $usuario = Usuario::BuscarPorEmail($usuarioDto->getEmail());
        if ($usuario->existe())
        {
            $response->code = "200";
            $response->message = "El usuario ya existe";
            return $response;
        }

        $usuario->setNombre($usuarioDto->getNombre());
        $usuario->setPassword($usuarioDto->getPassword());
        $usuario->setRole($usuarioDto->getRole());
        $usuario->setEmail($usuarioDto->getEmail());
        $usuario->setEstado($usuarioDto->getEstado());
        $usuario->setOrientadorID($usuarioDto->getOrientadorID());
        $usuario->setPuedeCargarFirmas($usuarioDto->puedeCargarFirmas());

        $exito = $usuario->crear();

        if ($exito)
        {
            $response->code = "201";
            $response->message = "Usuario creado con éxito";
            return $response;
        }

        return $response;
    }
}