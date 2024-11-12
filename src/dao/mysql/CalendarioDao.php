<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Src\domain\Calendario;
use Src\domain\Curso;
use Src\domain\CursoCalendario;
use Src\domain\repositories\CalendarioRepository;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Sentry\Laravel\Facade as Sentry;
use Src\domain\Convenio;
use Src\domain\FormularioInscripcion;
use Src\domain\Grupo;
use Src\domain\Orientador;
use Src\domain\Participante;
use Src\infraestructure\util\FormatoMoneda;
use Src\view\dto\AreaInscripcionDto;
use Src\view\dto\GrupoInscripcionDto;

class CalendarioDao extends Model implements CalendarioRepository {

    protected $table = 'calendarios';
    protected $fillable = ['nombre', 'fec_ini', 'fec_fin', 'fec_ini_clase'];   
    
    public function cursos() {
        return $this->belongsToMany(CursoDao::class, 'curso_calendario', 'calendario_id', 'curso_id')
                    ->withPivot(['costo', 'modalidad', 'id'])
                    ->withTimestamps()
                    ->orderBy('nombre');
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
                $calendario->setFechaInicioClase($result['fec_ini_clase']);
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
                $calendario->setFechaInicioClase($result['fec_ini_clase']);
            }
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
        return $calendario;
    }

    public function crearCalendario(Calendario &$calendario): bool {
        $exito = false;
        try {

            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");
                        
            $result = CalendarioDao::create([
                'nombre' => $calendario->getNombre(),
                'fec_ini' => $calendario->getFechaInicio(),
                'fec_fin' => $calendario->getFechaFinal(),
                'fec_ini_clase' => $calendario->getFechaInicioClase(),
            ]);

            $calendario->setId($result['id']);
            $exito = true;

        } catch (\Exception $e) {
            Sentry::captureException($e);            
        }   
        return $exito;
    }

    public function eliminarCalendario(Calendario $calendario): bool {
        $exito = false;
        try {

            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

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

                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");

                $rs->update([
                    'nombre' => $calendario->getNombre(),
                    'fec_ini' => $calendario->getFechaInicio(),
                    'fec_fin' => $calendario->getFechaFinal(),
                    'fec_ini_clase' => $calendario->getFechaInicioClase(),
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

                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");

                $calendario->cursos()->attach($cursoCalendario->getCursoId(), [
                    'costo' => $cursoCalendario->getCosto(), 
                    'modalidad' => $cursoCalendario->getModalidad(), 
                    // 'cupo' => $cursoCalendario->getCupo()
                ]);

            }            

            $exito = true;

        } catch(\Exception $e) {
            Sentry::captureException($e);
        }

        return $exito;
    }

    public function retirarCurso(CursoCalendario $cursoCalendario): bool {

        $resultado = true;
        try {
            $calendario = CalendarioDao::find($cursoCalendario->getCalendarioId());
            if ($calendario) {
                $resultado = DB::table('curso_calendario')->where('id', $cursoCalendario->getId())->delete();
            }            

        } catch(\Exception $e) {
            $resultado = false;
            // Sentry::captureException($e);
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
                    // 'cupo' => $c->pivot->cupo, 
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
                    // 'cupo' => $item->pivot->cupo, 
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
        $fechaActual = Carbon::now()->toDateTimeString();
        $resultado = DB::table('calendarios')
            ->selectRaw("IF('$fechaActual' BETWEEN fec_ini AND fec_fin, 'true', 'false') AS esta_entre_fechas")
            ->orderBy('id', 'desc')
            ->first();
        
        if (!$resultado)
            return false;

        return ($resultado->esta_entre_fechas === 'true');
    }

    public static function obtenerCalendarioActualVigente(): Calendario{
        $calendarioVigente = new Calendario();

        $fechaActual = now()->toDateTimeString();
        
        $result = DB::table('calendarios')
            ->select('id', 'nombre', 'fec_ini', 'fec_fin', 'fec_ini_clase')
            ->where(function ($query) use ($fechaActual) {
                $query->where('fec_ini', '<=', $fechaActual)
                    ->where('fec_fin', '>=', $fechaActual);
            })
            ->orWhere(function ($query) use ($fechaActual) {
                $query->where('fec_ini', '>=', $fechaActual)
                    ->where('fec_fin', '<=', $fechaActual);
            })
            ->orWhere(function ($query) use ($fechaActual) {
                $query->where('fec_ini', '<=', $fechaActual)
                    ->where('fec_fin', '>=', $fechaActual);
            })
            ->first();

        if ($result) {
            $calendarioVigente->setId($result->id);
            $calendarioVigente->setNombre($result->nombre);
            $calendarioVigente->setFechaInicio($result->fec_ini);
            $calendarioVigente->setFechaFinal($result->fec_fin);  
            $calendarioVigente->setFechaInicioClase($result->fec_ini_clase);            
        }

        return $calendarioVigente;
    }

    public function listarInscripcionesPorCalendario(int $calendarioId): array {
        $inscripciones = array();

        $result = FormularioInscripcionDao::select(
            'formulario_inscripcion.id',
            'formulario_inscripcion.grupo_id',
            'formulario_inscripcion.participante_id',
            'formulario_inscripcion.convenio_id',
            'formulario_inscripcion.numero_formulario',
            'formulario_inscripcion.estado',
            'formulario_inscripcion.costo_curso',
            'formulario_inscripcion.valor_descuento',
            'formulario_inscripcion.total_a_pagar'
        )
        ->join('grupos', function($join) use ($calendarioId) {
            $join->on('grupos.id', '=', 'formulario_inscripcion.grupo_id')
                 ->where('grupos.calendario_id', '=', $calendarioId);
        })
        ->get();

        foreach($result as $r) {
            $inscripcion = new FormularioInscripcion();              

            $grupo = new Grupo();
            $grupo->setId($r->grupo_id);
            
            $participante = new Participante();
            $participante->setId($r->participante_id);

            $convenio = new Convenio();
            $convenioId = 0;
            if ($r->convenio_id) {
                $convenioId = $r->convenio_id;
            }
            $convenio->setId($convenioId);

            $inscripcion->setId($r->id);
            $inscripcion->setGrupo($grupo);
            $inscripcion->setParticipante($participante);
            $inscripcion->setConvenio($convenio);
            $inscripcion->setNumero($r->numero_formulario);
            $inscripcion->setEstado($r->estado);
            $inscripcion->setCostoCurso($r->costo_curso);
            $inscripcion->setValorDescuento($r->valor_descuento);
            $inscripcion->setTotalAPagar($r->total_a_pagar);

            array_push($inscripciones, $inscripcion);        
        }
    
        return $inscripciones;
    }

    public function listarGruposParaInscripcion(int $calendarioId): array {
        $areas = [];
        try {            
            $items = DB::table('grupos as g')
                        ->select('a.id as areaId', 'a.nombre as areaNombre', 'g.id as grupoId', 'g.nombre as grupoNombre', 'g.dia', 'g.jornada', 'g.observaciones', 'habilitado_para_preinscripcion',
                                 'ca.nombre as periodo', 'c.nombre as cursoNombre', 'cc.costo', 'cc.modalidad', 'o.nombre as orientadorNombre',
                                 DB::raw('(g.cupos - (select count(*) from formulario_inscripcion f where f.grupo_id = g.id and (f.estado = \'Pagado\' or f.estado = \'Pendiente de pago\' or f.estado = \'Revisar comprobante de pago\'))) as cuposDisponibles'))
                        ->join('curso_calendario as cc', function ($join) use ($calendarioId) {
                            $join->on('g.curso_calendario_id', '=', 'cc.id')
                                ->where('cc.calendario_id', '=', $calendarioId);
                        })
                        ->join('calendarios as ca', 'ca.id', '=', 'cc.calendario_id')
                        ->join('cursos as c', 'c.id', '=', 'cc.curso_id')
                        ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
                        ->join('areas as a', 'a.id', '=', 'c.area_id')
                        ->where('g.bloqueado', 0)
                        ->where('g.cancelado', 0)
                        ->where('g.cerrado_para_inscripcion', 0)
                        ->orderBy('areaNombre')
                        ->orderBy('cursoNombre')
                        ->get();

            foreach($items as $item) {
                $key = $item->areaId;
                if (!isset($areas[$key])) {
                    $areas[$key] = new AreaInscripcionDto();
                }                
                $areas[$key]->areaId = $item->areaId;
                $areas[$key]->areaNombre = mb_strtoupper($item->areaNombre, 'UTF-8');
                
                $grupo = new GrupoInscripcionDto();
                    $grupo->grupoId = $item->grupoId;
                    $grupo->grupoNombre = $item->grupoNombre;
                    $grupo->dia = $item->dia;
                    $grupo->jornada = $item->jornada;
                    $grupo->periodo = $item->periodo;
                    $grupo->cursoNombre = $item->cursoNombre;
                    $grupo->costo = FormatoMoneda::PesosColombianos($item->costo);
                    $grupo->modalidad = $item->modalidad;
                    $grupo->cuposDisponibles = $item->cuposDisponibles;  
                    $grupo->nombreOrientador = $item->orientadorNombre;    
                    $grupo->observaciones = $item->observaciones;
                    $grupo->habilitadoParaPreInscripcion = $item->habilitado_para_preinscripcion;

                $areas[$key]->grupos[] = $grupo;
                
            }

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        return $areas;
    }

    public static function pasarANoDisponibleLosBeneficiosPorConvenioDeUnParticipante(): void {        
        try {
            
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            DB::table('convenio_participante')->update(['disponible' => 'NO']);
        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
    }

    public static function listadoParticipantesPorCalendario($calendarioId=0): array {
        $participantes = [];

        try {
            $items = DB::table('participantes as p')
            ->join('formulario_inscripcion as fi', 'fi.participante_id', '=', 'p.id')
            ->join('grupos as g', function($join) use ($calendarioId) {
                $join->on('g.id', '=', 'fi.grupo_id')
                     ->where('g.calendario_id', '=', $calendarioId);
            })
            ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
            ->join('calendarios as ca', 'ca.id', '=', 'g.calendario_id')
            ->join('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
            ->join('cursos as cu', 'cu.id', '=', 'cc.curso_id')
            ->join('areas as a', 'a.id', '=', 'cu.area_id')
            ->leftJoin('convenios as c', 'c.id', '=', 'fi.convenio_id')
            ->select(
                'fi.numero_formulario',
                DB::raw("CONCAT(p.primer_nombre, ' ', p.segundo_nombre, ' ', p.primer_apellido, ' ', p.segundo_apellido) AS nombre_participante"),
                DB::raw("CONCAT(p.tipo_documento, ' - ', p.documento) AS documento_participante"),
                'p.telefono',
                'p.email',
                DB::raw("IF(c.nombre IS NULL, 'N/A', c.nombre) as convenio"),
                DB::raw("
                    IF(fi.convenio_id IS NULL,  
                        IF(fi.estado='Pagado', 'Legalizado', 'No legalizado'), 
                        IF(c.es_cooperativa, 'Legalizado', 
                            IF(fi.estado='Pagado', 'Legalizado', 'No legalizado')
                        )
                    ) as estadoInscripcion
                "),
                'g.nombre as grupo',
                'g.dia',
                'g.jornada',
                'a.nombre as nombre_area',
                'cu.nombre as curso',
                'o.nombre as orientador',
                'ca.nombre as calendario',
                'fi.total_a_pagar',
                'p.sexo',
                'p.estado_civil',
                'p.direccion',
                'p.eps',
                'p.contacto_emergencia',
                'p.telefono_emergencia',
                'p.vinculado_a_unicolmayor',
                'fi.created_at as fecha_inscripcion',
            )
            ->orderBy('p.primer_nombre')
            ->orderBy('p.primer_apellido')
            ->get();

            $participantes[] = ['PARTICIPANTE', 'DOCUMENTO', 'TELEFONO', 'CORREO_ELECTRONICO', 'GENERO', 'ESTADO_CIVIL', 'DIRECCION', 'EPS', 'CONTACTO_EMERGENCIA', 'TELEFONO_EMERGENCIA', 'VINCULADO_UNICOLMAYOR', 'AREA', 'CURSO', 'GRUPO', 'DIA', 'JORNADA', 'CONVENIO', 'TOTAL_PAGO', 'ESTADO', 'PERIODO', 'FECHA_INSCRIPCION'];
            foreach($items as $item) {            
                $tieneVinculoUnicolMayor = ($item->vinculado_a_unicolmayor ? 'SI' : 'NO');    
                $fechaInscripcion = new \DateTime($item->fecha_inscripcion);        
                $participantes[] = [mb_strtoupper($item->nombre_participante, 'UTF-8'),
                                    mb_strtoupper($item->documento_participante, 'UTF-8'), 
                                    $item->telefono, 
                                    mb_strtoupper($item->email, 'UTF-8'),
                                    mb_strtoupper($item->sexo, 'UTF-8'),
                                    mb_strtoupper($item->estado_civil, 'UTF-8'),
                                    mb_strtoupper($item->direccion, 'UTF-8'),
                                    mb_strtoupper($item->eps, 'UTF-8'),
                                    mb_strtoupper($item->contacto_emergencia, 'UTF-8'),
                                    mb_strtoupper($item->telefono_emergencia, 'UTF-8'),
                                    mb_strtoupper($tieneVinculoUnicolMayor, 'UTF-8'),
                                    mb_strtoupper($item->nombre_area, 'UTF-8'),  
                                    mb_strtoupper($item->curso, 'UTF-8'),                                    
                                    $item->grupo, 
                                    mb_strtoupper($item->dia, 'UTF-8'), 
                                    mb_strtoupper($item->jornada, 'UTF-8'),                
                                    mb_strtoupper($item->convenio, 'UTF-8'), 
                                    // number_format($item->total_a_pagar, 0, ',', '.'),
                                    number_format($item->total_a_pagar, 0, '', ''),
                                    mb_strtoupper($item->estadoInscripcion, 'UTF-8'),
                                    mb_strtoupper($item->calendario, 'UTF-8'),
                                    $fechaInscripcion->format('Y-m-d')
                                ];
            }


        } catch(\Exception $e) {
            Sentry::captureException($e);
        }
        
        return $participantes;        
    } 
    
    public static function listaDeGrupos($calendarioId): array {
        $grupos = [];

        $items = DB::table('grupos as g')
                    ->select('g.id', 'g.dia', 'g.jornada', 'g.curso_calendario_id', 'g.cupos', 'g.nombre', 'g.bloqueado', 'g.cancelado', 'o.nombre as nombre_orientador',
                            'o.id as orientador_id', 'c.id as curso_id', 's.id as salon_id', 'ca.id as calendario_id', 'c.nombre as nombre_curso', 'ca.nombre as calendario_nombre',
                            DB::raw('(select count(fi.grupo_id) from formulario_inscripcion fi where fi.grupo_id = g.id and fi.estado <> "Anulado" and fi.estado <> "Aplazado") as totalInscritos')
                            )
                    ->join('orientadores as o', 'o.id', '=', 'g.orientador_id')
                    ->join('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
                    ->join('calendarios as ca', 'ca.id', '=', 'cc.calendario_id')
                    ->join('cursos as c', 'c.id', '=', 'cc.curso_id')
                    ->join('salones as s', 's.id', '=', 'g.salon_id')
                    ->where('g.calendario_id', $calendarioId)
                    // ->orderBy('c.nombre')
                    ->orderBy('totalInscritos', 'desc')
                    ->get();
        
        foreach($items as $item) {
            $grupo = new Grupo();
            $grupo->setId($item->id);
            $grupo->setCupo($item->cupos);
            $grupo->setNombre($item->nombre);
            $grupo->setJornada($item->jornada);
            $grupo->setDia($item->dia);
            $grupo->setCancelado($item->cancelado);
            $grupo->setTotalInscritos($item->totalInscritos);
            $grupo->setCursoCalendario(new CursoCalendario(new Calendario($item->calendario_nombre), new Curso($item->nombre_curso)));
            
            $orientador = new Orientador();
            $orientador->setNombre($item->nombre_orientador);
            $grupo->setOrientador($orientador);

            $grupos[] = $grupo;
        }

        return $grupos;
    }

    public function listarConveniosPorCalendario($calendarioId): array
    {
        $convenios = [];

        try 
        {
            $registros = ConvenioDao::where('calendario_id', $calendarioId)->get();
            foreach($registros as $registro)
            {
                $convenio = new Convenio();
                $convenio->setId($registro->id);
                $convenio->setNombre($registro->nombre);
                $convenio->setFecInicio($registro->fec_ini);
                $convenio->setFecFin($registro->fec_fin);
                $convenio->setDescuento($registro->descuento);
                $convenio->setEsCooperativa($registro->es_cooperativa);
                $convenio->setTotalAPagar($registro->total_a_pagar);
                $convenio->setComentarios($registro->comentarios);
                $convenio->setHaSidoFacturado($registro->ha_sido_facturado);

                $convenios[] = $convenio;
            }
        } 
        catch (\Exception $e) 
        {
            Sentry::captureException($e);
        }

        return $convenios;
    }
}