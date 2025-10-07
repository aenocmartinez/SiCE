<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarCorreccionesAsistenciaRequest;
use Illuminate\Http\Request;
use Src\dao\mysql\GrupoDao;
use Src\dao\mysql\ParticipanteDao;
use Src\usecase\correccion_asistencia\CorregirAsistenciasUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;
use Src\usecase\participantes\ListarGruposDelParticipanteEnPeriodoUseCase;
use Src\usecase\participantes\ListarPeriodosDeParticipanteUseCase;
use Src\usecase\participantes\ListarSesionesDeParticipanteEnGrupoUseCase;
use Src\view\dto\BuscarParticipantePorDocumentoDTO;
use Src\view\dto\CorregirAsistenciasInput;

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

        $payload = [
            'participante_id' => $req->query('participante_id', $req->input('participante_id', $req->input('participanteId'))),
            'periodo_id'      => $req->query('periodo_id',      $req->input('periodo_id',      $req->input('periodoId'))),
        ];

        $validator = validator($payload, [
            'participante_id' => ['required','integer','min:1'],
            'periodo_id'      => ['required','integer','min:1'],
        ], [], [
            'participante_id' => 'participante',
            'periodo_id'      => 'periodo',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $participanteId = (int) $payload['participante_id'];
        $periodoId      = (int) $payload['periodo_id'];

        $grupos = (new ListarGruposDelParticipanteEnPeriodoUseCase(new ParticipanteDao()))
                    ->ejecutar($participanteId, $periodoId);

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

    // public function guardarCorrecciones(GuardarCorreccionesAsistenciaRequest $request)
    // {
    //     $data = $request->validated();

    //     return response()->json([
    //         'ok' => true,
    //         'mensaje' => 'Request validado correctamente.',
    //         'resumen' => [
    //             'participante_id' => $data['participante_id'],
    //             'grupo_id' => $data['grupo_id'],
    //             'total_cambios' => count($data['cambios']),
    //             'observacion' => $data['observacion'] ?? null,
    //         ],
    //     ]);
    // }

    public function guardarCorrecciones(GuardarCorreccionesAsistenciaRequest $request)
    {
        $d = $request->validated();

        $input = new CorregirAsistenciasInput(
            participanteId: (int) $d['participante_id'],
            grupoId:        (int) $d['grupo_id'],
            cambios:        $d['cambios'],
            observacion:    $d['observacion'] ?? null,
            actorId:        auth()->id(),
            actorNombre:    auth()->user()->name ?? 'sistema',
            actorIp:        $request->ip(),
            actorUserAgent: (string) $request->userAgent()
        );

        try {
            $grupoDao   = new GrupoDao();
            $partDao    = new ParticipanteDao();
            $listarUC   = new ListarSesionesDeParticipanteEnGrupoUseCase($partDao);

            $uc = new CorregirAsistenciasUseCase($grupoDao, $partDao, $listarUC);

            $out = $uc->ejecutar($input);

            return response()->json([
                'ok'       => true,
                'mensaje'  => 'Correcciones aplicadas.',
                'resumen'  => $out->resumen,        
                'estado'   => $out->estado_final,   
            ]);
        } catch (\Throwable $e) {
            // log($e->getMessage());
            return response()->json([
                'ok'      => false,
                'message' => 'No fue posible aplicar las correcciones.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}
