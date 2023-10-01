<?php

namespace Src\domain;

use DateTime;

class Calendario {
    private int $id;
    private string $nombre;
    private $fechaInicio;
    private $fechaFinal;
    private $repository;

    public function __construct(string $nombre="", $fechaInicio="", $fechaFinal="") {
        $this->id = 0;
        $this->nombre = $nombre;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
    }

    public function setRepository($repository): void {
        $this->repository = $repository;
    }

    public function setId(int $id): void {
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

    public function setFechaInicio($fechaInicio): void {
        $this->fechaInicio = $fechaInicio;
    }

    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    public function setFechaFinal($fechaFinal): void{
        $this->fechaFinal = $fechaFinal;
    }

    public function getFechaFinal() {
        return $this->fechaFinal;
    }

    public static function listar($repository): array {
        return $repository->listarCalendarios();
    }

    public static function buscarPorId(int $id=0, $repository): Calendario {
        return $repository->buscarCalendarioPorId($id);
    }

    public static function buscarPorNombre(string $nombre, $repository): Calendario {
        return $repository->buscarCalendarioPorNombre($nombre);
    }

    public function crear(): bool {
        return $this->repository->crearCalendario($this);
    }

    public function eliminar(): bool {
        return $this->repository->eliminarCalendario($this);
    }

    public function actualizar(): bool {
        return $this->repository->actualizarCalendario($this);
    }

    public function existe(): bool {
        return $this->id > 0;
    }

    public function esVigente(): bool {        
        $fechaActual = new DateTime(date("Y-m-d"));
        $fechaInicio = new DateTime($this->fechaInicio);
        $fechaFin = new DateTime($this->fechaFinal);
        return $fechaActual >= $fechaInicio && $fechaActual <= $fechaFin;        
    }

    public function estado(): string {
        return $this->esVigente() ? "Vigente" : "Caducado";
    }
}