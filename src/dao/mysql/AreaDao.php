<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Src\domain\repositories\AreaRepository;
use Src\domain\Area;
use Src\domain\Orientador;

class AreaDao extends Model implements AreaRepository {  
      
    protected $table = 'areas';
    protected $fillable = ['nombre'];

    public function listarAreas(): array {
        $areas = [];
        try {

            $rs = AreaDao::all();
            foreach($rs as $r) {
                array_push($areas, new Area($r['id'], $r['nombre']));
            }            

        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $areas;
    }

    public function buscarAreaPorNombre(string $nombre): Area {
        $area = new Area();
        try {
            $result = AreaDao::where('nombre', $nombre)->first();
            if ($result) {
                $area->setId($result['id']);
                $area->setNombre($result['nombre']);
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $area;
    }

    public function buscarAreaPorId(int $id = 0): Area {
        $area = new Area();
        try {
            $result = AreaDao::find($id);
            if ($result) {
                $area->setId($result['id']);
                $area->setNombre($result['nombre']);
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $area;
    }

    public function crearArea(Area $area): bool {
        try {
            $result = AreaDao::create([
                'nombre' => $area->getNombre()
            ]);
        } catch (\Exception $e) {
            $e->getMessage();
        }   
        return $result['id'] > 0;
    }

    public function eliminarArea(Area $area): bool {
        try {
            $exito = false;
            $rs = AreaDao::destroy($area->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }   
        return $exito;
    }

    public function actualizarArea(Area $area): bool {
        try {
            $exito = false;
            $rs = AreaDao::find($area->getId());
            if ($rs) {
                $rs->update([
                    'nombre' => $area->getNombre()
                ]);                
                $exito = true;
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }   
        return $exito; 
    }

    public function listarOrientadoresPorArea(int $cursoCalendarioId): array {
        $listOrientadores = [];
        try {

            $orientadores = DB::table('orientadores as o')
                ->select('o.id', 'o.nombre', 'o.documento', 'o.tipo_documento')
                ->distinct()
                ->join('orientador_areas as oa', 'o.id', '=', 'oa.orientador_id')
                ->join('areas as a', 'oa.area_id', '=', 'a.id')
                ->join('cursos as c', 'c.area_id', '=', 'a.id')
                ->join('curso_calendario as cc', 'cc.curso_id', '=', 'c.id')
                ->where('cc.id', $cursoCalendarioId)
                ->orderBy('o.nombre')
                ->get();

            foreach($orientadores as $o) {
                $orientador = new Orientador();
                $orientador->setId($o->id);
                $orientador->setNombre($o->nombre);
                $orientador->setDocumento($o->documento);
                $orientador->setTipoDocumento($o->tipo_documento);

                array_push($listOrientadores, $orientador);
            }

        } catch(\Exception $e) {
            $e->getMessage();
        }

        return $listOrientadores;
    }
}