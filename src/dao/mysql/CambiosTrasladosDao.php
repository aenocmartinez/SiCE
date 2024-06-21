<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\domain\CambioTraslado;
use Src\domain\FormularioInscripcion;
use Src\domain\Grupo;
use Src\domain\Participante;
use Src\infraestructure\util\Paginate;
use Sentry\Laravel\Facade as Sentry;

class CambiosTrasladosDao extends Model {

    protected $table = 'cambios_traslados';
    protected $fillable = [
        'formulario_id', 
        'periodo', 
        'accion', 
        'participante_id_inicial', 
        'nuevo_participante_id', 
        'grupo_id_inicial', 
        'nuevo_grupo_id', 
        'decision_sobre_pago', 
        'valor_decision_sobre_pago',
        'valor_a_pagar_inicial', 
        'nuevo_valor_a_pagar',
        'justificacion'
    ];

    public function crearCambioTraslado(CambioTraslado $cambioTraslado) {

        try {
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario = $idUsuarioSesion");
                        
            CambiosTrasladosDao::create([
                'formulario_id' => $cambioTraslado->getFormulario()->getId(), 
                'periodo' => $cambioTraslado->getPeriodo(), 
                'accion' => $cambioTraslado->getAccion(), 
                'participante_id_inicial' => $cambioTraslado->getParticipanteInicial()->getId(), 
                'nuevo_participante_id' => $cambioTraslado->getNuevoParticipante()->getId(), 
                'grupo_id_inicial' => $cambioTraslado->getGrupoInicial()->getId(), 
                'nuevo_grupo_id' => $cambioTraslado->getNuevoGrupo()->getId(), 
                'decision_sobre_pago' => $cambioTraslado->getDecisionDePago(), 
                'valor_a_pagar_inicial' => $cambioTraslado->getValorInicialAPagar(), 
                'nuevo_valor_a_pagar' => $cambioTraslado->getNuevoValorAPagar(),
                'justificacion' => $cambioTraslado->getJustificacion(),
            ]);

        } catch (Exception $e) {
            Sentry::captureEvent($e);
            return false;
        }

        return true;
    }

