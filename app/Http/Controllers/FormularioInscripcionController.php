<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuscarGruposDisponiblesInscripcion;
use App\Http\Requests\BuscarInscripciones;
use App\Http\Requests\BuscarParticipantePorDocumento;
use App\Http\Requests\ConfirmarInscription;
use App\Http\Requests\GuardarParticipante;
use Illuminate\Http\Request;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\areas\ListarAreasUseCase;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\convenios\ListarConveniosUseCase;
use Src\usecase\formularios\BuscarFormulariosUseCase;
use Src\usecase\formularios\ConfirmarInscripcionUseCase;
use Src\usecase\grupos\BuscarGrupoPorIdUseCase;
use Src\usecase\grupos\ListarGruposDisponiblesParaMatriculaUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;
use Src\usecase\participantes\BuscarParticipantePorIdUseCase;
use Src\usecase\participantes\GuardarParticipanteUseCase;
use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\ParticipanteDto;

class FormularioInscripcionController extends Controller
{
    public function listarParticipantes() {
        
        $periodos = (new ListarCalendariosUseCase)->ejecutar();
        $idPeriodo = 0;
        if (sizeof($periodos) > 0) {
            $idPeriodo = $periodos[0]->getId();
        }

        return view('formularios.index', [
            'periodos' => (new ListarCalendariosUseCase)->ejecutar(),
            'estadoFormulario' => (ListaDeValor::estadosFormularioInscripcion()),
            'formularios' => (new BuscarFormulariosUseCase)->ejecutar($idPeriodo, ''),
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
            'formularios' => (new BuscarFormulariosUseCase)->ejecutar($filtro['periodo'], $filtro['estado']),
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
        return view('formularios.create_matricula',[
            'participante' => (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId),
            'grupo' => (new BuscarGrupoPorIdUseCase)->ejecutar($grupoId),
            'convenios' => (new ListarConveniosUseCase)->ejecutar(),
        ]); 
    }
    
    public function confirmarInscripcion(ConfirmarInscription $req) {
        
        $datos = $req->validated();
        
        $formularioDto = $this->hydrateConfirmarInscripcionDto($datos);

        $response = (new ConfirmarInscripcionUseCase)->ejecutar($formularioDto);        

        return redirect()->route('formulario-inscripcion.paso-1')
                            ->with('code', $response->code)
                            ->with('status', $response->message);
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
