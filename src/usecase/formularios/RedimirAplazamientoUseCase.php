<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\AplazamientoDao;
use Src\domain\Aplazamiento;

class RedimirAplazamientoUseCase
{
    public function ejecutar($ids_de_aplazamientos_para_redimir = [])    
    {   
        $repositorio = new AplazamientoDao();

        foreach($ids_de_aplazamientos_para_redimir as $aplazamiento_id)
        {
            $aplazamiento = Aplazamiento::buscarPorId($aplazamiento_id);
            if (!$aplazamiento->fueRedimido() && !$aplazamiento->haCaducado())
            {                
                $aplazamiento->setRepositorio($repositorio);
                $aplazamiento->redimir();
            }
        }
    }
}