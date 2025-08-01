<?php

namespace Src\usecase\eps;

use Src\domain\repositories\EpsRepository;

class CrearEpsUseCase {

    private EpsRepository $epsRepo;

    public function __construct(EpsRepository $epsRepo)
    {
        $this->epsRepo = $epsRepo;
    }

    public function ejecutar(string $nombre): bool {

        $eps = $this->epsRepo->buscar($nombre);
        if ($eps->existe())
        {
            return false;
        }

        $eps->setNombre($nombre);

        return $eps->crear();
    }
}
