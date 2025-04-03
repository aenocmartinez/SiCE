<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Src\usecase\dashboard\BuscarFormulariosPorEstadoYCalendarioUseCase;
use Src\usecase\dashboard\DashboardUseCase;
use Src\usecase\dia_festivo\GuardarDiasFestivosDeUnAnioUseCase;
use Src\usecase\orientadores\DashboardOrientadorUseCase;

class DashboardController extends Controller
{
    public function index() {

        if(Auth::user()->esOrientador()) 
        {   
            $datosDashboard = (new DashboardOrientadorUseCase)->ejecutar();                                 
            return view('dashboard.homeOrientador', [
                "datos" => $datosDashboard,
            ]); 
        }
        
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

    private function getDatosDashboardOrientador()
    {
        return view('dashboard.homeOrientador');        
    }
}
