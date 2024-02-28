<?php

namespace Src\domain;

use Src\infraestructure\util\FormatoFecha;
use Src\infraestructure\util\FormatoMoneda;


class FormularioInscripcionPago {
    private $id = 0;
    private $valor = 0;
    private $medio;
    private $fecha;
    private $voucher;

    public function __construct($medioPago="Datafono", $valorPagoParcial=0, $voucher="", $fechaCreacion="") {
        $this->medio = $medioPago;
        $this->valor = $valorPagoParcial;
        $this->voucher = $voucher;
        $this->fecha = $fechaCreacion;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setValor($valor): void {
        $this->valor = $valor;
    }

    public function getValor() {
        return $this->valor;
    }

    public function getValorFormateado() {
        return FormatoMoneda::PesosColombianos($this->valor);
    }

    public function setMedio(string $medio): void {
        $this->medio = $medio;
    }

    public function getMedio(): string {
        return $this->medio;
    }

    public function setVoucher($voucher): void {
        $this->voucher = $voucher;
    }

    public function getVoucher() {
        return $this->voucher;
    }

    public function setFecha($fecha): void {
        $this->fecha = $fecha;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getFechaFormateada() {
        return FormatoFecha::fechaTimestampFormateadaA_YMD($this->fecha);
    }
}