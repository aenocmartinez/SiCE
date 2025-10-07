<?php

namespace Src\usecase\participantes;

use Src\domain\repositories\ParticipanteRepository;

class ListarSesionesDeParticipanteEnGrupoUseCase
{
    private ParticipanteRepository $participanteRepo;

    public function __construct(ParticipanteRepository $participanteRepo)
    {
        $this->participanteRepo = $participanteRepo;
    }

    public function ejecutar(int $participanteID, int $periodoID): array
    {
        if ($participanteID == 0 || $periodoID == 0) {
            return [];
        }

        return $this->participanteRepo->listarSesionesDeParticipanteEnGrupo($participanteID, $periodoID);
    }    
}