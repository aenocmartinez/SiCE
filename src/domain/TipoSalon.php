<?php

namespace Src\domain;

class TipoSalon {
    private int $id;
    private string $nombre;
    private $repository;

    public function __construct(string $nombre = "") {
        $this->id = 0;
        $this->nombre = $nombre;
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

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public static function listar($repository): array {
        return $repository->listarTipoSalones();
    }

    public static function buscadorSalones($criterio, $repository): array {
        return $repository->buscadorSalones($criterio);
    }

    public static function buscarPorId(int $id=0, $repository): TipoSalon {
        return $repository->buscarTipoSalonPorId($id);
    }

    public static function buscarPorNombre(string $nombre, $repository): TipoSalon {
        return $repository->buscarTipoSalonPorNombre($nombre);
    }

    public function crear(): bool {
        return $this->repository->crearTipoSalon($this);
    }

    public function eliminar(): bool {
        return $this->repository->eliminarTipoSalon($this);
    }

    public function actualizar(): bool {
        return $this->repository->actualizarTipoSalon($this);
    }

    public function existe(): bool {
        return $this->id > 0;
    }
}