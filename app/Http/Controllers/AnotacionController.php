<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuscarComentario;
use App\Http\Requests\BuscarParticipantePorDocumento;
use Illuminate\Http\Request;
use Src\domain\Participante;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\participantes\BuscarComentariosDeParticipanteEnUnPeriodoUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;

class AnotacionController extends Controller
{
    public function index()
    {
        return view('anotaciones.index', [
            'periodos' => (new ListarCalendariosUseCase)->ejecutar(),
            'tipos_de_documento' => ListaDeValor::tipoDocumentos()
        ]);
    }

    public function buscar_comentario(BuscarComentario $req)
    {
        $data = (object)$req->validated();

        $participante = (new BuscarParticipantePorDocumentoUseCase)->ejecutar($data->tipo_documento, $data->documento);
        
        if (!$participante->existe())
        {
            return redirect()->route('comentarios')->with('code', 404)->with('status', 'El participante no existe.');
        }

        return view('anotaciones.resultado_busqueda', [
            'participante' => $participante,
            'formularios' => $participante->formularios_inscritos_en_un_periodo($data->periodo),
        ]);
        
    }
}
