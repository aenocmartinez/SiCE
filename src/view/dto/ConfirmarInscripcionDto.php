<?php

namespace Src\view\dto;

class ConfirmarInscripcionDto {

    public int $participanteId;
    public int $grupoId;
    public string $medioPago;
    public int $convenioId;
    public int $formularioId = 0;
    public $costoCurso;
    public $valorDescuento;
    public $totalAPagar;
    public $voucher;
    public $valorPagoParcial;
    public $diasFesctivos;
    public $estado;
    public $pathComprobantePago;
    public $comentarios;
    public $fec_max_legalizacion;
    public $flagComprobante;    
}