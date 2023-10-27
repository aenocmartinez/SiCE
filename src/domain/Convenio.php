<?php

namespace Src\domain;

use Src\domain\repositories\ConvenioRepository;

class Convenio {
    private int $id;
    private string $nombre;
    private string $fecInicio;
    private string $fecFin;
    private Calendario $calendario;
    private $descuento;
    private ConvenioRepository $repository;

    public function __construct(string $nombre="")
    {
        $this->nombre = $nombre;
        $this->id = 0;
        $this->fecInicio = "";
        $this->fecFin = "";
        $this->calendario = new Calendario();
        $this->descuento = 0;
    }

    public function setRepository($repository): void {
        $this->repository = $repository;
    }

    public function setid(int $id): void{
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;        
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setFecInicio(string $fecInicio): void {
        $this->fecInicio = $fecInicio;
    }

    public function getFecInicio(): string {
        return $this->fecInicio;
    }

    public function setFecFin(string $fecFin): void {
        $this->fecFin = $fecFin;
    }

    public function getFecFin(): string {
        return $this->fecFin;
    }

    public function setDescuento($descuento): void {
        $this->descuento = $descuento;
    }

    public function getDescuento() {
        return $this->descuento;
    }

    public function setCalendario(Calendario $calendario): void {
        $this->calendario = $calendario;
    }

    public function getCalendarioId(): int {
        return $this->calendario->getId();
    }

    public function getNombreCalendario(): string {
        return $this->calendario->getNombre();
    }

    public function existe(): bool {
        return $this->id > 0;
    }

    public function crear(): bool {
        return $this->repository->crearConvenio($this);
    }

    public function eliminar(): bool {
        return $this->repository->eliminarConvenio($this->id);        
    }

    public function actualizar(): bool {
        return $this->repository->actualizarConvenio($this);
    }
}