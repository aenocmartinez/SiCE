<?php
declare(strict_types=1);

namespace Src\view\dto;

final class PeriodoDTO
{
    public function __construct(
        public int $id,
        public string $nombre
    ) {}

    public function toArray(): array
    {
        return ['id'=>$this->id, 'nombre'=>$this->nombre];
    }
}
