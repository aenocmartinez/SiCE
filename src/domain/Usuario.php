<?php

namespace Src\domain;

use App\Models\User;
use Src\domain\repositories\UsuarioRepository;
use Src\view\dto\UsuarioDto;

class Usuario
{
    private int $id;
    private string $nombre;
    private string $email;
    private string $password;
    private string $role;
    private string $estado;
    private bool $puedeCargarFirmas;
    private int $orientadorID;
    private $fecha_creacion;
    private $repository;

    public function __construct()
    {
        $this->id = 0;
        $this->nombre = "";
        $this->role = "";
        $this->password = "";
        $this->email = "";
        $this->estado = "";
        $this->orientadorID = 0;
        $this->repository = new User();
    }

    public static function Listar(): array
    {
        $usuarios = [];
        foreach(User::all() as $usuario)
        {
            $usuarioDto = new UsuarioDto();
            $usuarioDto->setId($usuario->id);
            $usuarioDto->setNombre($usuario->name);
            $usuarioDto->setEmail($usuario->email);
            $usuarioDto->setRole($usuario->role);
            $usuarioDto->setEstado($usuario->estado);
            $usuarioDto->setFechaCreacion($usuario->created_at);

            $usuarios[] = $usuarioDto;
        }

        return $usuarios;
    }

    public static function BuscarPorId(int $id=0): Usuario
    {
        return User::BuscarPorId($id);
    }

    public static function BuscarPorEmail(string $email): Usuario
    {
        return User::BuscarPorEmail($email);
    }

    public function crear(): bool 
    {
        return $this->repository->crearUsuario($this);
    }

    public function actualizar(): bool 
    {
        return $this->repository->actualizarUsuario($this);
    }

    public function Existe(): bool 
    {
        return $this->id > 0;
    }

    public function setRepository(UsuarioRepository $repository): void
    {
        $this->repository = $repository;
    }

    public function setId(int $id): void 
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): void 
    {
        $this->password = $password;
    }

    public function getPassword(): string 
    {
        return $this->password;
    }

    public function setRole(string $role): void 
    {
        $this->role = $role;
    }

    public function getRole(): string 
    {
        return $this->role;
    }

    public function setFechaCreacion($fecha_creacion): void
    {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getFechaCreacion() 
    {
        return $this->fecha_creacion;
    }  
    
    public function setEstado(string $estado): void 
    {
        $this->estado = $estado;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }    

    public function setPuedeCargarFirmas(bool $puedeCargarFirmas): void {
        $this->puedeCargarFirmas = $puedeCargarFirmas;
    }

    public function puedeCargarFirmas(): bool {
        return $this->puedeCargarFirmas;
    }  
    
    public function setOrientadorID(int $orientadorID): void {
        $this->orientadorID = $orientadorID;
    }

    public function getOrientadorID(): int {
        return $this->orientadorID;
    }    
}