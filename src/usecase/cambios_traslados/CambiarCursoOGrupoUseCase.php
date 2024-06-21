<?php

namespace Src\usecase\cambios_traslados;

use Src\domain\FormularioInscripcion;
use Src\domain\Grupo;

class CambiarCursoOGrupoUseCase {

    public function ejecutar(FormularioInscripcion $formulario, Grupo $nuevoGrupo, $datosComplementarios = ['justificacion', 'accion', 'decision_sobre_pago']): bool {


        $crearCambioUseCase = new CrearCambioYTrasladoUseCase();
        $datosDePago = $formulario->RecalcularDatosDePago($nuevoGrupo, $datosComplementarios);

        return $crearCambioUseCase->ejecutar($formulario, 
                                             $nuevoGrupo, 
                                             $formulario->getParticipante(), 
                                             [
                                                'accion' => 'cambio', 
                                                'decisionPago' => $datosComplementarios['decision_sobre_pago'], 
                                                'nuevoValorAPagar' => $datosDePago['totalAPagar'],
                                                'justificacion' => $datosComplementarios['justificacion'],
                                             ]);
    }
}