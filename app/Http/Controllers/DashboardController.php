<?php

namespace App\Http\Controllers;

use Src\usecase\dia_festivo\GuardarDiasFestivosDeUnAnioUseCase;

class DashboardController extends Controller
{
    public function index() {
        
        (new GuardarDiasFestivosDeUnAnioUseCase)->ejecutar();
        
        return view('dashboard.index');
    }
}
