<?php

namespace Src\view\dto;


class CalendarioDto {
    public int $id;
    public string $nombre;
    public $fechaInicial;
    public $fechaFinal;
    public $fechaInicioClase;
    public $fechaCertificado;
    public $estaFormularioInscripcionAbierto;

    public function __construct(string $nombre="", $fechaInicial="", $fechaFinal="") {
        $this->nombre = $nombre;
        $this->fechaInicial = $fechaInicial;
        $this->fechaFinal = $fechaFinal;
    }
}