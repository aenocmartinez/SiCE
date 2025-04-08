<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarParticipante;
use App\Http\Requests\LegalizarFormularioInscripcion;
use Src\domain\Participante;
use Src\infraestructure\util\ListaDeValor;
use Src\infraestructure\util\Validador;
use Src\usecase\convenios\ListarConveniosUseCase;
use Src\usecase\formularios\AnularFormularioUseCase;
use Src\usecase\formularios\BuscarFormularioPorNumeroUseCase;
use Src\usecase\formularios\LegalizarInscripcionUseCase;
use Src\usecase\participantes\BuscadorParticipantesUseCase;
use Src\usecase\participantes\BuscarParticipantePorIdUseCase;
use Src\usecase\participantes\EliminarParticipanteUseCase;
use Src\usecase\participantes\GuardarParticipanteUseCase;
use Src\usecase\participantes\ListarCursosRealizadosParaDescargarCertificadoUseCase;
use Src\usecase\participantes\ListarFormulariosParticipanteUseCase;
use Src\usecase\participantes\ListarParticipantesUseCase;
use Src\view\dto\ParticipanteDto;

class ParticipanteController extends Controller
{
    public function index($page=1) {
        
        return view('participantes.index',[
            'paginate' => (new ListarParticipantesUseCase)->ejecutar($page),
        ]);
    }

    public function create() {
        return view('participantes.create', [
            'participante' => (new Participante),
            'sexo' => ListaDeValor::sexo(),
            'estadoCivil' => ListaDeValor::estadoCivil(),
            'listaEps' => ListaDeValor::eps()
        ]);        
    }

    public function store(GuardarParticipante $req) {

        $participanteDto = $this->hydrateParticipanteDto($req->validated());

        $response = (new GuardarParticipanteUseCase)->ejecutar($participanteDto);

        return redirect()->route('participantes.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function show($id) {

    }    

    public function edit($id) {   
        
        $esValido = Validador::parametroId($id);
        if (!$esValido) 
            return redirect()->route('participantes.index')->with('code', "401")->with('status', "parámetro no válido");
        
        $participante = (new BuscarParticipantePorIdUseCase)->ejecutar($id);
        if (!$participante->existe()) {
            return redirect()->route('participantes.index')->with('code', "404")->with('status', "El participante no fue encontrado.");
        }

        return view('participantes.edit', [
            'participante' => $participante,
            'sexo' => ListaDeValor::sexo(),
            'estadoCivil' => ListaDeValor::estadoCivil(),
            'listaEps' => ListaDeValor::eps()
        ]);
    }

    public function update(GuardarParticipante $req) {
        
        $participanteDto = $this->hydrateParticipanteDto($req->validated());

        $response = (new GuardarParticipanteUseCase)->ejecutar($participanteDto);

        return redirect()->route('participantes.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function destroy($participanteId) {
        $esValido = Validador::parametroId($participanteId);
        if (!$esValido) {
            return redirect()->route('participantes.index')->with('code', "401")->with('status', "parámetro no válido");
        }

        $response = (new EliminarParticipanteUseCase)->ejecutar($participanteId);
        return redirect()->route('participantes.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function anularInscripcion($numeroFormulario, $participanteId) {        
        $response = (new AnularFormularioUseCase)->ejecutar($numeroFormulario);
        return redirect()->route('participantes.formularios', [$participanteId])->with('code', $response->code)->with('status', $response->message);
    }

    public function editLegalizarInscripcion($numeroFormulario) {
        
        $formulario = (new BuscarFormularioPorNumeroUseCase)->ejecutar($numeroFormulario);
        if (!$formulario->existe()) {
            return redirect()->route('participantes.index')->with('code', "404")->with('status', "El formulario no fue encontrado.");
        }

        return view('participantes.legalizar_inscripcion',[
            'formulario' => $formulario,
            'convenios' => (new ListarConveniosUseCase)->ejecutar(),
        ]);
    }

    public function legalizarInscripcion(LegalizarFormularioInscripcion $req) {
        $datosLegalizaacion = $req->validated(); 
        
        $response = (new LegalizarInscripcionUseCase)->ejecutar($datosLegalizaacion);
        return redirect()->route('participantes.formularios', [$datosLegalizaacion['participanteId']])
                        ->with('code', $response->code)
                        ->with('status', $response->message);        
    }

    public function verDetalleInscripcion($numeroFormulario) {
        
        $formulario = (new BuscarFormularioPorNumeroUseCase)->ejecutar($numeroFormulario);
        if (!$formulario->existe()) {
            return redirect()->route('formularios.index')->with('code', "404")->with('status', "El formulario no fue encontrado.");
        }
        
        return view('participantes.detalle_inscripcion',[
            'formulario' => $formulario,
            'convenios' => (new ListarConveniosUseCase)->ejecutar(),
        ]);
    }    

    public function listarFormularios($participanteId) {        
        $esValido = Validador::parametroId($participanteId);
        if (!$esValido) {
            return redirect()->route('participantes.index')->with('code', "401")->with('status', "parámetro no válido");
        }

        $participante = (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId);
        if (!$participante->existe()) {
            return redirect()->route('participantes.index')->with('code', "404")->with('status', "El participante no fue encontrado.");
        }

        return view('participantes.formularios', [
            'participante' => $participante,
            'formularios' => (new ListarFormulariosParticipanteUseCase)->ejecutar($participanteId),
        ]);
    }

    public function buscadorparticipantes(){
        $criterio = '';
        if (!is_null(request('criterio'))) {
            $criterio = request('criterio');
        }

        if (strlen($criterio)==0) {
            return redirect()->route('participantes.index');
        }

        return view("participantes.index", [
            "paginate" => (new BuscadorParticipantesUseCase)->ejecutar($criterio),
            "criterio" => $criterio,
        ]);         
    }

    public function buscadorParticipantesPaginados($page, $criterio) {
        
        if (strlen($criterio)==0) {
            return redirect()->route('participantes.index');
        }

        return view("participantes.index", [
            "paginate" => (new BuscadorParticipantesUseCase)->ejecutar($criterio, $page),
            "criterio" => $criterio,
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

        $participanteDto->vinculadoUnicolMayor = true;
        if (is_null(request()->vinculadoUnicolMayor)) {
            $participanteDto->vinculadoUnicolMayor = false;
        }

        return $participanteDto;
    }

    public function listarCursosRealizadosParaDescargarCertificado($participanteID)
    {
        $response = (new ListarCursosRealizadosParaDescargarCertificadoUseCase)->ejecutar($participanteID);

        if ($response->code == "404") {
            return redirect()->route('participantes.index')->with('code', $response->code)->with('status', $response->message);
        }

        $participante = $response->data;
        
        return view("participantes.descargar_certificados", [
            "participante" => $participante,
        ]);              
    }
}
