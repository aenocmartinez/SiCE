<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\domain\Area;
use Src\domain\CursoCalendario;
use Src\domain\Grupo;
use Src\domain\Orientador;
use Src\domain\repositories\OrientadorRepository;

use Sentry\Laravel\Facade as Sentry;
use Src\domain\Calendario;
use Src\infraestructure\util\Paginate;

class OrientadorDao extends Model implements OrientadorRepository {

    protected $table = 'orientadores';
    protected $fillable = ['nombre', 
                           'tipo_documento', 
                           'documento', 
                           'email_institucional', 
                           'email_personal', 
                           'direccion', 
                           'eps', 
                           'estado', 
                           'observacion',
                           'fec_nacimiento',
                           'nivel_estudio',
                           'rango_salarial'];

    public function areas() {
        return $this->belongsToMany(AreaDao::class, 'orientador_areas', 'orientador_id', 'area_id');
    }

    public function grupos($idOrientador) {        

        $calendario = Calendario::Vigente();
        if (!$calendario->existe()) {
            return [];
        }

        $calendarioId = $calendario->getId();
        
        return DB::table('grupos as g')
                ->select(
                    'g.id', 'g.curso_calendario_id', 'g.salon_id', 'g.dia', 'g.jornada', 'cc.curso_id', 'cc.calendario_id', 
                    'cc.modalidad', 'g.cupos', 'g.nombre', 'g.cancelado',
                    DB::raw('(
                        SELECT COUNT(*)
                        FROM formulario_inscripcion fi
                        WHERE fi.grupo_id = g.id
                        AND (
                            fi.estado = "Pagado"
                            OR (
                            fi.estado = "Pendiente de Pago"
                            AND fi.convenio_id IS NOT NULL
                            AND EXISTS (
                                SELECT 1
                                FROM convenios c
                                WHERE c.id = fi.convenio_id
                                AND c.es_cooperativa = true
                            )
                            )
                        )
                    ) as total_inscripciones_validas')
                )
                ->join('orientadores as o', 'g.orientador_id', '=', 'o.id')
                ->join('curso_calendario as cc', function($join) use ($calendarioId) {
                    $join->on('cc.id', '=', 'g.curso_calendario_id')
                        ->where('cc.calendario_id', $calendarioId);
                })
                ->where('o.id', $idOrientador)
                ->orderBy('g.curso_calendario_id', 'desc')
                ->get();
    }

