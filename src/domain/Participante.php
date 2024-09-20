<?php

namespace Src\domain;

use Src\dao\mysql\ParticipanteDao;
use Src\domain\repositories\ParticipanteRepository;

class Participante {
    private int $id;
    private string $primerNombre;
    private string $segundoNombre;
    private string $primerApellido;
    private string $segundoApellido;
    private string $fechaNacimiento;
    private string $tipoDocumento;
    private string $documento;
    private string $fechaExpedicion;
    private string $sexo;
    private string $estadoCivil;
    private string $direccion;
    private string $telefono;
    private string $email;
    private string $eps;
    private string $contactoEmergencia;
    private string $telefonoEmergencia;
    private $beneficio;
    private $aplazamientos;
    private bool $vinculadoUnicolMayor;
    private ParticipanteRepository $repository;

    public function __construct() {
        $this->id = 0;
        $this->tipoDocumento = "";
        $this->documento = "";
        $this->primerNombre = "";
        $this->segundoNombre = "";
        $this->primerApellido = "";
        $this->segundoApellido = "";
        $this->fechaNacimiento = "";
        $this->fechaExpedicion = "";
        $this->estadoCivil = "";
        $this->sexo = "";
        $this->direccion = "";
        $this->telefono = "";
        $this->email = "";
        $this->eps = "";
        $this->contactoEmergencia = "";
        $this->telefonoEmergencia = ""; 
        $this->aplazamientos = array();
        $this->beneficio = new Convenio();
        $this->vinculadoUnicolMayor = false;
        $this->repository = new ParticipanteDao();       
    }

    public function setRepository($repository): void {
        $this->repository = $repository;
    }    

    public function setVinculadoUnicolMayor(bool $vinculadoUnicolMayor): void {

        if ($vinculadoUnicolMayor) {                                
            $this->beneficio->setId(Convenio::UCMCActual()->getId());
            $this->beneficio->setNombre(Convenio::UCMCActual()->getNombre());
        }

        $this->vinculadoUnicolMayor = $vinculadoUnicolMayor;
    }

    public function vinculadoUnicolMayor(): bool {
        return $this->vinculadoUnicolMayor;
    }    

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setPrimerNombre(string $primerNombre): void {
        $this->primerNombre = $primerNombre;
    }

    public function getPrimerNombre(): string {
        return $this->primerNombre;
    }

    public function setSegundoNombre(string $segundoNombre): void {
        $this->segundoNombre = $segundoNombre;
    }

    public function getSegundoNombre(): string {
        return $this->segundoNombre;
    }

    public function setPrimerApellido(string $primerApellido): void {
        $this->primerApellido = $primerApellido;
    }

    public function getPrimerApellido(): string {
        return $this->primerApellido;
    }

    public function setSegundoApellido(string $segundoApellido): void {
        $this->segundoApellido = $segundoApellido;
    }

    public function getSegundoApellido(): string {
        return $this->segundoApellido;
    }

    public function setFechaNacimiento(string $fechaNacimiento): void {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function getFechaNacimiento(): string {
        return $this->fechaNacimiento;
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

    public function setFechaExpedicion(string $fechaExpedicion): void {
        $this->fechaExpedicion = $fechaExpedicion;
    }

    public function getFechaExpedicion(): string {
        return $this->fechaExpedicion;
    }

    public function setSexo(string $sexo): void {
        if ($sexo != "M" && $sexo != "F" && $sexo != "Otro") {
            $sexo = "M";
        }        
        $this->sexo = $sexo;
    }

    public function getSexo(): string {
        return $this->sexo;
    }

    public function setEstadoCivil(string $estadoCivil): void {
        $this->estadoCivil = $estadoCivil;
    }

    public function getEstadoCivil(): string {
        return $this->estadoCivil;
    }

    public function setDireccion(string $direccion): void {
        $this->direccion = $direccion;
    }

    public function getDireccion(): string {
        return $this->direccion;
    }

    public function setTelefono(string $telefono): void {
        $this->telefono = $telefono;
    }

    public function getTelefono(): string {
        return $this->telefono;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getEmail(): string {
        return $this->email;
    }    

    public function setEps(string $eps): void {
        $this->eps = $eps;
    }

    public function getEps(): string {
        return $this->eps;
    } 
    
    public function setContactoEmergencia(string $contactoEmergencia): void {
        $this->contactoEmergencia = $contactoEmergencia;
    }

    public function getContactoEmergencia(): string {
        return $this->contactoEmergencia;
    }

    public function setTelefonoEmergencia(string $telefonoEmergencia): void {
        $this->telefonoEmergencia = $telefonoEmergencia;
    }

    public function getTelefonoEmergencia(): string {
        return $this->telefonoEmergencia;
    }    

    public function existe(): bool {
        return $this->id > 0;
    }

    public function crear(): bool {
        return $this->repository->crearParticipante($this);
    }

    public function actualizar(): bool {
        return $this->repository->actualizarParticipante($this);
    }

    public function getNombreCompleto(): string {
        $nombreCompleto = "";

        $nombreCompleto .= $this->getPrimerNombre() . " ";
        if ($this->getSegundoNombre() != "") {
            $nombreCompleto .= $this->getSegundoNombre() . " ";   
        }

        $nombreCompleto .= $this->getPrimerApellido() . " ";
        if ($this->getSegundoApellido() != "") {
            $nombreCompleto .= $this->getSegundoApellido();   
        }

        return mb_strtoupper($nombreCompleto, 'UTF-8');
    }

    public function getDocumentoCompleto(): string {
        return $this->getTipoDocumento(). " - " . $this->getDocumento();
    }

    public static function buscarParticipantePorCedula($cedula): Participante {
        $participanteDao = new ParticipanteDao();
        return $participanteDao->buscarParticipantePorDocumento("CC", $cedula);
    }

    public function buscarBeneficioVigentePorConvenio() {
        
        if ($this->vinculadoUnicolMayor()) {        
            if ($this->totalFormulariosInscritosPeriodoActual() > 0) {
                return new Convenio();
            }
            $this->beneficio->setId(Convenio::UCMCActual()->getId());
            $this->beneficio->setNombre(Convenio::UCMCActual()->getNombre());
            return ;
        }
        
        $this->beneficio = $this->repository->buscarBeneficiosAlParticipante($this->id);
    }

    public function totalFormulariosInscritosPeriodoActual(): int {
        $calendario = Calendario::Vigente();
        return ParticipanteDao::totalDeFormulariosInscritoPorUnParticipanteEnUnPeriodo($this->id, $calendario->getId());                    
    }

    public function getIdBeneficioConvenio(): int {
        return $this->beneficio->getId();
    }

    public function beneficioEsTipoCooperativa(): bool {
        return $this->beneficio->esCooperativa();
    }

    public function formularioInscripcionPorConvenioPendienteDePago(Convenio $convenio): FormularioInscripcion {
        return $this->repository->buscarFormularioInscripcionPorParticipanteYConvenioPendienteDepago($this->id, $convenio->getId());
    }

    public function listarFormulariosPendientesDePago(): array {
        return $this->repository->listarFormulariosPendientesDePago($this->id);
    }

    public function tieneFormulariosPendientesDePago(): bool {
        return sizeof($this->listarFormulariosPendientesDePago()) > 0;
    }

    public function formulariosParaCambiosYTramites(): array {
        return $this->repository->listarFormulariosDeInscripcionParticipante($this->id);
    }

    public function setAplazamientos($aplazamientos): void {
        $this->aplazamientos = $aplazamientos;        
    }

    public function getAplazamientos() {
        return $this->aplazamientos;
    }
}