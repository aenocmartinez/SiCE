<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormularioPublicoConfirmarInscripcion;
use App\Http\Requests\FormularioPublicoGuardarParticipante;
use App\Http\Requests\FormularioPublicoInscripionConsultarExistencia;
use Illuminate\Http\Request;
use Src\domain\Calendario;
use Src\domain\Convenio;
use Src\domain\Grupo;
use Src\domain\Participante;
use Src\infraestructure\util\FormatoFecha;
use Src\infraestructure\util\FormatoMoneda;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\convenios\BuscarConvenioPorIdUseCase;
use Src\usecase\formularios\ConfirmarInscripcionUseCase;
use Src\usecase\formularios\GenerarReciboMatriculaUseCase;
use Src\usecase\grupos\BuscarGrupoPorIdUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;
use Src\usecase\participantes\BuscarParticipantePorIdUseCase;
use Src\usecase\participantes\GuardarParticipanteUseCase;
use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\ParticipanteDto;


class InscripcionPublicaController extends Controller
{
    public function index() {            
        return view('public.inicio');
    }

    public function consultarExistencia(FormularioPublicoInscripionConsultarExistencia $req) {
        $datoFormulario = $req->validated();
        
        $participante = (new BuscarParticipantePorDocumentoUseCase)->ejecutar($datoFormulario['tipoDocumento'], $datoFormulario['documento']);

        $participante->setTipoDocumento($datoFormulario['tipoDocumento']);
        $participante->setDocumento($datoFormulario['documento']);

        request()->session()->put('participante', $participante);

        return redirect()->route('public.formulario-participante');
    }

    public function formularioParticipante(Request $request) {
        $participante = $request->session()->get('participante');

        return view('public.actualizacion_datos', [
            'participante' => $participante,
            'listaEPS' => ListaDeValor::eps(),
            'listaSexo' => ListaDeValor::sexo(),
            'estadosCiviles' => ListaDeValor::estadoCivil()
        ]);
    }

    public function guardarDatosParticipante(FormularioPublicoGuardarParticipante $req) {

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
            dd("No hay calendarios vigentes");
        }

        $items = $calendarioVigente->listarGruposParaFormularioInscripcionPublico();
        
        return view('public.seleccion_de_cursos', [
            'items' => $items,
            'participante' => $participante,
        ]);
    }

    private function calcularValorDescuentoYTotalAPagar(Participante $participante, Grupo $grupo, Convenio $convenio): array {        
        
        $totalPago = $grupo->getCosto();
                
        $descuento = 0;
        if ($convenio->existe() && !$convenio->esCooperativa()) {
            $descuento = $grupo->getCosto() * ($convenio->getDescuento()/100);
        }

        $totalPago = $totalPago - $descuento;                    
    
        if ($participante->vinculadoUnicolMayor()) {     
            
            $totalPago = $grupo->getCosto();
            
            if ($participante->totalFormulariosInscritosPeriodoActual() == 0) {
                
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
        ];
    }

    public function formularioInscripcion($participanteId, $grupoId) {
        $participante = (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId);
        if (!$participante->existe()) {
            dd("No existe el participante");
        }

        $grupo = (new BuscarGrupoPorIdUseCase)->ejecutar($grupoId);
        if (!$grupo->existe()){ 
            dd("No existe el grupo");
        }

        $convenio = (new BuscarConvenioPorIdUseCase)->ejecutar($participante->getIdBeneficioConvenio());

        $datosDePago = $this->calcularValorDescuentoYTotalAPagar($participante, $grupo, $convenio);
        
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
            'formularioAMostrar' => $formularioAMostrar,
        ]);
    }

    public function confirmarInscripcion(FormularioPublicoConfirmarInscripcion $req) {
            
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
        
        if ($participanteId == 0) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Formulario no válido.");
        }    

        $resultado = (new GenerarReciboMatriculaUseCase)->ejecutar($participanteId);

        if (!$resultado["exito"]) {
            return redirect()->route('public.inicio')->with('code', "404")->with('status', "Formulario no válido.");
        }

        $nombre_archivo = $resultado["nombre_archivo"];
                
        $ruta_archivo = storage_path() . '/' . $nombre_archivo;

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nombre_archivo . '"',
        ];
        
        return response()->download($ruta_archivo, $nombre_archivo, $headers)->deleteFileAfterSend(true);        
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
