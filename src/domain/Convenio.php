<?php

namespace Src\domain;

use Carbon\Carbon;
use Src\dao\mysql\ConvenioDao;
use Src\domain\repositories\ConvenioRepository;
use Src\infraestructure\util\FormatoMoneda;

class Convenio {
    private int $id;
    private string $nombre;
    private string $fecInicio;
    private string $fecFin;
    private Calendario $calendario;
    private $descuento;
    private ConvenioRepository $repository;
    private $numeroBeneficiados;
    private $numeroInscritos;
    private $esCooperativa;
    private $esUCMC;
    private $totalPagar;
    private $comentarios;
    private $haSidoFacturado;

    public function __construct(string $nombre="")
    {
        $this->nombre = $nombre;
        $this->id = 0;
        $this->fecInicio = "";
        $this->fecFin = "";
        $this->calendario = Calendario::Vigente();
        $this->descuento = 0;
        $this->numeroBeneficiados = 0;
        $this->numeroInscritos = 0;
        $this->esCooperativa = false;
        $this->esUCMC = false;
        $this->totalPagar = 0;
        $this->comentarios = "";
        $this->haSidoFacturado = false;
        $this->repository = new ConvenioDao();
    }

    public function setRepository($repository): void {
        $this->repository = $repository;
    }

    public function setHaSidoFacturado($haSidoFacturado=false): void {
        $this->haSidoFacturado = $haSidoFacturado;
    }

    public function haSidoFacturado(): bool {
        return $this->haSidoFacturado;
    }

    public function setComentarios($comentarios=""): void {
        $this->comentarios = $comentarios;
    }

    public function getComentarios() {
        return $this->comentarios;
    }    

    public function setTotalAPagar($totalPagar=0): void {
        $this->totalPagar = $totalPagar;
    }

    public function getTotalAPagar() {
        return $this->totalPagar;
    }  
    
    public function getTotalAPagarFormatoMoneda() {
        return FormatoMoneda::PesosColombianos($this->totalPagar);
    }

    public function setEsCooperativa($esCooperativa=false): void {
        $this->esCooperativa = $esCooperativa;
    }

    public function esCooperativa(): bool {
        return $this->esCooperativa;
    }

    public function setId(int $id): void{
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

    public function setFecInicio(string $fecInicio): void {
        $this->fecInicio = $fecInicio;
    }

    public function getFecInicio(): string {
        return $this->fecInicio;
    }

    public function setFecFin(string $fecFin): void {
        $this->fecFin = $fecFin;
    }

    public function getFecFin(): string {
        return $this->fecFin;
    }

    public function setDescuento($descuento): void {
        $this->descuento = $descuento;
    }

    public function getDescuento() {
        return $this->descuento;
    }

    public function setCalendario(Calendario $calendario): void {
        $this->calendario = $calendario;
    }

    public function getCalendarioId(): int {
        return $this->calendario->getId();
    }

    public function getNombreCalendario(): string {
        return $this->calendario->getNombre();
    }

    public function existeCalendarioVigente(): bool {
        return $this->calendario->getId() > 0;
    }

    public function setNumeroBeneficiados($numeroBeneficiados): void {
        $this->numeroBeneficiados = $numeroBeneficiados;
    }

    public function getNumeroBeneficiados(): int {
        return $this->numeroBeneficiados;
    }
    
    public function setNumeroInscritos(int $numeroInscritos): void {
        $this->numeroInscritos = $numeroInscritos;
    }

    public function getNumeroInscritos(): int {
        return $this->numeroInscritos;
    }

    public function tieneBeneficiariosPotenciales(): bool {
        return $this->numeroBeneficiados > 0;
    }

    public function existe(): bool {
        return $this->id > 0;
    }

    public function crear(): bool {
        if ($this->esUCMC()) {
            ConvenioDao::cerrarElUltimoConveniosUCMC();            
        }
        return $this->repository->crearConvenio($this);
    }

    public function eliminar(): bool {
        return $this->repository->eliminarConvenio($this->id);        
    }

    public function actualizar(): bool {
        return $this->repository->actualizarConvenio($this);
    }

    public function esVigente(): bool {
        $fechaCaducidad = Carbon::parse($this->fecFin);
        $hoy = Carbon::now();        
        if ($hoy->lte($fechaCaducidad))
            return true;

/*         $diasRestantes = $hoy->diffInDays($fechaCaducidad, false);
        if (($diasRestantes + 1 == 1))
            return true; */
        
        return false;
    }

    public function getVigenciaEnTexto(): string {
        $fechaCaducidad = Carbon::parse($this->fecFin);
        $hoy = Carbon::now();        
        if ($hoy->lte($fechaCaducidad)) {
            $diasRestantes = $hoy->diffInDays($fechaCaducidad, false);            
            return "El convenio vence en ". ($diasRestantes + 1) . " días";
        } 

        $diasRestantes = $hoy->diffInDays($fechaCaducidad, false);
        if ($diasRestantes + 1 == 1)
            return "El convenio caduca hoy";

        return "El convenio ha caducado";
    }    

    public function agregarParticipante(Participante $participante): bool {
                
        return (new ConvenioDao())->agregarBeneficiarioAConvenio($this->id, $participante->getDocumento());
    }

    public function listarParticipantes(): array {
        // $calendario = Calendario::Vigente();
        // if (!$calendario->existe()) {
        //     return [];
        // }

        return $this->repository->listadoParticipantesPorConvenio($this->id, $this->getCalendarioId());
    }

    public function actualizarTotalAPagar() {
        $this->repository->actualizarValorAPagarConvenio($this);
    }

    public function esUCMC(): bool {
        return $this->esUCMC;
    }

    public function setEsUCMC(bool $esUCMC): void {
        $this->esUCMC = $esUCMC;
    }
    
    public static function UCMCActual(): Convenio {
        return ConvenioDao::obtenerConvenioUCMCActual();
    }

    public static function listadoDeConveniosPorPeriodo(Calendario $periodo): array {

        return ConvenioDao::buscarConveniosPorPeriodo($periodo);
    }
}