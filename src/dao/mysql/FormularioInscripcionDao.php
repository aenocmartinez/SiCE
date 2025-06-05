<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\domain\Convenio;
use Src\domain\FormularioInscripcion;
use Src\domain\repositories\FormularioRepository;

use Sentry\Laravel\Facade as Sentry;
use Src\domain\Calendario;
use Src\domain\FormularioInscripcionPago;
use Src\infraestructure\util\FormatoFecha;
use Src\infraestructure\util\Paginate;

class FormularioInscripcionDao extends Model implements FormularioRepository {

    protected $table = 'formulario_inscripcion';
    protected $fillable = ['grupo_id', 
                            'participante_id', 
                            'convenio_id', 
                            'costo_curso', 
                            'numero_formulario',
                            'valor_descuento', 
                            'total_a_pagar',
                            'fecha_max_legalizacion',
                            'path_comprobante_pago',
                            'comentarios',
                            'medio_inscripcion',
                            'estado'
                        ];
    
    public function grupo() {
        return $this->belongsTo(GrupoDao::class, 'grupo_id');
    }

    public function participante() {
        return $this->belongsTo(ParticipanteDao::class, 'participante_id');
    }  
    
    public function pagos() {
        return $this->hasMany(FormularioInscripcionPagoDao::class, 'formulario_id');
    }

    public function listarFormulariosPorPeriodo(int $calendarioId, $estado, $documento, $page=1): Paginate {    
        $paginate = new Paginate($page);
        
        $formularios = [];
        try {
            $grupoDao = new GrupoDao();
            $participanteDao = new ParticipanteDao();
            $convenioDao = new ConvenioDao();

            $query = FormularioInscripcionDao::with('grupo')
            ->leftJoin('participantes', 'formulario_inscripcion.participante_id', '=', 'participantes.id')
            ->leftJoin('grupos', 'formulario_inscripcion.grupo_id', '=', 'grupos.id')
            ->whereHas('grupo', function ($query) use ($calendarioId) {
                $query->where('calendario_id', $calendarioId);
            })
            ->where(function ($query) use ($estado, $documento) {
                $query->when($estado, function ($query, $estado) {
                    $query->where('estado', $estado);
                })
                ->when($documento, function ($query, $documento) {
                    $query->where(function ($query) use ($documento) {
                        $query->where('participantes.documento', 'LIKE', "%{$documento}%")
                              ->orWhere('formulario_inscripcion.numero_formulario', 'LIKE', "%{$documento}%")
                              ->orWhere('grupos.nombre', 'LIKE', "%{$documento}%")
                              ->orWhereRaw("CONCAT(participantes.primer_nombre, ' ', participantes.segundo_nombre, ' ', participantes.primer_apellido, ' ', participantes.segundo_apellido) LIKE ?", ["%{$documento}%"])
                              ->orWhereRaw("CONCAT(participantes.primer_nombre, ' ', participantes.primer_apellido, ' ', participantes.segundo_apellido) LIKE ?", ["%{$documento}%"])
                              ->orWhereRaw("CONCAT(participantes.primer_nombre, ' ', participantes.segundo_nombre, ' ', participantes.primer_apellido) LIKE ?", ["%{$documento}%"])
                              ->orWhereRaw("CONCAT(participantes.primer_nombre, ' ', participantes.primer_apellido) LIKE ?", ["%{$documento}%"]);
                    });
                });
            })
            ->orderByDesc('participante_id')
            ->orderByDesc('formulario_inscripcion.id');
        
        
        
        $totalRecords = $query->count(); 
        
        $resultados = $query->skip($paginate->Offset())
            ->take($paginate->Limit())
            ->get(['formulario_inscripcion.id', 'grupo_id', 'participante_id', 'convenio_id', 'formulario_inscripcion.created_at', 
                    'estado', 'costo_curso', 'valor_descuento', 'total_a_pagar',  
                    'numero_formulario', 'fecha_max_legalizacion']);

            foreach($resultados as $resultado) {
                $formulario = new FormularioInscripcion();
                $formulario->setId($resultado->id);
                $formulario->setEstado($resultado->estado);
                $formulario->setFechaCreacion($resultado->created_at);
                $formulario->setTotalAPagar($resultado->total_a_pagar);
                $formulario->setNumero($resultado->numero_formulario);
                $formulario->setFechaMaxLegalizacion($resultado->fecha_max_legalizacion);

                $grupo = $grupoDao->buscarGrupoPorId($resultado->grupo_id);
                $participante = $participanteDao->buscarParticipantePorId($resultado->participante_id);
                $convenio = new Convenio();

                if (!is_null($resultado->convenio_id)) {
                    $convenio = $convenioDao->buscarConvenioPorId($resultado->convenio_id);
                }
                
                $formulario->setGrupo($grupo);
                $formulario->setParticipante($participante);
                $formulario->setConvenio($convenio);
                
                array_push($formularios, $formulario);
            }

        } catch (Exception $e) {
            dd($e->getMessage());
            Sentry::captureException($e);
        }

        $paginate->setTotalRecords($totalRecords);
        $paginate->setRecords($formularios);

        return $paginate;
    }

