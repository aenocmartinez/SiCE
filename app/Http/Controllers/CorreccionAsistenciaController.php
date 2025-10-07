<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarCorreccionesAsistenciaRequest;
use Illuminate\Http\Request;
use Src\dao\mysql\ParticipanteDao;
use Src\usecase\asistencias\dto\CorregirAsistenciasInput;
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
        $data = $request->validated();

        $participanteId = (int) $data['participante_id'];
        $grupoId        = (int) $data['grupo_id'];
        $cambios        = $data['cambios'];       
        $observacion    = $data['observacion'] ?? null;

        $listar = new ListarSesionesDeParticipanteEnGrupoUseCase(new ParticipanteDao());
        $actual = $listar->ejecutar($participanteId, $grupoId);
        $sesionesActuales = is_array($actual['sesiones'] ?? null) ? $actual['sesiones'] : [];

        $mapActual = [];
        foreach ($sesionesActuales as $s) {
            $sid = (int)($s['id'] ?? $s['sesion_id'] ?? 0);
            if ($sid > 0) {
                $mapActual[$sid] = (int)($s['asistio'] ?? $s['presente'] ?? $s['asistencia'] ?? 0);
            }
        }
        $nuevoPorSesion = [];
        foreach ($cambios as $chg) {
            $sid = (int)$chg['sesion_id'];
            $val = (int)$chg['asistio']; // 0|1
            if ($sid > 0) $nuevoPorSesion[$sid] = $val;
        }

        $marcar   = []; // sesiones a poner en 1 (crear/activar)
        $desmarcar= []; // sesiones a poner en 0 (eliminar/anular)

        foreach ($nuevoPorSesion as $sid => $nuevo) {
            $antes = (int)($mapActual[$sid] ?? 0);
            if ($nuevo !== $antes) {
                if ($nuevo === 1) $marcar[] = $sid;
                else $desmarcar[] = $sid;
            }
        }

        $input = new CorregirAsistenciasInput(
            participanteId: $participanteId,
            grupoId:        $grupoId,
            marcar:         $marcar,
            desmarcar:      $desmarcar,
            observacion:    $observacion,
            actorId:        auth()->id(),
            actorNombre:    auth()->user()->name ?? 'sistema',
            actorIp:        $request->ip(),
            actorUserAgent: (string) $request->userAgent()
        );

        // 5) Ejecutar el caso de uso
        // $usecase   = new CorregirAsistenciasUseCase(new ParticipanteDao());
        // $resultado = $usecase->ejecutar($input);

        // 6) (Opcional) volver a consultar estado final para refrescar UI
        // $nuevoEstado = $listar->ejecutar($participanteId, $grupoId);

        return response()->json([
            'ok'       => true,
            'mensaje'  => 'Correcciones aplicadas.',
            'resumen'  => [
                'marcadas'    => count($marcar),
                'desmarcadas' => count($desmarcar),
            ],
            // 'estado'   => $nuevoEstado,
            // 'debug'    => $resultado, // si tu usecase retorna info extra útil
        ]);
    }


}
