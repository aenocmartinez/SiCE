<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\domain\Calendario;
use Src\domain\Convenio;
use Src\domain\Curso;
use Src\domain\CursoCalendario;
use Src\domain\FormularioInscripcion;
use Src\domain\Grupo;
use Src\domain\Participante;
use Src\domain\repositories\ParticipanteRepository;

use Sentry\Laravel\Facade as Sentry;
use Src\domain\Aplazamiento;
use Src\infraestructure\util\Paginate;
use Src\view\dto\CursoAprobadoDTO;
use Src\view\dto\CursoParticipadoDTO;
use Src\view\dto\FormularioComentarioDto;

class ParticipanteDao extends Model implements ParticipanteRepository {

    protected $table = 'participantes';
    protected $fillable = ['primer_nombre',
                            'segundo_nombre',
                            'primer_apellido',
                            'segundo_apellido',
                            'fecha_nacimiento',
                            'tipo_documento',
                            'documento',
                            'sexo',
                            'estado_civil',
                            'direccion',
                            'telefono',
                            'email',
                            'eps',
                            'contacto_emergencia',
                            'telefono_emergencia',
                            'vinculado_a_unicolmayor'
                        ];

    public function aplazamientos() {
        return $this->hasMany(AplazamientoDao::class, 'participante_id');
    } 
    
    public function formulariosInscripcion() {
        return $this->hasMany(FormularioInscripcionDao::class, 'participante_id');
    }

