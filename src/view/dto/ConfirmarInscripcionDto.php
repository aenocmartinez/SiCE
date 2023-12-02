<?php

namespace Src\view\dto;

class ConfirmarInscripcionDto {

    public int $participanteId;
    public int $grupoId;
    public string $medioPago;
    public int $convenioId;
    public int $formularioId;
    public $costoCurso;
    public $valorDescuento;
    public $totalAPagar;
    public $voucher;
    public $diasFesctivos;
}