    public static function listarCambios($page=1): Paginate {
        $paginate = new Paginate($page);
        $cambios = [];
        
        $items = CambiosTrasladosDao::select([
                        'cambios_traslados.id',
                        'cambios_traslados.accion',
                        'cambios_traslados.periodo',
                        'formulario_inscripcion.numero_formulario',
                        'formulario_inscripcion.id as formularioId',
                        'formulario_inscripcion.estado',
                        'participantes.primer_nombre',
                        'participantes.segundo_nombre',
                        'participantes.primer_apellido',
                        'participantes.segundo_apellido',
                        'participantes.tipo_documento',
                        'participantes.documento',
                        'participantes.id as participanteId',
                        'grupos.jornada as jornada_inicial',
                        'grupos.dia as dia_inicial',
                        'grupos.nombre as grupo_nombre_inicial',
                        'cursos.nombre as curso_nombre_inicial',
                        'nuevo_grupo.nombre as nuevo_grupo_nombre', 
                        'nuevo_grupo.id as nuevo_grupo_id',
                        'nuevo_grupo.jornada as nuevo_grupo_jornada',
                        'nuevo_grupo.dia as nuevo_grupo_dia',                                   
                        'nuevo_curso.nombre as nuevo_curso_nombre', 
                    ])
                    ->join('formulario_inscripcion', function($join) {
                        $join->on('formulario_inscripcion.id', '=', 'cambios_traslados.formulario_id')
                            ->where('formulario_inscripcion.estado', '<>', 'Anulado');
                    })
                    ->join('participantes', 'participantes.id', '=', 'cambios_traslados.participante_id_inicial')
                    ->join('grupos', 'grupos.id', '=', 'cambios_traslados.grupo_id_inicial')
                    ->join('curso_calendario', 'curso_calendario.id', '=', 'grupos.curso_calendario_id')
                    ->join('cursos', 'cursos.id', '=', 'curso_calendario.curso_id')
                    ->join('grupos as nuevo_grupo', 'nuevo_grupo.id', '=', 'cambios_traslados.nuevo_grupo_id')
                    ->join('curso_calendario as nuevo_curso_calendario', 'nuevo_curso_calendario.id', '=', 'nuevo_grupo.curso_calendario_id')
                    ->join('cursos as nuevo_curso', 'nuevo_curso.id', '=', 'nuevo_curso_calendario.curso_id')
                    ->orderBy('cambios_traslados.id', 'DESC')
                    ->skip($paginate->Offset())
                    ->take($paginate->Limit())
                    ->get();
        

            $totalItems = CambiosTrasladosDao::join('formulario_inscripcion', function($join) {
                $join->on('formulario_inscripcion.id', '=', 'cambios_traslados.formulario_id')
                        ->where('formulario_inscripcion.estado', '<>', 'Anulado');
            })->count();                

        foreach ($items as $item) {
            $participante = new Participante();
            $participante->setId($item->participanteId);
            $participante->setPrimerNombre($item->primer_nombre);
            $participante->setSegundoNombre($item->segundo_nombre);
            $participante->setPrimerApellido($item->primer_apellido);
            $participante->setSegundoApellido($item->segundo_apellido);
            $participante->setTipoDocumento($item->tipo_documento);
            $participante->setDocumento($item->documento);

            $formulario = new FormularioInscripcion();
            $formulario->setId($item->formularioId);
            $formulario->setNumero($item->numero_formulario);
            $formulario->setEstado($item->estado);

            $grupoInicial = new Grupo();
            $grupoInicial->setNombre($item->grupo_nombre_inicial);
            $grupoInicial->setJornada($item->jornada_inicial);
            $grupoInicial->setDia($item->dia_inicial);

            $nuevoGrupo = new Grupo();
            $nuevoGrupo->setNombre($item->nuevo_grupo_nombre);
            $nuevoGrupo->setJornada($item->nuevo_grupo_jornada);
            $nuevoGrupo->setDia($item->nuevo_grupo_dia);            

            $cambio = new CambioTraslado();
            $cambio->setId($item->id);
            $cambio->setPeriodo($item->periodo);
            $cambio->setAccion($item->accion);
            $cambio->setParticipanteInicial($participante);
            $cambio->setFormulario($formulario);
            $cambio->setGrupoInicial($grupoInicial);
            $cambio->setNuevoGrupo($nuevoGrupo);
            $cambio->setNombreCursoInicial($item->curso_nombre_inicial);
            $cambio->setNombreNuevoCurso($item->nuevo_curso_nombre);

            $cambios[] = $cambio;
        }
        
        $paginate->setTotalRecords($totalItems);
        $paginate->setRecords($cambios);

        return $paginate;
    }

