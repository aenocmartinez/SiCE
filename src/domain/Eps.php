<?php

namespace Src\domain;

use Src\domain\repositories\EpsRepository;

class Eps
{
    private string $nombre;
    private EpsRepository $epsRepo;

    public function __construct(EpsRepository $epsRepo)
    {
        $this->epsRepo = $epsRepo;
        $this->nombre = "";
    }

    public function setNombre(string $nombre="") {
        $this->nombre = $nombre;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function existe(): bool {
        return strlen($this->nombre ) > 0;
    }

    public function crear(): bool {
        return $this->epsRepo->crear($this->nombre);
    }
}