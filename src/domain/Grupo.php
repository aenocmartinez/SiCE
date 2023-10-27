<?php

namespace Src\domain;

class Grupo {
    private int $id;
    private string $dia;
    private Curso $curso;
    private Salon $salon;
    private string $jornada;
    private Orientador $orientador;
    private CursoCalendario $cursoCalendario;
    private $repository;

    public function __construct($cursoCalendarioId=0, $salonId=0, $orientadorId=0) {
        $this->id = 0;
        $this->dia = "";
        $this->jornada = "";

        $this->orientador = new Orientador;
        $this->orientador->setId($orientadorId);
        

        $this->cursoCalendario = new CursoCalendario(new Calendario(), new Curso());
        $this->cursoCalendario->setId($cursoCalendarioId);

        $this->salon = new Salon;         
        $this->salon->setId($salonId);
    }

    public function setRepository($repository): void {
        $this->repository = $repository;
    }

    public function setId($id=0): void {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    // public function setCurso(Curso $curso): void {
    //     $this->curso = $curso;
    // }

    public function setCursoCalendario(CursoCalendario $cursoCalendario): void {
        $this->cursoCalendario = $cursoCalendario;
    }

    // public function getCurso(): Curso {
    //     return $this->curso;
    // }

    public function getCursoCalendarioId(): int {
       return $this->cursoCalendario->getId();
    }

    public function getNombreCurso(): string {
        return $this->cursoCalendario->getCurso()->getNombre();
    }

    public function getNombreCalendario(): string {
        return $this->cursoCalendario->getCalendario()->getNombre();
    }

    public function getCalendarioId(): int {
        return $this->cursoCalendario->getCalendario()->getId();
    }    

    // public function setCalendario(Calendario $calendario): void {
    //     $this->cursoCalendario->setCalendario($calendario);
    // }

    // public function getCalendario(): Calendario {
    //     return $this->cursoCalendario->getCalendario();
    // }

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

    public function getOrientadorId(): int {
        return $this->orientador->getId();
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

    public static function validarExistencia(Grupo $grupo, $repository): bool {
        return $repository->existeGrupo($grupo);
    }

    public static function validarSalonDisponible(Grupo $grupo, $repository): bool {
        return $repository->salonDisponible($grupo);
    }
}