    public static function buscadorCambiosYTraslados(string $search, $page=1): Paginate {
        $paginate = new Paginate($page);
        $cambios = [];

        $query = CambiosTrasladosDao::select([
            'cambios_traslados.id',
            'cambios_traslados.accion',
            'cambios_traslados.periodo',
            'formulario_inscripcion.numero_formulario',
            'formulario_inscripcion.id as formularioId',
            'formulario_inscripcion.estado',
            'participantes.primer_nombre',
            'participantes.segundo_nombre',
            'participantes.primer_apellido',
            'participantes.segundo_apellido',
            'participantes.tipo_documento',
            'participantes.documento',
            'participantes.id as participanteId',
            'grupos.jornada as jornada_inicial',
            'grupos.dia as dia_inicial',
            'grupos.nombre as grupo_nombre_inicial',
            'cursos.nombre as curso_nombre_inicial',
            'nuevo_grupo.jornada as nuevo_grupo_jornada',
            'nuevo_grupo.dia as nuevo_grupo_dia',
            'nuevo_grupo.nombre as grupo_nombre_nuevo',
            'nuevo_curso.nombre as nuevo_curso_nombre'
        ])
        ->join('formulario_inscripcion', function($join) {
            $join->on('formulario_inscripcion.id', '=', 'cambios_traslados.formulario_id')
                 ->where('formulario_inscripcion.estado', '<>', 'Anulado');
        })
        ->join('participantes', 'participantes.id', '=', 'cambios_traslados.participante_id_inicial')
        ->join('grupos', 'grupos.id', '=', 'cambios_traslados.grupo_id_inicial')
        ->join('curso_calendario', 'curso_calendario.id', '=', 'grupos.curso_calendario_id')
        ->join('cursos', 'cursos.id', '=', 'curso_calendario.curso_id')
        ->join('grupos as nuevo_grupo', 'nuevo_grupo.id', '=', 'cambios_traslados.nuevo_grupo_id')
        ->join('curso_calendario as nuevo_curso_calendario', 'nuevo_curso_calendario.id', '=', 'nuevo_grupo.curso_calendario_id')
        ->join('cursos as nuevo_curso', 'nuevo_curso.id', '=', 'nuevo_curso_calendario.curso_id')
        ->when($search, function ($query, $search) {
            $terms = explode(' ', $search); // Dividir los términos de búsqueda
            return $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where(function ($sq) use ($term) {
                        $sq->where('participantes.primer_nombre', 'like', "%$term%")
                           ->orWhere('participantes.segundo_nombre', 'like', "%$term%")
                           ->orWhere('participantes.primer_apellido', 'like', "%$term%")
                           ->orWhere('participantes.segundo_apellido', 'like', "%$term%")
                           ->orWhere('participantes.documento', 'like', "%$term%")
                           ->orWhere('cambios_traslados.periodo', 'like', "%$term%")
                           ->orWhere('formulario_inscripcion.numero_formulario', 'like', "%$term%");
                    });
                }
            });
        })
        ->orderBy('cambios_traslados.created_at', 'DESC');
        

        $totalQuery = clone $query;
        $totalRecords = $totalQuery->count();

        $items = $query->skip($paginate->Offset())->take($paginate->Limit())->get();

        foreach ($items as $item) {
            $participante = new Participante();
            $participante->setId($item->participanteId);
            $participante->setPrimerNombre($item->primer_nombre);
            $participante->setSegundoNombre($item->segundo_nombre);
            $participante->setPrimerApellido($item->primer_apellido);
            $participante->setSegundoApellido($item->segundo_apellido);
            $participante->setTipoDocumento($item->tipo_documento);
            $participante->setDocumento($item->documento);

            $formulario = new FormularioInscripcion();
            $formulario->setId($item->formularioId);
            $formulario->setNumero($item->numero_formulario);
            $formulario->setEstado($item->estado);

            $grupoInicial = new Grupo();
            $grupoInicial->setNombre($item->grupo_nombre_inicial);
            $grupoInicial->setJornada($item->jornada_inicial);
            $grupoInicial->setDia($item->dia_inicial);

            $nuevoGrupo = new Grupo();
            $nuevoGrupo->setNombre($item->nuevo_grupo_nombre);
            $nuevoGrupo->setJornada($item->nuevo_grupo_jornada);
            $nuevoGrupo->setDia($item->nuevo_grupo_dia);            

            $cambio = new CambioTraslado();
            $cambio->setId($item->id);
            $cambio->setPeriodo($item->periodo);
            $cambio->setAccion($item->accion);
            $cambio->setParticipanteInicial($participante);
            $cambio->setFormulario($formulario);
            $cambio->setGrupoInicial($grupoInicial);
            $cambio->setNuevoGrupo($nuevoGrupo);
            $cambio->setNombreCursoInicial($item->curso_nombre_inicial);
            $cambio->setNombreNuevoCurso($item->nuevo_curso_nombre);

            $cambios[] = $cambio;
        }  

        $paginate->setRecords($cambios);
        $paginate->setTotalRecords($totalRecords);

        return $paginate;
    }    
}