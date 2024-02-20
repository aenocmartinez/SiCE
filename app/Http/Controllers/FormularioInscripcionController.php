<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuscarGruposDisponiblesInscripcion;
use App\Http\Requests\BuscarInscripciones;
use App\Http\Requests\BuscarParticipantePorDocumento;
use App\Http\Requests\ConfirmarInscription;
use App\Http\Requests\GuardarParticipante;
use App\Http\Requests\LegalizarFormularioInscripcion;
use Illuminate\Http\Request;
use Src\domain\Calendario;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\areas\ListarAreasUseCase;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\convenios\ListarConveniosUseCase;
use Src\usecase\formularios\BuscarFormularioPorNumeroUseCase;
use Src\usecase\formularios\BuscarFormulariosUseCase;
use Src\usecase\formularios\ConfirmarInscripcionUseCase;
use Src\usecase\formularios\LegalizarInscripcionUseCase;
use Src\usecase\grupos\BuscarGrupoPorIdUseCase;
use Src\usecase\grupos\ListarGruposDisponiblesParaMatriculaUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;
use Src\usecase\participantes\BuscarParticipantePorIdUseCase;
use Src\usecase\participantes\GuardarParticipanteUseCase;
use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\ParticipanteDto;
use Src\usecase\formularios\AnularFormularioUseCase;

class FormularioInscripcionController extends Controller
{
    public function listarParticipantes() {        
        $calendario = Calendario::Vigente();
        return view('formularios.index', [
            'periodos' => (new ListarCalendariosUseCase)->ejecutar(),
            'estadoFormulario' => (ListaDeValor::estadosFormularioInscripcion()),
            'paginate' => (new BuscarFormulariosUseCase)->ejecutar(),
            'periodo' => $calendario->getId(),
            'estado' => "",            
        ]);
    }

    public function index() {
        return view('formularios.buscar_por_documento');
    }

    public function filtrarInscripciones(BuscarInscripciones $req) {
        $filtro = $req->validated();
        return view('formularios.index', [
            'periodos' => (new ListarCalendariosUseCase)->ejecutar(),
            'estadoFormulario' => (ListaDeValor::estadosFormularioInscripcion()),
            'periodo' => $filtro['periodo'],
            'estado' => $filtro['estado'],
            'paginate' => (new BuscarFormulariosUseCase)->ejecutar($filtro['periodo'], $filtro['estado']),
        ]);        
    }

    public function buscadorFormulariosPaginados($page=1, $periodo, $estado="") {
        return view('formularios.index', [
            'periodos' => (new ListarCalendariosUseCase)->ejecutar(),
            'estadoFormulario' => (ListaDeValor::estadosFormularioInscripcion()),
            'periodo' => $periodo,
            'estado' => $estado,
            'paginate' => (new BuscarFormulariosUseCase)->ejecutar($periodo, $estado, $page),
        ]);    
    }

    public function create($tipoDocumento, $documento) {

        $participante = (new BuscarParticipantePorDocumentoUseCase)->ejecutar($tipoDocumento, $documento); 
        $participante->setTipoDocumento($tipoDocumento);
        $participante->setDocumento($documento);

        return view('formularios.create', [
            'participante' => $participante,
            'sexo' => ListaDeValor::sexo(),
            'estadoCivil' => ListaDeValor::estadoCivil(),
            'listaEps' => ListaDeValor::eps()
        ]);
    }

    public function store(GuardarParticipante $req) {
        $participanteDto = $this->hydrateParticipanteDto($req->validated());
        $response = (new GuardarParticipanteUseCase)->ejecutar($participanteDto);

        if ($response->code != "201" && $response->code != "200") {
            return redirect()->route('formulario-inscripcion.paso-1')->with('code', $response->code)->with('status', $response->message);
        }

        return redirect()->route('formulario-inscripcion.paso-3', [
            'tipoDocumento' => $participanteDto->tipoDocumento,
            'documento' => $participanteDto->documento,
        ]);
    }

    public function show($id) {
        //
    }

