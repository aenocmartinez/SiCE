<?php

namespace Src\domain;

use DateTime;
use Src\dao\mysql\CalendarioDao;
use Src\dao\mysql\ReporteNumeroCursoYParticipantePorJornadaDao;
use Src\infraestructure\rptNumCursosYParticipanteXJornada\PlantillaCursosPorJornada;
use Src\infraestructure\util\FormatoFecha;

class Calendario {
    private int $id;
    private string $nombre;
    private $fechaInicio;
    private $fechaFinal;
    private $fechaInicioClase;
    private $repository;
    private $cursos = [];

    public function __construct(string $nombre="", $fechaInicio="", $fechaFinal="") {
        $this->id = 0;
        $this->nombre = $nombre;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->repository = new CalendarioDao();
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

    
    public function setFechaInicioClase($fechaInicioClase): void {
        $this->fechaInicioClase = $fechaInicioClase;
    }

    public function getFechaInicioClase() {
        return $this->fechaInicioClase;
    }

    public function setFechaInicio($fechaInicio): void {
        $this->fechaInicio = $fechaInicio;
    }

    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    public function getFechaInicioFormateada(): string {
        if (is_null($this->fechaInicio))
            return "";
        
        return FormatoFecha::fechaDDdeMMdeYYYY($this->fechaInicio);
    }

    public function setFechaFinal($fechaFinal): void{
        $this->fechaFinal = $fechaFinal;
    }

    public function getFechaFinal() {
        return $this->fechaFinal;
    }

    public function getFechaFinalFormateada(): string {
        if (is_null($this->fechaFinal))
            return "";
        
        return FormatoFecha::fechaDDdeMMdeYYYY($this->fechaFinal);
    }    

    public static function listar($repository): array {
        return $repository->listarCalendarios();
    }

    public static function buscarPorId(int $id=0): Calendario {
        return (new CalendarioDao())->buscarCalendarioPorId($id);
    }

    public static function buscarPorNombre(string $nombre, $repository): Calendario {
        return $repository->buscarCalendarioPorNombre($nombre);
    }

    public function crear(): bool {
        return $this->repository->crearCalendario($this);
    }

    public function eliminar(): bool {
        return $this->repository->eliminarCalendario($this);
    }

    public function actualizar(): bool {
        return $this->repository->actualizarCalendario($this);
    }

    public function existe(): bool {
        return $this->id > 0;
    }

    public function esVigente(): bool {     
        $vigente = false;
        $fechaActual = new DateTime(date("Y-m-d"));
        $fechaInicio = new DateTime($this->fechaInicio);
        $fechaFin = new DateTime($this->fechaFinal);

        if ($fechaActual >= $fechaInicio && $fechaActual <= $fechaFin) {
            $vigente = true;
        } else if ($fechaInicio >= $fechaActual) {
            $vigente = true;
        }

        return $vigente;
    }

    public function estado(): string {
        return $this->esVigente() ? "Vigente" : "Caducado";
    }

    /**    
     * @param Curso: $curso
     * @param $datos: [(int)'cupo', (float)'costo', (string)'modalidad']
     */
    public function agregarCurso(Curso $curso, $datos=[]): bool {
        return $this->repository->agregarCurso(new CursoCalendario($this, $curso, $datos));
    }

    public function retirarCurso(Curso $curso): bool {        
        return $this->repository->retirarCurso(new CursoCalendario($this, $curso));
    }

    public function listarCursos(): array {
        return $this->repository->listarCursos($this);
    }

    public function listarCursosDelPeriodo(): array {        
        return $this->repository->listarCursos($this);
    }

    public function formulariosInscritos(): array {
        return $this->repository->listarInscripcionesPorCalendario($this->id);
    }

    public static function existeCalendarioVigente(): bool {
        return CalendarioDao::existeCalendarioVigente();
    }

    public static function Vigente(): Calendario {
        return CalendarioDao::obtenerCalendarioActualVigente();
    }

    public function listarGruposParaFormularioInscripcionPublico(): array {
        return $this->repository->listarGruposParaInscripcion($this->id);
    }

    public static function pasarANoDisponibleLosBeneficiosPorConvenioDeUnParticipante() {
        CalendarioDao::pasarANoDisponibleLosBeneficiosPorConvenioDeUnParticipante();
    }

    public function listaDeGrupos(): array {
        return CalendarioDao::listaDeGrupos($this->id);
    }

    public function generarReporteNumeroCursoYParticipantePorJornada(): array
    {
        $dao = new ReporteNumeroCursoYParticipantePorJornadaDao();
        $datos = $dao->obtenerReportePorJornada($this->id);

        return PlantillaCursosPorJornada::procesarDatos($datos, $this->nombre);
    }
}