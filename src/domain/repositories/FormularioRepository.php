<?php


namespace Src\domain\repositories;

use Src\domain\FormularioInscripcion;
use Src\domain\FormularioInscripcionPago;
use Src\infraestructure\util\Paginate;

interface FormularioRepository {
    public function listarFormulariosPorPeriodo(int $calendarioId, $estado, $page=1): Paginate;
    public function crearFormulario(FormularioInscripcion &$formulario): bool;
    public function anularInscripcion($numeroFormulario): bool;
    public function buscarFormularioPorNumero($numeroFormulario): FormularioInscripcion;
    public static function buscarFormularioPorId(int $id): FormularioInscripcion;
    public function legalizarFormulario(FormularioInscripcion $formulario): bool;
    public function realizarPagoFormularioInscripcion(int $formularioId, FormularioInscripcionPago $pago): bool;
    public function pagosRealizadosPorFormulario($formularioId): array;
    public function cambiarEstadoDePagoDeUnFormulario($formularioId, $estado="Pendiente de pago"): bool;
    public function redimirBeneficioConvenio($cedula, $convenioId): bool;
    public function actualizarFormulario(FormularioInscripcion $formulario): bool;
    public function actualizarFormularioPorFacturacionDeConvenio(FormularioInscripcion $formulario): bool;
}