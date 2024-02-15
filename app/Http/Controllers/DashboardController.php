<?php

namespace App\Http\Controllers;

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
}
