<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Src\domain\repositories\SalonRepository;
use Src\domain\Salon;
use Src\domain\TipoSalon;

class SalonDao extends Model implements SalonRepository {
    
    protected $table = 'salones';
    protected $fillable = ['nombre', 'capacidad', 'esta_disponible', 'tipo_salon_id', 'hoja_vida'];

    public function listarSalones(): array {
        $salones = [];
        try {
            $rs = SalonDao::orderBy('nombre')->get();
            foreach($rs as $r) {
                $salon = new Salon($r->nombre);
                $salon->setId($r->id);
                $salon->setCapacidad($r->capacidad);
                $salon->setDisponible($r->esta_disponible);

                $tipoSalanDao = new TipoSalonDao();
                $tipoSalon = $tipoSalanDao->buscarTipoSalonPorId((int)$r->tipo_salon_id);
                $salon->setTipoSalon($tipoSalon);
                array_push($salones, $salon);
            }            

        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $salones;
    }

    public function buscadorSalones(string $criterio): array {
        $salones = [];
        try {
            $filtro = [
                "nombre" => $criterio,
                "capacidad" => $criterio,
            ];

            $query = SalonDao::query();
            foreach ($filtro as $campo => $valor) {
                $query->orWhere($campo, 'like', '%' . $valor . '%');
            }            
            $rs = $query->get();

            foreach($rs as $r) {
                $salon = new Salon($r->nombre);
                $salon->setId($r->id);
                $salon->setCapacidad($r->capacidad);
                $salon->setDisponible($r->esta_disponible);
                $salon->setHojaVida($r->hoja_vida);
                $tipoSalanDao = new TipoSalonDao();
                $tipoSalon = $tipoSalanDao->buscarTipoSalonPorId((int)$r->tipo_salon_id);
                $salon->setTipoSalon($tipoSalon);                
                array_push($salones, $salon);
            }  

        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $salones;
    }

    public function buscarSalonPorId(int $id = 0): Salon {
        $salon = new Salon();        
        try {
            $rs = SalonDao::find($id);
            if ($rs) {
                $salon->setId($rs['id']);
                $salon->setNombre($rs['nombre']);
                $salon->setCapacidad($rs['capacidad']);
                $salon->setDisponible($rs['esta_disponible']);
                $salon->setHojaVida($rs['hoja_vida']);

                $tipoSalanDao = new TipoSalonDao();
                $tipoSalon = $tipoSalanDao->buscarTipoSalonPorId((int)$rs->tipo_salon_id);
                $salon->setTipoSalon($tipoSalon);                
            }            
        } catch (\Exception $e) {
            $e->getMessage();
        }

        return $salon;
    }

    public function buscarSalonPorNombre(string $nombre): Salon {
        $salon = new Salon();        
        try {
            $rs = SalonDao::where('nombre', $nombre)->first();
            if ($rs) {
                $salon->setId($rs['id']);
                $salon->setNombre($rs['nombre']);
                $salon->setCapacidad($rs['capacidad']);
                $salon->setDisponible($rs['esta_disponible']);
                $salon->setHojaVida($rs['hoja_vida']);

                $tipoSalanDao = new TipoSalonDao();
                $tipoSalon = $tipoSalanDao->buscarTipoSalonPorId((int)$rs->tipo_salon_id);
                $salon->setTipoSalon($tipoSalon);                
            }            
        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $salon;        
    }

    public function crearSalon(Salon $salon): bool {
        try {
            $result = SalonDao::create([
                'nombre' => $salon->getNombre(),
                'capacidad' => $salon->getCapacidad(),
                'esta_disponible' => $salon->estaDisponible(),
                'hoja_vida' => $salon->getHojaVida(),
                'tipo_salon_id' => $salon->getIdTipoSalon(),
            ]);
        } catch (\Exception $e) {
            $e->getMessage();
        }   
        return $result['id'] > 0;
    }

    public function eliminarSalon(Salon $salon): bool {
        try {
            $exito = false;
            $rs = SalonDao::destroy($salon->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }   
        return $exito;
    }

    public function actualizarSalon(Salon $salon): bool {            
        try {
            $exito = false;
            $rs = SalonDao::find($salon->getId());
            if ($rs) {                
                $rs->nombre = $salon->getNombre();
                $rs->capacidad = $salon->getCapacidad();
                $rs->esta_disponible = $salon->estaDisponible();
                $rs->tipo_salon_id = $salon->getIdTipoSalon();
                $rs->hoja_vida = $salon->getHojaVida();
                $rs->save();
                $exito = true;
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }   
        return $exito; 
    }

}