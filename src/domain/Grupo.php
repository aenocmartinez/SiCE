<?php

namespace Src\domain;

use Carbon\Carbon;

class Grupo {
    private int $id;
    private string $dia;
    private string $jornada;
    private int $totalInscritos;
    private int $cupo;
    private $hora;
    private Salon $salon;
    private Orientador $orientador;
    private CursoCalendario $cursoCalendario;
    private $repository;

    public function __construct($cursoCalendarioId=0, $salonId=0, $orientadorId=0) {
        $this->id = 0;
        $this->dia = "";
        $this->jornada = "";
        $this->hora = "";
        $this->totalInscritos = 0;
        $this->cupo = 0;

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

    public function setHora($hora): void {
        $this->hora = $hora;
    }

    public function getHora() {   
        if (strlen($this->hora) == 0) {
            return "";
        }

        return Carbon::createFromFormat('H:i:s', $this->hora)->format('H:i');

        // return Carbon::createFromFormat('H:i:s', $this->hora)->format('H:i');
    }    

    public function setCursoCalendario(CursoCalendario $cursoCalendario): void {
        $this->cursoCalendario = $cursoCalendario;
    }

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

    public function getModalidad(): string {
        return $this->cursoCalendario->getModalidad();
    }

    public function setSalon(Salon $salon): void {
        $this->salon = $salon;
    }

    public function getSalon(): Salon {
        return $this->salon;
    }

    public function getSalonId(): int {
        return $this->salon->getId();
    }

    public function getNombreSalon(): string {
        return $this->salon->getNombre();
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

    public function getNombreOrientador(): string {
        return $this->orientador->getNombre();
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

    public function setCupo(int $cupo): void {
        $this->cupo = $cupo;
    }

    public function getCupo(): int {
        return $this->cupo;
    }

    public function setTotalInscritos(int $totalInscritos): void {
        $this->totalInscritos = $totalInscritos;
    }

    public function getTotalInscritos(): int {
        return $this->totalInscritos;
    }

    public function getTotalCuposDisponibles(): int {
        return $this->getCupo() - $this->getTotalInscritos();
    }

    public function getCosto() {
        return $this->cursoCalendario->getCosto();
    }

    public function getCostoFormateado() {
        $montoFormateado = number_format($this->getCosto(), 0, ',', '.');
        return '$' . $montoFormateado . ' COP';
    }

    public function getCodigoGrupo(): string {
        return "G" . $this->getId();
    }

    public function esCalendarioVigente(): bool {
        return $this->cursoCalendario->esCalendarioVigente();
    }
}