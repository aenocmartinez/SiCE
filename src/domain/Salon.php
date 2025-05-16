<?php

namespace Src\domain;

use Src\dao\mysql\SalonDao;
use Src\infraestructure\util\Paginate;

class Salon {
    private int $id;
    private $capacidad;
    private $repository;
    private string $nombre;
    private $hojaVida;
    private bool $disponible;
    private TipoSalon $tipoSalon;

    public function __construct(string $nombre = "") {
        $this->id = 0;
        $this->hojaVida = "";
        $this->capacidad = "";
        $this->nombre = $nombre;
        $this->disponible = true;
        $this->tipoSalon = new TipoSalon();
    }

    public function setRepository($repository) {
        $this->repository = $repository;
    }

    public function setTipoSalon(TipoSalon $tipoSalon): void {
        $this->tipoSalon = $tipoSalon;
    }

    public function getNombreTipoSalon(): string {
        if (empty($this->tipoSalon->getNombre())) {
            return "No se ha definido el tipo de salón";
        }
        return $this->tipoSalon->getNombre();
    }    

    public function getNombreYTipoSalon(): string {
        return $this->nombre . " - " . $this->tipoSalon->getNombre();
    }

    public function getIdTipoSalon(): int {
        return $this->tipoSalon->getId();
    }        

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setCapacidad($capacidad): void {
        $this->capacidad = $capacidad;
    }

    public function setDisponible(bool $disponible): void {
        $this->disponible = $disponible;
    }

    public function setHojaVida($hojaVida): void {
        $this->hojaVida = $hojaVida;
    }

    public function getDisponibleTexto(): string {
        $texto = "disponible";
        if (!$this->disponible) {
            $texto = "no disponible";
        }

        return $texto;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        if (empty($this->nombre)) {
            return "Virtual";
        }
        return $this->nombre;
    }

    public function getCapacidad() {
        return $this->capacidad;
    }

    public function estaDisponible(): bool {
        return $this->disponible;
    }

    public function getHojaVida() {
        return $this->hojaVida;
    }    

    public static function listarSalones($repository): array {
        return $repository->listarSalones();
    }

    public static function buscadorSalones($criterio, $page=1): Paginate {
        return SalonDao::buscadorSalones($criterio, $page);
    }

    public static function buscarPorId(int $id=0, $repository): Salon {
        return $repository->buscarSalonPorId($id);
    }

    public static function buscarPorNombre(string $nombre, $repository): Salon {
        return $repository->buscarSalonPorNombre($nombre);
    }

    public function crear(): bool {
        return $this->repository->crearSalon($this);
    }

    public function eliminar(): bool {
        return $this->repository->eliminarSalon($this);
    }

    public function actualizar(): bool {
        return $this->repository->actualizarSalon($this);
    }

    public function existe(): bool {
        return $this->id > 0;
    }

    public static function paginar($page=1): Paginate {
        return SalonDao::listarSalonesPaginado($page);
    }
}