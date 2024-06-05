<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormularioPublicoConfirmarInscripcion;
use App\Http\Requests\FormularioPublicoConfirmarInscripcion2;
use App\Http\Requests\FormularioPublicoGuardarParticipante;
use App\Http\Requests\FormularioPublicoInscripionConsultarExistencia;
use Illuminate\Http\Request;
use Src\domain\Calendario;
use Src\domain\Convenio;
use Src\domain\FormularioInscripcion;
use Src\domain\Grupo;
use Src\domain\Participante;
use Src\infraestructure\util\FormatoFecha;
use Src\infraestructure\util\FormatoMoneda;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\convenios\BuscarConvenioPorIdUseCase;
use Src\usecase\formularios\BuscarFormularioPorIdUseCase;
use Src\usecase\formularios\ConfirmarInscripcionUseCase;
use Src\usecase\formularios\GenerarReciboMatriculaUseCase;
use Src\usecase\grupos\BuscarGrupoPorIdUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;
use Src\usecase\participantes\BuscarParticipantePorIdUseCase;
use Src\usecase\participantes\GuardarParticipanteUseCase;
use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\ParticipanteDto;

use Illuminate\Support\Str;


class InscripcionPublicaController extends Controller
{
    public $cursos_a_matricular = array();

    public function index() {            
        return view('public.inicio', ['mostrarBoton']);
    }

    public function consultarExistencia(FormularioPublicoInscripionConsultarExistencia $req) {
        
        $datoFormulario = $req->validated();
        
        $participante = (new BuscarParticipantePorDocumentoUseCase)->ejecutar($datoFormulario['tipoDocumento'], $datoFormulario['documento']);
        
        $participante->setTipoDocumento($datoFormulario['tipoDocumento']);        
        $participante->setDocumento($datoFormulario['documento']);  

        session()->forget('SESSION_UUID');
        session()->forget('cursos_a_matricular');
        request()->session()->put('SESSION_UUID', (string) Str::uuid());
        request()->session()->put('participante', $participante);


        return redirect()->route('public.formulario-participante');
    }

    public function salidaSegura() {

        if (is_null(request()->session()->get('SESSION_UUID'))) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Su sesión ha finalizado.");
        }        

        session()->forget('SESSION_UUID');
        session()->forget('cursos_a_matricular');

