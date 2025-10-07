<?php

namespace Src\view\dto;

class CorregirAsistenciasInput
{
    public function __construct(
        public int $participanteId,
        public int $grupoId,
        /** @var array<int,array{sesion_id:int, asistio:int}> */
        public array $cambios,          
        public ?string $observacion = null,
        public ?int $actorId = null,
        public ?string $actorNombre = null,
        public ?string $actorIp = null,
        public ?string $actorUserAgent = null,
    ) {}
}
