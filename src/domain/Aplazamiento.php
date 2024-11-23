<?php

namespace Src\domain;

use Src\dao\mysql\AplazamientoDao;

class Aplazamiento {

    private $repository;
    private $id;
    private $saldo;
    private bool $redimido;
    private bool $caducado;
    private $fechaCaducidad;
    private $comentarios;
    private $vouchers = [];

    public function __construct()
    {
        $this->repository = new AplazamientoDao();
    }

    public function setRepositorio($repository): void 
    {
        $this->repository = $repository;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setSaldo($saldo): void {
        $this->saldo = $saldo;
    }

    public function setRedimido(bool $redimido): void {
        $this->redimido = $redimido;
    }

    public function setCaducado(bool $caducado=false): void {
        $this->caducado = $caducado;
    }

    public function setFechaCaducidad($fechaCaducidad): void {
        $this->fechaCaducidad = $fechaCaducidad;
    }

    public function setComentarios($comentarios): void {
        $this->comentarios = $comentarios;
    }

    public function setVaouchers($vouchers=[]): void {
        $this->vouchers = $vouchers;
    }

    public function getId() {
        return $this->id;
    }

    public function getSaldo() {
        return $this->saldo;
    }

    public function fueRedimido(): bool {
        return $this->redimido;
    }

    public function haCaducado(): bool {
        return $this->caducado;
    }    

    public function getFechaCaducidad() {
        return $this->fechaCaducidad;
    }

    public function getComentarios() {
        return $this->comentarios;
    }

    public function getVouchers(): array {
        return $this->vouchers;
    }

    public static function buscarPorId($aplazamientoId=0): Aplazamiento
    {        
        return (new AplazamientoDao())->buscarPorId($aplazamientoId);
    }

    public function redimir()
    {
        $this->repository->redimir($this->id);
    }

}