        return redirect()->route('public.inicio');
    }

    public function formularioParticipante(Request $request) {

        if (is_null(request()->session()->get('SESSION_UUID'))) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Su sesión ha finalizado.");
        }

        $participante = $request->session()->get('participante');

        return view('public.actualizacion_datos', [
            'participante' => $participante,
            'listaEPS' => ListaDeValor::eps(),
            'listaSexo' => ListaDeValor::sexo(),
            'estadosCiviles' => ListaDeValor::estadoCivil()
        ]);
    }

    public function guardarDatosParticipante(FormularioPublicoGuardarParticipante $req) {

        if (is_null(request()->session()->get('SESSION_UUID'))) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Su sesión ha finalizado.");
        }        

        $participante = request()->session()->get('participante');
        $datosFormulario =  $req->validated();

        $participanteDto = $this->hydrateParticipanteDto($datosFormulario);
        $participante->setPrimerNombre($participanteDto->primerNombre);
        $participante->setSegundoNombre($participanteDto->segundoNombre);
        $participante->setPrimerApellido($participanteDto->primerApellido);
        $participante->setSegundoApellido($participanteDto->segundoApellido);

        $response = (new GuardarParticipanteUseCase)->ejecutar($participanteDto);

        if ($response->code == "201") {            
            $participante = (new BuscarParticipantePorDocumentoUseCase)->ejecutar($participanteDto->tipoDocumento, $participanteDto->documento);
        }
        
        $calendarioVigente = Calendario::Vigente();
        if (!$calendarioVigente->existe()) {
            return redirect('public.inicio')->with('message', 'No hay calendarios vigentes')->with('code', 500);
        }

        if ($participante->tieneFormulariosPendientesDePago()) {
            return view('public.pagos_pendientes', [
                'participante' => $participante
            ]);
        }
 

        $items = $calendarioVigente->listarGruposParaFormularioInscripcionPublico();
        
        return view('public.seleccion_de_cursos', [
            'items' => $items,
            'participante' => $participante,
            'formularioId' => 0,
        ]);
    }

    public function seleccionarCursoMatricula($participanteId) {

        if (is_null(request()->session()->get('SESSION_UUID'))) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Su sesión ha finalizado.");
        }

        $calendarioVigente = Calendario::Vigente();
        if (!$calendarioVigente->existe()) {
            return redirect('public.inicio')->with('message', 'No hay calendarios vigentes')->with('code', 500);
        }

        $participante = (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId);
        if (!$participante->existe()) {
            return redirect('public.inicio')->with('message', 'El participante no existe')->with('code', 500);
        }

        $items = $calendarioVigente->listarGruposParaFormularioInscripcionPublico();
       
        return view('public.seleccion_de_cursos', [
            'items' => $items,
            'participante' => $participante,
            'formularioId' => 0,
        ]);
    }

    private function calcularValorDescuentoYTotalAPagar(Participante $participante, Grupo $grupo, Convenio $convenio, FormularioInscripcion $formulario): array {        
        
        $totalPago = $grupo->getCosto();
        
        $descuento = 0;
        if ($convenio->existe() && !$convenio->esCooperativa()) {            
            $descuento = $grupo->getCosto() * ($convenio->getDescuento()/100);
        }
        $totalPago = $totalPago - $descuento; 

        $totalAbono = 0;
        if ($formulario->existe()) {                        
            foreach($formulario->PagosRealizados() as $pago) {
                $totalAbono += $pago->getValor();
            }
        }
    
        if ($participante->vinculadoUnicolMayor()) {     
            
            $totalPago = $grupo->getCosto();
            
            if ($participante->totalFormulariosInscritosPeriodoActual() == 0 && !$this->tieneCursoConBeneficioUCMCEnCursosAMatricular()) {
                $convenio = new Convenio(Convenio::UCMCActual()->getNombre());
                $convenio->setId(Convenio::UCMCActual()->getId());
                $totalPago = 0;
            }
        }

        return [
            'totalPagoFormateado' => FormatoMoneda::PesosColombianos($totalPago),
            'descuentoFormateado' => FormatoMoneda::PesosColombianos($descuento),
            'totalPago' => $totalPago,
            'descuento' => $descuento,
            'convenio' => $convenio,
            'totalAbono' => FormatoMoneda::PesosColombianos($totalAbono),
            'totalAPagarConAbono' => FormatoMoneda::PesosColombianos(($totalPago - $totalAbono)),
        ];
    }

    private function existeGrupoEnCursosAMatricular($grupoId=0) {
        $cursos_a_matricular = [];        
        if (!is_null(request()->session()->get('cursos_a_matricular'))) {
            $cursos_a_matricular = request()->session()->get('cursos_a_matricular');
        }

        foreach ($cursos_a_matricular as $curso) {
            if ($curso['grupoId'] == $grupoId) {
                return true;
            }
        }

        return false;
    }

    private function tieneCursoConBeneficioUCMCEnCursosAMatricular() {
        $cursos_a_matricular = [];        
        if (!is_null(request()->session()->get('cursos_a_matricular'))) {
            $cursos_a_matricular = request()->session()->get('cursos_a_matricular');
        }

        foreach ($cursos_a_matricular as $curso) {
            if ($curso['esUCMC']) {
                return true;
            }
        }
        
        return false;
    }    

    public function quitarCursoParaMatricular($participanteId, $grupoId, $formularioId=0) {
        
        $calendarioVigente = Calendario::Vigente();
        if (!$calendarioVigente->existe()) {
            return redirect('public.inicio')->with('message', 'No hay calendarios vigentes')->with('code', 500);
        }

        if (is_null(request()->session()->get('SESSION_UUID'))) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Su sesión ha finalizado.");
        } 
        
        $participante = (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId);
        if (!$participante->existe()) {
            return redirect('public.inicio')->with('message', 'El participante no existe')->with('code', 500);
        }        
        
        $aux_cursos_a_matricular = [];
        $cursos_a_matricular = [];
        if (!is_null(request()->session()->get('cursos_a_matricular'))) {
            $cursos_a_matricular = request()->session()->get('cursos_a_matricular');
            session()->forget('cursos_a_matricular');
        }   

        foreach ($cursos_a_matricular as $curso) {
            if ($curso['grupoId'] == $grupoId) {
                continue;
            }
            $aux_cursos_a_matricular[] = $curso;
        }

        request()->session()->put('cursos_a_matricular', $aux_cursos_a_matricular);

        $items = $calendarioVigente->listarGruposParaFormularioInscripcionPublico();
       
        return view('public.seleccion_de_cursos', [
            'items' => $items,
            'participante' => $participante,
            'formularioId' => $formularioId,
            'grupoId' => $grupoId,
        ]);        
    }

    public function agregarCursoParaMatricular($participanteId, $grupoId, $formularioId=0) {

        $calendarioVigente = Calendario::Vigente();
        if (!$calendarioVigente->existe()) {
            return redirect('public.inicio')->with('message', 'No hay calendarios vigentes')->with('code', 500);
        }

        if (is_null(request()->session()->get('SESSION_UUID'))) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Su sesión ha finalizado.");
        }        

        $participante = (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId);
        if (!$participante->existe()) {
            return redirect('public.inicio')->with('message', 'El participante no existe')->with('code', 500);
        }

        $grupo = (new BuscarGrupoPorIdUseCase)->ejecutar($grupoId);
        if (!$grupo->existe()){ 
            return redirect('public.inicio')->with('message', 'El grupo seleccionado no existe')->with('code', 500);
        }

        $formulario = (new BuscarFormularioPorIdUseCase)->ejecutar($formularioId);
        
        if ($formulario->existe()) {

            if ($formulario->Pagado() || $formulario->RevisarComprobanteDePago()) {
                return redirect('public.inicio')->with('message', 'La inscripción al curso está en estado pagado o en revisión de comprobante de pago.')->with('code', 500);
            }
        }

        $convenio = (new BuscarConvenioPorIdUseCase)->ejecutar($participante->getIdBeneficioConvenio());
        if ($formulario->tieneConvenio()) {         
            $convenio = $formulario->getConvenio();
        }

        $datosDePago = $this->calcularValorDescuentoYTotalAPagar($participante, $grupo, $convenio, $formulario);
        $datosDePago['grupoId'] = $grupoId;
        $datosDePago['nombre_curso'] = $grupo->getNombreCurso();
        $datosDePago['costo_curso'] = $grupo->getCosto();
        $datosDePago['jornada'] = $grupo->getJornada();
        $datosDePago['dia'] = $grupo->getDia();
        $datosDePago['esUCMC'] = $participante->vinculadoUnicolMayor();

        $cursos_a_matricular = [];        
        if (!is_null(request()->session()->get('cursos_a_matricular'))) {
            $cursos_a_matricular = request()->session()->get('cursos_a_matricular');
        }   
        
        if (!$this->existeGrupoEnCursosAMatricular($grupoId)) {
            $cursos_a_matricular[] = $datosDePago;
        }

        request()->session()->put('cursos_a_matricular', $cursos_a_matricular);

        $items = $calendarioVigente->listarGruposParaFormularioInscripcionPublico();
       
        return view('public.seleccion_de_cursos', [
            'items' => $items,
            'participante' => $participante,
            'formularioId' => $formularioId,
            'grupoId' => $grupoId,
        ]);

    }

    public function formularioInscripcion($participanteId, $grupoId, $formularioId=0) {

        if (is_null(request()->session()->get('SESSION_UUID'))) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Su sesión ha finalizado.");
        }        

        $participante = (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId);
        if (!$participante->existe()) {
            return redirect('public.inicio')->with('message', 'El participante no existe')->with('code', 500);
        }

        $grupo = (new BuscarGrupoPorIdUseCase)->ejecutar($grupoId);
        if (!$grupo->existe()){ 
            return redirect('public.inicio')->with('message', 'El grupo seleccionado no existe')->with('code', 500);
        }

        $formulario = (new BuscarFormularioPorIdUseCase)->ejecutar($formularioId);
        
        if ($formulario->existe()) {

            if ($formulario->Pagado() || $formulario->RevisarComprobanteDePago()) {
                return redirect('public.inicio')->with('message', 'La inscripción al curso está en estado pagado o en revisión de comprobante de pago.')->with('code', 500);
            }
        }

        $convenio = (new BuscarConvenioPorIdUseCase)->ejecutar($participante->getIdBeneficioConvenio());
        if ($formulario->tieneConvenio()) {         
            $convenio = $formulario->getConvenio();
        }

        $datosDePago = $this->calcularValorDescuentoYTotalAPagar($participante, $grupo, $convenio, $formulario);
        
        $formularioAMostrar = "public._form_confirmar_inscripcion_no_tiene_convenio";
        if ($convenio->existe()) 
        {                
            $formularioAMostrar = "public._form_confirmar_inscripcion_tiene_convenio";

            if ($convenio->esCooperativa()) 
            {
                $formularioAMostrar = "public._form_confirmar_inscripcion_es_cooperativa";
            }            
        }

        if ($participante->vinculadoUnicolMayor()) {
            $formularioAMostrar = "public._form_confirmar_inscripcion_no_tiene_convenio";
            if ($participante->totalFormulariosInscritosPeriodoActual() == 0) {
                $formularioAMostrar = "public._form_confirmar_inscripcion_ucmc";
            }
        }
        
        return view('public.confirmar_inscripcion', [
            'participante' => $participante,
            'grupo' => $grupo,
            'convenio' => $datosDePago['convenio'],
            'totalPago' => $datosDePago['totalPago'],
            'descuento' => $datosDePago['descuento'],
            'totalPagoFormateado' => $datosDePago['totalPagoFormateado'],
            'descuentoFormateado' => $datosDePago['descuentoFormateado'],
            'totalAPagarConAbono' => $datosDePago['totalAPagarConAbono'],
            'totalAbono' => $datosDePago['totalAbono'],
            'formularioAMostrar' => $formularioAMostrar,
            'formularioId' => $formularioId,
            'formulario' => $formulario,
        ]);
    }

    public function confirmarInscripcion2(FormularioPublicoConfirmarInscripcion2 $req) {
        
        $datos = $req->validated();

        if (is_null(request()->session()->get('SESSION_UUID'))) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Su sesión ha finalizado.");
        }

        $participante = (new BuscarParticipantePorIdUseCase)->ejecutar($datos['participanteId']);
        if (!$participante->existe()) {
            return redirect('public.inicio')->with('message', 'El participante no existe')->with('code', 500);
        }        

        $cursos_a_matricular = [];        
        if (!is_null(request()->session()->get('cursos_a_matricular'))) {
            $cursos_a_matricular = request()->session()->get('cursos_a_matricular');
        } 
        
        foreach($cursos_a_matricular as $curso) {
            // dd($curso);        
            $confirmarInscripcionDto = $this->hydrateConfirmarInscripcionDto2($req, $curso);
            $response = (new ConfirmarInscripcionUseCase)->ejecutar($confirmarInscripcionDto);
            if ($response->code != "201") {                
                break ;
            }
        }

        $calendarioVigente = Calendario::Vigente();

        return view('public.mensaje_respuesta', [
            'mensaje' => $response->message,
            'code' => $response->code,
            'participante' => $datos['participanteId'],
            'fec_ini_clase' => FormatoFecha::fechaFormateadaA5DeAgostoDe2024($calendarioVigente->getFechaInicioClase()),
        ]);        
    }

    public function confirmarInscripcion(FormularioPublicoConfirmarInscripcion $req) {

        if (is_null(request()->session()->get('SESSION_UUID'))) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Su sesión ha finalizado.");
        }

        $formularioDto = $this->hydrateConfirmarInscripcionDto( $req->validated() );
        
        $formularioDto->pathComprobantePago = "";
        
        if (!is_null(request()->pdf)) {

            $pdfPath = $req->file('pdf')->store('public/pdfs');
    
            if ($pdfPath) {
                $pdfPath = url('/') . "/" . $pdfPath;
                
                $pdfPath = str_replace('/public/', '/storage/app/', $pdfPath);
    
                $formularioDto->pathComprobantePago = $pdfPath;
            }
        }

        $calendarioVigente = Calendario::Vigente();

        $response = (new ConfirmarInscripcionUseCase)->ejecutar($formularioDto);  
        
        return view('public.mensaje_respuesta', [
            'mensaje' => $response->message,
            'code' => $response->code,
            'participante' => $formularioDto->participanteId,
            'fec_ini_clase' => FormatoFecha::fechaFormateadaA5DeAgostoDe2024($calendarioVigente->getFechaInicioClase()),
        ]);
    }

    function descargarReciboMatricula($participanteId) {

        if (is_null(request()->session()->get('SESSION_UUID'))) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Su sesión ha finalizado.");
        }
        
        if ($participanteId == 0) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Formulario no válido.");
        }    

        $resultado = (new GenerarReciboMatriculaUseCase)->ejecutar($participanteId);
        if (!$resultado["exito"]) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Formulario no válido.");
        }
        
        session()->forget('SESSION_UUID');
        session()->forget('cursos_a_matricular');

        $nombre_archivo = $resultado["nombre_archivo"];
                
        $ruta_archivo = storage_path() . '/' . $nombre_archivo;

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nombre_archivo . '"',
        ];
        
        return response()->download($ruta_archivo, $nombre_archivo, $headers)->deleteFileAfterSend(true);
    }

    private function obtenerPathPDFComprobanteDePago(FormularioPublicoConfirmarInscripcion2 $req) {
        $pdfPath = "";
        if (!is_null(request()->pdf)) {
            $pdfPath = $req->file('pdf')->store('public/pdfs');
            if ($pdfPath) {
                $pdfPath = url('/') . "/" . $pdfPath;                
                $pdfPath = str_replace('/public/', '/storage/app/', $pdfPath);
            }
        }
        return $pdfPath;
    }
    
    private function hydrateConfirmarInscripcionDto2($datos, $curso): ConfirmarInscripcionDto {        
        $confirmarInscripcionDto = new ConfirmarInscripcionDto();
        $confirmarInscripcionDto->participanteId = $datos['participanteId'];
        $confirmarInscripcionDto->grupoId = $curso['grupoId'];

        $medioPago = "pagoDatafono";
        if (isset($curso['medioPago'])) {
            $medioPago = $curso['medioPago'];
        }

        $confirmarInscripcionDto->medioPago = $medioPago;
        $confirmarInscripcionDto->convenioId = $curso['convenio']->getId();            
        $confirmarInscripcionDto->costoCurso = $curso['costo_curso'];
        $confirmarInscripcionDto->valorDescuento = $curso['descuento'];
        $confirmarInscripcionDto->totalAPagar = $curso['totalPago'];
        $confirmarInscripcionDto->formularioId = $datos['formularioId'];

        $confirmarInscripcionDto->flagComprobante = false;
        if (isset($datos['flagComprobante'])) {
            $confirmarInscripcionDto->flagComprobante = true;
        }        

        $confirmarInscripcionDto->estado = "Revisar comprobante de pago";
        if (isset($datos['estado'])) {
            $confirmarInscripcionDto->estado = $datos['estado'];
        }

        $confirmarInscripcionDto->pathComprobantePago = $this->obtenerPathPDFComprobanteDePago($datos);

        $voucher = 0;
        if (isset($datos['voucher'])) {
            $voucher = $datos['voucher'];
        }
        $confirmarInscripcionDto->voucher = $voucher;

        $valorPago = 0;
        if (isset($datos['valorPago'])) {
            $valorPago = $datos['valorPago'];
        }
        $confirmarInscripcionDto->valorPagoParcial = $valorPago;    

        return $confirmarInscripcionDto;
    }

    private function hydrateConfirmarInscripcionDto($datos): ConfirmarInscripcionDto {
        $formularioDto = new ConfirmarInscripcionDto;
        $formularioDto->participanteId = $datos['participanteId'];
        $formularioDto->grupoId = $datos['grupoId'];

        $medioPago = "pagoDatafono";
        if (isset($datos['medioPago'])) {
            $medioPago = $datos['medioPago'];
        }

        $formularioDto->medioPago = $medioPago;
        $formularioDto->convenioId = $datos['convenioId'];
        $formularioDto->costoCurso = $datos['costo_curso'];
        $formularioDto->valorDescuento = $datos['valor_descuento'];
        $formularioDto->totalAPagar = $datos['total_a_pagar'];
        $formularioDto->formularioId = $datos['formularioId'];

        $formularioDto->flagComprobante = false;
        if (isset($datos['flagComprobante'])) {
            $formularioDto->flagComprobante = true;
        }        

        $formularioDto->estado = "Revisar comprobante de pago";
        if (isset($datos['estado'])) {
            $formularioDto->estado = $datos['estado'];
        }

        $voucher = 0;
        if (isset($datos['voucher'])) {
            $voucher = $datos['voucher'];
        }
        $formularioDto->voucher = $voucher;

        $valorPago = 0;
        if (isset($datos['valorPago'])) {
            $valorPago = $datos['valorPago'];
        }
        $formularioDto->valorPagoParcial = $valorPago;

        return $formularioDto;
    }

    public function pagarMatricula($participanteId=0) {
        
        if (is_null(request()->session()->get('SESSION_UUID'))) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Su sesión ha finalizado.");
        }

        $participante = (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId);
        if (!$participante->existe()) {
            return redirect('public.inicio')->with('message', 'El participante no existe')->with('code', 500);
        }        
        
        $formularioAMostrar = "public._form_confirmar_inscripcion_no_tiene_convenio_2";

        
        // if ($participante->vinculadoUnicolMayor()) {
        //     $formularioAMostrar = "public._form_confirmar_inscripcion_no_tiene_convenio";
        //     if ($participante->totalFormulariosInscritosPeriodoActual() == 0) {
        //         $formularioAMostrar = "public._form_confirmar_inscripcion_ucmc";
        //     }
        // }
        
        return view('public.confirmar_inscripcion_2', [
            'participante' => $participante,
            'formularioAMostrar' => $formularioAMostrar,
            'formularioId' => 0,
        ]);
    }
    
    private function hydrateParticipanteDto($dato): ParticipanteDto {
        $participanteDto = new ParticipanteDto;
        $participanteDto->primerNombre = $dato['primerNombre'];
        
        $participanteDto->segundoNombre = '';
        if (!is_null($dato['segundoNombre'])) {
            $participanteDto->segundoNombre = $dato['segundoNombre'];
        }
        $participanteDto->primerApellido = $dato['primerApellido'];

        $participanteDto->segundoApellido = '';
        if (!is_null($dato['segundoApellido'])) {
            $participanteDto->segundoApellido = $dato['segundoApellido'];
        }

        $participanteDto->fechaNacimiento = $dato['fecNacimiento'];
        $participanteDto->tipoDocumento = $dato['tipoDocumento'];
        $participanteDto->documento = $dato['documento'];
        $participanteDto->sexo = $dato['sexo'];
        $participanteDto->estadoCivil = $dato['estadoCivil'];
        $participanteDto->direccion = $dato['direccion'];
        $participanteDto->telefono = $dato['telefono'];
        $participanteDto->email = $dato['email'];
        $participanteDto->eps = $dato['eps'];
        $participanteDto->contactoEmergencia = $dato['contactoEmergencia'];
        $participanteDto->telefonoEmergencia = $dato['telefonoEmergencia'];

        if (isset(request()->id)) {
            $participanteDto->id = request()->id;
        }

        return $participanteDto;
    }  
    
    public function uploadPDF(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:2048', // Solo PDFs y tamaño máximo de 2 MB
        ]);

        // Procesar el archivo PDF
        if ($request->file('pdf')->isValid()) {
            $pdfPath = $request->file('pdf')->store('public/pdfs');
            dd($pdfPath);
        } else {
            dd("Error carga PDF");
        }
    }    
}
