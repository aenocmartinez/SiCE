<?php

namespace Src\domain;

use Src\dao\mysql\OrientadorDao;
use Src\infraestructure\util\FormatoFecha;
use Src\infraestructure\util\Paginate;

class Orientador {

    private int $id;
    private string $nombre = "";
    private string $tipoDocumento = "";
    private string $documento = "";
    private string $emailInstitucional = "";
    private string $emailPersonal = "";
    private bool $estado = false;
    private string $observacion = "";
    private string $direccion = "";
    private string $eps = "";
    private string $fechaNacimiento = "";
    private string $nivelEducativo = "";
    private $rangoSalarial = "";
    private $areas;
    private $grupos;
    private $repository;

    public function __construct() {
        $this->id = 0;
        $this->estado = true;
        $this->areas = [];
    }

    public function setRepository($repository): void {
        $this->repository = $repository;
    }

    public function setFechaNacimiento($fechaNacimiento): void {
        if (is_null($fechaNacimiento)) {
            $fechaNacimiento = "";
        }

        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function setNivelEducativo($nivelEducativo): void {
        if (is_null($nivelEducativo)) {
            $nivelEducativo = "";
        }        
        $this->nivelEducativo = $nivelEducativo;
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
        return $this->tipoDocumento . ". " . $this->documento;
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

    public function getFechaNacimiento(): string {
        if (is_null($this->fechaNacimiento))
            return "";

        return $this->fechaNacimiento;
    }

    public function getNivelEducativo(): string {
        if (is_null($this->nivelEducativo)) 
            return "";

        return $this->nivelEducativo;
    }

    public function setDireccion(string $direccion): void {
        $this->direccion = $direccion;
    }

    public function getDireccion(): string {
        if (empty($this->direccion))
            return "No registra dirección";
        return $this->direccion;
    }

    public function setEps(string $eps): void {
        $this->eps = $eps;
    }

    public function getEps(): string {
        if (empty($this->direccion))
            return "No registra eps";        
        return $this->eps;
    }

    public function setAreas(array $areas): void {
        $this->areas = $areas;
    }

    public function setGruposPorCalendario($calendarioId=0): void {
        $this->grupos = (new OrientadorDao())->grupos($this->id, $calendarioId);
    }

    public function setRangoSalarial($rangoSalarial): void {
        $this->rangoSalarial = $rangoSalarial;
    }

    public function getRangoSalarial() {
        if (is_null($this->rangoSalarial)) 
            return "";
        
        return $this->rangoSalarial;
    }

    public function agregarArea(Area $area) {        
        $this->repository->agregarArea($this, $area);
    }

    public function quitarAreas() {
        $this->repository->quitarArea($this);
    }

    public function misAreas(): array {
        return $this->areas;
    }

    public function misGrupos(): array {
        return $this->grupos;
    }

    public static function listar($repository): array {
        return $repository->listarOrientadores();    
    }

    public static function buscarPorId(int $id, $repository): Orientador {
        return $repository->buscarOrientadorPorId($id);
    }

    public static function buscador($criterio, $page=1): Paginate {
        return OrientadorDao::buscadorOrientador($criterio, $page);
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

    public function tieneAreasAsignadas(): bool {
        return sizeof($this->areas) > 0;
    }

    public function numeroAreasPertenece(): int {
        return sizeof($this->areas);
    }

    public function nombreAreasPertenezco(): string {
        $nombreAreas = "";

        if (!$this->tieneAreasAsignadas()) {
            return "No tiene áreas asignadas";
        }

        foreach($this->misAreas() as $area) {
            $nombreAreas .= $area->getNombre() . ", ";
        }

        return substr($nombreAreas, 0, -2);
    }

    public function getFechaNacimientoFormateada(): string {
        if (is_null($this->fechaNacimiento))
            return "";
        
        return FormatoFecha::fecha01enero1970($this->fechaNacimiento);
    }

    public static function Paginar($page=1) {
        return OrientadorDao::listarOrientadoresPaginado($page);
    }
}