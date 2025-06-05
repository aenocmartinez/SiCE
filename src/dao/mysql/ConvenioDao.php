<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\domain\Convenio;
use Src\domain\repositories\ConvenioRepository;

use Sentry\Laravel\Facade as Sentry;
use Src\domain\Calendario;

class ConvenioDao extends Model implements ConvenioRepository {
    
    protected $table = 'convenios';
    protected $fillable = ['nombre', 'calendario_id', 'fec_ini', 'fec_fin', 'descuento', 'es_cooperativa', 'es_ucmc_actual', 'comentarios', 'ha_sido_facturado'];

    public function beneficiarios() {
        $this->belongsToMany(ParticipanteDao::class,'convenio_id', 'participante_id', 'convenio_participante');
    }

    public function listarConvenios(): array {
        $listaConvenios = array();

        try {

            $calendarioDao = new CalendarioDao();
            $convenios = ConvenioDao::all();

            $convenios = ConvenioDao::select('convenios.id', 'nombre', 'calendario_id', 'fec_ini', 'fec_fin', 'descuento', 'es_cooperativa', 'ha_sido_facturado',
                                        DB::raw('COUNT(convenio_participante.id) as numBeneficiados'))
                        ->leftJoin('convenio_participante', 'convenio_participante.convenio_id', '=', 'convenios.id')
                        ->groupBy('convenios.id', 'nombre', 'calendario_id', 'fec_ini', 'fec_fin', 'descuento', 'es_cooperativa', 'ha_sido_facturado')
                        ->orderBy('convenios.id', 'desc')
                        ->get();

            foreach($convenios as $c) {
                $convenio = new Convenio();
                $convenio->setId($c->id);
                $convenio->setNombre($c->nombre);
                $convenio->setFecInicio($c->fec_ini);
                $convenio->setFecFin($c->fec_fin);
                $convenio->setDescuento($c->descuento);
                $convenio->setNumeroBeneficiados($c->numBeneficiados);
                $convenio->setEsCooperativa($c->es_cooperativa);
                $convenio->setHaSidoFacturado($c->ha_sido_facturado);
    
                $calendario = $calendarioDao->buscarCalendarioPorId($c->calendario_id);
                $convenio->setCalendario($calendario);
    
                array_push($listaConvenios, $convenio);
            }

        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $listaConvenios;
    }

    public function buscarConvenioPorId(int $id): Convenio {
        
        $convenio = new Convenio();
        try {
            
            $c = DB::table('convenios as c')
            ->select(
                'c.id',
                'c.nombre',
                'c.calendario_id',
                'c.es_cooperativa',
                'c.fec_ini',
                'c.fec_fin',
                'c.descuento',
                'c.comentarios',
                'c.ha_sido_facturado',
                'c.es_ucmc_actual',
                DB::raw('(select count(*) from formulario_inscripcion where convenio_id = c.id and estado <> \'Anulado\' and estado <> \'Aplazado\') as numeroInscritos'),
                DB::raw('(select count(*) from convenio_participante where convenio_id = c.id) as numeroBeneficiados'),
                DB::raw('IFNULL(SUM(
                    CASE 
                        WHEN c.descuento = 0 THEN formulario_inscripcion.costo_curso
                        ELSE (formulario_inscripcion.costo_curso - (formulario_inscripcion.costo_curso * c.descuento / 100))
                    END
                ), 0) as total_a_pagar')
            )
            ->leftJoin('formulario_inscripcion', 'formulario_inscripcion.convenio_id', '=', 'c.id')
            ->where('c.id', $id)
            ->groupBy(
                'c.id',
                'c.nombre',
                'c.calendario_id',
                'c.es_cooperativa',
                'c.fec_ini',
                'c.fec_fin',
                'c.descuento',
                'c.comentarios',
                'c.ha_sido_facturado',
                'c.es_ucmc_actual'
            )
            ->first();        


            if ($c) {
                $convenio = new Convenio();
                $convenio->setId($c->id);
                $convenio->setNombre($c->nombre);
                $convenio->setFecInicio($c->fec_ini);
                $convenio->setFecFin($c->fec_fin);
                $convenio->setDescuento($c->descuento);
                $convenio->setNumeroInscritos($c->numeroInscritos);
                $convenio->setNumeroBeneficiados($c->numeroBeneficiados);
                $convenio->setEsCooperativa($c->es_cooperativa);
                $convenio->setTotalAPagar($c->total_a_pagar);
                $convenio->setComentarios($c->comentarios);
                $convenio->setHaSidoFacturado($c->ha_sido_facturado);
                $convenio->setEsUCMC($c->es_ucmc_actual);
            
                $calendarioDao = new CalendarioDao();
                $calendario = $calendarioDao->buscarCalendarioPorId($c->calendario_id);
                $convenio->setCalendario($calendario);

                $convenio->setReglasDescuento(ConvenioDao::obtenerReglasPorConvenio($id));

            }

            
        } catch (Exception $e) {
            dd($e->getMessage());
            Sentry::captureException($e);
        }

        return $convenio;
    }