    public function listarOrientadores(): array {
        $orientadores = [];
        try {
            $result = OrientadorDao::all();
            foreach($result as $rs) {
                $orientador = new Orientador();
                $orientador->setId($rs->id);
                $orientador->setNombre($rs->nombre);
                $orientador->setTipoDocumento($rs->tipo_documento);
                $orientador->setDocumento($rs->documento);
                $orientador->setEmailInstitucional($rs->email_institucional);
                $orientador->setEmailPersonal($rs->email_personal);
                $orientador->setDireccion($rs->direccion);
                $orientador->setEps($rs->eps);
                $orientador->setEstado($rs->estado);
                $orientador->setObservacion($rs->observacion);
                $orientador->setFechaNacimiento($rs->fec_nacimiento);
                $orientador->setNivelEducativo($rs->nivel_estudio);
                $orientador->setRangoSalarial($rs->rango_salarial);
                
                $areas = array();
                foreach($rs->areas as $area) 
                    array_push($areas, new Area($area->id, $area->nombre));
                
                $orientador->setAreas($areas);      
                
                array_push($orientadores, $orientador);
            }

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
        return $orientadores;
    }

    public function buscarOrientadorPorId($id): Orientador {        
        $orientador = new Orientador();        
        $cursoDao = new CursoDao();
        $calendarioDao = new CalendarioDao();
        $salonDao = new SalonDao();
                
        try {
            $rs = OrientadorDao::find($id);            
            if ($rs) {            
                $orientador->setId($rs['id']);
                $orientador->setNombre($rs['nombre']);
                $orientador->setTipoDocumento($rs['tipo_documento']);
                $orientador->setDocumento($rs['documento']);
                $orientador->setEmailInstitucional($rs['email_institucional']);
                $orientador->setEmailPersonal($rs['email_personal']);
                $orientador->setDireccion($rs['direccion']);
                $orientador->setEps($rs['eps']);
                $orientador->setEstado($rs['estado']);
                $orientador->setObservacion($rs['observacion']);
                $orientador->setFechaNacimiento($rs['fec_nacimiento']);
                $orientador->setNivelEducativo($rs['nivel_estudio']);
                $orientador->setRangoSalarial($rs->rango_salarial);

                $areas = array();
                foreach($rs->areas as $area) 
                    array_push($areas, new Area($area->id, $area->nombre));
                
                $orientador->setAreas($areas);

                $grupos = array();
                foreach($this->grupos($rs['id']) as $g) {
                    
                    $grupo = new Grupo();                
                    $grupo->setNombre($g->nombre);
                    $grupo->setid($g->id);
                    $grupo->setDia($g->dia);
                    $grupo->setJornada($g->jornada);
                    $grupo->setCupo($g->cupos);
                    $grupo->setTotalInscritos($g->total_inscripciones_validas);
                    $grupo->setCancelado($g->cancelado);
                    
                    $caledario = $calendarioDao->buscarCalendarioPorId($g->calendario_id);                    

                    $curso = $cursoDao->buscarCursoPorId($g->curso_id);
                    
                    $salon = $salonDao->buscarSalonPorId($g->salon_id);
                    

                    $cursoCalendario = new CursoCalendario($caledario, $curso);
                    $cursoCalendario->setId($g->curso_calendario_id);
                    $cursoCalendario->setModalidad($g->modalidad);
                    
                    $grupo->setCursoCalendario($cursoCalendario);
                    // $grupo->setOrientador($orientador);
                    $grupo->setSalon($salon);
                    
                    array_push($grupos, $grupo);
                    
                }

                $orientador->setGrupos($grupos);                
            }            
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        return $orientador;
    }

    public static function buscadorOrientador(string $criterio, $page=1): Paginate {
        $paginate = new Paginate($page);
        $orientadores = [];
        try {

            $criterio = str_replace(" ","%", $criterio);
            
            $filtro = [
                "orientadores.nombre" => $criterio,
                "orientadores.documento" => $criterio,
                "orientadores.email_institucional" => $criterio,
                "orientadores.email_personal" => $criterio,
                "orientadores.nivel_estudio" => $criterio,
                "areas.nombre" => $criterio,
            ];

            $query = OrientadorDao::query();
            $query->join('orientador_areas', 'orientador_areas.orientador_id', '=', 'orientadores.id')
                  ->join('areas', 'orientador_areas.area_id', '=', 'areas.id')
                  ->select(
                    'orientadores.id', 'orientadores.nombre',
                    'orientadores.documento', 'orientadores.email_institucional',
                    'orientadores.email_personal', 'orientadores.direccion', 'orientadores.tipo_documento',
                    'orientadores.eps', 'orientadores.estado',
                    'orientadores.observacion', 'orientadores.fec_nacimiento',
                    'orientadores.nivel_estudio', 'orientadores.rango_salarial'
                 );
            
            foreach ($filtro as $campo => $valor) {
                $query->orWhere($campo, 'like', '%' . $valor . '%');
            }
            
            $query->distinct('orientadores.id');
            
            $totalRecords = $query->count();
            $items = $query->skip($paginate->Offset())->take($paginate->Limit())->get();            
        
            
            foreach($items as $item) {
                $orientador = new Orientador();
                $orientador->setId($item->id);
                $orientador->setNombre($item->nombre);
                $orientador->setTipoDocumento($item->tipo_documento);
                $orientador->setDocumento($item->documento);
                $orientador->setEmailInstitucional($item->email_institucional);
                $orientador->setEmailPersonal($item->email_personal);
                $orientador->setDireccion($item->direccion);
                $orientador->setEps($item->eps);
                $orientador->setEstado($item->estado);
                $orientador->setObservacion($item->observacion);
                $orientador->setFechaNacimiento($item->fec_nacimiento);
                $orientador->setNivelEducativo($item->nivel_estudio); 
                $orientador->setRangoSalarial($item->rango_salarial);
                
                $areas = array();
                foreach($item->areas as $area) 
                    array_push($areas, new Area($area->id, $area->nombre));
                
                $orientador->setAreas($areas);
                                
                array_push($orientadores, $orientador);
            }
            
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        $paginate->setRecords($orientadores);
        $paginate->setTotalRecords($totalRecords);

        return $paginate;
    }

    public function buscarOrientadorPorDocumento(string $tipo, string $documento): Orientador {
        $orientador = new Orientador();        
        try {
            $rs = OrientadorDao::where('tipo_documento', $tipo)->where('documento', $documento)->first();
            if ($rs) {
                $orientador->setId($rs['id']);
                $orientador->setNombre($rs['nombre']);
                $orientador->setTipoDocumento($rs['tipo_documento']);
                $orientador->setDocumento($rs['documento']);
                $orientador->setEmailInstitucional($rs['email_institucional']);
                $orientador->setEmailPersonal($rs['email_personal']);
                $orientador->setDireccion($rs['direccion']);
                $orientador->setEps($rs['eps']);
                $orientador->setEstado($rs['estado']);
                $orientador->setObservacion($rs['observacion']);
                $orientador->setFechaNacimiento($rs['fec_nacimiento']);
                $orientador->setNivelEducativo($rs['nivel_estudio']);  
                $orientador->setRangoSalarial($rs->rango_salarial);                
                
            }            
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
        return $orientador;
    }

    public function crearOrientador(Orientador &$orientador): bool {
        try {
            
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $data = [
                'nombre' => $orientador->getNombre(), 
                'tipo_documento' => $orientador->getTipoDocumento(), 
                'documento' => $orientador->getDocumento(), 
                'email_institucional' => $orientador->getEmailInstitucional(), 
                'email_personal' => $orientador->getEmailPersonal(), 
                'direccion' => $orientador->getDireccion(), 
                'eps' => $orientador->getEps(), 
                'estado' => $orientador->getEstado(), 
                'observacion' => $orientador->getObservacion(),
                'rango_salarial' => $orientador->getRangoSalarial(),
            ];

            if ($orientador->getFechaNacimiento() != "") {
                $data['fec_nacimiento'] = $orientador->getFechaNacimiento();
            }

            if ($orientador->getNivelEducativo() != "") {
                $data['nivel_estudio'] = $orientador->getNivelEducativo();
            }            

            $result = OrientadorDao::create($data);

            $orientador->setId($result['id']);

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $orientador->getId() > 0;
    }

    public function eliminarOrientador(Orientador $orientador): bool {
        try {
            $exito = false;
            
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $rs = OrientadorDao::destroy($orientador->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito;
    }

    public function actualizarOrientador(Orientador $orientador): bool {
        try {            
            $exito = false;
            $rs = OrientadorDao::find($orientador->getId());
            if ($rs) {

                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");

                $rs->nombre = $orientador->getNombre();
                $rs->tipo_documento = $orientador->getTipoDocumento();
                $rs->documento = $orientador->getDocumento();
                $rs->email_institucional = $orientador->getEmailInstitucional();
                $rs->email_personal = $orientador->getEmailPersonal();
                $rs->direccion = $orientador->getDireccion();
                $rs->eps = $orientador->getEps();
                $rs->estado = $orientador->getEstado();
                $rs->observacion = $orientador->getObservacion();
                $rs->rango_salarial = $orientador->getRangoSalarial();
                
                $rs->fec_nacimiento = NULL;
                if ($orientador->getFechaNacimiento() != "") {
                    $rs->fec_nacimiento = $orientador->getFechaNacimiento();
                }

                $rs->nivel_estudio = NULL;
                if ($orientador->getNivelEducativo() != "") {
                    $rs->nivel_estudio = $orientador->getNivelEducativo();
                }

                $rs->save();
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito; 
    }

    public function agregarArea(Orientador $orientador, Area $area): bool {   
        $exito = true;   
        try {
            $o = OrientadorDao::find($orientador->getId());
            if ($o) {
                
                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");

                $o->areas()->attach([$area->getId()]);
            }
        } catch (\Exception $e) {
            $exito = false;   
            Sentry::captureException($e);
        }               
        return $exito;
    }

    public function quitarArea(Orientador $orientador): bool {
        try {
            $o = OrientadorDao::find($orientador->getId());
            if ($o) {

                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");
                $o->areas()->detach();
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }               
        return true;
    }

    public function listarAreasDeUnOrientador(Orientador $orientador): array {
        return [];
    }

    public static function listarOrientadoresPaginado($page=1): Paginate {            
        $paginate = new Paginate($page);
        $orientadores = [];
        try {
            
            $items = OrientadorDao::skip($paginate->Offset())->take($paginate->Limit())->orderBy('nombre')->get();

            foreach($items as $item) {
                $orientador = new Orientador();
                $orientador->setId($item->id);
                $orientador->setNombre($item->nombre);
                $orientador->setTipoDocumento($item->tipo_documento);
                $orientador->setDocumento($item->documento);
                $orientador->setEmailInstitucional($item->email_institucional);
                $orientador->setEmailPersonal($item->email_personal);
                $orientador->setDireccion($item->direccion);
                $orientador->setEps($item->eps);
                $orientador->setEstado($item->estado);
                $orientador->setObservacion($item->observacion);
                $orientador->setFechaNacimiento($item->fec_nacimiento);
                $orientador->setNivelEducativo($item->nivel_estudio);
                $orientador->setRangoSalarial($item->rango_salarial);
                
                $areas = array();
                foreach($item->areas as $area) 
                    array_push($areas, new Area($area->id, $area->nombre));
                
                $orientador->setAreas($areas);      
                
                array_push($orientadores, $orientador);
            }

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        $paginate->setTotalRecords(OrientadorDao::count());
        $paginate->setRecords($orientadores);

        return $paginate;
    }
}