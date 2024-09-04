<?php

namespace Src\usecase\formularios;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\dao\mysql\AplazamientoDao;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\view\dto\AplazarInscripcionDto;

class AplazarInscripcionUseCase {

    public function ejecutar(AplazarInscripcionDto $data):bool 
    {
        $formularioDao = new FormularioInscripcionDao();
        $exito = $formularioDao->aplazarInscripcion($data->numeroFormulario, $data->justifiacion);
        if (!$exito) {
            return false;
        }

        $idUsuarioSesion = Auth::id();
        DB::statement("SET @usuario_sesion = $idUsuarioSesion");

        AplazamientoDao::create([
            'participante_id' => $data->participanteId,
            'saldo_a_favor' => $data->saldoAFavor,
            'redimido' => false,
            'fecha_caducidad' => $data->fechaCaducidad,
            'comentarios' => $data->justifiacion,
            'calendario_id' => $data->calendarioId,
        ]);

        return true;
    }
}