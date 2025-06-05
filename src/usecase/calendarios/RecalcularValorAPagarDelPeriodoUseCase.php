<?php

namespace Src\usecase\calendarios;

use Src\dao\mysql\CalendarioDao;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\FormularioInscripcion;
use Src\view\dto\Response;

class RecalcularValorAPagarDelPeriodoUseCase
{
    public function ejecutar(int $periodoID): Response
    {
        $calendarioDao = new CalendarioDao();
        $calendario = $calendarioDao->buscarCalendarioPorId($periodoID);

        if (!$calendario->existe()) {
            return new Response('404', 'El periodo no existe');
        }

        /** @var FormularioInscripcion[] $formularios */
        $formularios = FormularioInscripcionDao::listarFormulariosPorEstadoYCalendario("Pagado", $periodoID);
        
        foreach ($formularios as $formulario) 
        {
            if ($formulario->tieneConvenio())
            {                
                /** @var \Src\domain\Convenio $convenio */
                $convenio = $formulario->getConvenio();

                $cantidadMatriculados = $convenio->getTotalParticipantesMatriculados();

                $totalAPagar = $convenio->calcularValorConDescuento($formulario->getCostoCurso(), $cantidadMatriculados);
                if ($convenio->esCooperativa()) 
                {
                    $porcentajeDescuento = $convenio->getDescuentoAplicable($cantidadMatriculados);
                    $valorDescuento = round(($formulario->getCostoCurso() * $porcentajeDescuento / 100), 2);                       
                } 
                else 
                {
                    $valorDescuento = round(($formulario->getCostoCurso() * $convenio->getDescuento() / 100), 2); 
                }

                $datosActualizar[] = [
                    'id' => $formulario->getId(),
                    'valor_descuento' => $valorDescuento,
                    'total_a_pagar' => $totalAPagar
                ];
            }

        }

        if (!empty($datosActualizar)) {
            FormularioInscripcionDao::actualizarValoresDescuento($datosActualizar);
        }        

        return new Response('200', 'Proceso completado con éxito');
    }
}