    public function crearFormulario(FormularioInscripcion &$formulario): bool {    

        $exito = true;

        try {

            $idUsuarioSesion = Auth::id();            
            if (strlen($idUsuarioSesion)==0) {
                $idUsuarioSesion = env('SYSTEM_USER');
            }
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");
                        
            $participante = ParticipanteDao::find($formulario->getParticipanteId());
            if ($participante) {
                $nuevoFormulario = new FormularioInscripcionDao();
                $nuevoFormulario->grupo_id = $formulario->getGrupoId();
                if ($formulario->getConvenioId() > 0) {
                    $nuevoFormulario->convenio_id = $formulario->getConvenioId();
                }
                $nuevoFormulario->costo_curso = $formulario->getCostoCurso();
                $nuevoFormulario->valor_descuento = $formulario->getValorDescuento();
                $nuevoFormulario->total_a_pagar = $formulario->getTotalAPagar();
                

                $nuevoFormulario->created_at =  $formulario->getFechaCreacion();
                $nuevoFormulario->updated_at =  $formulario->getFechaCreacion();
                $nuevoFormulario->fecha_max_legalizacion = $formulario->getFechaMaxLegalizacion();
                $nuevoFormulario->estado = $formulario->getEstado();
                $nuevoFormulario->medio_inscripcion = $formulario->getMedioInscripcion();

                $nuevoFormulario->numero_formulario = $formulario->getNumero();
                $nuevoFormulario->path_comprobante_pago = $formulario->getPathComprobantePago();
                $nuevoFormulario->comentarios = $formulario->getComentarios();

                $participante->formulariosInscripcion()->save($nuevoFormulario);

                $formulario->setId($nuevoFormulario->id);
            }
        } catch(Exception $e) {
            $exito = false;               
            Sentry::captureException($e);
        }

        return $exito;
    }
    
    public function actualizarFormulario(FormularioInscripcion $formulario): bool {
        $exito = true;

        try {
            $formularioDao = FormularioInscripcionDao::find($formulario->getId());
            if ($formularioDao) { 
                
                $idUsuarioSesion = Auth::id();
                if (strlen($idUsuarioSesion)==0) {
                    $idUsuarioSesion = env('SYSTEM_USER');
                }
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");

                $formularioDao->valor_descuento = $formulario->getValorDescuento();
                $formularioDao->total_a_pagar = $formulario->getTotalAPagar();
                $formularioDao->estado = $formulario->getEstado();
                $formularioDao->comentarios = $formulario->getComentarios();
                
                if ($formulario->getPathComprobantePago()) {
                    $formularioDao->path_comprobante_pago = $formulario->getPathComprobantePago();
                }
                
                if ($formulario->getConvenioId() > 0) {
                    $formularioDao->convenio_id = $formulario->getConvenioId();
                }

                $formularioDao->save();
            }            
        } catch(Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }

        return $exito;
    }
    
    public function anularInscripcion($numeroFormulario, $comentario=""): bool {
        $exito = true;
        try {
            $formulario = FormularioInscripcionDao::where('numero_formulario', $numeroFormulario)->first();
            if ($formulario) {
                
                $idUsuarioSesion = Auth::id();
                if (strlen($idUsuarioSesion)==0) {
                    $idUsuarioSesion = env('SYSTEM_USER');
                }
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");

                $formulario->estado = 'Anulado';
                $formulario->comentarios = $comentario;
                $formulario->save();
            }

        } catch(Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }
        return $exito;
    }

