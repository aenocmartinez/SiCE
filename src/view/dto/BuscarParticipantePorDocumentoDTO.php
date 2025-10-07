<?php

namespace Src\view\dto;

use JsonSerializable;
use Src\domain\Participante;

class BuscarParticipantePorDocumentoDTO implements JsonSerializable
{
    public int $id;
    public string $nombre;
    public string $tipoDoc;
    public string $documento;

    private function __construct(int $id, string $nombre, string $tipoDoc, string $documento)
    {
        $this->id        = $id;
        $this->nombre    = $nombre;
        $this->tipoDoc   = $tipoDoc;
        $this->documento = $documento;
    }

    public static function fromDomain(Participante $p): self
    {
        return new self(
            $p->getId(),
            $p->getNombreCompleto(),
            $p->getTipoDocumento(),
            $p->getDocumento(),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id'        => $this->id,
            'nombre'    => $this->nombre,
            'tipo_doc'  => $this->tipoDoc,
            'documento' => $this->documento,
        ];
    }
}
