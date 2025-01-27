<?php

namespace App\Http\Controllers;

use Src\domain\Calendario;
use Src\usecase\calendarios\BuscarCalendarioPorIdUseCase;
use Src\usecase\dashboard\BuscarFormulariosPorEstadoYCalendarioUseCase;
use Src\usecase\notificaciones\RecordatorioInicioDeClaseUseCase;

class NotificacionController extends Controller
{    
    public function recordarInicioDeClases() {
        $periodo = Calendario::Vigente();
        if (!$periodo->existe()) {
            return redirect()->route('dashboard')->with('code', "404")->with('status', "No existe periodo académico vigente.");
        }

        (new RecordatorioInicioDeClaseUseCase)->Ejecutar($periodo);
        return redirect()->route('calendario.index')->with('code', "200")->with('status', "Notificación enviada");
    }

    public function notificacionesPeriodo($periodoId) {
        $periodo = (new BuscarCalendarioPorIdUseCase)->ejecutar($periodoId);
        if (!$periodo->existe()) {
            return redirect()->route('calendario.index')->with('code', "404")->with('status', "Oeriodo académico no encontrado.");
        }

        return view('calendario.notificaciones');
    }
}
