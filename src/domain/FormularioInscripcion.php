<?php

namespace Src\domain;

class FormularioInscripcion {
    private int $id;
    private Participante $participante;
    private Convenio $convenio;
    private Grupo $grupo;
    private string $fecha;
    private string $codigo;
    private string $codigoBanco;

    public function __construct() {
        $this->id = 0;
        $this->codigoBanco = "";
    }

    public function setId(int $id=0): void {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setParticipante(Participante $participante): void {
        $this->participante = $participante;
    }

    public function getParticipanteId(): int {
        return $this->participante->getId();
    }

    public function getParticipantePrimerNombre(): string {
        return $this->participante->getPrimerNombre();
    }

    public function getParticipanteSegundoNombre(): string {
        return $this->participante->getSegundoNombre();
    }

    public function getParticipantePrimerApellido(): string {
        return $this->participante->getPrimerApellido();
    }

    public function getParticipanteSegundoApellido(): string {
        return $this->participante->getSegundoApellido();
    }

    public function getParticipanteNombreCompleto(): string {
        $nombres = $this->getParticipantePrimerNombre() . " " . $this->getParticipanteSegundoNombre();
        $apellidos = $this->getParticipantePrimerApellido() . " " . $this->getParticipanteSegundoApellido();        
        return strtoupper($nombres . " " . $apellidos);
    }

    public function getParticipanteFechaNacimiento(): string {
        return $this->participante->getFechaNacimiento();
    }

    public function getParticipanteTipoDocumento(): string {
        return $this->participante->getTipoDocumento();
    }

    public function getParticipanteDocumento(): string {
        return $this->participante->getDocumento();
    }

    public function getParticipanteTipoYDocumento(): string {
        return $this->getParticipanteTipoDocumento() . " " . $this->getParticipanteDocumento();
    }

    public function getParticipanteSexo(): string {
        return $this->participante->getSexo();
    }

    public function getParticipanteNombreSexo(): string {
        $nombreSexo = "Masculino";
        if ($this->participante->getSexo() == "F") {
            $nombreSexo = "Femenino";
        }
        return $nombreSexo;
    }

    public function getParticipanteFechaExpedicionDocumento(): string {
        return $this->participante->getFechaExpedicion();
    }

    public function getParticipanteEstadoCivil(): string {
        return $this->participante->getEstadoCivil();
    }

    public function getParticipanteDireccion(): string {
        return $this->participante->getDireccion();
    }

    public function getParticipanteTelefono(): string {
        return $this->participante->getTelefono();
    }

    public function getParticipanteEmail(): string {
        return $this->participante->getEmail();
    }

    public function getParticipanteEps(): string {
        return $this->participante->getEps();
    }

    public function setConvenio(Convenio $convenio): void {
        $this->convenio = $convenio;
    }

    public function getConvenioId(): int {
        return $this->convenio->getId();
    }

    public function getConvenioNombre(): string {
        return $this->convenio->getNombre();
    }

    public function getConvenioDescuento(): int {
        return $this->convenio->getDescuento();
    }

    public function esConvenioVigente(): bool {
        return $this->convenio->esVigente();
    }

    public function setGrupo(Grupo $grupo): void {
        $this->grupo = $grupo;
    }

    public function getGrupoId(): int {
        return $this->grupo->getId();
    }

    public function getGrupoNombreId(): string {
        return "G: " . $this->grupo->getId();
    }

    public function getGrupoNombreCurso(): string {
        return $this->grupo->getNombreCurso();
    }

    public function getGrupoCursoId(): int {
        return $this->grupo->getCursoCalendarioId();
    }

    public function getGrupoCalendarioNombre(): string {
        return $this->grupo->getNombreCalendario();
    }

    public function getGrupoCalendarioId(): int {
        return $this->grupo->getCalendarioId();
    }

    public function getGrupoNombreOrientador(): string {
        return $this->grupo->getNombreOrientador();
    }

    public function getGrupoOrientadorId(): int {
        return $this->grupo->getOrientadorId();
    }

    public function getGrupoJornada(): string {
        return $this->grupo->getJornada();
    }

    public function getGrupoDia(): string {
        return $this->grupo->getDia();
    }

    public function getGrupoSalon(): string {
        return $this->grupo->getNombreSalon();
    }

    public function setFecha(string $fecha): void {
        $this->fecha = $fecha;
    }

    public function getFecha(): string {
        return $this->fecha;
    }

    public function setCodigo(string $codigo): void {
        $this->codigo = $codigo;
    }

    public function getCodigo(): string {
        return $this->codigo;
    }

    public function getEstado(): string {
        return ($this->codigoBanco != "") ? "Legalizado" : "No legalizado";
    }

    public function setCodigoBanco(string $codigoBanco): void {
        $this->codigoBanco = $codigoBanco;
    }

    public function getCodigoBanco(): string {
        return $this->codigoBanco;
    }

    public function tieneConvenio(): bool {
        return $this->convenio->getId() > 0;
    }

}