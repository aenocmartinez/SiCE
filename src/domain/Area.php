<?php

namespace Src\domain;

use Src\dao\mysql\AreaDao;

class Area {

    private int $id;
    private string $nombre;
    private $repository;

    public function __construct(string $nombre = "") {
        $this->id = 0;
        $this->nombre = $nombre;
    }

    public function setRepository($repository): void {
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

    public static function buscarPorId(int $id=0, $repository): Area {
        return $repository->buscarAreaPorId($id);
    }

    public static function buscarPorNombre(string $nombre, $repository): Area {
        return $repository->buscarAreaPorNombre($nombre);
    }    

    public static function listar($repository): array {
        return $repository->listarAreas();
    }

    public function crear(): bool {
        return $this->repository->crearArea($this);
    }

    public function eliminar(): bool {
        return $this->repository->eliminarArea($this);
    }

    public function actualizar(): bool {
        return $this->repository->actualizarArea($this);
    }

    public function existe(): bool {
        return $this->id > 0;
    }
}
