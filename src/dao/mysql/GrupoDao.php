<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Src\domain\Grupo;
use Src\domain\repositories\GrupoRepository;

class GrupoDao extends Model implements GrupoRepository {
    protected $table = 'grupos';
    protected $fillable = ['curso_id', 'calendario_id', 'salon_id', 'orientador_id', 'dia', 'jornada'];

    public function listarGrupos(): array {
        $grupos = array();
        $cursoDao = new CursoDao();
        $calendarioDao = new CalendarioDao();
        $orientadorDao = new OrientadorDao();
        $salonDao = new SalonDao();

        try {
            $resultado = GrupoDao::orderBy('calendario_id', 'desc')->get();
            foreach ($resultado as $r) {
                $grupo = new Grupo();                
                $grupo->setid($r['id']);
                $grupo->setCalendario($calendarioDao->buscarCalendarioPorId($r['calendario_id']));
                $grupo->setOrientador($orientadorDao->buscarOrientadorPorId($r['orientador_id']));
                $grupo->setCurso($cursoDao->buscarCursoPorId($r['curso_id']));
                $grupo->setSalon($salonDao->buscarSalonPorId($r['salon_id']));
                $grupo->setDia($r['dia']);
                $grupo->setJornada($r['jornada']);

                array_push($grupos, $grupo);
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }

        return $grupos;
    }

    public function buscarGrupoPorId(int $id): Grupo {
        $grupo = new Grupo();
        $cursoDao = new CursoDao();
        $calendarioDao = new CalendarioDao();
        $orientadorDao = new OrientadorDao();
        $salonDao = new SalonDao();

        try {
            $r = GrupoDao::find($id);
            if ($r) {
                $grupo->setid($r['id']);
                $grupo->setCalendario($calendarioDao->buscarCalendarioPorId($r['calendario_id']));
                $grupo->setOrientador($orientadorDao->buscarOrientadorPorId($r['orientador_id']));
                $grupo->setCurso($cursoDao->buscarCursoPorId($r['curso_id']));
                $grupo->setSalon($salonDao->buscarSalonPorId($r['salon_id']));
                $grupo->setDia($r['dia']);
                $grupo->setJornada($r['jornada']);
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
        try {
            $result = GrupoDao::create([
                'curso_id' => $grupo->getCurso()->getId(), 
                'calendario_id' => $grupo->getCalendario()->getId(), 
                'salon_id' => $grupo->getSalon()->getId(), 
                'orientador_id' => $grupo->getOrientador()->getId(), 
                'dia' => $grupo->getDia(), 
                'jornada' => $grupo->getJornada()
            ]);

        } catch (\Exception $e) {
            $e->getMessage();
        }   

        return $result['id'] > 0;  
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
                    'curso_id' => $grupo->getCurso()->getId(), 
                    'calendario_id' => $grupo->getCalendario()->getId(), 
                    'salon_id' => $grupo->getSalon()->getId(), 
                    'orientador_id' => $grupo->getOrientador()->getId(), 
                    'dia' => $grupo->getDia(), 
                    'jornada' => $grupo->getJornada()
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
            $result = GrupoDao::where('curso_id', $grupo->getCurso()->getId())
                                ->where('calendario_id', $grupo->getCalendario()->getId())
                                ->where('salon_id', $grupo->getSalon()->getId())
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
            $result = GrupoDao::where('calendario_id', $grupo->getCalendario()->getId())
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
}