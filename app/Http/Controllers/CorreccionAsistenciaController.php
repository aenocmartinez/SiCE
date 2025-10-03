<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Src\dao\mysql\ParticipanteDao;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;
use Src\usecase\participantes\ListarGruposDelParticipanteEnPeriodoUseCase;
use Src\usecase\participantes\ListarPeriodosDeParticipanteUseCase;
use Src\usecase\participantes\ListarSesionesDeParticipanteEnGrupoUseCase;
use Src\view\dto\BuscarParticipantePorDocumentoDTO;

class CorreccionAsistenciaController extends Controller
{
    public function index()
    {        
        return view('correccion_asistencia.index');
    }

    public function buscarParticipantePorDocumento(Request $request)
    {
        $tipoDocumento = $request->input('tipo_doc');
        $documento     = $request->input('documento');

        $data = validator(
            ['tipoDocumento' => $tipoDocumento, 'documento' => $documento],
            [
                'tipoDocumento' => ['required','in:CC,TI,CE'],
                'documento'     => ['required','string','max:50'],
            ]
        )->validate();

        $resultado = (new BuscarParticipantePorDocumentoUseCase())->ejecutar(
            $data['tipoDocumento'],
            $data['documento']
        );

        $items = is_array($resultado) ? $resultado : array_filter([$resultado]);

        $dtos = array_map(fn($p) => BuscarParticipantePorDocumentoDTO::fromDomain($p), $items);

        return response()->json($dtos);
    }

    public function gruposPorPeriodoJson(Request $request)
    {
        $periodoId = (int) $request->query('periodo_id');
        if (!$periodoId) return response()->json(['message' => 'periodo_id es requerido'], 422);

        $grupoDao = new \Src\dao\mysql\GrupoDao();
        return response()->json($grupoDao->listarGruposPorPeriodo($periodoId));
    }    

    public function gruposPorPeriodoDeParticipante(Request $req)
    {
        $data = $req->validate([
            'participanteId' => ['required','integer','min:1'],
            'periodoId'      => ['required','integer','min:1'],
        ]);

        $grupos = (new ListarGruposDelParticipanteEnPeriodoUseCase(new ParticipanteDao()))
                    ->ejecutar($data['participanteId'], $data['periodoId']);

        return response()->json(['grupos' => $grupos ?? []]);
    }

    public function periodosDeParticipante(int $participanteId)
    {
        validator(['participanteId'=>$participanteId], [
            'participanteId' => ['required','integer','min:1']
        ])->validate();

        $periodos = (new ListarPeriodosDeParticipanteUseCase(new ParticipanteDao()))
                    ->ejecutar($participanteId);

        return response()->json(['periodos' => $periodos]);
    }

    public function sesionesDeParticipanteEnGrupo(int $participanteId, int $grupoId)
    {
        validator(compact('participanteId','grupoId'), [
            'participanteId' => ['required','integer','min:1'],
            'grupoId'        => ['required','integer','min:1'],
        ])->validate();

        $payload = (new ListarSesionesDeParticipanteEnGrupoUseCase(new ParticipanteDao()))
                    ->ejecutar($participanteId, $grupoId);


        return response()->json($payload ?? ['ultimo_registro'=>0,'sesiones'=>[]]);
    }


}
