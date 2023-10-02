<?php

namespace Src\domain;

class Grupo {
    private int $id;
    private string $dia;
    private Curso $curso;
    private Salon $salon;
    private string $jornada;
    private Calendario $calendario;
    private Orientador $orientador;
    private $repository;

    public function __construct() {
        $this->id = 0;
    }

    public function setRepository($repository): void {
        $this->repository = $repository;
    }

    public function setId(int $id=0): void {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setCurso(Curso $curso): void {
        $this->curso = $curso;
    }

    public function getCurso(): Curso {
        return $this->curso;
    }

    public function setCalendario(Calendario $calendario): void {
        $this->calendario = $calendario;
    }

    public function getCalendario(): Calendario {
        return $this->calendario;
    }

    public function setSalon(Salon $salon): void {
        $this->salon = $salon;
    }

    public function getSalon(): Salon {
        return $this->salon;
    }

    public function setOrientador(Orientador $orientador): void {
        $this->orientador = $orientador;
    }

    public function getOrientador(): Orientador {
        return $this->orientador;
    }

    public function setDia(string $dia): void {
        $this->dia = $dia;
    }

    public function getDia(): string {
        return $this->dia;
    }

    public function setJornada(string $jornada): void {
        $this->jornada = $jornada;
    }

    public function getJornada(): string {
        return $this->jornada;
    }

    public function existe(): bool {
        return $this->id > 0;
    }

    public static function listar($repository): array {
        return $repository->listarGrupos();
    }

    public static function buscarPorId(int $id, $repository): Grupo {
        return $repository->buscarGrupoPorId($id);
    }

    public function crear(): bool {
        return $this->repository->crearGrupo($this);
    }

    public function eliminar(): bool {
        return $this->repository->eliminarGrupo($this);
    }

    public function actualizar(): bool {
        return $this->repository->actualizarGrupo($this);
    }
}