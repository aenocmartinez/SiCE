<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\domain\repositories\AreaRepository;
use Src\domain\Area;
use Src\domain\Orientador;

use Sentry\Laravel\Facade as Sentry;
use Src\infraestructure\util\Paginate;

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
            Sentry::captureException($e);
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
            Sentry::captureException($e);
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
            Sentry::captureException($e);
        }
        return $area;
    }

    public function crearArea(Area $area): bool {
        $exito = false;
        try {

            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");
            
            $result = AreaDao::create([
                'nombre' => $area->getNombre()
            ]);

            $exito = $result['id'] > 0;
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito;
    }

    public function eliminarArea(Area $area): bool {
        $exito = false;
        try {
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $rs = AreaDao::destroy($area->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito;
    }

    public function actualizarArea(Area $area): bool {
        $exito = false;
        try {
            $rs = AreaDao::find($area->getId());
            if ($rs) {
                
                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");

                $rs->update([
                    'nombre' => $area->getNombre()
                ]);                
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
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
            Sentry::captureException($e);
        }

        return $listOrientadores;
    }

    public static function listaAreasPaginados($page=1): Paginate {
        $paginate = new Paginate($page);

        try {
            $areas = [];
            $items = AreaDao::skip($paginate->Offset())->take($paginate->Limit())->orderBy('nombre')->get();
            
            foreach($items as $item) {
                array_push($areas, new Area($item->id, $item->nombre));
            }            

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
        $paginate->setTotalRecords(AreaDao::count());
        $paginate->setRecords($areas);

        return $paginate;
    }
}