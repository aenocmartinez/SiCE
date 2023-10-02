<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Src\domain\Area;
use Src\domain\Curso;
use Src\domain\repositories\CursoRepository;

class CursoDao extends Model implements CursoRepository {  
      
    protected $table = 'cursos';
    protected $fillable = ['nombre', 'costo', 'modalidad', 'area_id'];

    public function area() {
        return $this->belongsTo(AreaDao::class);
    }

    public function listarCursos(): array {
        $cursos = [];
        try {
            $rs = CursoDao::all();
            foreach($rs as $r) {
                $curso = new Curso($r->nombre);
                $curso->setId($r->id);
                $curso->setModalidad($r->modalidad);
                $curso->setCosto($r->costo);
                $curso->setArea(new Area($r->area->id, $r->area->nombre));
                array_push($cursos, $curso);
            }            

        } catch (\Exception $e) {
            throw $e;
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
                $curso->setModalidad($rs['modalidad']);
                $curso->setCosto($rs['costo']);

                $area = new Area();
                $area->setId($rs['area']->id);
                $area->setNombre($rs['area']->nombre);

                $curso->setArea($area);
            }
        } catch (\Exception $e) {
            throw $e;
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
                $curso->setModalidad($rs['modalidad']);
                $curso->setCosto($rs['costo']);

                $area = new Area();
                $area->setId($rs['area']->id);
                $area->setNombre($rs['area']->nombre);

                $curso->setArea($area);
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $curso;
    }

    public function crearCurso(Curso $curso): bool {
        try {
            $result = CursoDao::create([
                'nombre' => $curso->getNombre(),
                'modalidad' => $curso->getModalidad(),
                'costo' => $curso->getCosto(),
                'area_id' => $curso->getArea()->getId(),
            ]);

        } catch (\Exception $e) {
            throw $e;
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
            throw $e;
        }   
        return $exito;
    }

    public function actualizarCurso(Curso $curso): bool {
        try {
            $exito = false;
            $rs = CursoDao::find($curso->getId());
            if ($rs) {
                $rs->nombre = $curso->getNombre();
                $rs->modalidad = $curso->getModalidad();
                $rs->costo = $curso->getCosto();
                $rs->area_id = $curso->getArea()->getId();
                $rs->save();
                
                $exito = true;
            }
        } catch (\Exception $e) {
            throw $e;
        }   
        return $exito; 
    }
}