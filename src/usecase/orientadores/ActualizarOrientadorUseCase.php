<?php
namespace Src\usecase\orientadores;

use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;
use Src\view\dto\OrientadorDto;

class ActualizarOrientadorUseCase {

    public function ejecutar(OrientadorDto $orientadorDto): array {
       
        $orientadorRepository = new OrientadorDao();

        $orientador = Orientador::buscarPorId($orientadorDto->id, $orientadorRepository);

        if (!$orientador->existe()) {
            return [
                "code" => "200",
                "message" => "orientador no encontrado"
            ];
        }

        $orientador->setRepository($orientadorRepository);
        $orientador->setNombre($orientadorDto->nombre);
        $orientador->setTipoDocumento($orientadorDto->tipoDocumento);
        $orientador->setDocumento($orientadorDto->documento);
        $orientador->setEmailInstitucional($orientadorDto->emailInstitucional);
        $orientador->setEmailPersonal($orientadorDto->emailPersonal);
        $orientador->setDireccion($orientadorDto->direccion);
        $orientador->setEps($orientadorDto->eps);
        $orientador->setEstado($orientadorDto->estado);
        $orientador->setObservacion($orientadorDto->observacion);

        $exito = $orientador->actualizar();
        if (!$exito) {
            return [
                "code" => "500",
                "message" => "ha ocurrido un error en el sistema",
            ];
        }

        return [
            "code" => "200",
            "message" => "registro actualizado con éxito"
        ];        
    }
}