    public function buscarConvenioPorNombreYCalendario(string $nombre, int $calendarioId): Convenio {
        $convenio = new Convenio();
        try {
            $calendarioDao = new CalendarioDao();
            $c = ConvenioDao::where('nombre', $nombre)->where('calendario_id', $calendarioId)->first();
            if ($c) {
                $convenio = new Convenio();
                $convenio->setId($c->id);
                $convenio->setNombre($c->nombre);
                $convenio->setFecInicio($c->fec_ini);
                $convenio->setFecFin($c->fec_fin);
                $convenio->setDescuento($c->descuento);
                $convenio->setEsCooperativa($c->es_cooperativa);
                $convenio->setComentarios($c->comentarios);
    
                $calendario = $calendarioDao->buscarCalendarioPorId($c->calendario_id);
                $convenio->setCalendario($calendario);

                $convenio->setReglasDescuento(ConvenioDao::obtenerReglasPorConvenio($c->id));
            }

            
        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $convenio;
    }

    public function crearConvenio(Convenio $convenio): bool 
    {
        $exito = false;

        try {
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            DB::beginTransaction();

            // Crear convenio y obtener ID
            $nuevo = ConvenioDao::create([
                'nombre' => $convenio->getNombre(),
                'calendario_id' => $convenio->getCalendarioId(),
                'fec_ini' => $convenio->getFecInicio(),
                'fec_fin' => $convenio->getFecFin(),
                'descuento' => $convenio->getDescuento(),
                'es_cooperativa' => $convenio->esCooperativa(),
                'es_ucmc_actual' => $convenio->esUCMC(),
                'comentarios' => $convenio->getComentarios(),
            ]);

            $convenioId = $nuevo->id;

            // Insertar reglas si aplica
            if ($convenio->esCooperativa() && $convenio->tieneReglasDeDescuento()) {
                foreach ($convenio->getReglasDescuento() as $regla) {
                    DB::table('reglas_descuento')->insert([
                        'convenio_id' => $convenioId,
                        'min_participantes' => $regla->getMinParticipantes(),
                        'max_participantes' => $regla->getMaxParticipantes(),
                        'descuento' => $regla->getDescuento(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();
            $exito = true;

        } catch(Exception $e) {
            DB::rollBack();
            Sentry::captureException($e);
        }

        return $exito;
    }

    public function actualizarConvenio(Convenio $convenio): bool {
        $exito = false;

        try {
            DB::beginTransaction();

            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $convenioEncontrado = ConvenioDao::find($convenio->getId());
            if ($convenioEncontrado) {
                $convenioEncontrado->nombre = $convenio->getNombre();
                $convenioEncontrado->calendario_id = $convenio->getCalendarioId();
                $convenioEncontrado->fec_ini = $convenio->getFecInicio();
                $convenioEncontrado->fec_fin = $convenio->getFecFin();
                $convenioEncontrado->descuento = $convenio->getDescuento();
                $convenioEncontrado->es_cooperativa = $convenio->esCooperativa();
                $convenioEncontrado->comentarios = $convenio->getComentarios();
                $convenioEncontrado->save();

                // Si es cooperativa, sincronizar reglas
                if ($convenio->esCooperativa()) {
                    DB::table('reglas_descuento')->where('convenio_id', $convenio->getId())->delete();

                    foreach ($convenio->getReglasDescuento() as $regla) {
                        DB::table('reglas_descuento')->insert([
                            'convenio_id' => $convenio->getId(),
                            'min_participantes' => $regla->getMinParticipantes(),
                            'max_participantes' => $regla->getMaxParticipantes(),
                            'descuento' => $regla->getDescuento(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            $exito = true;

        } catch(Exception $e) {
            DB::rollBack();
            Sentry::captureException($e);
        }

        return $exito;
    }

    public function eliminarConvenio(int $convenioId): bool {
        $exito = false;        
        try {
            $convenioEncontrado = ConvenioDao::find($convenioId);
            if ($convenioEncontrado) {
                
                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");

                $convenioEncontrado->delete();
            }
            $exito = true;
        } catch(Exception $e) {            
            Sentry::captureException($e);
        }

        return $exito;
    }

    public function agregarBeneficiarioAConvenio(int $convenioId, string $cedula): bool {

        try {
            
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $fechaHoraActual = now()->toDateTimeString();

            DB::table('convenio_participante')->insert([
                'convenio_id' => $convenioId,
                'cedula' => $cedula,
                'created_at' => $fechaHoraActual,
                'updated_at' => $fechaHoraActual,
            ]);

        } catch(\Exception $e) {
            dd($e->getMessage());
            Sentry::captureException($e);
        }
        return true;
    }

    public static function listadoParticipantesPorConvenio($convenioId=0, $calendarioId=0): array {

        $participantes = [];

        try {
            $items = ParticipanteDao::select(
                'participantes.primer_nombre',
                'participantes.segundo_nombre',
                'participantes.primer_apellido',
                'participantes.segundo_apellido',
                'participantes.tipo_documento',
                'participantes.documento',
                'cursos.nombre as nombre_curso',
                'calendarios.nombre as periodo',
                'grupos.nombre as grupo',
                'grupos.dia',
                'grupos.jornada',
                'convenios.descuento',
                DB::raw("CASE 
                    WHEN convenios.descuento = 0 THEN curso_calendario.costo
                    ELSE (curso_calendario.costo - (curso_calendario.costo * convenios.descuento / 100))
                END as total_a_pagar"),
                DB::raw("(curso_calendario.costo * convenios.descuento / 100) as valor_descuento"),
                'convenios.nombre as convenio',
                'curso_calendario.costo as costo_curso',
                'formulario_inscripcion.created_at as fecha_inscripcion',
                'formulario_inscripcion.estado as estado_formulario'
            )
            ->join('formulario_inscripcion', 'formulario_inscripcion.participante_id', '=', 'participantes.id')
            ->join('convenios', 'convenios.id', '=', 'formulario_inscripcion.convenio_id')
            ->join('grupos', 'grupos.id', '=', 'formulario_inscripcion.grupo_id')
            ->join('curso_calendario', 'curso_calendario.id', '=', 'grupos.curso_calendario_id')
            ->join('cursos', 'cursos.id', '=', 'curso_calendario.curso_id')
            ->join('calendarios', 'calendarios.id', '=', 'curso_calendario.calendario_id')
            ->where('convenios.id', $convenioId)
            ->where('calendarios.id', $calendarioId)
            ->whereNotIn('formulario_inscripcion.estado', ['Anulado', 'Aplazado', 'Devuelto'])
            ->orderBy('participantes.primer_nombre')
            ->orderBy('participantes.primer_apellido')
            ->get();
            
            
        
            $participantes[] = ['NOMBRE', 'TIPO_DOCUMENTO', 'DOCUMENTO', 'CURSO', 'GRUPO', 'HORARIO', 'COSTO_CURSO', 'PORCENTAJE_DESCUENTO', 'VALOR_DESCUENTO' ,'VALOR_A_PAGAR', 'CONVENIO' ,'PERIODO', 'FECHA_INSCRIPCION', 'ESTADO'];
            foreach($items as $item) {
                
                $nombreCompleto = $item->primer_nombre . " " . $item->segundo_nombre . " " . $item->primer_apellido . " " . $item->segundo_apellido;
                $fechaInscripcion = new \DateTime($item->fecha_inscripcion);

                $participantes[] = [mb_strtoupper($nombreCompleto, 'UTF-8'), 
                                    $item->tipo_documento, 
                                    $item->documento, 
                                    mb_strtoupper($item->nombre_curso, 'UTF-8'), 
                                    $item->grupo, 
                                    mb_strtoupper($item->dia."/".$item->jornada, 'UTF-8'), 
                                    $item->costo_curso,
                                    $item->descuento, 
                                    $item->valor_descuento,
                                    $item->total_a_pagar,
                                    // '$' . number_format($item->total_a_pagar, 2, ',', '.'),
                                    mb_strtoupper($item->convenio, 'UTF-8'),                                    
                                    mb_strtoupper($item->periodo, 'UTF-8'),
                                    $fechaInscripcion->format('Y-m-d'),
                                    mb_strtoupper($item->estado_formulario, 'UTF-8'),
                                ];
            }


        } catch(\Exception $e) {
            dd($e->getMessage());
            Sentry::captureException($e);
        }
        
        return $participantes;
    }

    public function actualizarValorAPagarConvenio(Convenio $convenio): bool {
        $exito = false;
        try {
            $convenioDao = ConvenioDao::find($convenio->getId());
            if ($convenioDao) {
                
                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");

                $convenioDao->total_a_pagar = $convenio->getTotalAPagar();
                $convenioDao->ha_sido_facturado = $convenio->haSidoFacturado();
                $convenioDao->save();
            }

            $exito = true;

        } catch(Exception $e) {            
            Sentry::captureException($e);
        }

        return $exito;        
    }

    public static function cerrarElUltimoConveniosUCMC(): bool {
        $exito = true;
        try {
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");
            
            static::where('es_ucmc_actual', true)->update(['es_ucmc_actual' => false]);
        } catch(Exception $e) {            
            $exito = false;
            Sentry::captureException($e);
        }

        return $exito;
    }

    public static function obtenerConvenioUCMCActual(): Convenio {
        $convenio = new Convenio();
        try {            
            $reg = static::where('es_ucmc_actual', true)->first();
            if ($reg) {
                $convenio->setNombre($reg->nombre);
                $convenio->setId($reg->id);
                $convenio->setDescuento($reg->descuento);
                $convenio->setEsUCMC(true);
            }
        } catch (Exception $e) {
            Sentry::captureException($e);
        }
        return $convenio;
    }

    public static function buscarConveniosPorPeriodo(Calendario $periodo): array
    {
        $convenios = [];
        $registros = ConvenioDao::where('calendario_id', $periodo->getId())
                    ->select('id', 'nombre', 'es_cooperativa', 'es_ucmc_actual', 'descuento', 'fec_ini', 'fec_fin')
                    ->get();

        foreach($registros as $registro)
        {
            $convenio = new Convenio();

            $convenio->setId($registro->id);
            $convenio->setNombre($registro->nombre);
            $convenio->setEsCooperativa($registro->es_cooperativa);
            $convenio->setEsUCMC($registro->es_ucmc_actual);            
            $convenio->setDescuento($registro->descuento);
            $convenio->setCalendario($periodo);
            $convenio->setFecInicio($registro->fec_ini);
            $convenio->setFecFin($registro->fec_fin);


            $convenios[] = $convenio;
        }

        return $convenios;
    }

    public static function obtenerReglasPorConvenio(int $convenioId): array
    {
        $reglas = [];

        try {
            $resultados = DB::table('reglas_descuento')
                ->where('convenio_id', $convenioId)
                ->orderBy('min_participantes')
                ->get();

            foreach ($resultados as $r) {
                $reglas[] = new \Src\domain\ConvenioRegla(
                    $r->min_participantes,
                    $r->max_participantes,
                    $r->descuento
                );
            }

        } catch (\Exception $e) {
            Sentry::captureException($e);
        }

        return $reglas;
    }

}