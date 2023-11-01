<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuscarParticipantePorDocumento;
use App\Http\Requests\GuardarFormularioInscripcion;
use Illuminate\Http\Request;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\convenios\ListarConveniosUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;
use Src\usecase\participantes\GuardarParticipanteUseCase;
use Src\view\dto\ParticipanteDto;

class ParticipanteController extends Controller
{
    public function index() {
        //
    }

    public function create($tipoDocumento, $documento) {

        $participante = (new BuscarParticipantePorDocumentoUseCase)->ejecutar($tipoDocumento, $documento);  

        return view('participantes.create', [
            'participante' => $participante,
            'sexo' => ListaDeValor::sexo(),
            'estadoCivil' => ListaDeValor::estadoCivil(),
            'listaEps' => ListaDeValor::eps(),
            'calendarios' => (new ListarCalendariosUseCase())->ejecutar(),
            'convenios' => (new ListarConveniosUseCase)->ejecutar(),
        ]);
    }

    public function store(GuardarFormularioInscripcion $req) {
        $participanteDto = $this->hydrateParticipanteDto($req->validated());
        $response = (new GuardarParticipanteUseCase)->ejecutar($participanteDto);
        return redirect()->route('participantes.buscar_participante')->with('code', $response->code)->with('status', $response->message);
    }

    public function show($id) {
        //
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
    
    private function hydrateParticipanteDto($dato): ParticipanteDto {
        $participanteDto = new ParticipanteDto;
        $participanteDto->primerNombre = $dato['primerNombre'];
        $participanteDto->segundoNombre = $dato['segundoNombre'];
        $participanteDto->primerApellido = $dato['primerApellido'];
        $participanteDto->segundoApellido = $dato['segundoApellido'];
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