    public function aplazarInscripcion($numeroFormulario, $comentario=""): bool {
        $exito = true;
        try {
            $formulario = FormularioInscripcionDao::where('numero_formulario', $numeroFormulario)->first();            
            if ($formulario) {
                
                $idUsuarioSesion = Auth::id();
                if (strlen($idUsuarioSesion)==0) {
                    $idUsuarioSesion = env('SYSTEM_USER');
                }
                DB::statement("SET @usuario_sesion = ?", [$idUsuarioSesion]);

                $formulario->estado = 'Aplazado';
                $formulario->comentarios = $comentario;
                $formulario->save();
            }

        } catch(Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }
        return $exito;
    }

    public function devolucionInscripcion($numeroFormulario, $comentario=""): bool {
        $exito = true;
        try {
            $formulario = FormularioInscripcionDao::where('numero_formulario', $numeroFormulario)->first();
            if ($formulario) {
                
                $idUsuarioSesion = Auth::id();
                if (strlen($idUsuarioSesion)==0) {
                    $idUsuarioSesion = env('SYSTEM_USER');
                }
                DB::statement("SET @usuario_sesion = ?", [$idUsuarioSesion]);

                $formulario->estado = 'Devuelto';
                $formulario->comentarios = $comentario;
                $formulario->save();
            }

        } catch(Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }
        return $exito;
    }    

    public function cambiarEstadoDePagoDeUnFormulario($formularioId, $estado="Pendiente de pago"): bool {
        $exito = true;
        try {
            $formulario = FormularioInscripcionDao::find($formularioId);
            if ($formulario) {

                $idUsuarioSesion = Auth::id();
                if (strlen($idUsuarioSesion)==0) {
                    $idUsuarioSesion = env('SYSTEM_USER');
                }
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");                

                $formulario->estado = $estado;
                $formulario->save();
            }
        } catch (\Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }
        return $exito;
    }

    public function realizarPagoFormularioInscripcion(int $formularioId, FormularioInscripcionPago $pago): bool {
        $exito = true;
        try {

            $idUsuarioSesion = Auth::id();
            if (strlen($idUsuarioSesion)==0) {
                $idUsuarioSesion = env('SYSTEM_USER');
            }
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            FormularioInscripcionPagoDao::create([
                'formulario_id' => $formularioId, 
                'valor' => $pago->getValor(), 
                'voucher' => $pago->getVoucher(), 
                'medio' => $pago->getMedio(), 
                'created_at' => $pago->getFecha(), 
                'updated_at' => $pago->getFecha()
            ]);

        } catch (\Exception $e) {     
            dd($e->getMessage());       
            Sentry::captureException($e);
            $exito = false;
        }

        return $exito;
    }

