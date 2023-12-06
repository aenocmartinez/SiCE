<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Src\domain\DiaFestivo;
use Src\domain\repositories\DiasFestivosRepository;

use Sentry\Laravel\Facade as Sentry;

class DiaFestivoDao extends Model implements DiasFestivosRepository{
    protected $table = 'dias_festivos';
    protected $fillable = ['anio', 'fechas']; 

    public static function buscarDiasFestivoPorAnio(int $anio=0): DiaFestivo {
        $diaFestivo = new DiaFestivo();

        try {
            $resultado = DiaFestivoDao::where('anio', $anio)->first();
            if ($resultado) {
                $diaFestivo->setId($resultado->id);
                $diaFestivo->setAnio($resultado->anio);
                $diaFestivo->setFechas($resultado->fechas);
            }
        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $diaFestivo;
    }

    public function crearDiasFestivoAnio(DiaFestivo $diaFestivo): bool {        
        $exito = false;
        try {
            DiaFestivoDao::create([
                'anio' => $diaFestivo->getAnio(),
                'fechas' => $diaFestivo->getFechas(),
            ]);
            $exito = true;
        } catch (Exception $e) {
            
            Sentry::captureException($e);
        }
        return $exito;
    }
}