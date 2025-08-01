<?php

namespace Src\usecase\eps;

use Src\domain\repositories\EpsRepository;

class ListarEpsUseCase {

    private EpsRepository $epsRepo;

    public function __construct(EpsRepository $epsRepo)
    {
        $this->epsRepo = $epsRepo;
    }

    public function ejecutar(): array {

        return $this->epsRepo->listar();
    }
}
