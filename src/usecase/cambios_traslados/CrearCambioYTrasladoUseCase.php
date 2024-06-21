<?php

namespace Src\usecase\cambios_traslados;

use Src\domain\CambioTraslado;
use Src\domain\FormularioInscripcion;
use Src\domain\Grupo;
use Src\domain\Participante;

class CrearCambioYTrasladoUseCase {

    public function ejecutar(FormularioInscripcion $formulario, Grupo $nuevoGrupo, Participante $nuevoParticipante, $datosComplementarios=[]): bool {

        $cambioTraslado = new CambioTraslado();
        $cambioTraslado->setFormulario($formulario);
        $cambioTraslado->setPeriodo($formulario->getGrupoCalendarioNombre());
        $cambioTraslado->setParticipanteInicial($formulario->getParticipante());
        $cambioTraslado->setNuevoParticipante($nuevoParticipante);
        $cambioTraslado->setGrupoInicial($formulario->getGrupo());
        $cambioTraslado->setNuevoGrupo($nuevoGrupo);
        $cambioTraslado->setAccion($datosComplementarios['accion']);
        $cambioTraslado->setDecisionDePago($datosComplementarios['decisionPago']);
        $cambioTraslado->setValorInicialAPagar($formulario->getTotalAPagar());
        $cambioTraslado->setNuevoValorAPagar($datosComplementarios['nuevoValorAPagar']);
        $cambioTraslado->setJustificacion($datosComplementarios['justificacion']);

        return $cambioTraslado->Crear();
    }
}