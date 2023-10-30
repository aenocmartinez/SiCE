<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuscarParticipantePorDocumento;
use Illuminate\Http\Request;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\convenios\ListarConveniosUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;

class ParticipanteController extends Controller
{
    public function index() {
        //
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        //
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

        $participante = (new BuscarParticipantePorDocumentoUseCase)->ejecutar($datos['tipoDocumento'], $datos['documento']);        
        return view('participantes.create', [
            'participante' => $participante,
            'sexo' => ListaDeValor::sexo(),
            'estadoCivil' => ListaDeValor::estadoCivil(),
            'listaEps' => ListaDeValor::eps(),
            'calendarios' => (new ListarCalendariosUseCase())->ejecutar(),
            'convenios' => (new ListarConveniosUseCase)->ejecutar(),
        ]);
    }    
}
