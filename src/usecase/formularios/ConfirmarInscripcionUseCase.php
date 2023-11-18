<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;

use Src\infraestructure\medioPago\IMedioPago;
use Src\infraestructure\medioPago\MedioDePagoFactory;
use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\Response;

class ConfirmarInscripcionUseCase {
    
    public function ejecutar(ConfirmarInscripcionDto $confirmarInscripcionDto): Response {

        $response = new Response();

        $participanteRepository = new FormularioInscripcionDao();

        $exito = $participanteRepository->crearInscripcion($confirmarInscripcionDto);

        if (!$exito) {
            $response->code = "500";
            $response->message = "Ha ocurrido un error en el sistema.";
            return $response;
        }

        // $grupoRepository = new GrupoDao();

        // $participante = $participanteRepository->buscarParticipantePorId($confirmarInscripcionDto->participanteId);
        // $grupo = $grupoRepository->buscarGrupoPorId($confirmarInscripcionDto->grupoId);
        
        // $medioPago = MedioDePagoFactory::Obtener($confirmarInscripcionDto->medioPago);
        // $medioPago->realizarPago($participante, $grupo, $confirmarInscripcionDto->totalAPagar);


        $response->code = "201";
        $response->message = "La inscripción se ha confirmado exitosamente.";

        return $response;
    }
}