<?php


namespace Src\domain\repositories;

interface FormularioRepository {
    public function listarFormulariosPorPeriodo(int $calendarioId, $estado): array;
}