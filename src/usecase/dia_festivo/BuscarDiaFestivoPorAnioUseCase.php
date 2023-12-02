<?php

namespace Src\usecase\dia_festivo;

use Src\dao\mysql\DiaFestivoDao;
use Src\domain\DiaFestivo;

class BuscarDiaFestivoPorAnioUseCase {

    public function ejecutar(int $anio): DiaFestivo {

        return DiaFestivoDao::buscarDiasFestivoPorAnio($anio);
    }
}
