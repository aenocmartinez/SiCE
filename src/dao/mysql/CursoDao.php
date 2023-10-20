<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Src\domain\Area;
use Src\domain\Curso;
use Src\domain\repositories\CursoRepository;

class CursoDao extends Model implements CursoRepository {  
      
    protected $table = 'cursos';
    protected $fillable = ['nombre', 'area_id'];

    public function area() {
        return $this->belongsTo(AreaDao::class);
    }

    public function calendarios() {
        return $this->belongsToMany(CalendarioDao::class, 'curso_calendario', 'curso_id', 'calendario_id')
                    ->withPivot(['costo', 'modalidad', 'cupo'])
                    ->withTimestamps();
    } 

    public function cuantasVecesEnUnCalendario(int $calendarioId): int {
        return $this->calendarios()->where('calendario_id', $calendarioId)->count();
    }

    public function listarCursos(): array {
        $cursos = [];
        try {
            $rs = CursoDao::all();
            foreach($rs as $r) {
                $curso = new Curso($r->nombre);
                $curso->setId($r->id);
                $curso->setArea(new Area($r->area->id, $r->area->nombre));
                array_push($cursos, $curso);
            }            

        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $cursos;
    }

    public function buscarCursoPorNombreYArea(string $nombre, int $areaId): Curso {
        $curso = new Curso();        
        try {
            $rs = CursoDao::where('nombre', $nombre)->where('area_id', $areaId)->first();
            if ($rs) {
                $curso->setId($rs['id']);
                $curso->setNombre($rs['nombre']);

                $area = new Area();
                $area->setId($rs['area']->id);
                $area->setNombre($rs['area']->nombre);

                $curso->setArea($area);
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $curso;
    }


    public function buscarCursoPorId(int $id = 0): Curso {
        $curso = new Curso();        
        try {
            $rs = CursoDao::find($id);
            if ($rs) {
                $curso->setId($rs['id']);
                $curso->setNombre($rs['nombre']);

                $area = new Area();
                $area->setId($rs['area']->id);
                $area->setNombre($rs['area']->nombre);

                $curso->setArea($area);
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $curso;
    }

    public function crearCurso(Curso $curso): bool {
        try {
            $result = CursoDao::create([
                'nombre' => $curso->getNombre(),
                'area_id' => $curso->getArea()->getId(),
            ]);

        } catch (\Exception $e) {
            $e->getMessage();
        }   

        return $result['id'] > 0;     
    }

    public function eliminarCurso(Curso $curso): bool {
        try {
            $exito = false;
            $rs = CursoDao::destroy($curso->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }   
        return $exito;
    }

    public function actualizarCurso(Curso $curso): bool {
        try {
            $exito = false;
            $rs = CursoDao::find($curso->getId());
            if ($rs) {
                $rs->nombre = $curso->getNombre();
                $rs->area_id = $curso->getArea()->getId();
                $rs->save();
                
                $exito = true;
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }   
        return $exito; 
    }

    public function listarCursosPorArea(int $areaId): array {
        $cursos = array();
        $calendarioId = 1;
        try {
            $filas = CursoDao::where('area_id', $areaId)->get();
            foreach($filas as $fila) {
                $curso = new Curso($fila->nombre);
                $curso->setId($fila->id);
                $curso->setArea(new Area($fila->area->id, $fila->area->nombre));
                $curso->setNumeroEnCalendario($fila->cuantasVecesEnUnCalendario($calendarioId));

                array_push($cursos, $curso);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $cursos;       
    }
}