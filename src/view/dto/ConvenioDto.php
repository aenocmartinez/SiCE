<?php

namespace Src\view\dto;

class ConvenioDto {

    public $id;
    public $calendarioId;
    public $nombre;
    public $fechaInicial;
    public $fechaFinal;
    public $descuento;
    public $esCooperativa;
    public $comentarios;
    public array $reglasDeDescuento = [];
}