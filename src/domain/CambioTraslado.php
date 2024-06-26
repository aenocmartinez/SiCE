<?php

namespace Src\domain;

use Src\dao\mysql\CambiosTrasladosDao;
use Src\infraestructure\util\Paginate;

class CambioTraslado {

    private int $id;
    private string $periodo;
    private string $accion;
    private $valorInicialAPagar;
    private $nuevoValorAPagar;
    private string $decisionDePago;
    private $createdAt;
    private $updatedAt;
    private FormularioInscripcion $formulario;
    private Participante $participanteInicial;
    private Participante $nuevoParticipante;
    private Grupo $grupoInicial;
    private Grupo $nuevoGrupo;
    private string $nombreCursoInicial;
    private string $nombreNuevoCurso;
    private string $justificacion;
    private $valorDecisionSobrePago;
    private $repositorio;

    public function __construct() {
        $this->repositorio = new CambiosTrasladosDao();
        $this->id = 0;
        $this->accion = 'cambio';
        $this->valorInicialAPagar = 0;
        $this->nuevoValorAPagar = 0;

        $this->formulario = new FormularioInscripcion();
        $this->participanteInicial = new Participante();
        $this->nuevoParticipante = new Participante();
        $this->grupoInicial = new Grupo();
        $this->nuevoGrupo = new Grupo();
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setPeriodo(string $periodo): void {
        $this->periodo = $periodo;
    }

    public function setAccion(string $accion): void {
        $this->accion = $accion;
    }

    public function setValorInicialAPagar($valorInicialAPagar): void {
        $this->valorInicialAPagar = $valorInicialAPagar;
    }

    public function setNuevoValorAPagar($nuevoValorAPagar): void {
        $this->nuevoValorAPagar = $nuevoValorAPagar;
    }

    public function setDecisionDePago(string $decisionDePago): void {
        $this->decisionDePago = $decisionDePago;
    }

    public function setJustificacion(string $justificacion): void {
        $this->justificacion = $justificacion;
    }

    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(string $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

    public function setParticipanteInicial(Participante $participante): void {
        $this->participanteInicial = $participante;
    }

    public function setNuevoParticipante(Participante $participante): void {
        $this->nuevoParticipante = $participante;
    }

    public function setGrupoInicial(Grupo $grupo): void {
        $this->grupoInicial = $grupo;
    }

    public function setNuevoGrupo(Grupo $grupo): void {
        $this->nuevoGrupo = $grupo;
    }

    public function setFormulario(FormularioInscripcion $formulario): void {
        $this->formulario = $formulario;
    }

    public function setNombreCursoInicial(string $nombreCursoInicial): void {
        $this->nombreCursoInicial = $nombreCursoInicial;
    }

    public function setNombreNuevoCurso(string $nombreNuevoCurso): void {
        $this->nombreNuevoCurso = $nombreNuevoCurso;
    }

    public function setValorDecisionSobrePago($valorDecisionSobrePago=0): void {
        $this->valorDecisionSobrePago = $valorDecisionSobrePago;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getPeriodo(): string {
        return $this->periodo;
    }

    public function getAccion(): string {
        return $this->accion;
    }

    public function getValorInicialAPagar() {
        return $this->valorInicialAPagar;
    }    

    public function getNuevoValorAPagar() {
        return $this->nuevoValorAPagar;
    }

    public function getDecisionDePago(): string {
        return 'devolucion';
        // return $this->decisionDePago;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }    

    public function getUpdatedAt(): string {
        return $this->updatedAt;
    }    

    public function getParticipanteInicial(): Participante {
        return $this->participanteInicial;
    }

    public function getNuevoParticipante(): Participante {
        return $this->nuevoParticipante;
    }

    public function getGrupoInicial(): Grupo {
        return $this->grupoInicial;
    }

    public function getNuevoGrupo(): Grupo {
        return $this->nuevoGrupo;
    } 
    
    public function getFormulario(): FormularioInscripcion {
        return $this->formulario;
    }

    public function getNombreCursoInicial(): string {
        return $this->nombreCursoInicial;
    }

    public function getNombreNuevoCurso(): string {
        return $this->nombreNuevoCurso;
    }    

    public function getJustificacion(): string {
        return $this->justificacion;
    }    

    public function getValorDecisionSobrePago() {
        return $this->valorDecisionSobrePago;
    }    

    public static function listar($page=1): Paginate {
        return CambiosTrasladosDao::listarCambios($page);
    }

    public static function buscadorCambiosYTraslados(string $criterio, $page=1): Paginate {
        return CambiosTrasladosDao::buscadorCambiosYTraslados($criterio, $page);
    }

    public function Crear(): bool {
        return $this->repositorio->crearCambioTraslado($this);
    }
}