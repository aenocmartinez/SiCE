<?php

namespace Src\domain\repositories;

interface CertificadoGeneradoRepository
{
    public function registrar(string $uuid, int $participanteID, int $grupoID): bool;

    public function buscarPorUuid(string $uuid): ?array;
    public function marcarComoValidado(string $uuid): void;
}
