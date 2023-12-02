<?php

namespace Src\usecase\dia_festivo;

use Carbon\Carbon;
use Src\dao\mysql\DiaFestivoDao;
use Src\infraestructure\diasFestivos\Calendario;

class GuardarDiasFestivosDeUnAnioUseCase {

    public function ejecutar(): bool {

        $diaFestivoRepository = new DiaFestivoDao();
        $anio = Carbon::now()->year;

        $diaFestivo = $diaFestivoRepository->buscarDiasFestivoPorAnio($anio);
        if ($diaFestivo->existe()) {
            return false;
        }

        $diaFestivo->setAnio($anio);
        $diaFestivo->setFechas(Calendario::obtenerDiasFestivo($anio));
        $diaFestivo->setRepository($diaFestivoRepository);

        return $diaFestivo->crear();
    }
}