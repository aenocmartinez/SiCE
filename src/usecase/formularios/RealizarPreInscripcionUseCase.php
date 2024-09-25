<?php

namespace Src\usecase\formularios;

use Carbon\Carbon;
use Src\dao\mysql\DiaFestivoDao;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\Grupo;
use Src\domain\Participante;
use Src\infraestructure\util\UUID;
use Src\view\dto\Response;
use Src\infraestructure\diasFestivos\Calendario;

class RealizarPreInscripcionUseCase
{
    public function ejecutar(Participante $participante, Grupo $grupo): Response 
    {
        date_default_timezone_set('America/Bogota');
        $fechaActual = Carbon::now();        
        $diaFestivo = DiaFestivoDao::buscarDiasFestivoPorAnio($fechaActual->year);
        $diasFestivos = [];
        if ($diaFestivo->existe()) {
            $diasFestivos = explode(',', $diaFestivo->getFechas());
        }
        
        $data = [
            'estado' => 'Pendiente de pago',
            'grupo_id' => $grupo->getId(),
            'participante_id' => $participante->getId(),
            'numero_formulario' => UUID::generarUUIDNumerico(),
            'costo_curso' => $grupo->getCosto(),
            'total_a_pagar' => $grupo->getCosto(),
            'medio_inscripcion' => 'formulario publico',
            'comentarios' => 'Preinscripción. ' . $grupo->getObservaciones(),
            'created_at' => $fechaActual,
            'fecha_max_legalizacion' => Calendario::fechaSiguienteDiaHabil($fechaActual, $diasFestivos),
        ];
        
        try {
            FormularioInscripcionDao::create($data);
        } catch (\Exception $e) {
            return new Response("500", "Se ha producido un error al intentar realizar la preinscripción. Por favor, inténtelo nuevamente más tarde.");
        }

        return new Response("201", "Su preinscripción ha sido exitosa. Le contactaremos para proceder con la inscripción una vez se haya alcanzado el cupo mínimo necesario.");
    }
}