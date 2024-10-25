<?php

namespace Src\view\dto;

class ReporteNumeroCursoYParticipantePorJornadaDto
{
    private string $area;
    private string $curso;
    private string $jornada;
    private string $sexo;
    private int $total_inscritos;
    private int $total_grupos;
    private int $total_participantes;
    private int $total_masculinos;
    private int $total_femeninos;
    private int $total_otro;

    public function getArea(): string
    {
        return $this->area;
    }

    public function setArea(string $area): void
    {
        $this->area = $area;
    }

    public function getCurso(): string
    {
        return $this->curso;
    }

    public function setCurso(string $curso): void
    {
        $this->curso = $curso;
    }

    public function getJornada(): string
    {
        return $this->jornada;
    }

    public function setJornada(string $jornada): void
    {
        $this->jornada = $jornada;
    }

    public function getSexo(): string
    {
        return $this->sexo;
    }

    public function setSexo(string $sexo): void
    {
        $this->sexo = $sexo;
    }

    public function getTotalInscritos(): int
    {
        return $this->total_inscritos;
    }

    public function setTotalInscritos(int $total_inscritos): void
    {
        $this->total_inscritos = $total_inscritos;
    }

    public function getTotalGrupos(): int
    {
        return $this->total_grupos;
    }

    public function setTotalGrupos(int $total_grupos): void
    {
        $this->total_grupos = $total_grupos;
    }

    public function getTotalParticipantes(): int
    {
        return $this->total_participantes;
    }

    public function setTotalParticipantes(int $total_participantes): void
    {
        $this->total_participantes = $total_participantes;
    }

    public function getTotalMasculinos(): int
    {
        return $this->total_masculinos;
    }

    public function setTotalMasculinos(int $total_masculinos): void
    {
        $this->total_masculinos = $total_masculinos;
    }

    public function getTotalFemeninos(): int
    {
        return $this->total_femeninos;
    }

    public function setTotalFemeninos(int $total_femeninos): void
    {
        $this->total_femeninos = $total_femeninos;
    }

    public function getTotalOtro(): int
    {
        return $this->total_otro;
    }

    public function setTotalOtro(int $total_otro): void
    {
        $this->total_otro = $total_otro;
    }
}
