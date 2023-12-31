<?php


namespace Src\domain\repositories;

use Src\domain\FormularioInscripcion;
use Src\view\dto\ConfirmarInscripcionDto;

interface FormularioRepository {
    public function listarFormulariosPorPeriodo(int $calendarioId, $estado): array;
    public function crearInscripcion(ConfirmarInscripcionDto &$dto): bool;
    public function anularInscripcion($numeroFormulario): bool;
    public function buscarFormularioPorNumero($numeroFormulario): FormularioInscripcion;
    public function buscarFormularioPorId(int $id): FormularioInscripcion;
    public function legalizarFormulario(int $formularioId, string $voucher): bool;
    public function pagarInscripcion($formularioId, $voucher): bool;
}