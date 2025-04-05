<?php
namespace Src\usecase\orientadores;

use App\Models\User;
use Src\dao\mysql\OrientadorDao;
use Src\domain\Orientador;
use Src\view\dto\OrientadorDto;
use Src\view\dto\Response;

class ActualizarOrientadorUseCase {

    public function ejecutar(OrientadorDto $orientadorDto): Response 
    {
        $usuario = User::where('email', $orientadorDto->emailInstitucional)->first();
        if ($usuario) 
        {
            if ($orientadorDto->id != $usuario->orientador_id)
            {
                return new Response('500', "Existe otro orientador(a) con el mismo correo electrónico institucional.");
            }
        }

       
        $orientadorRepository = new OrientadorDao();

        $orientador = Orientador::buscarPorId($orientadorDto->id, $orientadorRepository);

        if (!$orientador->existe()) {
            return new Response('404', 'Orientador no encontrado');
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
        $orientador->setFechaNacimiento($orientadorDto->fechaNacimiento);
        $orientador->setNivelEducativo($orientadorDto->nivelEducativo);
        $orientador->setRangoSalarial($orientadorDto->rangoSalarial);        

        $exito = $orientador->actualizar();
        if (!$exito) {
            return new Response('500', 'Ha ocurrido un error en el sistema');
        }

        (new AgregarAreaAOrientadorUseCase)->ejecutar($orientador->getId(), $orientadorDto->areas);

        if ($usuario->esOrientador()) 
        {
            (new AsignarUsuarioAOrientadorUseCase)->ejecutar($orientador);
        }


        return new Response('200', 'Registro actualizado con éxito.');
    }
}