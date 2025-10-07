<?php

namespace Src\view\dto;

class CorregirAsistenciasInput
{
    public int $participanteId;
    public int $grupoId;
    /** @var array<int,array{sesion_id:int, asistio:int}> */
    public array $cambios;
    public ?string $observacion;
    public ?int $actorId;
    public ?string $actorNombre;
    public ?string $actorIp;
    public ?string $actorUserAgent;

    public function __construct(
        int $participanteId,
        int $grupoId,
        array $cambios,
        ?string $observacion = null,
        ?int $actorId = null,
        ?string $actorNombre = null,
        ?string $actorIp = null,
        ?string $actorUserAgent = null
    ) {
        $this->participanteId = $participanteId;
        $this->grupoId = $grupoId;
        $this->cambios = $cambios;
        $this->observacion = $observacion;
        $this->actorId = $actorId;
        $this->actorNombre = $actorNombre;
        $this->actorIp = $actorIp;
        $this->actorUserAgent = $actorUserAgent;
    }
}