    public function buscarParticipantePorDocumento(string $tipo, string $documento): Participante {
        $participante = new Participante;
        try {

            $participanteDao = ParticipanteDao::where('tipo_documento', $tipo)->where('documento', $documento)->first();
            if ($participanteDao) {
                $participante->setId($participanteDao->id);
                $participante->setPrimerNombre($participanteDao->primer_nombre);
                $participante->setSegundoNombre($participanteDao->segundo_nombre);
                $participante->setPrimerApellido($participanteDao->primer_apellido);
                $participante->setSegundoApellido($participanteDao->segundo_apellido);
                $participante->setFechaNacimiento($participanteDao->fecha_nacimiento);
                $participante->setTipoDocumento($participanteDao->tipo_documento);
                $participante->setDocumento($participanteDao->documento);
                $participante->setSexo($participanteDao->sexo);
                $participante->setEstadoCivil($participanteDao->estado_civil);
                $participante->setDireccion($participanteDao->direccion);
                $participante->setTelefono($participanteDao->telefono);
                $participante->setEmail($participanteDao->email);
                $participante->setEps($participanteDao->eps);
                $participante->setContactoEmergencia($participanteDao->contacto_emergencia);
                $participante->setTelefonoEmergencia($participanteDao->telefono_emergencia);
                $participante->setVinculadoUnicolMayor($participanteDao->vinculado_a_unicolmayor);
            }

        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $participante;
    }

    public function crearParticipante(Participante $participante): bool {
        $exito = false;
        try {

            $idUsuarioSesion = Auth::id();
            if (strlen($idUsuarioSesion)==0) {
                $idUsuarioSesion = env('SYSTEM_USER');
            }
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");
                        
            ParticipanteDao::create([
                'primer_nombre' => $participante->getPrimerNombre(),
                'segundo_nombre' => $participante->getSegundoNombre(),
                'primer_apellido' => $participante->getPrimerApellido(),
                'segundo_apellido' => $participante->getSegundoApellido(),
                'fecha_nacimiento' => $participante->getFechaNacimiento(),
                'tipo_documento' => $participante->getTipoDocumento(),
                'documento' => $participante->getDocumento(),
                'sexo' => $participante->getSexo(),
                'estado_civil' => $participante->getEstadoCivil(),
                'direccion' => $participante->getDireccion(),
                'telefono' => $participante->getTelefono(),
                'email' => $participante->getEmail(),
                'eps' => $participante->getEps(),
                'contacto_emergencia' => $participante->getContactoEmergencia(),
                'telefono_emergencia' => $participante->getTelefonoEmergencia(),
                'vinculado_a_unicolmayor' => $participante->vinculadoUnicolMayor(),
            ]);

            $exito = true;


        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $exito;
    }

    public function actualizarParticipante(Participante $participante): bool {
        $exito = false;
        try {

            $idUsuarioSesion = Auth::id();            
            if (strlen($idUsuarioSesion)==0) {
                $idUsuarioSesion = env('SYSTEM_USER');
            }
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");            

            ParticipanteDao::whereId($participante->getId())
                ->update([
                    'primer_nombre' => $participante->getPrimerNombre(),
                    'segundo_nombre' => $participante->getSegundoNombre(),
                    'primer_apellido' => $participante->getPrimerApellido(),
                    'segundo_apellido' => $participante->getSegundoApellido(),
                    'fecha_nacimiento' => $participante->getFechaNacimiento(),
                    'tipo_documento' => $participante->getTipoDocumento(),
                    'documento' => $participante->getDocumento(),
                    'sexo' => $participante->getSexo(),
                    'estado_civil' => $participante->getEstadoCivil(),
                    'direccion' => $participante->getDireccion(),
                    'telefono' => $participante->getTelefono(),
                    'email' => $participante->getEmail(),
                    'eps' => $participante->getEps(),
                    'contacto_emergencia' => $participante->getContactoEmergencia(),
                    'telefono_emergencia' => $participante->getTelefonoEmergencia(),
                    'vinculado_a_unicolmayor' => $participante->vinculadoUnicolMayor(),
                ]);

            $exito = true;


        } catch (Exception $e) {
            dd($e->getMessage());
            // Sentry::captureException($e);
        }

        return $exito;
    }

    public function buscarParticipantePorId(int $participanteId): Participante {
        $participante = new Participante;
        try {

            $participanteDao = ParticipanteDao::find($participanteId);
            if ($participanteDao) {
                $participante->setId($participanteDao->id);
                $participante->setPrimerNombre($participanteDao->primer_nombre);
                $participante->setSegundoNombre($participanteDao->segundo_nombre);
                $participante->setPrimerApellido($participanteDao->primer_apellido);
                $participante->setSegundoApellido($participanteDao->segundo_apellido);
                $participante->setFechaNacimiento($participanteDao->fecha_nacimiento);
                $participante->setTipoDocumento($participanteDao->tipo_documento);
                $participante->setDocumento($participanteDao->documento);
                $participante->setSexo($participanteDao->sexo);
                $participante->setEstadoCivil($participanteDao->estado_civil);
                $participante->setDireccion($participanteDao->direccion);
                $participante->setTelefono($participanteDao->telefono);
                $participante->setEmail($participanteDao->email);
                $participante->setEps($participanteDao->eps);
                $participante->setContactoEmergencia($participanteDao->contacto_emergencia);
                $participante->setTelefonoEmergencia($participanteDao->telefono_emergencia);
                $participante->setVinculadoUnicolMayor($participanteDao->vinculado_a_unicolmayor);

                $aplazamientos = [];
                foreach($participanteDao->aplazamientos()->get() as $item) {
                    $aplazamiento = new Aplazamiento();
                    $aplazamiento->setId($item->id);
                    $aplazamiento->setFechaCaducidad($item->fecha_caducidad);
                    $aplazamiento->setComentarios($item->comentarios);
                    $aplazamiento->setRedimido($item->redimido);
                    $aplazamiento->setSaldo($item->saldo_a_favor);
                    $aplazamiento->setVaouchers($item->formulario->pagos->toArray());
                    // dd($item->formulario->pagos->toArray());

                    if (!$aplazamiento->fueRedimido()) 
                    {
                        $aplazamientos[] = $aplazamiento;
                    }
                }      
                
                $participante->setAplazamientos($aplazamientos);
            }

        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $participante;          
    }

    public function listarParticipantes($page=1): Paginate {
        $paginate = new Paginate($page);
        $participantes = [];
        try {

            $resultados = ParticipanteDao::select(
                            'id', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 
                            'tipo_documento', 'documento', 'sexo', 'telefono', 'email')
                            ->skip($paginate->Offset())->take($paginate->Limit())
                            ->orderBy('primer_nombre')
                            ->get();

            foreach($resultados as $resultado) {           
                $participante = new Participante();
                $participante->setId($resultado->id);
                $participante->setPrimerNombre($resultado->primer_nombre);
                $participante->setSegundoNombre($resultado->segundo_nombre);
                $participante->setPrimerApellido($resultado->primer_apellido);
                $participante->setSegundoApellido($resultado->segundo_apellido);
                $participante->setTipoDocumento($resultado->tipo_documento);
                $participante->setDocumento($resultado->documento);
                $participante->setSexo($resultado->sexo);
                $participante->setTelefono($resultado->telefono);
                $participante->setEmail($resultado->email);

                array_push($participantes, $participante);
            }

        } catch (Exception $e) {
            dd($e);
            Sentry::captureException($e);
        }

        $paginate->setRecords($participantes);
        $paginate->setTotalRecords(ParticipanteDao::count());
        
        return $paginate;
    }

    public function buscadorParticipantes(string $criterio, $page=1): Paginate {
        $paginate = new Paginate($page);
        $participantes = [];
        try {

            // $criterio = str_replace(" ","%", $criterio);            

            $query = ParticipanteDao::select('id','primer_nombre','segundo_nombre','primer_apellido',
                        'segundo_apellido','tipo_documento','documento','sexo','telefono','email'
                    )
                    ->whereRaw("MATCH(primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, documento, telefono, email) 
                                AGAINST(? IN BOOLEAN MODE)", ['+'.$criterio.'*']);

            $totalRecords = $query->count();

            $items = $query->skip($paginate->Offset())->take($paginate->Limit())->get();
            

            foreach($items as $item) {           
                $participante = new Participante();
                $participante->setId($item->id);
                $participante->setPrimerNombre($item->primer_nombre);
                $participante->setSegundoNombre($item->segundo_nombre);
                $participante->setPrimerApellido($item->primer_apellido);
                $participante->setSegundoApellido($item->segundo_apellido);
                $participante->setTipoDocumento($item->tipo_documento);
                $participante->setDocumento($item->documento);
                $participante->setSexo($item->sexo);
                $participante->setTelefono($item->telefono);
                $participante->setEmail($item->email);

                $participantes[] = $participante;
            }

        } catch (Exception $e) {
            dd($e->getMessage());
            Sentry::captureException($e);
        }

        $paginate->setRecords($participantes);
        $paginate->setTotalRecords($totalRecords);

        return $paginate;
    }

    public function listarFormulariosDeInscripcionParticipante(int $participanteId): array {
        $formularios = array();

        try {
            $calendarioVigente = Calendario::Vigente();
            if (!$calendarioVigente->existe()) {
                return $formularios;
            }
            
            $resultados = FormularioInscripcionDao::select(
                    'formulario_inscripcion.id',
                    'formulario_inscripcion.participante_id',
                    'formulario_inscripcion.numero_formulario',
                    'formulario_inscripcion.estado',
                    'formulario_inscripcion.total_a_pagar',
                    'formulario_inscripcion.fecha_max_legalizacion',
                    'formulario_inscripcion.created_at',
                    'grupos.id as grupo_id',
                    'grupos.dia',
                    'grupos.jornada',
                    'curso_calendario.modalidad',
                    'calendarios.nombre as nombre_calendario',
                    'calendarios.id as calendarioId',
                    'calendarios.fec_ini as calendarioFechaIni',
                    'calendarios.fec_fin as calendarioFechaFin',
                    'cursos.nombre as nombre_curso',
                    'convenios.nombre as nombre_convenio',
                    'convenios.id as convenioId'
                )
                ->join('participantes as p', 'p.id', '=', 'formulario_inscripcion.participante_id')
                ->join('grupos', 'grupos.id', '=', 'formulario_inscripcion.grupo_id')
                ->join('calendarios', 'calendarios.id', '=', 'grupos.calendario_id')
                ->join('curso_calendario', 'curso_calendario.id', '=', 'grupos.curso_calendario_id')
                ->join('cursos', 'cursos.id', '=', 'curso_calendario.curso_id')
                ->leftJoin('convenios', 'convenios.id', '=', 'formulario_inscripcion.convenio_id')
                ->where('p.id', $participanteId)
                ->where('grupos.calendario_id', $calendarioVigente->getId())
                ->where('formulario_inscripcion.estado', '<>', 'Anulado')
                ->orderByDesc('formulario_inscripcion.id')
                ->get();

                foreach($resultados as $resultado) {
                    $formulario = new FormularioInscripcion();

                    $formulario->setId($resultado->id);
                    $formulario->setNumero($resultado->numero_formulario);
                    $formulario->setEstado($resultado->estado);
                    $formulario->setTotalAPagar($resultado->total_a_pagar);
                    $formulario->setFechaCreacion($resultado->created_at);
                    $formulario->setFechaMaxLegalizacion($resultado->fecha_max_legalizacion);
                    
                    $participante = new Participante();
                    $participante->setId($resultado->participante_id);

                    $formulario->setParticipante($participante);
                    
                    $convenio = new Convenio();
                    if (!is_null($resultado->nombre_convenio)) {                        
                        $convenio->setNombre($resultado->nombre_convenio);
                        $convenio->setid($resultado->convenioId);
                    }

                    $formulario->setConvenio($convenio);

                    $grupo = new Grupo();
                    $grupo->setDia($resultado->dia);
                    $grupo->setJornada($resultado->jornada);
                    // $grupo->setHora($resultado->hora);
                    $grupo->setId($resultado->grupo_id);

                        $calendario = new Calendario();
                        $calendario->setId($resultado->calendarioId);
                        $calendario->setNombre($resultado->nombre_calendario);
                        $calendario->setFechaInicio($resultado->calendarioFechaIni);
                        $calendario->setFechaFinal($resultado->calendarioFechaFin);

                        $curso = new Curso();
                        $curso->setNombre($resultado->nombre_curso);

                        $cursoCalendario = new CursoCalendario($calendario, $curso);
                        $cursoCalendario->setModalidad($resultado->modalidad);

                    $grupo->setCursoCalendario($cursoCalendario);

                    $formulario->setGrupo($grupo);

                    array_push($formularios, $formulario);

                }
            
        } catch(Exception $e) {
            Sentry::captureException($e);
        }

        return $formularios;
    }

    public function listarFormulariosPendientesDePago(int $participanteId): array {
        $formularios = array();

        try {
            $calendarioVigente = Calendario::Vigente();
            if (!$calendarioVigente->existe()) {
                return $formularios;
            }
            
            $resultados = FormularioInscripcionDao::select(
                    'formulario_inscripcion.id',
                    'formulario_inscripcion.participante_id',
                    'formulario_inscripcion.numero_formulario',
                    'formulario_inscripcion.estado',
                    'formulario_inscripcion.total_a_pagar',
                    'formulario_inscripcion.fecha_max_legalizacion',
                    'formulario_inscripcion.created_at',
                    'grupos.id as grupo_id',
                    'grupos.dia',
                    'grupos.jornada',
                    'curso_calendario.modalidad',
                    'calendarios.nombre as nombre_calendario',
                    'calendarios.id as calendarioId',
                    'calendarios.fec_ini as calendarioFechaIni',
                    'calendarios.fec_fin as calendarioFechaFin',
                    'cursos.nombre as nombre_curso',
                    'convenios.nombre as nombre_convenio',
                    'convenios.id as convenioId'
                )
                ->join('participantes as p', 'p.id', '=', 'formulario_inscripcion.participante_id')
                ->join('grupos', 'grupos.id', '=', 'formulario_inscripcion.grupo_id')
                ->join('calendarios', 'calendarios.id', '=', 'grupos.calendario_id')
                ->join('curso_calendario', 'curso_calendario.id', '=', 'grupos.curso_calendario_id')
                ->join('cursos', 'cursos.id', '=', 'curso_calendario.curso_id')
                ->leftJoin('convenios', 'convenios.id', '=', 'formulario_inscripcion.convenio_id')
                ->where('p.id', $participanteId)
                ->where('grupos.calendario_id', $calendarioVigente->getId())
                ->whereNotIn('formulario_inscripcion.estado', ['Anulado', 'Pagado', 'Revisar comprobante de pago'])
                ->orderByDesc('formulario_inscripcion.id')
                ->get();

                foreach($resultados as $resultado) {
                    $formulario = new FormularioInscripcion();

                    $formulario->setId($resultado->id);
                    $formulario->setNumero($resultado->numero_formulario);
                    $formulario->setEstado($resultado->estado);
                    $formulario->setTotalAPagar($resultado->total_a_pagar);
                    $formulario->setFechaCreacion($resultado->created_at);
                    $formulario->setFechaMaxLegalizacion($resultado->fecha_max_legalizacion);
                    
                    $participante = new Participante();
                    $participante->setId($resultado->participante_id);

                    $formulario->setParticipante($participante);
                    
                    $convenio = new Convenio();
                    if (!is_null($resultado->nombre_convenio)) {                        
                        $convenio->setNombre($resultado->nombre_convenio);
                        $convenio->setid($resultado->convenioId);
                    }

                    $formulario->setConvenio($convenio);

                    $grupo = new Grupo();
                    $grupo->setDia($resultado->dia);
                    $grupo->setJornada($resultado->jornada);
                    // $grupo->setHora($resultado->hora);
                    $grupo->setId($resultado->grupo_id);

                        $calendario = new Calendario();
                        $calendario->setId($resultado->calendarioId);
                        $calendario->setNombre($resultado->nombre_calendario);
                        $calendario->setFechaInicio($resultado->calendarioFechaIni);
                        $calendario->setFechaFinal($resultado->calendarioFechaFin);

                        $curso = new Curso();
                        $curso->setNombre($resultado->nombre_curso);

                        $cursoCalendario = new CursoCalendario($calendario, $curso);
                        $cursoCalendario->setModalidad($resultado->modalidad);

                    $grupo->setCursoCalendario($cursoCalendario);

                    $formulario->setGrupo($grupo);

                    array_push($formularios, $formulario);

                }
            
        } catch(Exception $e) {
            Sentry::captureException($e);
        }

        return $formularios;
    }

    public function eliminarParticipante(int $participanteId): bool {
        $exito = true;
        try {

            $participante = ParticipanteDao::find($participanteId);
            if ($participante) {
                $idUsuarioSesion = Auth::id();
                if (strlen($idUsuarioSesion)==0) {
                    $idUsuarioSesion = env('SYSTEM_USER');
                }
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");
                                
                $participante->delete();
            }

        } catch (Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }
        return $exito;
    }

    public static function numeroParticipantesPorGeneroYCalendario($sexo='M', $calendarioId): int {   
        
        return FormularioInscripcionDao::join('grupos as g', 'g.id', '=', 'formulario_inscripcion.grupo_id')
            ->where(function($query) {
                $query->where('formulario_inscripcion.estado', 'Pagado')
                    ->orWhere('formulario_inscripcion.estado', 'Pendiente de pago');
            })
            ->leftJoin('participantes', 'formulario_inscripcion.participante_id', '=', 'participantes.id')
            ->where('g.calendario_id', $calendarioId)
            ->where('participantes.sexo', '=', $sexo)
            ->count();               
    }

    public static function numeroParticipantesPorConvenioYCalendario($calendarioId): int {

        $total = FormularioInscripcionDao::join('grupos as g', function ($join) use ($calendarioId) {
            $join->on('formulario_inscripcion.grupo_id', '=', 'g.id')
                 ->where('g.calendario_id', '=', $calendarioId)
                 ->where('formulario_inscripcion.estado', '=', 'Pagado')
                 ->whereNotNull('formulario_inscripcion.convenio_id');
        })
        ->count();
    
        
        return $total;
    }

    public static function numeroParticipantesUnicosPorCalendario($calendarioId): int {

        $subquery = DB::table('formulario_inscripcion as fi')
                    ->join('grupos as g', 'g.id', '=', 'fi.grupo_id')
                    ->leftJoin('convenios as c', 'c.id', '=', 'fi.convenio_id')
                    ->where('g.calendario_id', $calendarioId)
                    ->select(
                        'fi.participante_id',
                        'fi.convenio_id',
                        'fi.estado',
                        'c.es_cooperativa',
                        DB::raw("
                            CASE
                                WHEN fi.convenio_id IS NULL THEN
                                    CASE
                                        WHEN fi.estado = 'Pagado' THEN 'Legalizado'
                                        ELSE 'No legalizado'
                                    END
                                ELSE
                                    CASE
                                        WHEN c.es_cooperativa = 1 THEN 'Legalizado'
                                        ELSE
                                            CASE
                                                WHEN fi.estado = 'Pagado' THEN 'Legalizado'
                                                ELSE 'No legalizado'
                                            END
                                    END
                            END AS estado_legalizado
                        ")
                    );

                    $numeroDeParticipantesUnicos = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
                    ->mergeBindings($subquery)
                    ->where('sub.estado_legalizado', 'Legalizado')
                    ->select(DB::raw('COUNT(DISTINCT sub.participante_id) AS numero_de_participantes_unicos'))
                    ->first();

                $numeroDeParticipantesUnicos = $numeroDeParticipantesUnicos->numero_de_participantes_unicos;
    
        
        return $numeroDeParticipantesUnicos;
    }    

    public function buscarBeneficiosAlParticipante(int $participanteId): Convenio {
        $convenio = new Convenio();

        try {
            $item = DB::table('convenios as c')
                ->select('c.id', 'c.descuento')
                ->join('convenio_participante as cp', 'c.id', '=', 'cp.convenio_id')
                ->join('participantes as p', 'p.documento', '=', 'cp.cedula')
                ->where('p.id', $participanteId)
                // ->where('cp.redimido', 'NO')
                ->where('cp.disponible', 'SI')
                ->first();
            

            if($item) {
                $convenio->setId($item->id);
                $convenio->setDescuento($item->descuento);
            }

        } catch(\Exception $e) {
            Sentry::captureException($e);
        }

        return $convenio;
    }

    public static function totalDeFormulariosInscritoPorUnParticipanteEnUnPeriodo($participanteId=0, $calendarioId=0): int {
            $total = DB::table('formulario_inscripcion as f')
            ->join('grupos as g', function ($join) use ($calendarioId) {
                $join->on('g.id', '=', 'f.grupo_id')
                    ->where('g.calendario_id', '=', $calendarioId);
            })
            ->where('f.participante_id', '=', $participanteId)
            ->count();        
        return $total;
    }

    public function buscarFormularioInscripcionPorParticipanteYConvenioPendienteDepago($participanteId=0, $convenioId=0): FormularioInscripcion {
        $formularioInscripcion = new FormularioInscripcion;

        try {
            $resultado = FormularioInscripcionDao::select('id', 'costo_curso')
                        ->where('participante_id', $participanteId)
                        ->where('convenio_id', $convenioId)
                        // ->where('estado', 'Pendiente de pago')
                        ->first();
            
            if ($resultado) {
                $formularioInscripcion->setId($resultado->id);
                $formularioInscripcion->setCostoCurso($resultado->costo_curso);
            }

        } catch(\Exception $e) {
            dd($e->getMessage());
            Sentry::captureException($e);
        }

        return $formularioInscripcion;
    }

    public function formularios_inscritos_en_un_periodo($participante_id, $periodo_id): array
    {
        $formularios = [];
        $registros = DB::table('formulario_inscripcion as f')
                        ->select(
                            'f.id',
                            'f.numero_formulario',
                            'f.estado',
                            'c.nombre',
                            'f.comentarios'
                        )
                        ->join('grupos as g', 'g.id', '=', 'f.grupo_id')
                        ->join('curso_calendario as cc', 'cc.id', '=', 'g.curso_calendario_id')
                        ->join('cursos as c', 'c.id', '=', 'cc.curso_id')
                        ->join('participantes as p', 'p.id', '=', 'f.participante_id')
                        ->where('f.participante_id', $participante_id)
                        ->where('g.calendario_id', $periodo_id)
                        ->get();

        foreach($registros as $registro)
        {
            $formulario = new FormularioComentarioDto();
            $formulario->setFormularioId($registro->id);
            $formulario->setNumeroFormulario($registro->numero_formulario);
            $formulario->setEstadoFormulario($registro->estado);
            $formulario->setCurso($registro->nombre);
            $formulario->setComentario($registro->comentarios);

            $formularios[] = $formulario;
        }
        
        return $formularios;
    }
    
    public function listarCursosAprobados(int $participanteID): array
    {
        $resultados = DB::table('asistencia_clase as a')
            ->join('grupos as g', 'a.grupo_id', '=', 'g.id')
            ->join('curso_calendario as cc', 'g.curso_calendario_id', '=', 'cc.id')
            ->join('cursos as cu', 'cc.curso_id', '=', 'cu.id')
            ->joinSub(
                DB::table('asistencia_clase')
                    ->select('grupo_id', DB::raw('COUNT(DISTINCT sesion) as total_sesiones'))
                    ->groupBy('grupo_id'),
                'sesiones',
                'sesiones.grupo_id',
                '=',
                'a.grupo_id'
            )
            ->where('a.participante_id', $participanteID)
            ->where('a.presente', true)
            ->groupBy('cu.id', 'cu.nombre', 'g.id', 'sesiones.total_sesiones')
            ->havingRaw('ROUND(100 * COUNT(a.id) / sesiones.total_sesiones, 2) > 80')
            ->orderByDesc(DB::raw('ROUND(100 * COUNT(a.id) / sesiones.total_sesiones, 2)'))
            ->get([
                'cu.id as curso_id',
                'cu.nombre as nombre_curso',
                'g.id as grupo_id',
                DB::raw('COUNT(a.id) as asistencias'),
                'sesiones.total_sesiones',
                DB::raw('ROUND(100 * COUNT(a.id) / sesiones.total_sesiones, 2) as porcentaje_asistencia'),
            ]);
    
        return $resultados->map(function ($row) {
            return new CursoAprobadoDTO(
                $row->curso_id,
                $row->nombre_curso,
                $row->grupo_id,
                $row->asistencias,
                $row->total_sesiones,
                $row->porcentaje_asistencia
            );
        })->all();
    }
    
    public function listarCursosParticipados(int $participanteID): array
    {
        $resultados = DB::table('asistencia_clase as a')
            ->join('grupos as g', 'a.grupo_id', '=', 'g.id')
            ->join('curso_calendario as cc', 'g.curso_calendario_id', '=', 'cc.id')
            ->join('cursos as cu', 'cc.curso_id', '=', 'cu.id')
            ->joinSub(
                DB::table('asistencia_clase')
                    ->select('grupo_id', DB::raw('COUNT(DISTINCT sesion) as total_sesiones'))
                    ->groupBy('grupo_id'),
                'sesiones',
                'sesiones.grupo_id',
                '=',
                'a.grupo_id'
            )
            ->where('a.participante_id', $participanteID)
            ->where('g.cancelado', false)
            ->groupBy('cu.id', 'cu.nombre', 'g.id', 'sesiones.total_sesiones')
            ->orderByDesc(DB::raw('ROUND(100 * SUM(a.presente) / sesiones.total_sesiones, 2)'))
            ->get([
                'cu.id as curso_id',
                'cu.nombre as nombre_curso',
                'g.id as grupo_id',
                DB::raw('SUM(a.presente) as asistencias'),
                'sesiones.total_sesiones',
                DB::raw('ROUND(100 * SUM(a.presente) / sesiones.total_sesiones, 2) as porcentaje_asistencia'),
            ]);
    
        return $resultados->map(function ($row) {
            return new CursoParticipadoDto(
                $row->curso_id,
                $row->nombre_curso,
                $row->grupo_id,
                $row->asistencias,
                $row->total_sesiones,
                $row->porcentaje_asistencia,
                $row->porcentaje_asistencia >= 80
            );
        })->all();
    }
        
}