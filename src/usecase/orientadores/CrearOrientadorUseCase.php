<?php

namespace Src\usecase\orientadores;

use App\Http\Requests\AgregarAreaOrientador;
use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;
use Src\view\dto\OrientadorDto;
use Src\view\dto\Response;

class CrearOrientadorUseCase {

    public function ejecutar(OrientadorDto $orientadorDto): Response {

        $orientadorRepository = new OrientadorDao();

        $orientador = Orientador::buscarPorDocumento($orientadorDto->tipoDocumento, $orientadorDto->documento, $orientadorRepository);

        if ($orientador->existe()) {
            return new Response('200', 'El orientador ya existe');
        }

        $orientador->setRepository($orientadorRepository);
        $orientador->setNombre($orientadorDto->nombre);
        $orientador->setTipoDocumento($orientadorDto->tipoDocumento);
        $orientador->setDocumento($orientadorDto->documento);
        $orientador->setEmailInstitucional($orientadorDto->emailInstitucional);
        $orientador->setEmailPersonal($orientadorDto->emailPersonal);
        $orientador->setDireccion($orientadorDto->direccion);
        $orientador->setEps($orientadorDto->eps);
        $orientador->setObservacion($orientadorDto->observacion);
        $orientador->setRangoSalarial($orientadorDto->rangoSalarial);
        $orientador->setNivelEducativo($orientadorDto->nivelEducativo);
        $orientador->setFechaNacimiento($orientadorDto->fechaNacimiento);

        $exito = $orientador->crear();
        if (!$exito) {
            return new Response('500', 'Ha ocurrido un error en el sistema');
        }

        (new AgregarAreaAOrientadorUseCase)->ejecutar($orientador->getId(), $orientadorDto->areas);

        (new AsignarUsuarioAOrientadorUseCase)->ejecutar($orientador);

        return new Response('200', 'Registro creado con éxito');
    }
}