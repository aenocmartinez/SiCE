<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Src\domain\Calendario;
use Src\usecase\calendarios\BuscarCalendarioPorIdUseCase;

class NotificacionController extends Controller
{    

    public function enviarNotificacion() {

        $tipo = request()->get('tipo');

        $periodo = Calendario::Vigente();
        if (!$periodo->existe()) {
            return redirect()->route('dashboard')->with('code', "404")->with('status', "No existe periodo académico vigente.");
        }

        // Se obtiene con: which php
        $phpBin = '/Applications/MAMP/bin/php/php8.2.0/bin//php';

        if ($tipo == "inicioClase") {
            $comando = $phpBin . ' ' . base_path('scripts/processRecordatorio.php') . ' --periodo=' . $periodo->getId() . ' > ' . storage_path('logs/processRecordatorio.log') . ' 2>&1 &';
            exec($comando);
        } 
        
        if ($tipo == "noLegalizados") {
            $comando = $phpBin . ' ' . base_path('scripts/processRecordatorioLegalizarInscripcion.php') . ' --periodo=' . $periodo->getId() . ' > ' . storage_path('logs/processRecordatorio.log') . ' 2>&1 &';
            exec($comando);
        }

        return redirect()->route('calendario.index')->with('code', "200")->with('status', 'El envío de correos ha comenzado y continuará en segundo plano.');
    }

    public function notificacionesPeriodo($periodoId) {
        $periodo = (new BuscarCalendarioPorIdUseCase)->ejecutar($periodoId);
        if (!$periodo->existe()) {
            return redirect()->route('calendario.index')->with('code', "404")->with('status', "Periodo académico no encontrado.");
        }

        return view('calendario.notificaciones', [
            'periodo' => $periodo
        ]);
    }
}
