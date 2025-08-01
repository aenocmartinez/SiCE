<?php

namespace Src\domain\repositories;

use Src\view\dto\ResumenParticipantesNuevosAntiguosDTO;

interface EstadisticasRepository {
    public function actualizarResumenParticipantesNuevosYAntiguos(int $calendarioID=0): bool;
    public function buscarResumenParticipantesPorCalendario(int $calendarioID): ResumenParticipantesNuevosAntiguosDTO;
}