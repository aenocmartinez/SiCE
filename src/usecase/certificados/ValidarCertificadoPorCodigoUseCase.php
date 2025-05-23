<?php

namespace Src\usecase\certificados;

use Src\domain\repositories\CertificadoGeneradoRepository;

class ValidarCertificadoPorCodigoUseCase
{
    private CertificadoGeneradoRepository $repository;

    public function __construct(CertificadoGeneradoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function ejecutar(string $uuid): ?array
    {
        $registro = $this->repository->buscarPorUuid($uuid);

        if ($registro) {
            $this->repository->marcarComoValidado($uuid);
        }

        return $registro;
    }
}
