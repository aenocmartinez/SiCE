<?php

namespace Src\usecase\orientadores;

use Illuminate\Support\Facades\Auth;
use Src\dao\mysql\CalendarioDao;
use Src\dao\mysql\OrientadorDao;
use Src\view\dto\Response;

class ObtenerDatosFormularioReporteAsistenciaUseCase
{
public function ejecutar(): Response
{
    $orientadorDao = new OrientadorDao();
    $calendarioDao = new CalendarioDao();

    $orientador = $orientadorDao->buscarOrientadorPorId(Auth::user()->orientador_id);
    if (!$orientador->existe()) {
        return new Response("404", "Orientador no encontrado.");
    }

    $periodosAll = $calendarioDao->listarCalendarios();          
    $periodosLivianos = $calendarioDao->listarCalendariosLivianos(); 

    $datos = [];
    foreach ($periodosAll as $p) {
        $datos[$p->getNombre()] = []; 
    }

    return new Response(200, "OK", [
        'datos'    => $datos,
        'periodos' => $periodosLivianos,
    ]);
}

}
