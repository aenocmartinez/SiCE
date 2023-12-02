<?php

namespace Src\domain;

use Carbon\Carbon;
use Src\dao\mysql\DiaFestivoDao;

class DiaFestivo {
    
    private int $id;
    private int $anio;
    private string $fechas;
    private $repository;

    public function __construct() {
        $this->id = 0;
        $this->fechas = "";
        $this->anio = Carbon::now()->year;
    }

    public function setRepository($repository): void {
        $this->repository = $repository;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setAnio(int $anio): void {
        $this->anio = $anio;
    }

    public function getAnio(): int {
        return $this->anio;
    }

    public function setFechas(string $fechas): void {
        $this->fechas = $fechas;
    }

    public function getFechas(): string {
        return $this->fechas;
    }

    public function existe(): bool {
        return $this->id > 0;
    }

    public static function buscarDiasFestivosPorAnio(int $anio): DiaFestivo {
        return DiaFestivoDao::buscarDiasFestivosPorAnio($anio);
    }

    public function crear(): bool {
        return $this->repository->crearDiasFestivoAnio($this);
    }
}