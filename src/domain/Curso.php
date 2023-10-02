<?php

namespace Src\domain;

class Curso {
    private int $id;
    private $repository;
    private Area $area;    
    private $costo;
    private string $nombre;
    private string $modalidad;

    public function __construct(string $nombre="") {
        $this->id = 0;
        $this->costo = "";
        $this->nombre = $nombre;
        $this->modalidad = "Presencial";
        $this->area = new Area();
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

    public function setCosto($costo): void {
        $this->costo = $costo;
    }

    public function setModalidad(string $modalidad): void {
        $this->modalidad = $modalidad;
    }

    public function setArea(Area $area): void {
        $this->area = $area;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getCosto() {
        return $this->costo;
    }

    public function getModalidad(): string {
        return $this->modalidad;
    }

    public function getArea(): Area {
        return $this->area;
    }

    public static function listar($repository): array {
        return $repository->listarCursos();
    }

    public static function buscarPorId(int $id, $repository): Curso {
        return $repository->buscarCursoPorId($id);
    }

    public static function buscarPorNombreYArea(string $nombre, int $areaId, $repository): Curso {
        return $repository->buscarCursoPorNombreYArea($nombre, $areaId);
    }
    
    public function crear(): bool {
        return $this->repository->crearCurso($this);
    }

    public function eliminar(): bool {
        return $this->repository->eliminarCurso($this);
    }

    public function actualizar(): bool {
        return $this->repository->actualizarCurso($this);
    }

    public function existe(): bool {
        return $this->id > 0;
    }
}