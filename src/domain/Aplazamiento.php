<?php

namespace Src\domain;

use Src\dao\mysql\AplazamientoDao;

class Aplazamiento {

    private $repository;
    private $id;
    private $saldo;
    private bool $redimido;
    private $fechaCaducidad;
    private $comentarios;

    public function __construct()
    {
        $this->repository = new AplazamientoDao();
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

    public function setFechaCaducidad($fechaCaducidad): void {
        $this->fechaCaducidad = $fechaCaducidad;
    }

    public function setComentarios($comentarios): void {
        $this->comentarios = $comentarios;
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

    public function getFechaCaducidad() {
        return $this->fechaCaducidad;
    }

    public function getComentarios() {
        return $this->comentarios;
    }    

}