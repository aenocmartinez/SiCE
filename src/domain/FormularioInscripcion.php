<?php

namespace Src\domain;

use Carbon\Carbon;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\repositories\FormularioRepository;
use Src\infraestructure\util\FormatoMoneda;

class FormularioInscripcion {
    private int $id;
    private Participante $participante;
    private Convenio $convenio;
    private Grupo $grupo;
    private string $numero;
    private string $estado;
    private $fechaCreacion;
    private $totalAPagar;
    private $valorDescuento;
    private $fechaMaxLegalizacion;
    private $costoCurso;
    private $valorPago;
    private $pathComprobantePago;
    private FormularioRepository $repository;

    public function __construct() {
        $this->id = 0;
        $this->totalAPagar = 0;
        $this->valorDescuento = 0;
        $this->costoCurso = 0;
        $this->valorPago = 0;
        $this->pathComprobantePago = "";
        $this->repository = new FormularioInscripcionDao();
    }

    public function setId(int $id=0): void {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setPathComprobantePago($pathComprobantePago): void {
        $this->pathComprobantePago = $pathComprobantePago;
    }

    public function getPathComprobantePago() {
        return $this->pathComprobantePago;
    }   
    
    public function tieneComprobanteDePago(): bool {
        return $this->pathComprobantePago != "";
    }

    public function pagadoPorEcollect(): bool {
        return $this->tieneComprobanteDePago();
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

    public function getParticipanteIdBeneficioConvenio() {
        $this->participante->buscarBeneficioVigentePorConvenio();
        
        if ($this->participante->getIdBeneficioConvenio() > 0) {
            return $this->participante->getIdBeneficioConvenio();
        }

        return $this->getConvenioId();
    }

    public function getParticipanteNombreCompleto(): string {
        $nombres = $this->getParticipantePrimerNombre() . " " . $this->getParticipanteSegundoNombre();
        $apellidos = $this->getParticipantePrimerApellido() . " " . $this->getParticipanteSegundoApellido();        
        return mb_strtoupper($nombres . " " . $apellidos, 'UTF-8');
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

    public function getGrupoCursoCosto() {
        return $this->grupo->getCostoFormateado();
    }

    public function getGrupoCalendarioId(): int {
        return $this->grupo->getCalendarioId();
    }

    public function getGrupoModalidad(): string {
        return $this->grupo->getModalidad();
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

    public function setNumero(string $numero): void {
        $this->numero = $numero;
    }

    public function getNumero(): string {
        return $this->numero;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function tieneConvenio(): bool {
        return $this->convenio->getId() > 0;
    }

    public function setEstado(string $estado): void {
        $this->estado = $estado;
    }

    public function setFechaCreacion($fechaCreacion=""): void {
        if ($fechaCreacion == "") {
            date_default_timezone_set('America/Bogota');
            $fechaCreacion = Carbon::now();            
        }
        $this->fechaCreacion = $fechaCreacion;
    }

    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }
    
    public function setTotalAPagar($totalAPagar): void {
        $this->totalAPagar = $totalAPagar;
    }

    public function getTotalAPagar() {
        return $this->totalAPagar;
    }    

    public function getTotalAPagarFormateado() {
        return FormatoMoneda::PesosColombianos($this->totalAPagar);
    }
    
    public function setValorDescuento($valorDescuento): void {
        $this->valorDescuento = $valorDescuento;
    }

    public function getValorDescuento() {
        return $this->valorDescuento;
    }

    public function getValorDescuentoFormateado() {
        return FormatoMoneda::PesosColombianos($this->valorDescuento);
    }

    public function setValorPago($valorPago): void {
        $this->valorPago = $valorPago;
    }

    public function getValorPago() {
        return $this->valorPago;
    }

    public function existe(): bool {
        return $this->id > 0;
    }

    public function setFechaMaxLegalizacion($fechaMaxLegalizacion): void {
        $this->fechaMaxLegalizacion = $fechaMaxLegalizacion;
    }

    public function getFechaMaxLegalizacion() {
        return $this->fechaMaxLegalizacion;
    }

    public function setCostoCurso($costoCurso): void {
        $this->costoCurso = $costoCurso;
    }

    public function getCostoCurso() {
        return $this->costoCurso;
    }

    public function Pagado(): bool {
        return $this->estado == "Pagado";
    }

    public function Anulado(): bool {
        return $this->estado == "Anulado";
    }

    public function PendienteDePago(): bool {
        return $this->estado == "Pendiente de pago";
    }
    
    public function RevisarComprobanteDePago(): bool {
        return $this->estado == "Revisar comprobante de pago";
    }

    public function PagosRealizados(): array {
        return $this->repository->pagosRealizadosPorFormulario($this->id);
    }

    public function TotalPagoRealizado() {
        $totalPagado = 0;
        $pagos = $this->PagosRealizados();
        foreach($pagos as $pago) {
            $totalPagado += $pago->getValor();
        }
        return $totalPagado;
    }

    public function EstadoPago(): string {

        if ($this->estado == "Anulado") {
            return $this->estado;
        }

        if ($this->estado == "Revisar comprobante de pago") {
            return $this->estado;
        }
        
        $estado = "Pendiente de pago";
        if ($this->totalAPagar == $this->TotalPagoRealizado()) {
            $estado = "Pagado";
        }
        return $estado;
    }

    public function RedimirBeneficioConvenio(): bool {                
        return $this->repository->redimirBeneficioConvenio($this->getParticipanteDocumento(), $this->getConvenioId());
    }

    public function AgregarPago(FormularioInscripcionPago $pago): bool {      
        $exito = $this->repository->realizarPagoFormularioInscripcion($this->id, $pago);
        if (!$exito) {
            return false;
        }

        return $this->repository->cambiarEstadoDePagoDeUnFormulario($this->id, $this->EstadoPago());
    }

    public function tienePagosParciales(): bool {
        return $this->TotalPagoRealizado() > 0;
    }

    public function totalAPagarConDescuentoDePagoParcialFormateado() {        
        return FormatoMoneda::PesosColombianos( ($this->totalAPagar - $this->TotalPagoRealizado()) );
    }

    public function totalPendientePorPagar() {
        return $this->totalAPagar - $this->TotalPagoRealizado();
    }

    public function totalPendientePorPagarFormateado() {
        return FormatoMoneda::PesosColombianos(($this->totalAPagar - $this->TotalPagoRealizado()));
    }

    public function Crear(): bool {
        return $this->repository->crearFormulario($this);
    }

    public function Actualizar(): bool {
        return $this->repository->actualizarFormulario($this);
    }

    public function FacturarConvenio(): bool {
        $this->repository = new FormularioInscripcionDao();
        return $this->repository->actualizarFormularioPorFacturacionDeConvenio($this);
    }
}