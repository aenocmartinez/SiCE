<?php

namespace Src\domain\repositories;

use Src\domain\Convenio;

interface ConvenioRepository  {
    public function listarConvenios(): array;
    public function buscarConvenioPorId(int $id): Convenio;
    public function buscarConvenioPorNombreYCalendario(string $nombre, int $calendarioId): Convenio;
    public function crearConvenio(Convenio $convenio): bool;
    public function actualizarConvenio(Convenio $convenio): bool;
    public function eliminarConvenio(int $convenioId): bool;
    public function agregarBeneficiarioAConvenio(int $convenioId, string $cedula): bool;
    public static function listadoParticipantesPorConvenio($convenioId=0, $calendarioId=0): array;
    public function actualizarValorAPagarConvenio(Convenio $convenio): bool;
}