<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Src\domain\Area;
use Src\domain\Calendario;
use Src\domain\Curso;
use Src\domain\Grupo;
use Src\domain\Orientador;
use Src\domain\repositories\OrientadorRepository;
use Src\domain\Salon;

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
                           'observacion'];

    public function areas() {
        return $this->belongsToMany(AreaDao::class, 'orientador_areas', 'orientador_id', 'area_id');
    }

    public function grupos($idOrientador) {
        return OrientadorDao::select("grupos.id", "grupos.curso_id", "grupos.calendario_id", "grupos.salon_id", "grupos.dia", "grupos.jornada")
        ->join("grupos", "grupos.orientador_id", "=", "orientadores.id")
        ->where('orientadores.id', $idOrientador)
        ->orderBy('grupos.id', 'desc')
        ->limit(10)
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
                
                $areas = array();
                foreach($rs->areas as $area) 
                    array_push($areas, new Area($area->id, $area->nombre));
                
                $orientador->setAreas($areas);      
                
                array_push($orientadores, $orientador);
            }

        } catch (\Exception $e) {
            throw $e;
        }
        return $orientadores;
    }

    public function buscarOrientadorPorId($id): Orientador {
        $orientador = new Orientador();        
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

                $areas = array();
                foreach($rs->areas as $area) 
                    array_push($areas, new Area($area->id, $area->nombre));
                
                $orientador->setAreas($areas);

                $grupos = array();
                foreach($this->grupos($rs['id']) as $g) {
                    $grupo = new Grupo($g->curso_id, $g->calendario_id, $g->salon_id);
                    $grupo->setCurso(Curso::buscarPorId($g->curso_id, new CursoDao));
                    $grupo->setCalendario(Calendario::buscarPorId($g->calendario_id, new CalendarioDao));
                    $grupo->setDia($g->dia);
                    $grupo->setJornada($g->jornada);
                    $grupo->setId($g->id);
                    array_push($grupos, $grupo);
                }

                $orientador->setGrupos($grupos);
                
            }            
        } catch (\Exception $e) {
            throw $e;
        }
        return $orientador;
    }

    public function buscadorOrientador(string $criterio): array {
        $orientadores = [];
        try {
            $filtro = [
                "nombre" => $criterio,
                "documento" => $criterio,
                "email_institucional" => $criterio,
                "email_personal" => $criterio,
                "eps" => $criterio,
                "direccion" => $criterio,
    
            ];

            $query = OrientadorDao::query();
            foreach ($filtro as $campo => $valor) {
                $query->orWhere($campo, 'like', '%' . $valor . '%');
            }

            $result = $query->get();
            
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
                array_push($orientadores, $orientador);
            }
            
        } catch (\Exception $e) {
            throw $e;
        }
        return $orientadores;
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
            }            
        } catch (\Exception $e) {
            throw $e;
        }
        return $orientador;
    }

    public function crearOrientador(Orientador $orientador): bool {
        try {
            $result = OrientadorDao::create([
                'nombre' => $orientador->getNombre(), 
                'tipo_documento' => $orientador->getTipoDocumento(), 
                'documento' => $orientador->getDocumento(), 
                'email_institucional' => $orientador->getEmailInstitucional(), 
                'email_personal' => $orientador->getEmailPersonal(), 
                'direccion' => $orientador->getDireccion(), 
                'eps' => $orientador->getEps(), 
                'estado' => $orientador->getEstado(), 
                'observacion' => $orientador->getObservacion()
            ]);
        } catch (\Exception $e) {
            throw $e;
        }   
        return $result['id'] > 0;
    }

    public function eliminarOrientador(Orientador $orientador): bool {
        try {
            $exito = false;
            $rs = OrientadorDao::destroy($orientador->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            throw $e;
        }   
        return $exito;
    }

    public function actualizarOrientador(Orientador $orientador): bool {
        try {
            $exito = false;
            $rs = OrientadorDao::find($orientador->getId());
            if ($rs) {
                $rs->nombre = $orientador->getNombre();
                $rs->tipo_documento = $orientador->getTipoDocumento();
                $rs->documento = $orientador->getDocumento();
                $rs->email_institucional = $orientador->getEmailInstitucional();
                $rs->email_personal = $orientador->getEmailPersonal();
                $rs->direccion = $orientador->getDireccion();
                $rs->eps = $orientador->getEps();
                $rs->estado = $orientador->getEstado();
                $rs->observacion = $orientador->getObservacion();
                $rs->save();
                $exito = true;
            }
        } catch (\Exception $e) {
            throw $e;
        }   
        return $exito; 
    }

    public function agregarArea(Orientador $orientador, Area $area): bool {      
        try {
            $o = OrientadorDao::find($orientador->getId());
            if ($o) {
                $o->areas()->attach([$area->getId()]);
            }
        } catch (\Exception $e) {
            throw $e;
        }               
        return true;
    }

    public function quitarArea(Orientador $orientador, Area $area): bool {
        try {
            $o = OrientadorDao::find($orientador->getId());
            if ($o) {
                $o->areas()->detach([$area->getId()]);
            }
        } catch (\Exception $e) {
            throw $e;
        }               
        return true;
    }

    public function listarAreasDeUnOrientador(Orientador $orientador): array {
        return [];
    }
}