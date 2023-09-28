<?php

namespace Src\domain;

class Salon {
    private int $id;
    private string $nombre;
    private int $capacidad;
    private bool $disponible;
    private $repository;

    public function __construct(string $nombre = "") {
        $this->id = 0;
        $this->capacidad = 0;
        $this->nombre = $nombre;
        $this->disponible = true;
    }

    public function setRepository($repository) {
        $this->repository = $repository;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setCapacidad(int $capacidad): void {
        $this->capacidad = $capacidad;
    }

    public function setDisponible(bool $disponible): void {
        $this->disponible = $disponible;
    }

    public function getDisponibleTexto(): string {
        $texto = "disponible";
        if (!$this->disponible) {
            $texto = "no disponible";
        }

        return $texto;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getCapacidad(): int {
        return $this->capacidad;
    }

    public function estaDisponible(): bool {
        return $this->disponible;
    }

    public static function listarSalones($repository): array {
        return $repository->listarSalones();
    }

    public static function buscadorSalones($criterio, $repository): array {
        return $repository->buscadorSalones($criterio);
    }

    public static function buscarPorId(int $id=0, $repository): Salon {
        return $repository->buscarSalonPorId($id);
    }

    public static function buscarPorNombre(string $nombre, $repository): Salon {
        return $repository->buscarSalonPorNombre($nombre);
    }

    public function crear(): bool {
        return $this->repository->crearSalon($this);
    }

    public function eliminar(): bool {
        return $this->repository->eliminarSalon($this);
    }

    public function actualizar(): bool {
        return $this->repository->actualizarSalon($this);
    }

    public function existe(): bool {
        return $this->id > 0;
    }
}