    public function buscarFormularioPorNumero($numeroFormulario): FormularioInscripcion {
        $formulario = new FormularioInscripcion;
        $grupoDao = new GrupoDao();
        $participanteDao = new ParticipanteDao();
        $convenioDao = new ConvenioDao();

        try {
            $resultado = FormularioInscripcionDao::select('id','numero_formulario','estado','total_a_pagar','created_at', 'path_comprobante_pago', 
                'valor_descuento','participante_id','grupo_id','convenio_id', 'fecha_max_legalizacion', 'comentarios', 'medio_inscripcion')    
                ->where('formulario_inscripcion.numero_formulario', $numeroFormulario)
                ->first();

            if ($resultado) {
                $formulario = new FormularioInscripcion();
                $formulario->setId($resultado->id);
                $formulario->setEstado($resultado->estado);
                $formulario->setFechaCreacion($resultado->created_at);
                $formulario->setValorDescuento($resultado->valor_descuento);
                $formulario->setTotalAPagar($resultado->total_a_pagar);                
                $formulario->setNumero($resultado->numero_formulario);
                $formulario->setPathComprobantePago($resultado->path_comprobante_pago);
                $formulario->setFechaMaxLegalizacion($resultado->fecha_max_legalizacion);
                $formulario->setComentarios($resultado->comentarios);
                $formulario->setMedioInscripcion($resultado->medio_inscripcion);

                $grupo = $grupoDao->buscarGrupoPorId($resultado->grupo_id);
                $participante = $participanteDao->buscarParticipantePorId($resultado->participante_id);
                $convenio = new Convenio();

                if (!is_null($resultado->convenio_id)) {
                    $convenio = $convenioDao->buscarConvenioPorId($resultado->convenio_id);
                }
                
                $formulario->setGrupo($grupo);
                $formulario->setParticipante($participante);
                $formulario->setConvenio($convenio);                
            }
        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $formulario;
    }

    public static function buscarFormularioPorId(int $id): FormularioInscripcion {
        $formulario = new FormularioInscripcion;
        $grupoDao = new GrupoDao();
        $participanteDao = new ParticipanteDao();
        $convenioDao = new ConvenioDao();

        try {
            $resultado = FormularioInscripcionDao::select('id','numero_formulario','estado','total_a_pagar','created_at', 'path_comprobante_pago', 
                'valor_descuento','participante_id','grupo_id','convenio_id', 'medio_inscripcion')
                ->where('formulario_inscripcion.id', $id)
                ->first();

            if ($resultado) {
                $formulario = new FormularioInscripcion();
                $formulario->setId($resultado->id);
                $formulario->setEstado($resultado->estado);
                $formulario->setFechaCreacion($resultado->created_at);
                $formulario->setValorDescuento($resultado->valor_descuento);
                $formulario->setTotalAPagar($resultado->total_a_pagar);                
                $formulario->setNumero($resultado->numero_formulario);
                $formulario->setPathComprobantePago($resultado->path_comprobante_pago);

                $grupo = $grupoDao->buscarGrupoPorId($resultado->grupo_id);
                $participante = $participanteDao->buscarParticipantePorId($resultado->participante_id);
                $convenio = new Convenio();

                if (!is_null($resultado->convenio_id)) {
                    $convenio = $convenioDao->buscarConvenioPorId($resultado->convenio_id);
                }
                
                $formulario->setGrupo($grupo);
                $formulario->setParticipante($participante);
                $formulario->setConvenio($convenio);                
            }
        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $formulario;        
    }

    public function legalizarFormulario(FormularioInscripcion $formulario): bool {
        $exito = true;
        try {

            $formularioDao = FormularioInscripcionDao::find($formulario->getId());
            if ($formularioDao) {
                
                $idUsuarioSesion = Auth::id();
                if (strlen($idUsuarioSesion)==0) {
                    $idUsuarioSesion = env('SYSTEM_USER');
                }
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");

                if ($formulario->getConvenioId() > 0 ) {
                    $formularioDao->convenio_id = $formulario->getConvenioId();
                }

                $formularioDao->total_a_pagar = $formulario->getTotalAPagar();
                $formularioDao->valor_descuento = $formulario->getValorDescuento();
                $formularioDao->estado = 'Pagado';
                
                $formularioDao->save();
            }

        } catch(Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }

        return $exito;
    }

    public function pagosRealizadosPorFormulario($formularioId): array {
        $pagos = [];
        try {

            $items = DB::table('formulario_inscripcion_pagos')
                ->select('id', 'valor', 'medio', 'voucher', 'created_at')
                ->where('formulario_id', $formularioId)
                ->get();

            foreach($items as $item) {
                $pago = new FormularioInscripcionPago();
                $pago->setId($item->id);
                $pago->setValor($item->valor);
                $pago->setMedio($item->medio);
                $pago->setVoucher($item->voucher);
                $pago->setFecha($item->created_at);

                $pagos[] = $pago;
            }

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }
        return $pagos;
    }

    public function redimirBeneficioConvenio($particianteId, $convenioId): bool {
        $exito = true;
        try {            
            
            $idUsuarioSesion = Auth::id();
            if (strlen($idUsuarioSesion)==0) {
                $idUsuarioSesion = env('SYSTEM_USER');
            }
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            DB::table('convenio_participante')
                ->where('convenio_id', $convenioId)
                ->where('cedula', $particianteId)
                ->where('disponible', 'SI')
                ->update(['redimido' => 'SI']);

        } catch (\Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }

        return $exito;
    }

    public function actualizarFormularioPorFacturacionDeConvenio(FormularioInscripcion $formulario): bool {
        $exito = false;

        try {
            $formularioDao = FormularioInscripcionDao::find($formulario->getId());
            if ($formularioDao) {

                $idUsuarioSesion = Auth::id();
                if (strlen($idUsuarioSesion)==0) {
                    $idUsuarioSesion = env('SYSTEM_USER');
                }                
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");
                                
                $formularioDao->valor_descuento = $formulario->getValorDescuento();
                $formularioDao->total_a_pagar = $formulario->getTotalAPagar();
                $formularioDao->estado = $formulario->getEstado();
                $formularioDao->save();
            }

        } catch (\Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }
        return $exito;
    }

    public static function listarFormulariosPorEstadoYCalendario($estado="", $calendarioId=0): array{
        $formularios = [];

        try {
            $grupoDao = new GrupoDao();
            $participanteDao = new ParticipanteDao();
            $convenioDao = new ConvenioDao();

            $resultados = FormularioInscripcionDao::with('grupo')
                    ->leftJoin('participantes', 'formulario_inscripcion.participante_id', '=', 'participantes.id')
                    ->whereHas('grupo', function ($query) use ($calendarioId) {
                        $query->where('calendario_id', $calendarioId);
                    })
                    ->where(function ($query) use ($estado) {
                        $query->when($estado, function ($query, $estado) {
                            $query->where('estado', $estado);
                        });
                    })
                    ->orderByDesc('participante_id')
                    ->orderByDesc('id')
                    ->get(['formulario_inscripcion.id', 'grupo_id', 'participante_id', 'convenio_id', 'formulario_inscripcion.created_at', 
                           'estado', 'costo_curso', 'valor_descuento', 'total_a_pagar', 'numero_formulario', 'fecha_max_legalizacion']);

            foreach($resultados as $resultado) {
                $formulario = new FormularioInscripcion();
                $formulario->setId($resultado->id);
                $formulario->setEstado($resultado->estado);
                $formulario->setFechaCreacion($resultado->created_at);
                $formulario->setTotalAPagar($resultado->total_a_pagar);
                $formulario->setNumero($resultado->numero_formulario);
                $formulario->setFechaMaxLegalizacion($resultado->fecha_max_legalizacion);

                $grupo = $grupoDao->buscarGrupoPorId($resultado->grupo_id);
                $participante = $participanteDao->buscarParticipantePorId($resultado->participante_id);
                $convenio = new Convenio();

                if (!is_null($resultado->convenio_id)) {
                    $convenio = $convenioDao->buscarConvenioPorId($resultado->convenio_id);
                }
                
                $formulario->setGrupo($grupo);
                $formulario->setParticipante($participante);
                $formulario->setConvenio($convenio);
                
                array_push($formularios, $formulario);
            }

        } catch (Exception $e) {
            // Sentry::captureException($e);
        }

        return $formularios;
    }

    public static function listarFormulariosParaCorreo($estado = "", $calendarioId = 0): array
    {
        $formularios = [];
    
        try {

            $resultados = FormularioInscripcionDao::join('grupos', 'formulario_inscripcion.grupo_id', '=', 'grupos.id')
                ->join('curso_calendario', 'grupos.curso_calendario_id', '=', 'curso_calendario.id')
                ->join('cursos', 'curso_calendario.curso_id', '=', 'cursos.id')
                ->join('participantes', 'formulario_inscripcion.participante_id', '=', 'participantes.id')
                ->where('grupos.calendario_id', $calendarioId)
                ->when($estado, function ($query) use ($estado) {
                    $query->where('formulario_inscripcion.estado', $estado);
                })
                ->orderByDesc('formulario_inscripcion.participante_id')
                ->orderByDesc('formulario_inscripcion.id')
                ->get([
                    'formulario_inscripcion.id as formulario_id',
                    'formulario_inscripcion.estado',
                    'formulario_inscripcion.numero_formulario',
                    'participantes.id as participante_id',
                    DB::raw("CONCAT(
                        COALESCE(participantes.primer_nombre, ''), ' ',
                        COALESCE(participantes.segundo_nombre, ''), ' ',
                        COALESCE(participantes.primer_apellido, ''), ' ',
                        COALESCE(participantes.segundo_apellido, '')
                    ) as participante_nombre"),
                    'participantes.email as participante_email',
                    'cursos.nombre as curso_nombre',
                ]);    
    
            foreach ($resultados as $resultado) {
                $formularios[] = [
                    'formulario_id' => $resultado->formulario_id,
                    'estado' => $resultado->estado,
                    'numero_formulario' => $resultado->numero_formulario,
                    'participante' => [
                        'id' => $resultado->participante_id,
                        'nombre' => $resultado->participante_nombre,
                        'email' => $resultado->participante_email,
                    ],
                    'curso' => [
                        'nombre' => $resultado->curso_nombre,
                    ],
                ];
            }
        } catch (\Exception $e) {
            // Manejar excepciones, por ejemplo, capturándolas con Sentry o un log
            // Sentry::captureException($e);
        }
    
        return $formularios;
    }
        

    public static function GenerarReciboDeMatricula($participanteId=0, $calendarioId = 0): array {        
        $datosReciboMatricula = [];  
        
        $calendario = Calendario::buscarPorId($calendarioId);
        // $calendario = Calendario::Vigente();
        if (!$calendario->existe()) {
            return $datosReciboMatricula;
        }

        $calendarioId = $calendario->getId();
        
        try {            

            $items = FormularioInscripcionDao::select([
                'formulario_inscripcion.numero_formulario',
                'calendarios.nombre as PERIODO',
                'formulario_inscripcion.estado',
                DB::raw("CONCAT(participantes.primer_nombre, ' ', participantes.segundo_nombre, ' ', participantes.primer_apellido, ' ', participantes.segundo_apellido) as PARTICIPANTE_NOMBRE"),
                DB::raw("CONCAT(participantes.tipo_documento, ' ', participantes.documento) as DOCUMENTO"),
                'participantes.telefono',
                'participantes.email',
                'participantes.direccion',
                'cursos.nombre as CURSO_NOMBRE',
                'formulario_inscripcion.costo_curso',
                'formulario_inscripcion.valor_descuento',
                'formulario_inscripcion.total_a_pagar',
                'formulario_inscripcion.fecha_max_legalizacion',
                'grupos.jornada',
                'grupos.dia',
                'convenios.nombre as CONVENIO_NOMBRE' 
            ])
            ->join('grupos', function($join) use ($participanteId, $calendarioId) {
                $join->on('grupos.id', '=', 'formulario_inscripcion.grupo_id')
                     ->where('formulario_inscripcion.participante_id', '=', $participanteId)
                     ->where('grupos.calendario_id', '=', $calendarioId);
            })
            ->join('curso_calendario', 'curso_calendario.id', '=', 'grupos.curso_calendario_id')
            ->join('cursos', 'cursos.id', '=', 'curso_calendario.curso_id')
            ->join('calendarios', 'calendarios.id', '=', 'curso_calendario.calendario_id')
            ->join('participantes', 'participantes.id', '=', 'formulario_inscripcion.participante_id')
            ->leftJoin('convenios', 'convenios.id', '=', 'formulario_inscripcion.convenio_id') 
            ->where('formulario_inscripcion.estado', '<>', 'Anulado')
            ->where('formulario_inscripcion.estado', '<>', 'Aplazado')
            ->get();
            
            foreach($items as $item) {                    
                $datosReciboMatricula[] = [
                    $item->numero_formulario,
                    $item->PERIODO,
                    $item->estado,
                    mb_strtoupper($item->PARTICIPANTE_NOMBRE, 'utf8'),
                    $item->DOCUMENTO,
                    $item->telefono,
                    $item->email,
                    $item->direccion,
                    $item->CURSO_NOMBRE,
                    $item->costo_curso,
                    $item->valor_descuento,
                    $item->total_a_pagar,
                    $item->fecha_max_legalizacion,
                    $item->jornada,
                    $item->dia,
                    $item->CONVENIO_NOMBRE,
                    FormatoFecha::fechaFormateadaA5DeAgostoDe2024($calendario->getFechaInicioClase()),
                ];
            }
            
        } catch (Exception $e) {
            dd($e->getMessage());
            // Sentry::captureException($e);
        }

        return $datosReciboMatricula;
    }

    public function actualizarGrupoFormulario($formularioId, $grupoId, $datos = []): bool {
        try {
            $formularioDao = FormularioInscripcionDao::find($formularioId);
            if ($formularioDao) {
                $formularioDao->grupo_id = $grupoId;
                $formularioDao->comentarios = $datos['comentarios'];
                $formularioDao->valor_descuento = $datos['descuento'];
                $formularioDao->total_a_pagar = $datos['totalAPagar'];
                $formularioDao->costo_curso = $datos['costoCurso'];
                $formularioDao->estado = $datos['estado'];
                $formularioDao->save();
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            // Sentry::captureException($e);
            return false;
        }
        return true;
    }

    public static function cambiarPendientesDePagoAAnulado(): bool
    {
        try {
            self::where('estado', 'Pendiente de pago')->update(['estado' => 'Anulado']);
            return true;
        } catch (\Exception $e) {

            return false;
        }
    }

    public static function contadorInscripcionesSegunMedio(string $medio = 'en oficina'): int {
        $calendario = Calendario::Vigente();
        if (!$calendario->existe()) {
            return 0;
        }
        
        return DB::table('formulario_inscripcion as f')
                ->where('f.medio_inscripcion', $medio)
                ->where(function($query) {
                    $query->where('f.estado', 'Pagado')
                        ->orWhere('f.estado', 'Pendiente de pago');
                })
                ->whereNotNull('f.convenio_id')
                ->count();
    }

    public static function asignarConvenioAFormulario(int $formularioID, int $convenioID): bool
    {
        $valorConvenio = ($convenioID === 0) ? null : $convenioID;

        $actualizados = DB::table('formulario_inscripcion')
            ->where('id', $formularioID)
            ->update(['convenio_id' => $valorConvenio]);

        return $actualizados > 0;
    }

    /**
     * Retorna el número de formularios en estado Pagado asociados a un convenio.
     *
     * @param int $convenioId
     * @return int
     */
    public static function contarFormulariosPagadosPorConvenio(int $convenioId): int
    {
        return self::where('convenio_id', $convenioId)
                    ->where('estado', 'Pagado')
                    ->count();
    }

    /**
     * Retorna un array de objetos FormularioInscripcion en estado Pagado asociados a un convenio.
     * Solo se llenan los datos propios de la tabla formulario_inscripcion.
     *
     * @param int $convenioId
     * @return FormularioInscripcion[]
     */
    public static function listarFormulariosPagadosPorConvenio(int $convenioId): array
    {
        $formularios = [];

        try {
            $resultados = FormularioInscripcionDao::query()
                ->where('estado', 'Pagado')
                ->where('convenio_id', $convenioId)
                ->get([
                    'id',
                    'grupo_id',
                    'participante_id',
                    'convenio_id',
                    'numero_formulario',
                    'estado',
                    'costo_curso',
                    'valor_descuento',
                    'total_a_pagar',
                    'fecha_max_legalizacion',
                    'created_at',
                    'updated_at',
                    'medio_inscripcion',
                    'comentarios',
                    'path_comprobante_pago'
                ]);

            foreach ($resultados as $resultado) {
                $formulario = new FormularioInscripcion();
                $formulario->setId($resultado->id);
                $formulario->setEstado($resultado->estado);
                $formulario->setFechaCreacion($resultado->created_at);
                $formulario->setTotalAPagar($resultado->total_a_pagar);
                $formulario->setNumero($resultado->numero_formulario);
                $formulario->setFechaMaxLegalizacion($resultado->fecha_max_legalizacion);
                $formulario->setCostoCurso($resultado->costo_curso);
                $formulario->setValorDescuento($resultado->valor_descuento);
                $formulario->setMedioInscripcion($resultado->medio_inscripcion);
                $formulario->setComentarios($resultado->comentarios);
                $formulario->setPathComprobantePago($resultado->path_comprobante_pago);
                // Si tienes métodos setGrupoId, setParticipanteId, setConvenioId, puedes también:
                // $formulario->setGrupoId($resultado->grupo_id);
                // $formulario->setParticipanteId($resultado->participante_id);
                // $formulario->setConvenioId($resultado->convenio_id);

                $formularios[] = $formulario;
            }
        } catch (\Exception $e) {
            \Sentry\captureException($e);
        }

        return $formularios;
    }

    /**
     * Actualiza los campos valor_descuento y total_a_pagar en los formularios de inscripción dados.
     * La actualización se realiza de forma individual para cada formulario mediante múltiples consultas SQL.
     *
     * @param array $datosFormularios Arreglo asociativo con estructura:
     *  [
     *      int $formularioId => ['valor_descuento' => float, 'total_a_pagar' => float],
     *      ...
     *  ]
     *
     * @return void
     */
    public static function actualizarValoresDescuento(array $datos): void
    {
        try {
            foreach ($datos as $valores) {
                DB::table('formulario_inscripcion')
                    ->where('id', $valores['id'])
                    ->update([
                        'valor_descuento' => $valores['valor_descuento'],
                        'total_a_pagar'   => $valores['total_a_pagar'],
                    ]);
            }
        } catch (\Throwable $e) {

            dd($e->getMessage());
            // Registra el error si usas Sentry
            if (class_exists('\Sentry\captureException')) {
                \Sentry\captureException($e);
            }

            // Opcional: relanza la excepción si quieres que se propague
            throw $e;
        }
    }


}