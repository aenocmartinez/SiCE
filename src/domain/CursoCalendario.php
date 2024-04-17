<?php

namespace Src\domain;

class CursoCalendario {
    private int $id;
    // private int $cupo;
    private float $costo;
    private string $modalidad;
    private Calendario $calendario;
    private Curso $curso;

    /**
     * @param Calendario: $calendario
     * @param Curso: $curso
     * @param $datos: [(int)'cupo', (float)'costo', (string)'modalidad']
     */
    public function __construct(Calendario $calendario, Curso $curso, $datos=['cupo' => 0, 'costo' => 0, 'modalidad' => 'Presencial']) {
        $this->id = 0;
        $this->calendario = $calendario;
        $this->curso = $curso;
        // $this->cupo = (int)$datos['cupo'];
        $this->costo = floatval($datos['costo']);
        $this->modalidad = $datos['modalidad'];
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setCalendario(Calendario $calendario): void {
        $this->calendario = $calendario;
    }

    public function setCurso(Curso $curso): void {
        $this->curso = $curso;
    }

    public function setModalidad(string $modalidad): void {
        $this->modalidad = $modalidad;
    }

    public function setCosto(float $costo): void {
        $this->costo = $costo;
    }

    // public function setCupo(int $cupo): void {
    //     $this->cupo = $cupo;
    // }

    public function getModalidad(): string {
        return $this->modalidad;
    }

    public function getCosto(): float {
        return $this->costo;
    }

    // public function getCupo(): int {
    //     return $this->cupo;
    // }

    public function getCalendario(): Calendario {
        return $this->calendario;
    }

    public function getCalendarioId(): int {
        return $this->calendario->getId();
    }

    public function getCurso(): Curso {
        return $this->curso;
    }

    public function getCursoId(): int {
        return $this->curso->getId();
    }

    public function getNombreCurso(): string {
        return $this->curso->getNombre();
    }

    public function getId(): int {
        return $this->id;
    }

    public function existe(): bool {
        return $this->id > 0;
    }

    public function esCalendarioVigente(): bool {
        return $this->calendario->esVigente();
    }

}