<?php

namespace Src\usecase\participantes;

use Src\dao\mysql\ParticipanteDao;
use Src\view\dto\ParticipanteDto;
use Src\view\dto\Response;

class GuardarParticipanteUseCase {

    public function ejecutar(ParticipanteDto $participanteDto): Response {        
        $exito = false;
        $code = "200";
        $message = "";
        
        $response = new Response();
        $participanteRepository = new ParticipanteDao();
        
        $participante =$participanteRepository->buscarParticipantePorId($participanteDto->id);

        // $participante = $participanteRepository->buscarParticipantePorDocumento($participanteDto->tipoDocumento, $participanteDto->documento);

        $participante->setPrimerNombre($participanteDto->primerNombre);
        $participante->setSegundoNombre($participanteDto->segundoNombre);
        $participante->setPrimerApellido($participanteDto->primerApellido);
        $participante->setSegundoApellido($participanteDto->segundoApellido);
        $participante->setFechaNacimiento($participanteDto->fechaNacimiento);
        $participante->setTipoDocumento($participanteDto->tipoDocumento);
        $participante->setDocumento($participanteDto->documento);
        $participante->setSexo($participanteDto->sexo);
        $participante->setEstadoCivil($participanteDto->estadoCivil);
        $participante->setDireccion($participanteDto->direccion);        
        $participante->setTelefono($participanteDto->telefono);
        $participante->setEmail($participanteDto->email);
        $participante->setEps($participanteDto->eps);
        $participante->setContactoEmergencia($participanteDto->contactoEmergencia);
        $participante->setTelefonoEmergencia($participanteDto->telefonoEmergencia);
        $participante->setRepository($participanteRepository);
        
        if (isset($participanteDto->vinculadoUnicolMayor)) {
            $participante->setVinculadoUnicolMayor($participanteDto->vinculadoUnicolMayor);
        }

        if ($participante->existe()) {
            $exito = $participante->actualizar();
            $code = ($exito) ? "200" : "500";
            $message = ($exito) ? "Registro actualizado con éxito" : "Ha ocurrido un error en el sistema";
        } else {
            $exito = $participante->crear();
            $code = ($exito) ? "201" : "500";
            $message = ($exito) ? "Registro creado con éxito" : "Ha ocurrido un error en el sistema";
        }

        $response->code = $code;
        $response->message = $message;

        return $response;
    }
}