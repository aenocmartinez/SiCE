<?php

namespace Src\view\dto;

class CorregirAsistenciasOutput
{
    public function __construct(
        public array $resumen,      
        public array $estado_final  
    ) {}
}
