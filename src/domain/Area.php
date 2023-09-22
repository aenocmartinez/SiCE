<?php

namespace Src\domain;

use Src\dao\mysql\AreaDao;

class Area {

    private int $id;
    private string $nombre;
    private AreaRepository $repository;

    public function __construct(string $nombre = "") {
        $this->nombre = $nombre;
    }

    public function setRepository(AreaRepository $repository): void {
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

    public static function buscarPorId(int $id=0): Area {
        return null;
    }

    public static function listar($repository): array {
        return $repository->listarAreas();
    }

    public function crear(): bool {
        return false;
    }

    public function eliminar(): bool {
        return false;
    }

    public function actualizar(): bool {
        return false;
    }

    public function existe(): bool {
        return $this->id > 0;
    }
}
