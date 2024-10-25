<?php

namespace Src\infraestructure\rptNumCursosYParticipanteXJornada;

class RegistroDTO 
{
    private string $curso_actual;
    private int $curso_actual_total_grupos;
    private int $curso_actual_total_genero_femenino;
    private int $curso_actual_total_genero_masculino;
    private int $curso_actual_total_genero_otro;
    private int $curso_actual_total_participantes;
    private string $area_actual;

    public function __construct()
    {
        $this->curso_actual = "";
        $this->curso_actual_total_grupos = 0;
        $this->curso_actual_total_genero_femenino = 0;
        $this->curso_actual_total_genero_masculino = 0;
        $this->curso_actual_total_genero_otro = 0;
        $this->curso_actual_total_participantes = 0;
        $this->area_actual = "";
    }

    public function getCursoActual(): string {
        return $this->curso_actual;
    }

    public function setCursoActual(string $curso_actual): void {
        $this->curso_actual = $curso_actual;
    }

    public function getCursoActualTotalGrupos(): int {
        return $this->curso_actual_total_grupos;
    }

    public function setCursoActualTotalGrupos(int $curso_actual_total_grupos): void {
        $this->curso_actual_total_grupos = $curso_actual_total_grupos;
    }

    public function getCursoActualTotalGeneroFemenino(): int {
        return $this->curso_actual_total_genero_femenino;
    }

    public function setCursoActualTotalGeneroFemenino(int $curso_actual_total_genero_femenino): void {
        $this->curso_actual_total_genero_femenino = $curso_actual_total_genero_femenino;
    }

    public function getCursoActualTotalGeneroMasculino(): int {
        return $this->curso_actual_total_genero_masculino;
    }

    public function setCursoActualTotalGeneroMasculino(int $curso_actual_total_genero_masculino): void {
        $this->curso_actual_total_genero_masculino = $curso_actual_total_genero_masculino;
    }

    public function getCursoActualTotalGeneroOtro(): int {
        return $this->curso_actual_total_genero_otro;
    }

    public function setCursoActualTotalGeneroOtro(int $curso_actual_total_genero_otro): void {
        $this->curso_actual_total_genero_otro = $curso_actual_total_genero_otro;
    }

    public function getCursoActualTotalParticipantes(): int {
        return $this->curso_actual_total_participantes;
    }

    public function setCursoActualTotalParticipantes(int $curso_actual_total_participantes): void {
        $this->curso_actual_total_participantes = $curso_actual_total_participantes;
    }

    public function getAreaActual(): string {
        return $this->area_actual;
    }

    public function setAreaActual(string $area_actual): void {
        $this->area_actual = $area_actual;
    }
}
