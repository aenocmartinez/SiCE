<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class AlarmaDao extends Model{

    public static function numeroUltimosInscritos(): array {
        $respuesta = ['fecha' => '', 'total' => 0];
        // return FormularioInscripcionDao::where('created_at', '>=', DB::raw('NOW() - INTERVAL 15 MINUTE'))->count();
        $resultado = FormularioInscripcionDao::select([
            DB::raw('COUNT(id) as total_formularios'),
            DB::raw('MAX(created_at) as fecha_ultimo_formulario')
        ])
        ->where('created_at', '>=', DB::raw('NOW() - INTERVAL 15 MINUTE'))
        ->first();

        if ($resultado) {
            $respuesta = [
                'fecha' => $resultado->fecha_ultimo_formulario, 
                'total' => $resultado->total_formularios,
            ];
        }

        return $respuesta;
    }

}