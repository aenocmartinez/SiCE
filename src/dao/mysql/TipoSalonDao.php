<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Src\domain\repositories\TipoSalonRepository;
use Src\domain\Salon;
use Src\domain\TipoSalon;

class TipoSalonDao extends Model implements TipoSalonRepository {
    
    protected $table = 'tipo_salones';
    protected $fillable = ['nombre'];

    public function listarTipoSalones(): array {
        $tipoSalones = [];
        try {
            $rs = TipoSalonDao::all();
            foreach($rs as $r) {
                $tipoSalon = new TipoSalon($r->nombre);
                $tipoSalon->setId($r->id);
                array_push($tipoSalones, $tipoSalon);
            }            

        } catch (\Exception $e) {
            throw $e;
        }
        return $tipoSalones;
    }

    public function buscarTipoSalonPorId(int $id = 0): TipoSalon {
        $tipoSalon = new TipoSalon();        
        try {
            $rs = TipoSalonDao::find($id);
            if ($rs) {
                $tipoSalon->setId($rs['id']);
                $tipoSalon->setNombre($rs['nombre']);
            }            
        } catch (\Exception $e) {
            throw $e;
        }
        return $tipoSalon;
    }

    public function buscarTipoSalonPorNombre(string $nombre): TipoSalon {
        $tipoSalon = new TipoSalon();        
        try {
            $rs = TipoSalonDao::where('nombre', $nombre)->first();
            if ($rs) {
                $tipoSalon->setId($rs['id']);
                $tipoSalon->setNombre($rs['nombre']);
            }            
        } catch (\Exception $e) {
            throw $e;
        }
        return $tipoSalon;        
    }

    public function crearTipoSalon(TipoSalon $tipoSalon): bool {
        try {
            $result = TipoSalonDao::create([
                'nombre' => $tipoSalon->getNombre(),
            ]);
        } catch (\Exception $e) {
            throw $e;
        }   
        return $result['id'] > 0;
    }

    public function eliminarTipoSalon(TipoSalon $tipoSalon): bool {
        try {
            $exito = false;
            $rs = TipoSalonDao::destroy($tipoSalon->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            throw $e;
        }   
        return $exito;
    }

    public function actualizarTipoSalon(TipoSalon $tipoSalon): bool {
        try {
            $exito = false;
            $rs = TipoSalonDao::find($tipoSalon->getId());
            if ($rs) {
                $rs->nombre = $tipoSalon->getNombre();
                $rs->save();
                $exito = true;
            }
        } catch (\Exception $e) {
            throw $e;
        }   
        return $exito; 
    }

}