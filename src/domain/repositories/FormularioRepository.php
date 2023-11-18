<?php


namespace Src\domain\repositories;

use Src\view\dto\ConfirmarInscripcionDto;

interface FormularioRepository {
    public function listarFormulariosPorPeriodo(int $calendarioId, $estado): array;
    public function crearInscripcion(ConfirmarInscripcionDto $dto): bool;
    public function anularInscripcion($numeroFormulario): bool;
}