<?php

namespace Src\domain;

class Orientador {

    private int $id;
    private string $nombre;
    private string $tipoDocumento;
    private string $documento;
    private string $emailInstitucional;
    private string $emailPersonal;
    private bool $estado;
    private string $observacion;
    private string $direccion;
    private string $eps;
    private $areas;
    private $repository;

    public function __construct() {
        $this->id = 0;
        $this->estado = true;
        $this->areas = [];
    }

    public function setRepository($repository): void {
        $this->repository = $repository;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setTipoDocumento(string $tipoDocumento): void {
        $this->tipoDocumento = $tipoDocumento;
    }

    public function getTipoDocumento(): string {
        return $this->tipoDocumento;
    }

    public function setDocumento(string $documento): void {
        $this->documento = $documento;
    }

    public function getDocumento(): string {
        return $this->documento;
    }

    public function getTipoNumeroDocumento(): string {
        return $this->tipoDocumento . " - " . $this->documento;
    }

    public function setEmailInstitucional(string $emailInstitucional): void {
        $this->emailInstitucional = $emailInstitucional;
    }

    public function getEmailInstitucional(): string {
        return $this->emailInstitucional;
    }

    public function setEmailPersonal(string $emailPersonal): void {
        $this->emailPersonal = $emailPersonal;
    }

    public function getEmailPersonal(): string {
        return $this->emailPersonal;
    }

    public function setEstado(bool $estado): void {
        $this->estado = $estado;
    }

    public function getEstado(): bool {
        return $this->estado;
    }

    public function getEstadoComoTexto(): string {
        return $this->estado ? "Activo" : "Inactivo";
    }

    public function setObservacion(string $observacion): void {
        $this->observacion = $observacion;
    }

    public function getObservacion(): string {
        return $this->observacion;
    }

    public function setDireccion(string $direccion): void {
        $this->direccion = $direccion;
    }

    public function getDireccion(): string {
        return $this->direccion;
    }

    public function setEps(string $eps): void {
        $this->eps = $eps;
    }

    public function getEps(): string {
        return $this->eps;
    }

    public function agregarArea(Area $area): void {
        array_push($this->areas, $area);
    }

    public function quitarArea(Area $area): void {

    }

    public function misAreas(): array {
        return $this->areas;
    }

    public static function listar($repository): array {
        return $repository->listarOrientadores();    
    }

    public static function buscarPorId(int $id, $repository): Orientador {
        return $repository->buscarOrientadorPorId($id);
    }

    public static function buscador($criterio, $repository): array {
        return $repository->buscadorOrientador($criterio);
    }

    public static function buscarPorDocumento(string $tipo, string $documento, $repository): Orientador {
        return $repository->buscarOrientadorPorDocumento($tipo, $documento);
    }

    public function crear(): bool {
        return $this->repository->crearOrientador($this);
    }

    public function eliminar(): bool {
        return $this->repository->eliminarOrientador($this);
    }

    public function actualizar(): bool {
        return $this->repository->actualizarOrientador($this);
    }

    public function existe(): bool {
        return $this->id > 0;
    }
}