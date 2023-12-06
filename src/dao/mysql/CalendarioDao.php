<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Src\domain\Calendario;
use Src\domain\Curso;
use Src\domain\CursoCalendario;
use Src\domain\repositories\CalendarioRepository;

use Carbon\Carbon;

use Sentry\Laravel\Facade as Sentry;

class CalendarioDao extends Model implements CalendarioRepository {

    protected $table = 'calendarios';
    protected $fillable = ['nombre', 'fec_ini', 'fec_fin'];   
    
    public function cursos() {
        return $this->belongsToMany(CursoDao::class, 'curso_calendario', 'calendario_id', 'curso_id')
                    ->withPivot(['costo', 'modalidad', 'cupo', 'id'])
                    ->withTimestamps();
    }

    public function listarCalendarios(): array {
        $calendarios = array();
        try {
            $resultado = CalendarioDao::orderBy('fec_ini', 'desc')->get();
            foreach ($resultado as $r) {
                $calendario = new Calendario($r['nombre'], $r['fec_ini'], $r['fec_fin']);
                $calendario->setid($r['id']);
                array_push($calendarios, $calendario);
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        return $calendarios;
    }

    public function buscarCalendarioPorNombre(string $nombre): Calendario {
        $calendario = new Calendario();
        try {
            $result = CalendarioDao::where('nombre', $nombre)->first();
            if ($result) {
                $calendario->setId($result['id']);
                $calendario->setNombre($result['nombre']);
                $calendario->setFechaInicio($result['fec_ini']);
                $calendario->setFechaFinal($result['fec_fin']);
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
        return $calendario;
    }

    public function buscarCalendarioPorId(int $id = 0): Calendario {
        $calendario = new Calendario();
        try {
            $result = CalendarioDao::find($id);
            if ($result) {
                $calendario->setId($result['id']);
                $calendario->setNombre($result['nombre']);
                $calendario->setFechaInicio($result['fec_ini']);
                $calendario->setFechaFinal($result['fec_fin']);
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
        return $calendario;
    }

    public function crearCalendario(Calendario $calendario): bool {
        $exito = false;
        try {
            $result = CalendarioDao::create([
                'nombre' => $calendario->getNombre(),
                'fec_ini' => $calendario->getFechaInicio(),
                'fec_fin' => $calendario->getFechaFinal()
            ]);

            $exito = $result['id'] > 0;

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito;
    }

    public function eliminarCalendario(Calendario $calendario): bool {
        $exito = false;
        try {
            $rs = CalendarioDao::destroy($calendario->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito;
    }

    public function actualizarCalendario(Calendario $calendario): bool {
        $exito = false;
        try {
            $rs = CalendarioDao::find($calendario->getId());
            if ($rs) {
                $rs->update([
                    'nombre' => $calendario->getNombre(),
                    'fec_ini' => $calendario->getFechaInicio(),
                    'fec_fin' => $calendario->getFechaFinal()
                ]);                
                $exito = true;
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }   
        return $exito; 
    }

    public function agregarCurso(CursoCalendario $cursoCalendario): bool {
        $exito = false;

        try {

            $calendario = CalendarioDao::find($cursoCalendario->getCalendarioId());
            if ($calendario) {
                $calendario->cursos()->attach($cursoCalendario->getCursoId(), [
                    'costo' => $cursoCalendario->getCosto(), 
                    'modalidad' => $cursoCalendario->getModalidad(), 
                    'cupo' => $cursoCalendario->getCupo()
                ]);

            }            

            $exito = true;

        } catch(\Exception $e) {
            Sentry::captureException($e);
        }

        return $exito;
    }

    public function retirarCurso(CursoCalendario $cursoCalendario): bool {
        $resultado = false;
        try {
            $calendario = CalendarioDao::find($cursoCalendario->getCalendarioId());
            if ($calendario) {
                $resultado = DB::table('curso_calendario')->where('id', $cursoCalendario->getId())->delete();
            }

        } catch(\Exception $e) {
            Sentry::captureException($e);
        }
        return $resultado;
    }

    public function listarCursos(int $calendarioId, int $areaId): array {
        $cursos = array();
       
        $calendario = new Calendario();
        $calendario->setId($calendarioId);

        $listaCursos = CalendarioDao::find($calendarioId)->cursos()->where('area_id', $areaId)->get();    

        foreach($listaCursos as $c) {
            $curso = new Curso($c->nombre);
            $curso->setId($c->id);

            $datos = [
                    'cupo' => $c->pivot->cupo, 
                    'costo' => $c->pivot->costo, 
                    'modalidad' => $c->pivot->modalidad
                ];

            $cursoCalendario = new CursoCalendario($calendario, $curso, $datos);
            $cursoCalendario->setId($c->pivot->id);
            array_push($cursos, $cursoCalendario);
        }

        return $cursos;
    }

    public function buscarCursoCalendario(int $calendariId=0, int $cursoId=0, string $modalidad=''): CursoCalendario {
        $cursoCalendario = new CursoCalendario(new Calendario(), new Curso());
        return $cursoCalendario;
    }

    public function listarCursosPorCalendario(int $calendarioId): array {
        $cursosCalendario = array();

        $calendarioEncontrado = CalendarioDao::find($calendarioId);    

        if ($calendarioEncontrado) {

            $calendario = new Calendario($calendarioEncontrado->nombre);
            $calendario->setId($calendarioId);            

            foreach($calendarioEncontrado->cursos as $item) {
                $curso = new Curso($item->nombre);
                $curso->setId($item->id);
                $datos = [
                    'cupo' => $item->pivot->cupo, 
                    'costo' => $item->pivot->costo, 
                    'modalidad' => $item->pivot->modalidad
                ];

                $cursoCalendario = new CursoCalendario($calendario, $curso, $datos);
                $cursoCalendario->setId($item->pivot->id);

                array_push($cursosCalendario, $cursoCalendario);
            }
        }

        return $cursosCalendario;
    }

    public static function existeCalendarioVigente(): bool {
        $fechaActual = Carbon::now()->toDateString();
        $resultado = DB::table('calendarios')
            ->selectRaw("IF('$fechaActual' BETWEEN fec_ini AND fec_fin, 'true', 'false') AS esta_entre_fechas")
            ->first();
        
        if (!$resultado)
            return false;

        return ($resultado->esta_entre_fechas === 'true');
    }
}