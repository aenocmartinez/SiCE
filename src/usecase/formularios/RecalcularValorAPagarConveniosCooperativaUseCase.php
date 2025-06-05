<?php

namespace Src\usecase\formularios;

use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\Convenio;

class RecalcularValorAPagarConveniosCooperativaUseCase
{
    /**
     * Recalcula y actualiza los valores de descuento y total a pagar para todos los formularios
     * pagados asociados a un convenio de tipo cooperativa, excluyendo el nuevo formulario creado.
     *
     * @param Convenio $convenio Instancia del convenio cooperativo
     * @param int $nuevoFormularioID ID del formulario que se debe excluir del recálculo
     * @return void
     */
    public function ejecutar(Convenio $convenio): void
    {
        // Si el convenio no es cooperativa, no se recalcula
        if (!$convenio->esCooperativa()) {
            return;
        }

        /** @var \Src\domain\FormularioInscripcion[] $formularios */
        $formularios = FormularioInscripcionDao::listarFormulariosPagadosPorConvenio($convenio->getId());

        $cantidadMatriculados = count($formularios);
        $porcentajeDescuento = $convenio->getDescuentoAplicable($cantidadMatriculados);

        $datosActualizar = [];

        foreach ($formularios as $formulario) 
        {
            $totalAPagar = $convenio->calcularValorConDescuento($formulario->getCostoCurso(), $cantidadMatriculados);
            $valorDescuento = round(($formulario->getCostoCurso() * $porcentajeDescuento / 100), 0);

            $datosActualizar[] = [
                'id' => $formulario->getId(),
                'valor_descuento' => $valorDescuento,
                'total_a_pagar' => $totalAPagar
            ];
        }

        if (!empty($datosActualizar)) {
            FormularioInscripcionDao::actualizarValoresDescuento($datosActualizar);
        }
    }
}
 