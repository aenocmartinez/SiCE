<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\domain\repositories\SalonRepository;
use Src\domain\Salon;

use Sentry\Laravel\Facade as Sentry;
use Src\infraestructure\util\Paginate;

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
            Sentry::captureException($e);
        }
        return $salones;
    }

    public static function buscadorSalones(string $criterio, $page): Paginate {
        $paginate = new Paginate($page);
        $salones = [];
        try {
            $filtro = [
                "salones.nombre" => $criterio,
                "salones.capacidad" => $criterio,
                "tipo_salones.nombre" => $criterio,
            ];

            $query = SalonDao::query();
            $query->join('tipo_salones', 'salones.tipo_salon_id', '=', 'tipo_salones.id')
                    ->select(
                        'salones.nombre',
                        'salones.id',
                        'salones.capacidad',
                        'salones.esta_disponible',
                        'salones.tipo_salon_id'
                    );
            foreach ($filtro as $campo => $valor) {
                $query->orWhere($campo, 'like', '%' . $valor . '%');
            }        
                
            $totalRecords = $query->count();

            $items = $query->skip($paginate->Offset())->take($paginate->Limit())->get();
            
            foreach($items as $item) {
                $salon = new Salon($item->nombre);
                $salon->setId($item->id);
                $salon->setCapacidad($item->capacidad);
                $salon->setDisponible($item->esta_disponible);
                $tipoSalanDao = new TipoSalonDao();
                $tipoSalon = $tipoSalanDao->buscarTipoSalonPorId((int)$item->tipo_salon_id);
                $salon->setTipoSalon($tipoSalon);                
                array_push($salones, $salon);
            }  

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
       
        $paginate->setRecords($salones);
        $paginate->setTotalRecords($totalRecords);
        
        return $paginate;
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
                // $salon->setHojaVida($rs['hoja_vida']);

                $tipoSalanDao = new TipoSalonDao();
                $tipoSalon = $tipoSalanDao->buscarTipoSalonPorId((int)$rs->tipo_salon_id);
                $salon->setTipoSalon($tipoSalon);                
            }            
        } catch (\Exception $e) {
            Sentry::captureException($e);
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
                // $salon->setHojaVida($rs['hoja_vida']);

                $tipoSalanDao = new TipoSalonDao();
                $tipoSalon = $tipoSalanDao->buscarTipoSalonPorId((int)$rs->tipo_salon_id);
                $salon->setTipoSalon($tipoSalon);                
            }            
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
        return $salon;        
    }

    public function crearSalon(Salon $salon): bool {
        try {
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");
                        
            $result = SalonDao::create([
                'nombre' => $salon->getNombre(),
                'capacidad' => $salon->getCapacidad(),
                'esta_disponible' => $salon->estaDisponible(),
                // 'hoja_vida' => $salon->getHojaVida(),
                'tipo_salon_id' => $salon->getIdTipoSalon(),
            ]);
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $result['id'] > 0;
    }

    public function eliminarSalon(Salon $salon): bool {
        try {
            $exito = false;

            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $rs = SalonDao::destroy($salon->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito;
    }

    public function actualizarSalon(Salon $salon): bool {   
        try {
            $exito = false;
            $rs = SalonDao::find($salon->getId());
            if ($rs) {     
                
                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");
                                
                $rs->nombre = $salon->getNombre();
                $rs->capacidad = $salon->getCapacidad();
                $rs->esta_disponible = $salon->estaDisponible();
                $rs->tipo_salon_id = $salon->getIdTipoSalon();
                // $rs->hoja_vida = $salon->getHojaVida();
                $rs->save();
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito; 
    }

    public function listarSalonesPorEstado(bool $estado): array {
        $salones = [];
        try {
            $rs = SalonDao::where('esta_disponible', $estado)->orderBy('nombre')->get();
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
            Sentry::captureException($e);
        }
        return $salones;       
    }

    public static function listarSalonesPaginado($page): Paginate {
        $paginate = new Paginate($page);
        try {
            $salones = [];
            
            $items = SalonDao::skip($paginate->Offset())->take($paginate->Limit())->orderBy('nombre')->get();

            foreach($items as $item) {
                $salon = new Salon($item->nombre);
                $salon->setId($item->id);
                $salon->setCapacidad($item->capacidad);
                $salon->setDisponible($item->esta_disponible);

                $tipoSalanDao = new TipoSalonDao();
                $tipoSalon = $tipoSalanDao->buscarTipoSalonPorId((int)$item->tipo_salon_id);
                $salon->setTipoSalon($tipoSalon);
                array_push($salones, $salon);
            }            

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
        
        $paginate->setRecords($salones);
        $paginate->setTotalRecords(SalonDao::count());

        return $paginate;
    }
}