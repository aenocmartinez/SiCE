<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\domain\Alarma;

class AlarmaController extends Controller
{
    public function numeroUltimosInscritos() {
        
        $resultado = Alarma::ultimosInscritos();
        
        $total = 0;
        $diferenciaMinutos = 0;
        if(isset($resultado['total'])) {
            date_default_timezone_set('America/Bogota');
            
            $total = $resultado['total'];
            $fechaEspecifica = Carbon::parse($resultado['fecha']);

            $diferenciaMinutos = $fechaEspecifica->diffInMinutes(Carbon::now());
        }

        return view('alarmas.numeroUltimosInscritos', [
            'diferenciaMinutos' => $diferenciaMinutos,
            'total' => $total,
        ]);
    }
}
