<?php

namespace Src\infraestructure\util;

class Mensajes {

    public static function getMessage() {
        $message = array();
        $message["201"] = "Registro creado con éxito";
        $message["404"] = "Registro no encontrado";
        $message["500"] = "Ha ocurrido un error en el sistema";
    }

    public static function CuerpoCorreoConfirmacionInscripcion($numeroFormulario) {
        return "Una nueva inscripción ha sido realizada identificada con número: " . $numeroFormulario;
    }
}