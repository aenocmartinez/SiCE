<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuscarGruposDisponiblesMartricula;
use App\Http\Requests\BuscarParticipantePorDocumento;
use App\Http\Requests\ConfirmarInscription;
use App\Http\Requests\GuardarFormularioInscripcion;
use Illuminate\Http\Request;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\areas\ListarAreasUseCase;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\convenios\ListarConveniosUseCase;
use Src\usecase\grupos\BuscarGrupoPorIdUseCase;
use Src\usecase\grupos\ListarGruposDisponiblesParaMatriculaUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;
use Src\usecase\participantes\BuscarParticipantePorIdUseCase;
use Src\usecase\participantes\ConfirmarInscripcionUseCase;
use Src\usecase\participantes\GuardarParticipanteUseCase;
use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\ParticipanteDto;

class ParticipanteController extends Controller
{
    public function index() {
        //
    }

    public function create($tipoDocumento, $documento) {

        $participante = (new BuscarParticipantePorDocumentoUseCase)->ejecutar($tipoDocumento, $documento); 
        $participante->setTipoDocumento($tipoDocumento);
        $participante->setDocumento($documento);

        return view('participantes.create', [
            'participante' => $participante,
            'sexo' => ListaDeValor::sexo(),
            'estadoCivil' => ListaDeValor::estadoCivil(),
            'listaEps' => ListaDeValor::eps()
        ]);
    }

    public function store(GuardarFormularioInscripcion $req) {
        $participanteDto = $this->hydrateParticipanteDto($req->validated());
        $response = (new GuardarParticipanteUseCase)->ejecutar($participanteDto);

        if ($response->code != "201" && $response->code != "200") {
            return redirect()->route('participantes.buscar_participante')->with('code', $response->code)->with('status', $response->message);
        }

        return redirect()->route('participantes.form_matricula', [
            'tipoDocumento' => $participanteDto->tipoDocumento,
            'documento' => $participanteDto->documento,
        ]);

    }

    public function show($id) {

    }    

    public function edit($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($id) {
        //
    }

    public function formularioBuscarPorDocumento() {
        return view('participantes.buscar_por_documento');
    }

    public function buscarParticipantePorDocumento(BuscarParticipantePorDocumento $req) {
        $datos = $req->validated();
        return redirect()->route('participantes.create', [
            'tipoDocumento' => $datos['tipoDocumento'],
            'documento' => $datos['documento']
        ]);
    } 

    public function formularioMatricula($tipoDocumento, $documento) {   
        
        $participante = (new BuscarParticipantePorDocumentoUseCase)->ejecutar($tipoDocumento, $documento);  

        return view('participantes.select_grupo_inscripcion', [
            'participante' => $participante,
            'calendarios' => (new ListarCalendariosUseCase)->ejecutar(),
            'areas' => (new ListarAreasUseCase)->ejecutar(),
            'calendarioId' => '',
            'areaId' => '',
            'grupos' => array(),
        ]);
    }

    public function buscarGruposDisponiblesParaMatricula(BuscarGruposDisponiblesMartricula $req) {
        $datos = $req->validated();
        $areaId = $datos['area'];
        $calendarioId = $datos['calendario'];
        $participanteId = $datos['participante'];

        return redirect()->route('participantes.buscar-grupos-2', [
            'participante' => (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId),
            'calendarios' => (new ListarCalendariosUseCase)->ejecutar(),
            'areas' => (new ListarAreasUseCase)->ejecutar(),
            'grupos' => (new ListarGruposDisponiblesParaMatriculaUseCase)->ejecutar($calendarioId, $areaId),
            'calendarioId' => $calendarioId,
            'participanteId' => $participanteId,
            'areaId' => $areaId            
        ]);
    }

    public function formularioBuscarGruposDisponibles($participanteId, $calendarioId, $areaId) {
        return view('participantes.select_grupo_inscripcion', [
            'participante' => (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId),
            'calendarios' => (new ListarCalendariosUseCase)->ejecutar(),
            'areas' => (new ListarAreasUseCase)->ejecutar(),
            'grupos' => (new ListarGruposDisponiblesParaMatriculaUseCase)->ejecutar($calendarioId, $areaId),
            'calendarioId' => $calendarioId,
            'participanteId' => $participanteId,
            'areaId' => $areaId            
        ]);
    }

    public function formulariMatricula($participanteId, $grupoId) {

        return view('participantes.create_matricula',[
            'participante' => (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId),
            'grupo' => (new BuscarGrupoPorIdUseCase)->ejecutar($grupoId),
            'convenios' => (new ListarConveniosUseCase)->ejecutar(),
        ]); 
    }

    public function confirmarInscripcion(ConfirmarInscription $req) {
        $datos = $req->validated();

        $formularioDto = new ConfirmarInscripcionDto;
        $formularioDto->participanteId = $datos['participanteId'];
        $formularioDto->grupoId = $datos['grupoId'];
        $formularioDto->medioPago = $datos['medioPago'];
        $formularioDto->convenioId = $datos['convenioId'];
        $formularioDto->costoCurso = $datos['costo_curso'];
        $formularioDto->valorDescuento = $datos['valor_descuento'];
        $formularioDto->totalAPagar = $datos['total_a_pagar'];

        $response = (new ConfirmarInscripcionUseCase)->ejecutar($formularioDto);        

        return redirect()->route('participantes.buscar_participante')
                            ->with('code', $response->code)
                            ->with('status', $response->message);
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
        // $participanteDto->fechaExpedicion = $dato['fechaExpedicion'];
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
