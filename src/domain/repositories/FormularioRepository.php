<?php


namespace Src\domain\repositories;

use Src\domain\FormularioInscripcion;
use Src\domain\FormularioInscripcionPago;
use Src\infraestructure\util\Paginate;

interface FormularioRepository {
    public function listarFormulariosPorPeriodo(int $calendarioId, $estado, $page=1): Paginate;
    public function crearInscripcion(FormularioInscripcion &$formulario): bool;
    public function anularInscripcion($numeroFormulario): bool;
    public function buscarFormularioPorNumero($numeroFormulario): FormularioInscripcion;
    public function buscarFormularioPorId(int $id): FormularioInscripcion;
    public function legalizarFormulario(FormularioInscripcion $formulario): bool;
    public function realizarPagoFormularioInscripcion(int $formularioId, FormularioInscripcionPago $pago): bool;
    public function pagosRealizadosPorFormulario($formularioId): array;
    public function cambiarEstadoDePagoDeUnFormulario($formularioId, $estado="Pendiente de pago"): bool;
}