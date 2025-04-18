<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\domain\Area;
use Src\domain\Curso;
use Src\domain\repositories\CursoRepository;

use Sentry\Laravel\Facade as Sentry;
use Src\infraestructure\util\Paginate;

class CursoDao extends Model implements CursoRepository {  
      
    protected $table = 'cursos';
    protected $fillable = ['nombre', 'area_id', 'tipo_curso'];

    public function area() {
        return $this->belongsTo(AreaDao::class);
    }

    public function calendarios() {
        return $this->belongsToMany(CalendarioDao::class, 'curso_calendario', 'curso_id', 'calendario_id')
                    ->withPivot(['costo', 'modalidad'])
                    ->withTimestamps();
    } 

    public function cuantasVecesEnUnCalendario(int $calendarioId): int {
        return $this->calendarios()->where('calendario_id', $calendarioId)->count();
    }

    public function listarCursos(): array {        
        $cursos = [];
        try {
            $rs = CursoDao::all();
            foreach($rs as $r) {
                $curso = new Curso($r->nombre);
                $curso->setId($r->id);
                if (!is_null($r->tipo_curso)) {
                    $curso->setTipoCurso($r->tipo_curso);
                }
                $curso->setArea(new Area($r->area->id, $r->area->nombre));
                array_push($cursos, $curso);
            }            

        } catch (\Exception $e) {            
            Sentry::captureException($e);
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
                if (!is_null($rs->tipo_curso)) {
                    $curso->setTipoCurso($rs->tipo_curso);
                }

                $area = new Area();
                $area->setId($rs['area']->id);
                $area->setNombre($rs['area']->nombre);

                $curso->setArea($area);
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
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
                if (!is_null($rs->tipo_curso)) {
                    $curso->setTipoCurso($rs->tipo_curso);
                }

                $area = new Area();
                $area->setId($rs['area']->id);
                $area->setNombre($rs['area']->nombre);

                $curso->setArea($area);
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
        return $curso;
    }

    public function crearCurso(Curso $curso): bool {
        try {
            
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $result = CursoDao::create([
                'nombre' => $curso->getNombre(),
                'area_id' => $curso->getArea()->getId(),
                'tipo_curso' => $curso->getTipoCurso(),
            ]);

        } catch (\Exception $e) {                        
            Sentry::captureException($e);
            return false;
        }   

        return $result['id'] > 0;     
    }

    public function eliminarCurso(Curso $curso): bool {
        try {
            $exito = false;

            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $rs = CursoDao::destroy($curso->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito;
    }

    public function actualizarCurso(Curso $curso): bool {
        try {
            $exito = false;
            $rs = CursoDao::find($curso->getId());
            if ($rs) {

                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");
                                
                $rs->nombre = $curso->getNombre();
                $rs->area_id = $curso->getArea()->getId();
                $rs->tipo_curso = $curso->getTipoCurso();
                $rs->save();
                
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito; 
    }

    public function listarCursosPorArea(int $areaId): array {
        $cursos = array();
        $calendarioId = 1;
        try {
            $filas = CursoDao::where('area_id', $areaId)->orderBy('nombre', 'asc')->get();
            foreach($filas as $fila) {
                $curso = new Curso($fila->nombre);
                $curso->setId($fila->id);
                if (!is_null($fila->tipo_curso)) {
                    $curso->setTipoCurso($fila->tipo_curso);
                }
                $curso->setArea(new Area($fila->area->id, $fila->area->nombre));                
                $curso->setNumeroEnCalendario($fila->cuantasVecesEnUnCalendario($calendarioId));

                array_push($cursos, $curso);
            }
        } catch (\Exception $e) {
            echo Sentry::captureException($e);
        }
        return $cursos;       
    }

    public static function listaCursosPaginados($page=1): Paginate {
        $paginate = new Paginate($page);
        
        try {
            
            $cursos = [];

            $items = CursoDao::skip($paginate->Offset())->take($paginate->Limit())->orderBy('nombre')->get();
        
            foreach($items as $item) {
                $curso = new Curso($item->nombre);
                $curso->setId($item->id);
                if (!is_null($item->tipo_curso)) {
                    $curso->setTipoCurso($item->tipo_curso);
                }
                $curso->setArea(new Area($item->area->id, $item->area->nombre));
                array_push($cursos, $curso);
            }            

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        $paginate->setTotalRecords(CursoDao::count());
        $paginate->setRecords($cursos);

        
        return $paginate;        
    }

    public static function buscadorCursos(string $criterio, $page): Paginate {
        $paginate = new Paginate($page);
        
        try {
            $cursos = [];

            $criterio = str_replace(" ","%", $criterio);

            $filtro = [
                "cursos.nombre" => $criterio,
                "areas.nombre" => $criterio,
                "cursos.tipo_curso" => $criterio,
            ];
            
            $query = CursoDao::query();
            $query->join('areas', 'cursos.area_id', '=', 'areas.id')
                ->select(
                    'cursos.id',
                    'cursos.nombre',
                    'cursos.area_id',
                    'cursos.tipo_curso',
                );
            
            foreach ($filtro as $campo => $valor) {
                $query->orWhere($campo, 'like', '%' . $valor . '%');
            }
            
            $totalRecords = $query->count();

            $items = $query->skip($paginate->Offset())->take($paginate->Limit())->get();

            $areaDao = new AreaDao();
            foreach($items as $item) {
                $curso = new Curso();

                $curso->setId((int)$item->id);
                $curso->setNombre($item->nombre);
                $curso->setTipoCurso($item->tipo_curso);
            
                $curso->setArea($areaDao->buscarAreaPorId((int)$item->area_id));
                array_push($cursos, $curso);
            }              

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        $paginate->setRecords($cursos);
        $paginate->setTotalRecords($totalRecords);

        return $paginate;
    }

    public static function top5CursosMasInscritosPorCalendario($calendarioId): array {
        $cursos = [];

        try {
            $items = CursoDao::select('cursos.nombre', DB::raw('count(cursos.id) as total'))
                ->join('curso_calendario as cc', 'cursos.id', '=', 'cc.curso_id')
                ->join('grupos as g', 'g.curso_calendario_id', '=', 'cc.id')
                ->join('formulario_inscripcion as f', 'f.grupo_id', '=', 'g.id')
                ->where('cc.calendario_id', $calendarioId)
                ->groupBy('cursos.nombre')->orderByDesc('total')->limit(5)->get();

            foreach($items as $item) {
                $data["nombre"] = $item->nombre;
                $data["total"] = $item->total;

                $cursos[] = $data;
            }

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        return $cursos;
    }
}