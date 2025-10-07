<?php

namespace Src\usecase\asistencias\dto;

class CorregirAsistenciasInput
{
    public function __construct(
        public int $participanteId,
        public int $grupoId,
        /** @var int[] sesiones a marcar (crear/activar) */
        public array $marcar,
        /** @var int[] sesiones a desmarcar (eliminar/anular) */
        public array $desmarcar,
        public ?string $observacion = null,
        // auditoría:
        public ?int $actorId = null,
        public ?string $actorNombre = null,
        public ?string $actorIp = null,
        public ?string $actorUserAgent = null,
    ) {}
}
