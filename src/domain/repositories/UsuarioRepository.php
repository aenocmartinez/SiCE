<?php

namespace Src\domain\repositories;

use Src\domain\Usuario;

interface UsuarioRepository 
{
    public static function BuscarPorId(int $id=0): Usuario;
    public static function BuscarPorEmail(string $email): Usuario;
    public function crearUsuario(Usuario $usuario): bool;
    public function actualizarUsuario(Usuario $usuario):bool;
}