<?php

namespace Src\usecase\usuarios;

use Src\domain\Usuario;

class ListarUsuariosUseCase
{
    public function Ejecutar(): array
    {
        return Usuario::Listar();
    }
}