    public function edit($tipoDocumento, $documento) {   

        if (!Calendario::existeCalendarioVigente()) {
            return redirect()->route('formulario-inscripcion.paso-1')->with('code', "404")->with('status', "No existe periodo académica vigente.");
        }        
        
        $participante = (new BuscarParticipantePorDocumentoUseCase)->ejecutar($tipoDocumento, $documento);  
        
        return view('formularios.select_grupo_inscripcion', [
            'participante' => $participante,
            'calendarios' => (new ListarCalendariosUseCase)->ejecutar(),
            'areas' => (new ListarAreasUseCase)->ejecutar(),
            'calendarioId' => '',
            'areaId' => '',
            'grupos' => array(),
        ]);
    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($id) {
        //
    }

    public function buscarParticipantePorDocumento(BuscarParticipantePorDocumento $req) {
        
        if (!Calendario::existeCalendarioVigente()) {
            return redirect()->route('formulario-inscripcion.paso-1')->with('code', "404")->with('status', "No existe periodo académica vigente.");
        }

        $datos = $req->validated();
        return redirect()->route('formulario-inscripcion.paso-2', [
            'tipoDocumento' => $datos['tipoDocumento'],
            'documento' => $datos['documento']
        ]);
    } 

    public function buscarGruposDisponiblesParaInscripcion(BuscarGruposDisponiblesInscripcion $req) {  
        $datos = $req->validated();
        $areaId = $datos['area'];
        $calendarioId = $datos['calendario'];
        $participanteId = $datos['participante'];

        if (!Calendario::existeCalendarioVigente()) {
            return redirect()->route('formulario-inscripcion.paso-1')->with('code', "404")->with('status', "No existe periodo académica vigente.");
        }

        return redirect()->route('formulario-inscripcion.paso-3-1.buscar-grupos', [
            'participante' => (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId),
            'calendarios' => (new ListarCalendariosUseCase)->ejecutar(),
            'areas' => (new ListarAreasUseCase)->ejecutar(),
            'grupos' => (new ListarGruposDisponiblesParaMatriculaUseCase)->ejecutar($calendarioId, $areaId),
            'calendarioId' => $calendarioId,
            'participanteId' => $participanteId,
            'areaId' => $areaId            
        ]);
    }    

    public function buscarGruposDisponiblesParaInscripcion2($participanteId, $calendarioId, $areaId) {
        return view('formularios.select_grupo_inscripcion', [
            'participante' => (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId),
            'calendarios' => (new ListarCalendariosUseCase)->ejecutar(),
            'areas' => (new ListarAreasUseCase)->ejecutar(),
            'grupos' => (new ListarGruposDisponiblesParaMatriculaUseCase)->ejecutar($calendarioId, $areaId),
            'calendarioId' => $calendarioId,
            'participanteId' => $participanteId,
            'areaId' => $areaId            
        ]);
    }    

    public function vistaConfirmarInscripcion($participanteId, $grupoId) {
        return view('formularios.confirmar_inscripcion',[
            'participante' => (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId),
            'grupo' => (new BuscarGrupoPorIdUseCase)->ejecutar($grupoId),
            'convenios' => (new ListarConveniosUseCase)->ejecutar(),
        ]); 
    }
    
    public function confirmarInscripcion(ConfirmarInscription $req) {
        
        $datos = $req->validated();

        $formularioDto = $this->hydrateConfirmarInscripcionDto($datos);

        $response = (new ConfirmarInscripcionUseCase)->ejecutar($formularioDto);  

        $nombre_archivo = "";
        if (isset($response->data['nombre_archivo'])) {
            $nombre_archivo = $response->data['nombre_archivo'];
        }

        return redirect()->route('formulario-inscripcion.paso-1')
                            ->with('code', $response->code)
                            ->with('status', $response->message)
                            ->with('nombre_archivo', $nombre_archivo);
    }

    function descargarFormatoPagoInscripcion($nombre_archivo) {
        $ruta_archivo = $pdfPath = storage_path() . '/' . $nombre_archivo;
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nombre_archivo . '"',
        ];
    
        return response()->download($ruta_archivo, $nombre_archivo, $headers)->deleteFileAfterSend(true);
    }    


    public function editLegalizarInscripcion($numeroFormulario) {
        
        $formulario = (new BuscarFormularioPorNumeroUseCase)->ejecutar($numeroFormulario);
        if (!$formulario->existe()) {
            return redirect()->route('formularios.index')->with('code', "404")->with('status', "El formulario no fue encontrado.");
        }

        return view('formularios.legalizar_inscripcion',[
            'formulario' => $formulario,
        ]);
    }

    public function legalizarInscripcion(LegalizarFormularioInscripcion $req) {
        $parametro = $req->validated();
        $response = (new LegalizarInscripcionUseCase)->ejecutar($parametro['formularioId'], $parametro['voucher']);
        return redirect()->route('formularios.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function anularInscripcion($numeroFormulario, $participanteId) {        
        $response = (new AnularFormularioUseCase)->ejecutar($numeroFormulario);
        return redirect()->route('formularios.index')->with('code', $response->code)->with('status', $response->message);
    }    

    private function hydrateConfirmarInscripcionDto($datos): ConfirmarInscripcionDto{
        $formularioDto = new ConfirmarInscripcionDto;
        $formularioDto->participanteId = $datos['participanteId'];
        $formularioDto->grupoId = $datos['grupoId'];
        $formularioDto->medioPago = $datos['medioPago'];
        $formularioDto->convenioId = $datos['convenioId'];
        $formularioDto->costoCurso = $datos['costo_curso'];
        $formularioDto->valorDescuento = $datos['valor_descuento'];
        $formularioDto->totalAPagar = $datos['total_a_pagar'];
        $formularioDto->voucher = $datos['voucher'];

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
}
