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

            $filas = AreaDao::all();
            foreach($filas as $fila) {            
                $area = new Area($fila['nombre']);
                $area->setid($fila['id']);
                array_push($areas, $area);
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
}