<?php

namespace Src\view\dto;

class CorregirAsistenciasOutput
{
    public array $resumen;
    public array $estado_final;

    public function __construct(array $resumen, array $estado_final)
    {
        $this->resumen = $resumen;
        $this->estado_final = $estado_final;
    }
}
