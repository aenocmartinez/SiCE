<?php
namespace Src\usecase\tipo_salones;

use Src\dao\mysql\TipoSalonDao;
use Src\domain\TipoSalon;
use Src\view\dto\Response;
use Src\view\dto\TipoSalonDto;

class ActualizarTipoSalonUseCase {

    public function ejecutar(TipoSalonDto $tipoSalonDto): Response {
        
        $tipoSalonRepository = new TipoSalonDao();

        $tipoSalon = TipoSalon::buscarPorId($tipoSalonDto->id, $tipoSalonRepository);
        if (!$tipoSalon->existe()) 
            return new Response("404", "tipo de salón no encontrado");

        $tipoSalon->setRepository($tipoSalonRepository);
        $tipoSalon->setNombre($tipoSalonDto->nombre);

        $exito = $tipoSalon->actualizar();
        if (!$exito) 
            return new Response("500", "Ha ocurrido un error en el sistema");

        
        return new Response("200", "Registro actualizado con éxito");
    }
}