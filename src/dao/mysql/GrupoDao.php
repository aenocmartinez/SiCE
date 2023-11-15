<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Src\domain\Calendario;
use Src\domain\Curso;
use Src\domain\CursoCalendario;
use Src\domain\Grupo;
use Src\domain\repositories\GrupoRepository;

class GrupoDao extends Model implements GrupoRepository {
    protected $table = 'grupos';
    protected $fillable = ['curso_calendario_id', 'salon_id', 'orientador_id', 'dia', 'jornada', 'cupos', 'calendario_id'];


    public function formulariosInscripcion() {
        return $this->hasMany(FormularioInscripcionDao::class, 'grupo_id');
    }

    public function listarGrupos(): array {
        $listaGrupos = array();
        $cursoDao = new CursoDao();
        $calendarioDao = new CalendarioDao();
        $orientadorDao = new OrientadorDao();
        $salonDao = new SalonDao();

        try {
            $grupos = DB::table('grupos as g')
                        ->select('g.id', 'g.dia', 'g.jornada', 'g.curso_calendario_id', 'g.cupos',
                                'o.id as orientador_id', 'c.id as curso_id', 's.id as salon_id', 'ca.id as calendario_id',
                                DB::raw('(select count(fi.grupo_id) from formulario_inscripcion fi where fi.grupo_id = g.id) as totalInscritos')
                                )
                        ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
                        ->join('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
                        ->join('calendarios as ca', 'ca.id', '=', 'cc.calendario_id')
                        ->join('cursos as c', 'c.id', '=', 'cc.curso_id')
                        ->join('salones as s', 's.id', '=', 'g.salon_id')
                        ->orderByDesc('g.id')
                        ->get();

            foreach ($grupos as $g) {
                $grupo = new Grupo();                
                $grupo->setid($g->id);
                $grupo->setDia($g->dia);
                $grupo->setJornada($g->jornada);
                $grupo->setCupo($g->cupos);
                
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

                array_push($listaGrupos, $grupo);
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }

        return $listaGrupos;
    }

    public function buscarGrupoPorId(int $id): Grupo {
        $grupo = new Grupo();
        $cursoDao = new CursoDao();
        $calendarioDao = new CalendarioDao();
        $orientadorDao = new OrientadorDao();
        $salonDao = new SalonDao();

        try {
            $g = DB::table('grupos as g')
                    ->select('g.id', 'g.dia', 'g.jornada', 'g.curso_calendario_id', 'g.cupos',
                            'o.id as orientador_id', 'c.id as curso_id', 's.id as salon_id', 'ca.id as calendario_id', 'cc.costo', 'cc.modalidad'                          
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
            $e->getMessage();
        }

        return $grupo;
    }
    
    public function buscadorGrupo(string $criterio): array {
        $grupos = array();
        return $grupos;
    }

    public function crearGrupo(Grupo $grupo): bool {
        $exito = true;
        try {
            $result = GrupoDao::create([
                'curso_calendario_id' => $grupo->getCursoCalendarioId(), 
                'salon_id' => $grupo->getSalon()->getId(), 
                'orientador_id' => $grupo->getOrientador()->getId(), 
                'dia' => $grupo->getDia(), 
                'jornada' => $grupo->getJornada(),
                'cupos' => $grupo->getCupo(),
                'calendario_id' => $grupo->getCalendarioId()
            ]);

        } catch (\Exception $e) {
            $exito = false;
            $e->getMessage();
        }   

        return $exito;
    }

    public function eliminarGrupo(Grupo $grupo): bool {
        try {
            $exito = false;
            $rs = GrupoDao::destroy($grupo->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }   
        return $exito;
    }

    public function actualizarGrupo(Grupo $grupo): bool {
        try {
            $exito = false;
            $rs = GrupoDao::find($grupo->getId());
            if ($rs) {
                $rs->update([
                    'salon_id' => $grupo->getSalon()->getId(), 
                    'orientador_id' => $grupo->getOrientador()->getId(), 
                    'dia' => $grupo->getDia(), 
                    'jornada' => $grupo->getJornada(),
                    'cupos' => $grupo->getCupo(),
                    'calendario_id' => $grupo->getCalendarioId()
                ]);                
                $exito = true;
            }
        } catch (\Exception $e) {
            $e->getMessage();
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
            $e->getMessage();
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
            $e->getMessage();
        }

        return $disponible;        
    }

    public function listarGruposDisponiblesParaMatricula(int $calendarioId, int $areaId): array {
        $grupos = array();
        try {
            $resultados = DB::table('grupos as g')
                        ->select(
                            'g.id as grupoId',
                            'c.id as cursoId',
                            'ca.id as calendarioId',
                            'ca.nombre as calendarioNombre',
                            'c.nombre as nombreCurso',
                            'g.dia',
                            'g.jornada',
                            'g.cupos',
                            'cc.costo',
                            'cc.modalidad',
                            DB::raw('(select count(fi.grupo_id) from formulario_inscripcion fi where fi.grupo_id = g.id) as totalInscritos')
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
                        ->get();
        

            foreach($resultados as $r) {
                $grupo = new Grupo();

                $grupo->setId($r->grupoId);
                
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

                array_push($grupos, $grupo);
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return $grupos;
    }

    public function buscadorGrupos(string $criterio): array {
        $listaGrupos = array();
        $cursoDao = new CursoDao();
        $calendarioDao = new CalendarioDao();
        $orientadorDao = new OrientadorDao();
        $salonDao = new SalonDao();

        try {

            $grupos = DB::table('grupos as g')
                ->select(
                    'g.id',
                    'g.dia',
                    'g.jornada',
                    'g.curso_calendario_id',
                    'g.cupos',
                    'o.id as orientador_id',
                    'c.id as curso_id',
                    's.id as salon_id',
                    'ca.id as calendario_id',
                    DB::raw('(select count(fi.grupo_id) from formulario_inscripcion fi where fi.grupo_id = g.id) as totalInscritos')
                )
                ->leftJoin('salones as s', 's.id', '=', 'g.salon_id')
                ->leftJoin('orientadores as o', 'o.id', '=', 'g.orientador_id')
                ->leftJoin('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
                ->leftJoin('cursos as c', 'c.id', '=', 'cc.curso_id')
                ->leftJoin('calendarios as ca', 'ca.id', '=', 'cc.calendario_id')
                ->where(function ($query) use ($criterio) {
                    $campos = ['o.nombre', 's.nombre', 'ca.nombre', 'cc.modalidad', 'g.cupos', 'g.dia', 'g.jornada', 'c.nombre', 'g.id'];
            
                    foreach ($campos as $campo) {
                        $query->orWhere($campo, 'like', '%' . $criterio . '%');
                    }
                })
                ->get();
            

            foreach ($grupos as $g) {
                $grupo = new Grupo();                
                $grupo->setid($g->id);
                $grupo->setDia($g->dia);
                $grupo->setJornada($g->jornada);
                $grupo->setCupo($g->cupos);
                
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

                array_push($listaGrupos, $grupo);
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }

        return $listaGrupos;        
    }
}