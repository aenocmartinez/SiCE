<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\domain\Calendario;
use Src\domain\Curso;
use Src\domain\CursoCalendario;
use Src\domain\Grupo;
use Src\domain\repositories\GrupoRepository;

use Sentry\Laravel\Facade as Sentry;
use Src\domain\AsistenciaClase;
use Src\domain\FormularioInscripcion;
use Src\domain\Orientador;
use Src\domain\Participante;
use Src\domain\Salon;
use Src\infraestructure\util\FormatoMoneda;
use Src\infraestructure\util\Paginate;

class GrupoDao extends Model implements GrupoRepository {
    protected $table = 'grupos';
    protected $fillable = [
        'curso_calendario_id', 
        'salon_id', 
        'orientador_id', 
        'dia', 
        'jornada', 
        'cupos', 
        'nombre', 
        'calendario_id', 
        'bloqueado', 
        'cancelado', 
        'cerrado_para_inscripcion',
        'habilitado_para_preinscripcion',
        'observaciones'
    ];


    public function formulariosInscripcion() {
        return $this->hasMany(FormularioInscripcionDao::class, 'grupo_id');
    }

    public static function listarGrupos($page=1, Calendario $calendario): Paginate {
        
        $paginate = new Paginate($page, env('APP_PAGINADOR_NUM_ITEMS_GRUPOS'));

        $listaGrupos = array();
        $cursoDao = new CursoDao();
        $calendarioDao = new CalendarioDao();
        $orientadorDao = new OrientadorDao();
        $salonDao = new SalonDao();

        try {

            $query = DB::table('grupos as g')
                        ->select('g.id', 'g.dia', 'g.jornada', 'g.curso_calendario_id', 'g.cupos', 'g.nombre', 'g.bloqueado', 'g.cancelado','g.habilitado_para_preinscripcion',
                                'o.id as orientador_id', 'c.id as curso_id', 's.id as salon_id', 'ca.id as calendario_id', 'cc.modalidad',
                                DB::raw('(select count(fi.grupo_id) from formulario_inscripcion fi where fi.grupo_id = g.id and fi.estado <> "Anulado" and fi.estado <> "Devuelto" and fi.estado <> "Aplazado") as totalInscritos')
                                )
                        ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
                        ->join('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
                        ->join('calendarios as ca', 'ca.id', '=', 'cc.calendario_id')
                        ->join('cursos as c', 'c.id', '=', 'cc.curso_id')
                        ->join('salones as s', 's.id', '=', 'g.salon_id')
                        ->where('cc.calendario_id', $calendario->getId())
                        ->orderByDesc('g.id');
                
            $totalRecords = $query->count();
            $grupos = $query->skip($paginate->Offset())->take($paginate->Limit())->get();               
            
            foreach ($grupos as $g) {
                $grupo = new Grupo();                
                $grupo->setid($g->id);
                $grupo->setDia($g->dia);
                $grupo->setJornada($g->jornada);
                $grupo->setCupo($g->cupos);
                $grupo->setNombre($g->nombre);
                $grupo->setBloqueado($g->bloqueado);
                $grupo->setCancelado($g->cancelado);
                $grupo->setHabilitadoParaPreInscripcion($g->habilitado_para_preinscripcion);
                
                // $caledario = $calendarioDao->buscarCalendarioPorId($g->calendario_id);
                // if (!$caledario->esVigente()) {
                //     continue;
                // }
                
                $orientador = $orientadorDao->buscarOrientadorPorId($g->orientador_id);
                $curso = $cursoDao->buscarCursoPorId($g->curso_id);

                $salon = new Salon();
                if (!is_null($g->salon_id)) {
                    $salon = $salonDao->buscarSalonPorId($g->salon_id);
                }

                $cursoCalendario = new CursoCalendario($calendario, $curso);
                $cursoCalendario->setId($g->curso_calendario_id);
                $cursoCalendario->setModalidad($g->modalidad);

                $grupo->setCursoCalendario($cursoCalendario);
                $grupo->setOrientador($orientador);
                $grupo->setSalon($salon);
                $grupo->setTotalInscritos($g->totalInscritos);

                array_push($listaGrupos, $grupo);
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        $paginate->setRecords($listaGrupos);
        $paginate->setTotalRecords($totalRecords);

        return $paginate;
    }

    public function buscarGrupoPorId(int $id): Grupo {
        $grupo = new Grupo();
        $cursoDao = new CursoDao();
        $calendarioDao = new CalendarioDao();
        $orientadorDao = new OrientadorDao();
        $salonDao = new SalonDao();

        try {
            $g = DB::table('grupos as g')
                    ->select('g.id', 'g.dia', 'g.jornada', 'g.curso_calendario_id', 'g.cupos', 'g.nombre', 'g.bloqueado', 'g.cancelado', 'g.cerrado_para_inscripcion', 'g.observaciones', 'g.habilitado_para_preinscripcion',
                            'o.id as orientador_id', 'c.id as curso_id', 's.id as salon_id', 'ca.id as calendario_id', 'cc.costo', 'cc.modalidad', 
                            DB::raw('(select count(fi.grupo_id) from formulario_inscripcion fi where fi.grupo_id = g.id and fi.estado <> "Anulado" and fi.estado <> "Aplazado" and fi.estado <> "Devuelto") as totalInscritos')                          
                            )
                    ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
                    ->join('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
                    ->join('calendarios as ca', 'ca.id', '=', 'cc.calendario_id')
                    ->join('cursos as c', 'c.id', '=', 'cc.curso_id')
                    ->leftJoin('salones as s', 's.id', '=', 'g.salon_id')
                    ->where('g.id', $id)
                    ->first();

            if ($g) {
                $grupo = new Grupo();                
                $grupo->setid($g->id);
                $grupo->setDia($g->dia);
                $grupo->setJornada($g->jornada);
                $grupo->setCupo($g->cupos);
                $grupo->setNombre($g->nombre);
                $grupo->setTotalInscritos($g->totalInscritos);
                $grupo->setBloqueado($g->bloqueado);
                $grupo->setCancelado($g->cancelado);
                $grupo->setCerradoParaInscripcion($g->cerrado_para_inscripcion);
                $grupo->setObservaciones($g->observaciones);
                $grupo->setHabilitadoParaPreInscripcion($g->habilitado_para_preinscripcion);
                
                $caledario = $calendarioDao->buscarCalendarioPorId($g->calendario_id);
                $orientador = $orientadorDao->buscarOrientadorPorId($g->orientador_id);

                $curso = $cursoDao->buscarCursoPorId($g->curso_id);

                $salon = new Salon();
                if (!is_null($g->salon_id)) {
                    $salon = $salonDao->buscarSalonPorId($g->salon_id);
                }

                $cursoCalendario = new CursoCalendario($caledario, $curso);
                $cursoCalendario->setId($g->curso_calendario_id);
                $cursoCalendario->setCosto($g->costo);
                $cursoCalendario->setModalidad($g->modalidad);

                $grupo->setCursoCalendario($cursoCalendario);
                $grupo->setOrientador($orientador);
                $grupo->setSalon($salon);
            }            
        } catch (\Exception $e) {         
            Sentry::captureException($e);
        }

        return $grupo;
    }

    public function crearGrupo(Grupo $grupo): bool {
        $exito = true;
        try {
            
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $nuevoGrupo = GrupoDao::create([
                'curso_calendario_id' => $grupo->getCursoCalendarioId(), 
                'salon_id' => ($grupo->getSalon()->getId() == 0 ? NULL : $grupo->getSalon()->getId()), 
                'orientador_id' => $grupo->getOrientador()->getId(), 
                'dia' => $grupo->getDia(), 
                'nombre' => null,
                'jornada' => $grupo->getJornada(),
                'cupos' => $grupo->getCupo(),                
                'calendario_id' => $grupo->getCalendarioId(),
                'bloqueado' => $grupo->estaBloqueado(),
                'observaciones' => $grupo->getObservaciones(),
                'habilitado_para_preinscripcion' => $grupo->estaHabilitadoParaPreInscripcion(),
            ]);

        } catch (\Exception $e) {               
            Sentry::captureException($e);
            return false;
        }

        $nuevoGrupo->nombre = "G" . $nuevoGrupo->id;
        $nuevoGrupo->save();

        return $exito;
    }

    public function eliminarGrupo(Grupo $grupo): bool {
        try {
            $exito = false;

            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $rs = GrupoDao::destroy($grupo->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito;
    }

    public function actualizarGrupo(Grupo $grupo): bool {
        try {            
            $exito = false;
            $rs = GrupoDao::find($grupo->getId());
            if ($rs) {            
                
                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");
                                
                $rs->update([
                    'salon_id' => ($grupo->getSalon()->getId() == 0 ? NULL : $grupo->getSalon()->getId()), 
                    'orientador_id' => $grupo->getOrientador()->getId(), 
                    'dia' => $grupo->getDia(), 
                    'jornada' => $grupo->getJornada(),
                    'cupos' => $grupo->getCupo(),
                    'calendario_id' => $grupo->getCalendarioId(),
                    'bloqueado' => $grupo->estaBloqueado(),
                    'curso_calendario_id' => $grupo->getCursoCalendarioId(),
                    'cerrado_para_inscripcion' => $grupo->estaCerradoParaInscripcion(),
                    'observaciones' => $grupo->getObservaciones(),
                    'habilitado_para_preinscripcion' => $grupo->estaHabilitadoParaPreInscripcion(),
                ]);                
                $exito = true;
            }
        } catch (\Exception $e) {            
            Sentry::captureException($e);
        }   
        return $exito; 
    }

    public function existeGrupo(Grupo $grupo): bool {
        $existe = false;
        try {
            $result = GrupoDao::where('salon_id', $grupo->getSalon()->getId())
                                ->where('orientador_id', $grupo->getOrientador()->getId())
                                ->where('jornada', $grupo->getJornada())
                                ->where('dia', $grupo->getDia())
                                ->first();
            if ($result)
                $existe = true;

        } catch(\Exception $e) {
            Sentry::captureException($e);
        }

        return $existe;
    }

    public function salonDisponible(Grupo $grupo): bool {
        $disponible = true;
        try {
            $result = GrupoDao::where('curso_calendario_id', $grupo->getCursoCalendarioId())
                                ->where('salon_id', $grupo->getSalon()->getId())                                
                                ->where('jornada', $grupo->getJornada())
                                ->where('dia', $grupo->getDia())
                                ->first();
            if ($result)
                $disponible = false;

        } catch(\Exception $e) {
            Sentry::captureException($e);
        }

        return $disponible;        
    }

    public function listarGruposDisponiblesParaMatricula(int $calendarioId, int $areaId): array {
        $grupos = array();
        try {

            $resultados =DB::table('grupos as g')
                        ->select(
                            'g.id as grupoId', 'c.id as cursoId', 'ca.id as calendarioId', 'ca.nombre as calendarioNombre',
                            'c.nombre as nombreCurso', 'g.dia', 'g.jornada', 'g.cupos', 'cc.costo', 'g.bloqueado', 'g.cancelado',
                            'cc.modalidad', 'g.nombre', 'o.nombre as orientadorNombre', 'g.habilitado_para_preinscripcion',
                            DB::raw('(select count(fi.grupo_id) from formulario_inscripcion fi where fi.grupo_id = g.id and fi.estado <> "Anulado" and fi.estado <> "Aplazado" and fi.estado <> "Devuelto") as totalInscritos')
                        )
                        ->join('curso_calendario as cc', function ($join) use ($calendarioId) {
                            $join->on('cc.id', '=', 'g.curso_calendario_id')
                                ->where('cc.calendario_id', '=', $calendarioId);
                        })
                        ->join('calendarios as ca', 'ca.id', '=', 'cc.calendario_id')
                        ->join('cursos as c', function ($join) use ($areaId) {
                            $join->on('c.id', '=', 'cc.curso_id')
                                ->where('c.area_id', '=', $areaId);
                        })
                        ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
                        // ->where('g.bloqueado', 0)
                        ->get();        

            foreach($resultados as $r) {
                $grupo = new Grupo();

                $grupo->setId($r->grupoId);
                $grupo->setNombre($r->nombre);
                $grupo->setHabilitadoParaPreInscripcion($r->habilitado_para_preinscripcion);
                
                $curso = new Curso($r->nombreCurso);
                $curso->setId($r->cursoId);

                $calendario = new Calendario();
                $calendario->setId($r->calendarioId);
                $calendario->setNombre($r->calendarioNombre);

                $cursoCalendario = new CursoCalendario($calendario, $curso, [
                                    'cupo' => 0, 
                                    'costo' => $r->costo, 
                                    'modalidad' => $r->modalidad]);

                $grupo->setCursoCalendario($cursoCalendario);
                $grupo->setDia($r->dia);
                $grupo->setJornada($r->jornada);
                $grupo->setTotalInscritos($r->totalInscritos);
                $grupo->setCupo($r->cupos);

                $orientador = new Orientador();
                $orientador->setNombre($r->orientadorNombre);
                $grupo->setOrientador($orientador);
                $grupo->setBloqueado($r->bloqueado);
                $grupo->setCancelado($r->cancelado);

                array_push($grupos, $grupo);
            }

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        return $grupos;
    }

    public static function buscadorGrupos(string $criterio, Calendario $calendario, $page=1): Paginate {
        
        $paginate = new Paginate($page, env('APP_PAGINADOR_NUM_ITEMS_GRUPOS'));

        $listaGrupos = array();
        $cursoDao = new CursoDao();
        $calendarioDao = new CalendarioDao();
        $orientadorDao = new OrientadorDao();
        $salonDao = new SalonDao();

        try {

            $query = DB::table('grupos as g')
                    ->select(
                        'g.id', 'g.dia', 'g.jornada', 'g.nombre', 'g.curso_calendario_id', 'g.cupos', 'g.bloqueado', 'g.cancelado', 'g.habilitado_para_preinscripcion',
                        'o.id as orientador_id', 'c.id as curso_id', 's.id as salon_id', 'ca.id as calendario_id', 'cc.modalidad',
                        DB::raw('(select count(fi.grupo_id) from formulario_inscripcion fi where fi.grupo_id = g.id and fi.estado <> "Anulado" and fi.estado <> "Aplazado" and fi.estado <> "Devuelto") as totalInscritos')
                    )
                    ->leftJoin('salones as s', 's.id', '=', 'g.salon_id')
                    ->leftJoin('orientadores as o', 'o.id', '=', 'g.orientador_id')
                    ->leftJoin('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
                    ->leftJoin('cursos as c', 'c.id', '=', 'cc.curso_id')
                    ->leftJoin('calendarios as ca', 'ca.id', '=', 'cc.calendario_id')
                    ->where('ca.id', $calendario->getId())
                    ->where(function ($query) use ($criterio) {
                        $campos = ['o.nombre', 's.nombre', 'ca.nombre', 'cc.modalidad', 'g.cupos', 'g.dia', 'g.jornada', 'c.nombre', 'g.nombre'];
                
                        foreach ($campos as $campo) {
                            $query->orWhere($campo, 'like', '%' . $criterio . '%');
                        }
                    });
        
            $totalRecords = $query->count();                    
            $grupos = $query->skip($paginate->Offset())->take($paginate->Limit())->orderByDesc('g.id')->get();

            foreach ($grupos as $g) {
                $grupo = new Grupo();                
                $grupo->setid($g->id);
                $grupo->setDia($g->dia);
                $grupo->setJornada($g->jornada);
                $grupo->setCupo($g->cupos);
                $grupo->setNombre($g->nombre);
                $grupo->setBloqueado($g->bloqueado);
                $grupo->setCancelado($g->cancelado);
                $grupo->setHabilitadoParaPreInscripcion($g->habilitado_para_preinscripcion);
                
                $caledario = $calendarioDao->buscarCalendarioPorId($g->calendario_id);
                $orientador = $orientadorDao->buscarOrientadorPorId($g->orientador_id);
                $curso = $cursoDao->buscarCursoPorId($g->curso_id);

                $salon = new Salon();
                if (!is_null($g->salon_id)) {
                    $salon = $salonDao->buscarSalonPorId($g->salon_id);
                }

                $cursoCalendario = new CursoCalendario($caledario, $curso);
                $cursoCalendario->setId($g->curso_calendario_id);
                $cursoCalendario->setModalidad($g->modalidad);

                $grupo->setCursoCalendario($cursoCalendario);
                $grupo->setOrientador($orientador);
                $grupo->setSalon($salon);
                $grupo->setTotalInscritos($g->totalInscritos);

                $listaGrupos[] = $grupo;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        $paginate->setTotalRecords($totalRecords);
        $paginate->setRecords($listaGrupos);

        return $paginate;
    }

    public function tieneCuposDisponibles($grupoId=0): bool {

        $result = GrupoDao::select(
            DB::raw("
                IF((cupos - (SELECT count(*) AS totalInscritos FROM formulario_inscripcion WHERE grupo_id = grupos.id AND estado <> 'Anulado' AND estado <> 'Aplazado')) > 0, 'SI', 'NO') as tieneCuposDisponibles
            ")
        )
        ->where('id', $grupoId)
        ->first();        

        return $result->tieneCuposDisponibles == "SI";
    }

    public static function totalGruposSinCupoDisponible($calendarioId=0): int {

        return GrupoDao::select(
            DB::raw("
                grupos.id,
                (cupos - (SELECT COUNT(*) 
                          FROM formulario_inscripcion 
                          WHERE grupo_id = grupos.id 
                            AND estado <> 'Anulado' 
                            AND estado <> 'Aplazado')) AS cuposDisponibles
            ")
        )
        ->where('calendario_id', $calendarioId)
        ->having('cuposDisponibles', '<=', 0) 
        ->count(); 

    }

    public static function listadoParticipantesGrupo($grupoId=0): array {
        $participantes = [];

        try {
            
            $items = DB::table('participantes as p')
            ->select([
                'fi.numero_formulario',
                DB::raw("CONCAT(p.primer_nombre, ' ', p.segundo_nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS nombre_participante"),
                DB::raw("CONCAT(p.tipo_documento, ' - ', p.documento) AS documento_participante"),
                'p.telefono',
                'p.email',
                DB::raw("IF(c.nombre IS NULL, 'N/A', c.nombre) as convenio"),
                DB::raw("IF(
                    fi.convenio_id IS NULL, 
                    IF(fi.estado='Pagado', 'Legalizado', 'No legalizado'), 
                    IF(c.es_cooperativa, 'Legalizado', 
                        IF(fi.estado='Pagado', 'Legalizado', 'No legalizado')
                    )
                ) as estadoInscripcion"),
                'g.nombre as grupo',
                'g.dia',
                'g.jornada',
                'cu.nombre as curso',
                'o.nombre as orientador',
                'ca.nombre as calendario',
                'fi.total_a_pagar',
                's.nombre as salon_nombre',
            ])
            ->join('formulario_inscripcion as fi', function($join) use ($grupoId) {
                $join->on('fi.participante_id', '=', 'p.id')
                     ->where('fi.grupo_id', '=', $grupoId);
            })
            ->join('grupos as g', 'g.id', '=', 'fi.grupo_id')
            ->leftJoin('salones as s', 's.id', '=', 'g.salon_id')
            ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
            ->join('calendarios as ca', 'ca.id', '=', 'g.calendario_id')
            ->join('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
            ->join('cursos as cu', 'cu.id', '=', 'cc.curso_id')
            ->leftJoin('convenios as c', 'c.id', '=', 'fi.convenio_id')
            ->where('fi.estado', '<>', 'Anulado')
            ->where('fi.estado', '<>', 'Aplazado')
            ->where('fi.estado', '<>', 'Devuelto')
            ->orderBy('p.primer_nombre')
            ->orderBy('p.primer_apellido')            
            ->get();
    

            
            $participantes[] = ['CURSO', 'INSTRUCTOR', 'GRUPO', 'DIA', 'JORNADA', 'PARTICIPANTE', 'DOCUMENTO', 'TELEFONO', 'CORREO_ELECTRONICO', 'CONVENIO', 'ESTADO', 'PERIODO', 'TOTAL_A_PAGAR', 'SALON'];
            foreach($items as $item) {    
                $nombre_salon = "";
                if (!is_null($item->salon_nombre)) {
                    $nombre_salon = $item->salon_nombre;
                }
                $participantes[] = [mb_strtoupper($item->curso, 'UTF-8'),
                                    mb_strtoupper($item->orientador, 'UTF-8'),
                                    $item->grupo, 
                                    mb_strtoupper($item->dia, 'UTF-8'), 
                                    mb_strtoupper($item->jornada, 'UTF-8'),                
                                    mb_strtoupper($item->nombre_participante, 'UTF-8'), 
                                    mb_strtoupper($item->documento_participante, 'UTF-8'), 
                                    $item->telefono, 
                                    mb_strtolower($item->email, 'UTF-8') , 
                                    mb_strtoupper($item->convenio, 'UTF-8'), 
                                    mb_strtoupper($item->estadoInscripcion, 'UTF-8'),
                                    mb_strtoupper($item->calendario, 'UTF-8'),
                                    FormatoMoneda::PesosColombianos($item->total_a_pagar),
                                    mb_strtoupper($nombre_salon, 'UTF-8'),
                                ];
            }


        } catch(\Exception $e) {
            Sentry::captureException($e);
        }
        
        return $participantes;        
    }

    public static function listadoParticipantesPlanillaAsistencia($grupoId=0): array {
        $participantes = [];

        try {
                $items = DB::table('participantes as p')
                ->select([
                    'fi.numero_formulario',
                    DB::raw("CONCAT(p.primer_nombre, ' ', p.segundo_nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS nombre_participante"),
                    DB::raw("CONCAT(p.tipo_documento, ' - ', p.documento) AS documento_participante"),
                    'p.telefono',
                    'p.email',
                    DB::raw("IF(c.nombre IS NULL, 'N/A', c.nombre) as convenio"),
                    DB::raw("IF(
                        fi.estado = 'Pagado', 
                        1, 
                        IF(
                            fi.estado = 'Pendiente de pago' AND fi.convenio_id IS NOT NULL AND c.es_cooperativa = 1, 
                            1, 
                            IF(
                                fi.estado = 'Pendiente de pago' AND fi.convenio_id IS NOT NULL AND c.es_cooperativa = 0, 
                                0, 
                                0
                            )
                        )
                    ) as estadoInscripcion"),
                    'g.nombre as grupo',
                    'g.dia',
                    'g.jornada',
                    'cu.nombre as curso',
                    'o.nombre as orientador',
                    'ca.nombre as calendario',
                    'c.nombre as nombre_convenio',
                    's.nombre as nombre_salon',
                    'a.nombre as nombre_area',
                ])
                ->join('formulario_inscripcion as fi', function($join) use ($grupoId) {
                    $join->on('fi.participante_id', '=', 'p.id')
                            ->where('fi.grupo_id', '=', $grupoId);
                })
                ->join('grupos as g', 'g.id', '=', 'fi.grupo_id')
                ->leftJoin('salones as s', 's.id', '=', 'g.salon_id')
                ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
                ->join('calendarios as ca', 'ca.id', '=', 'g.calendario_id')
                ->join('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
                ->join('cursos as cu', 'cu.id', '=', 'cc.curso_id')
                ->join('areas as a', 'a.id', '=', 'cu.area_id')
                ->leftJoin('convenios as c', 'c.id', '=', 'fi.convenio_id')
                ->having('estadoInscripcion', '=', 1)
                ->orderBy('p.primer_nombre')
                ->orderBy('p.primer_apellido')
                ->get();                                    
            
            $participantes[] = ['CURSO', 'INSTRUCTOR', 'GRUPO', 'DIA', 'JORNADA', 'PARTICIPANTE', 'DOCUMENTO', 'TELEFONO', 'CORREO_ELECTRONICO', 'CONVENIO', 'ESTADO', 'PERIODO', 'CONVENIO', 'SALON', 'AREA'];
            foreach($items as $item) {
                $nombreConvenio = mb_strtoupper($item->nombre_convenio, 'UTF-8');                  
                if ($item->nombre_convenio == "Convenio empleados y contratistas UnicolMayor") {
                    $nombreConvenio = "UCMC";
                }

                $nombre_salon = "";
                if (!is_null($item->nombre_salon)) {
                    $nombre_salon = $item->nombre_salon;
                }
                
                $participantes[] = [mb_strtoupper($item->curso, 'UTF-8'),
                                    mb_strtoupper($item->orientador, 'UTF-8'),
                                    $item->grupo, 
                                    mb_strtoupper($item->dia, 'UTF-8'), 
                                    mb_strtoupper($item->jornada, 'UTF-8'),                
                                    mb_strtoupper($item->nombre_participante, 'UTF-8'), 
                                    mb_strtoupper($item->documento_participante, 'UTF-8'), 
                                    $item->telefono, 
                                    mb_strtoupper($item->email, 'UTF-8'), 
                                    mb_strtoupper($item->convenio, 'UTF-8'), 
                                    mb_strtoupper($item->estadoInscripcion, 'UTF-8'),
                                    mb_strtoupper($item->calendario, 'UTF-8'),                                    
                                    $nombreConvenio,
                                    mb_strtoupper($nombre_salon, 'UTF-8'),
                                    mb_strtoupper($item->nombre_area, 'UTF-8')                                    
                                ];
            }

        } catch(\Exception $e) {
            Sentry::captureException($e);
        }

        return $participantes;   
    }


    public static function listadoParticipantesParaTomarAsistencia($grupoId=0): array {
        $participantes = [];

        try {
                $items = DB::table('participantes as p')
                ->select([
                    'fi.numero_formulario',
                    DB::raw("CONCAT(p.primer_nombre, ' ', p.segundo_nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS nombre_participante"),
                    DB::raw("CONCAT(p.tipo_documento, ' - ', p.documento) AS documento_participante"),
                    'p.telefono',
                    'p.email',
                    DB::raw("IF(c.nombre IS NULL, 'N/A', c.nombre) as convenio"),
                    DB::raw("IF(
                        fi.estado = 'Pagado', 
                        1, 
                        IF(
                            fi.estado = 'Pendiente de pago' AND fi.convenio_id IS NOT NULL AND c.es_cooperativa = 1, 
                            1, 
                            IF(
                                fi.estado = 'Pendiente de pago' AND fi.convenio_id IS NOT NULL AND c.es_cooperativa = 0, 
                                0, 
                                0
                            )
                        )
                    ) as estadoInscripcion"),
                    'g.nombre as grupo',
                    'g.dia',
                    'g.jornada',
                    'cu.nombre as curso',
                    'o.nombre as orientador',
                    'ca.nombre as calendario',
                    'c.nombre as nombre_convenio',
                    's.nombre as nombre_salon',
                    'a.nombre as nombre_area',
                    'p.id as participante_id',
                ])
                ->join('formulario_inscripcion as fi', function($join) use ($grupoId) {
                    $join->on('fi.participante_id', '=', 'p.id')
                            ->where('fi.grupo_id', '=', $grupoId);
                })
                ->join('grupos as g', 'g.id', '=', 'fi.grupo_id')
                ->leftJoin('salones as s', 's.id', '=', 'g.salon_id')
                ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
                ->join('calendarios as ca', 'ca.id', '=', 'g.calendario_id')
                ->join('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
                ->join('cursos as cu', 'cu.id', '=', 'cc.curso_id')
                ->join('areas as a', 'a.id', '=', 'cu.area_id')
                ->leftJoin('convenios as c', 'c.id', '=', 'fi.convenio_id')
                ->having('estadoInscripcion', '=', 1)
                ->orderBy('p.primer_nombre')
                ->orderBy('p.primer_apellido')
                ->get();                                    
            
            $participantes[] = ['CURSO', 'INSTRUCTOR', 'GRUPO', 'DIA', 'JORNADA', 'PARTICIPANTE', 'DOCUMENTO', 'TELEFONO', 'CORREO_ELECTRONICO', 'CONVENIO', 'ESTADO', 'PERIODO', 'CONVENIO', 'SALON', 'AREA'];
            foreach($items as $item) {
                $nombreConvenio = mb_strtoupper($item->nombre_convenio, 'UTF-8');                  
                if ($item->nombre_convenio == "Convenio empleados y contratistas UnicolMayor") {
                    $nombreConvenio = "UCMC";
                }

                $nombre_salon = "";
                if (!is_null($item->nombre_salon)) {
                    $nombre_salon = $item->nombre_salon;
                }
                
                $participantes[] = [mb_strtoupper($item->curso, 'UTF-8'),
                                    mb_strtoupper($item->orientador, 'UTF-8'),
                                    $item->grupo, 
                                    mb_strtoupper($item->dia, 'UTF-8'), 
                                    mb_strtoupper($item->jornada, 'UTF-8'),                
                                    mb_strtoupper($item->nombre_participante, 'UTF-8'), 
                                    mb_strtoupper($item->documento_participante, 'UTF-8'), 
                                    $item->telefono, 
                                    mb_strtoupper($item->email, 'UTF-8'), 
                                    mb_strtoupper($item->convenio, 'UTF-8'), 
                                    mb_strtoupper($item->estadoInscripcion, 'UTF-8'),
                                    mb_strtoupper($item->calendario, 'UTF-8'),                                    
                                    $nombreConvenio,
                                    mb_strtoupper($nombre_salon, 'UTF-8'),
                                    mb_strtoupper($item->nombre_area, 'UTF-8'),
                                    $item->participante_id,
                                ];
            }

        } catch(\Exception $e) {
            Sentry::captureException($e);
        }

        return $participantes;   
    }

    public function cancelarGrupo($grupoId=0): bool {
        try {            
            $grupo = GrupoDao::find($grupoId);

            if ($grupo) {                            
                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");
                                
                $grupo->update(['cancelado' => true]);                
            }

        } catch (\Exception $e) {     
            Sentry::captureException($e);
            return false;
        } 

        return true; 
    }

    public static function restriccionesParaCrearOActualizarUnGrupo(Grupo $grupo, Calendario $calendario): string 
    {
        $cruce = GrupoDao::where('dia', $grupo->getDia())
            ->where('jornada', $grupo->getJornada())
            ->where('cerrado_para_inscripcion', false)
            ->where('cancelado', false)
            ->where('calendario_id', $calendario->getId())
            ->where(function($query) use ($grupo) {
                $query->where(function($q) use ($grupo) {
                    // Conflicto por salón
                    $q->where('salon_id', $grupo->getSalonId());
                })
                ->orWhere(function($q) use ($grupo) {
                    // Conflicto por orientador
                    $q->where('orientador_id', $grupo->getOrientadorId());
                });
            })
            ->first();

        if ($cruce) {
            if ($cruce->salon_id == $grupo->getSalonId()) {
                return 'Conflicto de salón';
            } elseif ($cruce->orientador_id == $grupo->getOrientadorId()) {
                return 'Conflicto de orientador';
            }
        }

        return 'OK';
    }

    public function totalDeParticipantesPendienteDePagoSinConvenio(int $grupoId): int {
        $total = 0;

        try {

            $total = DB::table('formulario_inscripcion as fi')
            ->leftJoin('convenios as c', 'fi.convenio_id', '=', 'c.id')
            ->where('fi.grupo_id', $grupoId)
            ->where(function ($query) {
                $query->where(function ($subquery) {
                    $subquery->whereNull('fi.convenio_id')
                             ->where('fi.estado', 'Pendiente de pago');
                })
                ->orWhere(function ($subquery) {
                    $subquery->whereNotNull('fi.convenio_id')
                             ->where('fi.estado', 'Pendiente de pago')
                             ->where('c.es_cooperativa', false);
                });
            })
            ->count();

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        return $total;
    }

    public function participantesPendienteDePagoSinConvenio(int $grupoId): array
    {
        $participantes = [];

        try {
            
            $registros = DB::table('formulario_inscripcion as fi')
            ->leftJoin('convenios as c', 'fi.convenio_id', '=', 'c.id')
            ->join('participantes as p', 'fi.participante_id', '=', 'p.id')
            ->where('fi.grupo_id', $grupoId)
            ->where(function ($query) {
                $query->where(function ($subquery) {
                    $subquery->whereNull('fi.convenio_id')
                             ->where('fi.estado', 'Pendiente de pago');
                })
                ->orWhere(function ($subquery) {
                    $subquery->whereNotNull('fi.convenio_id')
                             ->where('fi.estado', 'Pendiente de pago')
                             ->where('c.es_cooperativa', false);
                });
            })
            ->select(
                'p.primer_nombre',
                'p.segundo_nombre',
                'p.primer_apellido',
                'p.segundo_apellido',
                'p.tipo_documento',
                'p.documento',
                'p.telefono',
                'p.email',
                'fi.created_at',
                'fi.fecha_max_legalizacion',
                'fi.numero_formulario'
            )
            ->get();

            foreach($registros as $registro) {
                $participante = new Participante();
                $participante->setPrimerNombre($registro->primer_nombre);
                $participante->setSegundoNombre($registro->segundo_nombre);
                $participante->setPrimerApellido($registro->primer_apellido);
                $participante->setSegundoApellido($registro->segundo_apellido);
                $participante->setTipoDocumento($registro->tipo_documento);
                $participante->setDocumento($registro->documento);
                $participante->setTelefono($registro->telefono);
                $participante->setEmail($registro->email);

                $formulario = new FormularioInscripcion();
                $formulario->setNumero($registro->numero_formulario);
                $formulario->setFechaCreacion($registro->created_at);
                $formulario->setFechaMaxLegalizacion($registro->fecha_max_legalizacion);

                $participante->setFormularioInscripcion($formulario);

                $participantes[] = $participante;
            }
        

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }        
        
        return $participantes;
    }

    public function registrarAsistenciaAClase(int $grupoID, AsistenciaClase $asistencia): bool
    {
        try {
            DB::table('asistencia_clase')->insert([
                'grupo_id'               => $grupoID,
                'participante_id'        => $asistencia->getParticipante()->getId(),
                'sesion'                 => $asistencia->getSesion(),
                'presente'               => $asistencia->estaPresente(),
                'orientador_que_registra'=> Auth::id(), 
                'created_at'             => now(),
            ]);
        } catch (\Exception $e) {
            Sentry::captureException($e);
            return false;
        }
    
        return true;
    }
    
    public function obtenerAsistenciaAClase(int $grupoID): array
    {
        $registros = DB::table('asistencia_clase')
            ->join('participantes', 'asistencia_clase.participante_id', '=', 'participantes.id')
            ->where('asistencia_clase.grupo_id', $grupoID)
            ->select(
                'asistencia_clase.sesion',
                'asistencia_clase.presente',
                'participantes.id as participante_id',
                'asistencia_clase.created_at',
            )
            ->get();
    
        $asistencias = [];
    
        foreach ($registros as $registro) {
            $participante = (new ParticipanteDao())->buscarParticipantePorId($registro->participante_id);    
            $asistencias[] = new AsistenciaClase(
                $participante,
                (int) $registro->sesion,
                (bool) $registro->presente,
                (string) $registro->created_at,
            );
        }
    
        return $asistencias;
    }
    
    public function obtenerAsistenciaAClaseParticipante(int $grupoID, int $participanteID): array
    {
        $registros = DB::table('asistencia_clase')
            ->join('participantes', 'asistencia_clase.participante_id', '=', 'participantes.id')
            ->where('asistencia_clase.grupo_id', $grupoID)
            ->where('asistencia_clase.participante_id', $participanteID)
            ->select(
                'asistencia_clase.sesion',
                'asistencia_clase.presente',
                'participantes.id as participante_id',
            )
            ->get();
    
        $asistencias = [];
    
        foreach ($registros as $registro) {
            $participante = (new ParticipanteDao())->buscarParticipantePorId($registro->participante_id);   
    
            $asistencias[] = new AsistenciaClase(
                $participante,
                (int) $registro->sesion,
                (bool) $registro->presente
            );
        }
    
        return $asistencias;
    }
    
    public function buscarAsistenciaPorSesion(int $grupoID, int $sesion): bool
    {
        return DB::table('asistencia_clase')
            ->where('grupo_id', $grupoID)
            ->where('sesion', $sesion)
            ->exists();
    }      

    public function obtenerLaUltimaAsistenciaRegistrada(int $grupoID): int
    {
        return (int) DB::table('asistencia_clase')
            ->where('grupo_id', $grupoID)
            ->max('sesion') ?? 0;
    }

    /**
     * Lista grupos del orientador en un periodo (sin datos pesados).
     * Retorna arreglo con: id, codigo_grupo, dia, jornada, salon, nombre_curso, area
     */
    public function listarGruposPorPeriodoYOrientador(int $periodoId, int $orientadorId): array
    {
        return DB::table('grupos as g')
            ->join('salones as s','s.id','=','g.salon_id')
            ->join('curso_calendario as cc','cc.id','=','g.curso_calendario_id')
            ->join('cursos as c','c.id','=','cc.curso_id')
            ->join('areas as a','a.id','=','c.area_id')
            ->where('g.calendario_id', $periodoId)
            ->where('g.orientador_id', $orientadorId)
            ->where('g.cancelado', 0)
            ->orderBy('g.id','desc')
            ->get([
                'g.id',
                'g.nombre as codigo_grupo',
                'g.dia',
                'g.jornada',
                's.nombre as salon',
                'c.nombre as nombre_curso',
                'a.nombre as area',
            ])
            ->map(fn($r) => [
                'id'           => (int)$r->id,
                'codigo_grupo' => $r->codigo_grupo,
                'dia'          => $r->dia,
                'jornada'      => $r->jornada,
                'salon'        => $r->salon,
                'nombre_curso' => $r->nombre_curso,
                'area'         => $r->area,
            ])
            ->toArray();
    }  
    
    /**
     * Lista sesiones reales registradas para un grupo.
     * Retorna [{ num, fecha }]
     */
    public function listarSesionesDeGrupo(int $grupoId): array
    {
        return DB::table('asistencia_clase as ac')
            ->where('ac.grupo_id', $grupoId)
            ->select('ac.sesion', DB::raw('MIN(ac.created_at) as fecha'))
            ->groupBy('ac.sesion')
            ->orderBy('ac.sesion')
            ->get()
            ->map(fn($r) => [
                'num'   => (int)$r->sesion,
                'fecha' => optional(\Illuminate\Support\Carbon::parse($r->fecha))->format('Y-m-d'),
            ])
            ->toArray();
    }
    
    /**
     * Encabezado/meta del grupo (curso, jornada, salón, día, área).
     * Retorna null si no existe.
     */
    public function obtenerMetaDeGrupo(int $grupoId): ?array
    {
        $g = DB::table('grupos as gr')
            ->join('salones as s','s.id','=','gr.salon_id')
            ->join('curso_calendario as cc','cc.id','=','gr.curso_calendario_id')
            ->join('cursos as c','c.id','=','cc.curso_id')
            ->join('areas as a','a.id','=','c.area_id')
            ->join('orientadores as o','o.id','=','gr.orientador_id')
            ->join('calendarios as ca','ca.id','=','cc.calendario_id')
            ->where('gr.id', $grupoId)
            ->first([
                'gr.id',
                'gr.dia',
                'gr.jornada',
                's.nombre as salon',
                'c.nombre as nombre_curso',
                'a.nombre as area',
                'o.nombre as orientador',
                'ca.nombre as calendario',
            ]);

        if (!$g) return null;

        return [
            'id'           => (int) $g->id,
            'dia'          => mb_convert_case($g->dia ?? '', MB_CASE_TITLE, "UTF-8"),
            'jornada'      => mb_convert_case($g->jornada ?? '', MB_CASE_TITLE, "UTF-8"),
            'salon'        => mb_convert_case($g->salon ?? '', MB_CASE_TITLE, "UTF-8"),
            'nombre_curso' => mb_convert_case($g->nombre_curso ?? '', MB_CASE_TITLE, "UTF-8"),
            'area'         => mb_convert_case($g->area ?? '', MB_CASE_TITLE, "UTF-8"),
            'orientador'   => mb_convert_case($g->orientador ?? '', MB_CASE_TITLE, "UTF-8"),
            'calendario'   => mb_convert_case($g->calendario ?? '', MB_CASE_TITLE, "UTF-8"),
        ];
    }
    
    /**
     * Detalle de asistencia por sesión (convenio incluido vía LEFT JOIN).
     * Retorna ['meta'=>..., 'participantes'=>[...]]
     */
    public function obtenerAsistenciaPorSesionDetalle(int $grupoId, int $sesion): array
    {
        $meta = $this->obtenerMetaDeGrupo($grupoId);

        $acUlt = DB::table('asistencia_clase as ac')
            ->where('ac.grupo_id', $grupoId)
            ->where('ac.sesion', $sesion)
            ->select('ac.participante_id', DB::raw('MAX(ac.id) as ac_id'))
            ->groupBy('ac.participante_id');

        $fiUlt = DB::table('formulario_inscripcion as fi')
            ->where('fi.grupo_id', $grupoId)
            ->select('fi.participante_id', DB::raw('MAX(fi.id) as fi_id'))
            ->groupBy('fi.participante_id');

        $filas = DB::query()
            ->fromSub($acUlt, 'acu')
            ->join('asistencia_clase as ac', 'ac.id', '=', 'acu.ac_id') 
            ->join('participantes as p', 'p.id', '=', 'ac.participante_id')
            ->leftJoinSub($fiUlt, 'fiu', 'fiu.participante_id', '=', 'p.id')
            ->leftJoin('formulario_inscripcion as fi', 'fi.id', '=', 'fiu.fi_id')
            ->leftJoin('convenios as cv', 'cv.id', '=', 'fi.convenio_id')
            ->orderBy('p.primer_apellido')
            ->get([
                'p.primer_nombre',
                'p.segundo_nombre',
                'p.primer_apellido',
                'p.segundo_apellido',
                'p.documento as doc',
                'cv.nombre as convenio',
                'ac.presente',
                'ac.created_at as fecha_registro',
            ]);

        $participantes = [];
        $fechaCabecera = null;

        foreach ($filas as $r) {
            $nombre = trim(implode(' ', array_filter([
                $r->primer_nombre,
                $r->segundo_nombre,
                $r->primer_apellido,
                $r->segundo_apellido,
            ])));

            $fecha = optional(\Illuminate\Support\Carbon::parse($r->fecha_registro))->format('Y-m-d');
            $fechaCabecera = $fechaCabecera ?? $fecha;

            $participantes[] = [
                'nombre'   => mb_strtoupper($nombre, 'utf-8'),
                'doc'      => $r->doc,
                'convenio' => $r->convenio ?? '',
                'presente' => (bool) $r->presente,
                'fecha'    => $fecha,
            ];
        }

        if ($meta) {
            $meta['fecha'] = $fechaCabecera;
        }

        return [
            'meta'          => $meta,
            'participantes' => $participantes,
        ];
    }
  
    public function obtenerUltimasAsistenciasParaMatriz(int $grupoId): array
    {
        $acUlt = DB::table('asistencia_clase as ac')
            ->where('ac.grupo_id', $grupoId)
            ->select('ac.sesion', 'ac.participante_id', DB::raw('MAX(ac.id) as ac_id'))
            ->groupBy('ac.sesion', 'ac.participante_id');

        $rows = DB::query()
            ->fromSub($acUlt, 'acu')
            ->join('asistencia_clase as ac', 'ac.id', '=', 'acu.ac_id') 
            ->join('participantes as p', 'p.id', '=', 'ac.participante_id')
            ->leftJoin('formulario_inscripcion as fi', function ($j) use ($grupoId) {
                $j->on('fi.participante_id', '=', 'p.id')
                ->where('fi.grupo_id', '=', $grupoId);
            })
            ->leftJoin('convenios as cv', 'cv.id', '=', 'fi.convenio_id')
            ->orderBy('p.primer_apellido')
            ->get([
                'acu.sesion as sesion_num',
                'p.primer_nombre','p.segundo_nombre','p.primer_apellido','p.segundo_apellido',
                'p.documento as doc',           
                'cv.nombre as convenio',
                'ac.presente',
                DB::raw("DATE_FORMAT(ac.created_at, '%Y-%m-%d') as fecha_registro"),
            ]);
            
        return array_map(fn($r) => [
            'sesion_num'     => (int)$r->sesion_num,
            'primer_nombre'  => $r->primer_nombre,
            'segundo_nombre' => $r->segundo_nombre,
            'primer_apellido'=> $r->primer_apellido,
            'segundo_apellido'=> $r->segundo_apellido,
            'doc'            => $r->doc,
            'convenio'       => $r->convenio,
            'presente'       => (bool)$r->presente,
            'fecha_registro' => $r->fecha_registro,
        ], $rows->all());
    }


    /**
     * Lista grupos del orientador en un periodo (sin datos pesados).
     * Retorna arreglo con: id, codigo_grupo, dia, jornada, salon, nombre_curso, area
     */
    public function listarGruposPorPeriodo(int $periodoId): array
    {
        return DB::table('grupos as g')
            ->join('salones as s','s.id','=','g.salon_id')
            ->join('curso_calendario as cc','cc.id','=','g.curso_calendario_id')
            ->join('cursos as c','c.id','=','cc.curso_id')
            ->join('areas as a','a.id','=','c.area_id')
            ->where('g.calendario_id', $periodoId)
            ->where('g.cancelado', 0)
            ->orderBy('g.id','desc')
            ->get([
                'g.id',
                'g.nombre as codigo_grupo',
                'g.dia',
                'g.jornada',
                's.nombre as salon',
                'c.nombre as nombre_curso',
                'a.nombre as area',
            ])
            ->map(fn($r) => [
                'id'           => (int)$r->id,
                'codigo_grupo' => $r->codigo_grupo,
                'dia'          => $r->dia,
                'jornada'      => $r->jornada,
                'salon'        => $r->salon,
                'nombre_curso' => $r->nombre_curso,
                'area'         => $r->area,
            ])
            ->toArray();
    }  

    
}