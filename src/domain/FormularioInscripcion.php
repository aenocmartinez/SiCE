<?php

namespace Src\domain;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
    private $comentarios;
    private string $medioInscripcion;
    private FormularioRepository $repository;

    public function __construct() {
        $this->id = 0;
        $this->totalAPagar = 0;
        $this->valorDescuento = 0;
        $this->costoCurso = 0;
        $this->valorPago = 0;
        $this->pathComprobantePago = "";
        $this->comentarios = "Sin comentarios";
        $this->medioInscripcion = 'en oficina';
        $this->convenio = new Convenio();
        $this->repository = new FormularioInscripcionDao();
    }

    public function setComentarios(string $comentarios=""): void {
        $this->comentarios = $comentarios;
    }

    public function getComentarios(): string {
        return $this->comentarios;
    }

    public function setId(int $id=0): void {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setMedioInscripcion(string $medioInscripcion): void {
        $this->medioInscripcion = $medioInscripcion;
    }

    public function getMedioInscripcion(): string {
        return $this->medioInscripcion;
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

    public function esCalendarioVigente(): bool {
        return $this->grupo->esCalendarioVigente();
    }

    public function getGrupoCursoCosto() {
        return $this->grupo->getCostoFormateado();
    }

    public function getGrupoCursoCostoSinFormato() {
        return $this->grupo->getCosto();
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

    public function getConvenio(): Convenio {
        return $this->convenio;
    }

    public function tipoConvenioCooperativa(): bool {
        return $this->convenio->esCooperativa();
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

        if ($this->Pagado()) {
            return $this->totalAPagar;
        }

        $totalPagado = 0;
        $pagos = $this->PagosRealizados();
        foreach($pagos as $pago) {
            $totalPagado += $pago->getValor();
        }
        return $totalPagado;
    }

    public function EstadoPago(): string {

        if ($this->convenio->esCooperativa()) {
            $estado = "Pagado";
        }

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

    public function getParticipante(): Participante {
        return $this->participante;
    }

    public function getGrupo(): Grupo {
        return $this->grupo;
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
        // return FormatoMoneda::PesosColombianos( ($this->totalAPagar - $this->TotalPagoRealizado()) );
        return FormatoMoneda::PesosColombianos( ($this->totalAPagar) );
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

    public function CambiarGrupoYValoresDePago(Grupo $nuevoGrupo, $datosDePago=[]): bool {
        return $this->repository->actualizarGrupoFormulario($this->id, $nuevoGrupo->getId(), $datosDePago);
    }

    public function FacturarConvenio(): bool {
        $this->repository = new FormularioInscripcionDao();
        return $this->repository->actualizarFormularioPorFacturacionDeConvenio($this);
    }

    public function RecalcularDatosDePago(Grupo $grupo, $datosComplementarios = ['justificacion', 'accion', 'decision_sobre_pago']): array {

        $totalPagoActual = $this->totalAPagar;
        $descuento = 0;

        $datosDePago = ['comentarios' => $datosComplementarios['justificacion'], 
                    'costoCurso' => $grupo->getCosto(), 
                    'totalAPagar' => $totalPagoActual, 
                    'descuento' => $this->valorDescuento, 
                    'estado' => $this->estado,
                    'decision_sobre_pago' => $datosComplementarios['decision_sobre_pago'],
                    'pago_decision_sobre_pago' => 0,
                ];

        if ($this->tieneConvenio()) {            
            $descuento = $grupo->getCosto() * ($this->getConvenioDescuento() / 100);
        }
        
        if ($this->participante->vinculadoUnicolMayor()) {            
            if ($this->participante->totalFormulariosInscritosPeriodoActual() < 2) {
                $datosDePago['totalAPagar'] = 0;
                $datosDePago['descuento'] = $grupo->getCosto();        
                $this->CambiarGrupoYValoresDePago($grupo, $datosDePago);
                return $datosDePago;                
            }
        }        
        
        $saldo = $totalPagoActual - ($grupo->getCosto() - $descuento);
        if ($saldo == 0) {
            $this->CambiarGrupoYValoresDePago($grupo, $datosDePago);
            return $datosDePago;
        }

        // Saldo a favor
        if ($saldo > 0) {
            $datosDePago['totalAPagar'] = ($grupo->getCosto() - $descuento);
            $datosDePago['descuento'] = $descuento;
            $datosDePago['estado'] = 'Pagado';
            $datosDePago['valor_decision_sobre_pago'] = ($this->totalAPagar - $datosDePago['totalAPagar']);                    

            if ($this->RevisarComprobanteDePago()) {
                $abono = new FormularioInscripcionPago();
                $abono->setValor($this->totalAPagar);                    
                $abono->setVoucher(0);
                $abono->setMedio($datosComplementarios['accion']);
                $abono->setFecha(Carbon::now('America/Bogota')->format('Y-m-d H:i:s'));
                $this->repository->realizarPagoFormularioInscripcion($this->id, $abono);
                $datosDePago['estado'] = 'Revisar comprobante de pago';
            }

            if (!$this->PendienteDePago() && $datosDePago['decision_sobre_pago'] == 'devolución') {
                $saldoAFavor = new FormularioInscripcionPago();                      
                $saldoAFavor->setValor((($this->totalAPagar - $datosDePago['totalAPagar'])*-1));                    
                $saldoAFavor->setVoucher(0);
                $saldoAFavor->setMedio('Devolución saldo a favor por cambio de grupo');
                $saldoAFavor->setFecha(Carbon::now('America/Bogota')->format('Y-m-d H:i:s'));
                $this->repository->realizarPagoFormularioInscripcion($this->id, $saldoAFavor);            
            }

            if ($this->PendienteDePago()) {
                $datosDePago['estado'] = 'Pendiente de pago';
            }
            
            $this->CambiarGrupoYValoresDePago($grupo, $datosDePago);

            return $datosDePago;            
        }

        // Saldo en contra
        if ($saldo < 0) {
            $datosDePago['estado'] = 'Pendiente de pago';
            $datosDePago['totalAPagar'] = $grupo->getCosto() - $totalPagoActual;

            if ($this->tieneConvenio()) {
                $datosDePago['descuento'] = $descuento;
                $datosDePago['totalAPagar'] = $datosDePago['totalAPagar'] - $descuento;
            }

            $this->CambiarGrupoYValoresDePago($grupo, $datosDePago);
            
            if ($this->RevisarComprobanteDePago()) {
                $abono = new FormularioInscripcionPago();
                $abono->setValor($this->totalAPagar);                    
                $abono->setVoucher(0);
                $abono->setMedio($datosComplementarios['accion']);
                $abono->setFecha(Carbon::now('America/Bogota')->format('Y-m-d H:i:s'));
                $this->repository->realizarPagoFormularioInscripcion($this->id, $abono);
            }
            
            return $datosDePago;
        }

        return $datosDePago;
    }

    public static function totalPorEstadoYCalendario($estado, $calendarioId=0) {
        return  FormularioInscripcionDao::join('grupos as g', 'g.id', '=', 'formulario_inscripcion.grupo_id')
                ->where('formulario_inscripcion.estado', $estado)
                ->where('g.calendario_id', $calendarioId)
                ->count();
    }

    public static function contadorInscripcionesSegunMedio(string $medio, int $calendarioId=0): int {
        return FormularioInscripcionDao::join('grupos as g', 'g.id', '=', 'formulario_inscripcion.grupo_id')
                ->where('formulario_inscripcion.medio_inscripcion', $medio)
                ->where(function($query) {
                    $query->where('formulario_inscripcion.estado', 'Pagado')
                        ->orWhere('formulario_inscripcion.estado', 'Pendiente de pago');
                })
                ->where('g.calendario_id', $calendarioId)
                ->count();
    }

    public static function totalInscripcionesLegalizadas($calendarioId=0): int {

        return FormularioInscripcionDao::join('grupos as g', 'g.id', '=', 'formulario_inscripcion.grupo_id')
                ->where(function($query) {
                    $query->where('formulario_inscripcion.estado', 'Pagado')
                        ->orWhere('formulario_inscripcion.estado', 'Pendiente de pago');
                })
                ->where('g.calendario_id', $calendarioId)
                ->count();
    }   

    public static function totalInscripcionesLegalizadasPorConvenio($calendarioId=0): int {

        return FormularioInscripcionDao::join('grupos as g', 'g.id', '=', 'formulario_inscripcion.grupo_id')
                ->where(function($query) {
                    $query->where('formulario_inscripcion.estado', 'Pagado')
                        ->orWhere('formulario_inscripcion.estado', 'Pendiente de pago');
                })
                ->whereNotNull('formulario_inscripcion.convenio_id')
                ->where('g.calendario_id', $calendarioId)
                ->count();
    }
    
    public static function totalInscripcionesLegalizadasRegulares($calendarioId=0): int {
        
        return FormularioInscripcionDao::join('grupos as g', 'g.id', '=', 'formulario_inscripcion.grupo_id')
                ->where(function($query) {
                    $query->where('formulario_inscripcion.estado', 'Pagado')
                        ->orWhere('formulario_inscripcion.estado', 'Pendiente de pago');
                })
                ->whereNull('formulario_inscripcion.convenio_id')
                ->where('g.calendario_id', $calendarioId)
                ->count();
    }    
    
    public static function totalDeDineroRecaudado($calendarioId=0): array {

        $sumaTotal = FormularioInscripcionDao::join('grupos as g', 'g.id', '=', 'formulario_inscripcion.grupo_id')
        ->where(function($query) {
            $query->where('formulario_inscripcion.estado', 'Pagado')
                  ->orWhere('formulario_inscripcion.estado', 'Pendiente de pago');
        })
        ->where('g.calendario_id', $calendarioId)
        ->selectRaw('
            SUM(formulario_inscripcion.total_a_pagar) as RECAUDO_TOTAL,
            SUM(CASE WHEN formulario_inscripcion.convenio_id IS NOT NULL THEN formulario_inscripcion.total_a_pagar ELSE 0 END) as RECAUDO_POR_CONVENIO,
            SUM(CASE WHEN formulario_inscripcion.convenio_id IS NULL THEN formulario_inscripcion.total_a_pagar ELSE 0 END) as RECAUDO_SIN_CONVENIO
        ')
        ->first();

        return [
            "RECAUDO_TOTAL" => $sumaTotal->RECAUDO_TOTAL,
            "RECAUDO_POR_CONVENIO" => $sumaTotal->RECAUDO_POR_CONVENIO,
            "RECAUDO_SIN_CONVENIO" => $sumaTotal->RECAUDO_SIN_CONVENIO,
        ];
    }   
    
    public static function totalDeDineroPendienteDePago($calendarioId=0) {
        return FormularioInscripcionDao::join('grupos as g', 'g.id', '=', 'formulario_inscripcion.grupo_id')
                ->where('formulario_inscripcion.estado', 'Pendiente de Pago')
                ->where('g.calendario_id', $calendarioId)
                ->sum('formulario_inscripcion.total_a_pagar');
    
    }
    
    public static function listadoDeRecaudoPorAreas($calendarioId=0) {
        
        $results = FormularioInscripcionDao::join('grupos as g', 'g.id', '=', 'formulario_inscripcion.grupo_id')
                    ->join('curso_calendario as cc', function($join) use ($calendarioId) {
                        $join->on('cc.id', '=', 'g.curso_calendario_id')
                            ->where('g.calendario_id', '=', $calendarioId);
                    })
                    ->join('cursos as c', 'c.id', '=', 'cc.curso_id')
                    ->join('areas as a', 'a.id', '=', 'c.area_id')
                    ->where(function($query) {
                        $query->where('formulario_inscripcion.estado', 'Pagado')
                            ->orWhere('formulario_inscripcion.estado', 'Pendiente de pago');
                    })
                    ->groupBy('a.nombre')
                    ->select('a.nombre', 
                            DB::raw("REPLACE(FORMAT(SUM(formulario_inscripcion.total_a_pagar), 0), ',', '.') as TOTAL_RECAUDO"))
                    ->orderBy('a.nombre')
                    ->get();
                
            
            $totalRecaudo = $results->sum(function ($result) {
                return (float)str_replace('.', '', $result->TOTAL_RECAUDO); // Convertir a número
            });
            
            $totalRecaudoFormatted = number_format($totalRecaudo, 0, ',', '.'); 

            return [
                'areas' => $results,
                'total_recaudo' => $totalRecaudoFormatted
            ];
    
    }
}