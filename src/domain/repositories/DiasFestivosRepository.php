<?php

namespace Src\domain\repositories;

use Src\domain\DiaFestivo;

interface DiasFestivosRepository {

    public static function buscarDiasFestivoPorAnio(int $anio=0): DiaFestivo;
    public function crearDiasFestivoAnio(DiaFestivo $diaFestivo): bool;
}