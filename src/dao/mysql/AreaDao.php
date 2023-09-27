<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;

use Src\domain\repositories\AreaRepository;
use Src\domain\Area;

class AreaDao extends Model implements AreaRepository {  
      
    protected $table = 'areas';
    protected $fillable = ['nombre'];

    public function listarAreas(): array {
        $areas = [];
        try {

            $rs = AreaDao::all();
            foreach($rs as $r) {
                array_push($areas, [
                    "id" => $r["id"],
                    "nombre" => $r["nombre"],
                ]);
            }            

        } catch (\Exception $e) {
            throw $e;
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
            throw $e;
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
            throw $e;
        }
        return $area;
    }

    public function crearArea(Area $area): bool {
        try {
            $result = AreaDao::create([
                'nombre' => $area->getNombre()
            ]);

        } catch (\Exception $e) {
            throw $e;
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
            throw $e;
        }   
        return $exito;
    }

    public function actualizarArea(Area $area): bool {
        try {
            $exito = false;
            $rs = AreaDao::find($area->getId());
            if ($rs) {
                $rs->nombre = $area->getNombre();
                $rs->save();
                $exito = true;
            }
        } catch (\Exception $e) {
            throw $e;
        }   
        return $exito; 
    }
}