<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;

use Src\domain\repositories\AreaRepository;
use Src\domain\Area;

class AreaDao extends Model implements AreaRepository {  
      
    protected $table = 'areas';

    public function listarAreas(): array {
        $areas = [];

        $filas = AreaDao::all();
        foreach($filas as $fila) {            
            $area = new Area($fila['nombre']);
            $area->setid($fila['id']);
            array_push($areas, $area);
        }

        return $areas;
    }
}