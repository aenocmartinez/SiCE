<?php
declare(strict_types=1);

namespace Src\view\dto;

final class PeriodoDTO
{
    /** @var int */
    public $id;

    /** @var string */
    public $nombre;

    public function __construct(int $id, string $nombre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function toArray(): array
    {
        return [
            'id'      => $this->id,
            'nombre'  => $this->nombre,
        ];
    }
}
