<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\domain\repositories\TipoSalonRepository;
use Src\domain\TipoSalon;

use Sentry\Laravel\Facade as Sentry;
use Src\infraestructure\util\Paginate;

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
            Sentry::captureException($e);
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
            Sentry::captureException($e);
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
            Sentry::captureException($e);
        }
        return $tipoSalon;        
    }

    public function crearTipoSalon(TipoSalon $tipoSalon): bool {
        try {
            
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $result = TipoSalonDao::create([
                'nombre' => $tipoSalon->getNombre(),
            ]);
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $result['id'] > 0;
    }

    public function eliminarTipoSalon(TipoSalon $tipoSalon): bool {
        try {
            $exito = false;

            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $rs = TipoSalonDao::destroy($tipoSalon->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito;
    }

    public function actualizarTipoSalon(TipoSalon $tipoSalon): bool {
        try {
            $exito = false;
            $rs = TipoSalonDao::find($tipoSalon->getId());
            if ($rs) {
                
                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");

                $rs->nombre = $tipoSalon->getNombre();
                $rs->save();
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito; 
    }

    public static function listarTipoSalonesPaginado($page): Paginate {
        $paginate = new Paginate($page);
        try {
            $tipoSalones = [];
            
            $items = TipoSalonDao::skip($paginate->Offset())->take($paginate->Limit())->get();
            foreach($items as $item) {
                $tipoSalon = new TipoSalon($item->nombre);
                $tipoSalon->setId($item->id);
                array_push($tipoSalones, $tipoSalon);
            }            

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
        
        $paginate->setRecords($tipoSalones);
        $paginate->setTotalRecords(TipoSalonDao::count());
        
        return $paginate;
    }

}