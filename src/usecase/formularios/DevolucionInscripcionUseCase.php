<?php

namespace Src\usecase\formularios;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\dao\mysql\AplazamientoDao;
use Src\dao\mysql\DevolucionDao;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\view\dto\DevolucionInscripcionDto;

class DevolucionInscripcionUseCase {

    public function ejecutar(DevolucionInscripcionDto $data):bool 
    {
        $formularioDao = new FormularioInscripcionDao();
        $exito = $formularioDao->devolucionInscripcion($data->numeroFormulario, $data->justifiacion);
        if (!$exito) {
            return false;
        }

        $idUsuarioSesion = Auth::id();
        DB::statement("SET @usuario_sesion = $idUsuarioSesion");

        DevolucionDao::create([
            'participante_id' => $data->participanteId,
            'total_devuelto' => $data->valorDevolucion,
            'porcentaje' => $data->porcentaje,
            'origen' => $data->origen,
            'comentarios' => $data->justifiacion,
            'calendario_id' => $data->calendarioId,
        ]);

        return true;
    }
}