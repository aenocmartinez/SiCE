<?php

namespace Src\usecase\certificados;

use Exception;
use Src\dao\mysql\FirmaDao;
use Src\domain\Firma;
use Src\view\dto\Response;

class GuardarFirmasUseCase
{
    public function ejecutar(Firma $firma): Response
    {
        try {
            $resultado = (new FirmaDao())->GuardarFirma($firma);

            if ($resultado) {
                return new Response("200", "Firmas guardadas correctamente", $firma);
            } else {
                return new Response("500", "No se pudieron guardar las firmas", []);
            }
        } catch (Exception $e) {
            return new Response("500", "Error al guardar las firmas: " . $e->getMessage(), []);
        }
    }
}
