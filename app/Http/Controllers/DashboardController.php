<?php

namespace App\Http\Controllers;

use Src\usecase\dashboard\BuscarFormulariosPorEstadoYCalendarioUseCase;
use Src\usecase\dashboard\DashboardUseCase;
use Src\usecase\dia_festivo\GuardarDiasFestivosDeUnAnioUseCase;

class DashboardController extends Controller
{
    public function index() {
        
        (new GuardarDiasFestivosDeUnAnioUseCase)->ejecutar();        
        return view('dashboard.index', [
            'datosDashboard' => (new DashboardUseCase)->ejecutar()]
        );
    }

    public function buscarFormulariosPorEstado($estado) {

        $periodo = 0;
        if (!is_null(request('periodo'))) {
            $periodo = request('periodo');
        }

        return view('dashboard.formularios',[
            'formularios' => (new BuscarFormulariosPorEstadoYCalendarioUseCase)->ejecutar($estado, $periodo)
        ]);
    }
}
