<?php

namespace Src\usecase\participantes;

use Src\domain\repositories\ParticipanteRepository;
use Src\view\dto\PeriodoDTO;

class ListarPeriodosDeParticipanteUseCase
{
    private ParticipanteRepository $participanteRepo;

    public function __construct(ParticipanteRepository $participanteRepo)
    {
        $this->participanteRepo = $participanteRepo;
    }

    public function ejecutar(int $participanteID): array
    {
        if ($participanteID < 0) {
            return [];
        }

        $resultado = $this->participanteRepo->listarPeriodosMatriculadosDeUnParticipante($participanteID);

        return array_values(array_filter(array_map(
            function ($r): ?PeriodoDTO {
                $id  = is_array($r) ? ($r['id'] ?? $r['calendario_id'] ?? null) : ($r->id ?? $r->calendario_id ?? null);
                $nom = is_array($r) ? ($r['nombre'] ?? $r['periodo'] ?? null)   : ($r->nombre ?? $r->periodo ?? null);

                if (!$id || !$nom) return null;
                return new PeriodoDTO((int)$id, (string)$nom);
            },
            (array)$resultado
        )));        
    }
}