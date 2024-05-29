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
use Src\domain\Orientador;
use Src\infraestructure\util\Paginate;

class GrupoDao extends Model implements GrupoRepository {
    protected $table = 'grupos';
    protected $fillable = ['curso_calendario_id', 'salon_id', 'orientador_id', 'dia', 'jornada', 'cupos', 'nombre', 'calendario_id', 'bloqueado'];


    public function formulariosInscripcion() {
        return $this->hasMany(FormularioInscripcionDao::class, 'grupo_id');
    }

    public static function listarGrupos($page=1): Paginate {
        
        $paginate = new Paginate($page, env('APP_PAGINADOR_NUM_ITEMS_GRUPOS'));

        $listaGrupos = array();
        $cursoDao = new CursoDao();
        $calendarioDao = new CalendarioDao();
        $orientadorDao = new OrientadorDao();
        $salonDao = new SalonDao();

        try {

            $query = DB::table('grupos as g')
                        ->select('g.id', 'g.dia', 'g.jornada', 'g.curso_calendario_id', 'g.cupos', 'g.nombre', 'g.bloqueado', 
                                'o.id as orientador_id', 'c.id as curso_id', 's.id as salon_id', 'ca.id as calendario_id',
                                DB::raw('(select count(fi.grupo_id) from formulario_inscripcion fi where fi.grupo_id = g.id and fi.estado <> "Anulado") as totalInscritos')
                                )
                        ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
                        ->join('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
                        ->join('calendarios as ca', 'ca.id', '=', 'cc.calendario_id')
                        ->join('cursos as c', 'c.id', '=', 'cc.curso_id')
                        ->join('salones as s', 's.id', '=', 'g.salon_id')
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
                
                $caledario = $calendarioDao->buscarCalendarioPorId($g->calendario_id);
                if (!$caledario->esVigente()) {
                    continue;
                }
                
                $orientador = $orientadorDao->buscarOrientadorPorId($g->orientador_id);
                $curso = $cursoDao->buscarCursoPorId($g->curso_id);
                $salon = $salonDao->buscarSalonPorId($g->salon_id);

                $cursoCalendario = new CursoCalendario($caledario, $curso);
                $cursoCalendario->setId($g->curso_calendario_id);

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
                    ->select('g.id', 'g.dia', 'g.jornada', 'g.curso_calendario_id', 'g.cupos', 'g.nombre', 'g.bloqueado', 
                            'o.id as orientador_id', 'c.id as curso_id', 's.id as salon_id', 'ca.id as calendario_id', 'cc.costo', 'cc.modalidad',
                            DB::raw('(select count(fi.grupo_id) from formulario_inscripcion fi where fi.grupo_id = g.id and fi.estado <> "Anulado") as totalInscritos')                          
                            )
                    ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
                    ->join('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
                    ->join('calendarios as ca', 'ca.id', '=', 'cc.calendario_id')
                    ->join('cursos as c', 'c.id', '=', 'cc.curso_id')
                    ->join('salones as s', 's.id', '=', 'g.salon_id')
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
                
                $caledario = $calendarioDao->buscarCalendarioPorId($g->calendario_id);
                $orientador = $orientadorDao->buscarOrientadorPorId($g->orientador_id);
                $curso = $cursoDao->buscarCursoPorId($g->curso_id);
                $salon = $salonDao->buscarSalonPorId($g->salon_id);

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
                'salon_id' => $grupo->getSalon()->getId(), 
                'orientador_id' => $grupo->getOrientador()->getId(), 
                'dia' => $grupo->getDia(), 
                'nombre' => null,
                'jornada' => $grupo->getJornada(),
                'cupos' => $grupo->getCupo(),                
                'calendario_id' => $grupo->getCalendarioId(),
                'bloqueado' => $grupo->estaBloqueado(),
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
                    'salon_id' => $grupo->getSalon()->getId(), 
                    'orientador_id' => $grupo->getOrientador()->getId(), 
                    'dia' => $grupo->getDia(), 
                    'jornada' => $grupo->getJornada(),
                    'cupos' => $grupo->getCupo(),
                    'calendario_id' => $grupo->getCalendarioId(),
                    'bloqueado' => $grupo->estaBloqueado(),
                    'curso_calendario_id' => $grupo->getCursoCalendarioId(),
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
                            'c.nombre as nombreCurso', 'g.dia', 'g.jornada', 'g.cupos', 'cc.costo',
                            'cc.modalidad', 'g.nombre', 'o.nombre as orientadorNombre',
                            DB::raw('(select count(fi.grupo_id) from formulario_inscripcion fi where fi.grupo_id = g.id and fi.estado <> "Anulado") as totalInscritos')
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
                        ->where('g.bloqueado', 0)
                        ->get();        

            foreach($resultados as $r) {
                $grupo = new Grupo();

                $grupo->setId($r->grupoId);
                $grupo->setNombre($r->nombre);
                
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
                // $grupo->setHora($r->hora);

                array_push($grupos, $grupo);
            }

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        return $grupos;
    }

    public static function buscadorGrupos(string $criterio, $page=1): Paginate {
        
        $paginate = new Paginate($page, env('APP_PAGINADOR_NUM_ITEMS_GRUPOS'));

        $listaGrupos = array();
        $cursoDao = new CursoDao();
        $calendarioDao = new CalendarioDao();
        $orientadorDao = new OrientadorDao();
        $salonDao = new SalonDao();

        try {

            $query = DB::table('grupos as g')
                    ->select(
                        'g.id', 'g.dia', 'g.jornada', 'g.nombre', 'g.curso_calendario_id', 'g.cupos',
                        'o.id as orientador_id', 'c.id as curso_id', 's.id as salon_id', 'ca.id as calendario_id',
                        DB::raw('(select count(fi.grupo_id) from formulario_inscripcion fi where fi.grupo_id = g.id and fi.estado <> "Anulado") as totalInscritos')
                    )
                    ->leftJoin('salones as s', 's.id', '=', 'g.salon_id')
                    ->leftJoin('orientadores as o', 'o.id', '=', 'g.orientador_id')
                    ->leftJoin('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
                    ->leftJoin('cursos as c', 'c.id', '=', 'cc.curso_id')
                    ->leftJoin('calendarios as ca', 'ca.id', '=', 'cc.calendario_id')
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
                
                $caledario = $calendarioDao->buscarCalendarioPorId($g->calendario_id);
                $orientador = $orientadorDao->buscarOrientadorPorId($g->orientador_id);
                $curso = $cursoDao->buscarCursoPorId($g->curso_id);
                $salon = $salonDao->buscarSalonPorId($g->salon_id);

                $cursoCalendario = new CursoCalendario($caledario, $curso);
                $cursoCalendario->setId($g->curso_calendario_id);

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
                IF((cupos - (SELECT count(*) AS totalInscritos FROM formulario_inscripcion WHERE grupo_id = grupos.id AND estado <> 'Anulado')) > 0, 'SI', 'NO') as tieneCuposDisponibles
            ")
        )
        ->where('id', $grupoId)
        ->first();        

        return $result->tieneCuposDisponibles == "SI";
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
                'ca.nombre as calendario'
            ])
            ->join('formulario_inscripcion as fi', function($join) use ($grupoId) {
                $join->on('fi.participante_id', '=', 'p.id')
                     ->where('fi.grupo_id', '=', $grupoId);
            })
            ->join('grupos as g', 'g.id', '=', 'fi.grupo_id')
            ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
            ->join('calendarios as ca', 'ca.id', '=', 'g.calendario_id')
            ->join('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
            ->join('cursos as cu', 'cu.id', '=', 'cc.curso_id')
            ->leftJoin('convenios as c', 'c.id', '=', 'fi.convenio_id')
            ->orderBy('p.primer_nombre')
            ->orderBy('p.primer_apellido')
            ->get();
    

            
            $participantes[] = ['CURSO', 'ORIENTADOR', 'GRUPO', 'DIA', 'JORNADA', 'PARTICIPANTE', 'DOCUMENTO', 'TELEFONO', 'CORREO_ELECTRONICO', 'CONVENIO', 'ESTADO', 'PERIODO'];
            foreach($items as $item) {                        
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
                                    mb_strtoupper($item->calendario, 'UTF-8')];
            }


        } catch(\Exception $e) {
            dd($e->getMessage());
            Sentry::captureException($e);
        }
        
        return $participantes;        
    }
}