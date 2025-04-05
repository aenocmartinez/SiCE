<?php

namespace Src\view\dto;

class UsuarioDto
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

    public function __construct()
    {
        $this->nombre = "";
        $this->email = "";
        $this->password = "";
        $this->role = "";
        $this->estado = "";
        $this->orientadorID = 0;
        $this->puedeCargarFirmas = false;
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