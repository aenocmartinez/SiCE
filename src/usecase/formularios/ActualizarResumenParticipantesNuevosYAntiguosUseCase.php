<?php

namespace Src\usecase\formularios;

use Src\domain\repositories\EstadisticasRepository;

class ActualizarResumenParticipantesNuevosYAntiguosUseCase {

    private EstadisticasRepository $estadisticasRepo;

    public function __construct(EstadisticasRepository $estadisticasRepo)
    {
        $this->estadisticasRepo = $estadisticasRepo;
    }

    public function ejecutar(int $calendarioID=0):  bool {

        return $this->estadisticasRepo->actualizarResumenParticipantesNuevosYAntiguos($calendarioID);
    }
}