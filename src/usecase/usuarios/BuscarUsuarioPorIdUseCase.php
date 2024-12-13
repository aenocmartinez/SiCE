<?php

namespace Src\usecase\usuarios;

use Src\domain\Usuario;

class BuscarUsuarioPorIdUseCase
{
    public function Ejecutar(int $id): Usuario
    {
        return Usuario::BuscarPorId($id);
    }
}