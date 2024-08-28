<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\domain\Convenio;
use Src\domain\repositories\ConvenioRepository;

use Sentry\Laravel\Facade as Sentry;

class ConvenioDao extends Model implements ConvenioRepository {
    
    protected $table = 'convenios';
    protected $fillable = ['nombre', 'calendario_id', 'fec_ini', 'fec_fin', 'descuento', 'es_cooperativa', 'es_ucmc_actual', 'comentarios'];

    public function beneficiarios() {
        $this->belongsToMany(ParticipanteDao::class,'convenio_id', 'participante_id', 'convenio_participante');
    }

    public function listarConvenios(): array {
        $listaConvenios = array();

        try {

            $calendarioDao = new CalendarioDao();
            $convenios = ConvenioDao::all();

            $convenios = ConvenioDao::select('convenios.id', 'nombre', 'calendario_id', 'fec_ini', 'fec_fin', 'descuento', 'es_cooperativa', 
                                        DB::raw('COUNT(convenio_participante.id) as numBeneficiados'))
                        ->leftJoin('convenio_participante', 'convenio_participante.convenio_id', '=', 'convenios.id')
                        ->groupBy('convenios.id', 'nombre', 'calendario_id', 'fec_ini', 'fec_fin', 'descuento', 'es_cooperativa')
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
                DB::raw('(select count(*) from formulario_inscripcion where convenio_id = c.id and estado <> \'Anulado\') as numeroInscritos'),
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
                'c.comentarios'
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
            
                $calendarioDao = new CalendarioDao();
                $calendario = $calendarioDao->buscarCalendarioPorId($c->calendario_id);
                $convenio->setCalendario($calendario);
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
            }

            
        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $convenio;
    }

    public function crearConvenio(Convenio $convenio): bool {
        $exito = false;
        try {       

            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");
            
            ConvenioDao::create([
                'nombre' => $convenio->getNombre(),
                'calendario_id' => $convenio->getCalendarioId(), 
                'fec_ini' => $convenio->getFecInicio(), 
                'fec_fin' => $convenio->getFecFin(), 
                'descuento' => $convenio->getDescuento(),
                'es_cooperativa' => $convenio->esCooperativa(),
                'es_ucmc_actual' => $convenio->esUCMC(),
                'comentarios' => $convenio->getComentarios(),
            ]);
            
            $exito = true;

        } catch(Exception $e) {          
            Sentry::captureException($e);
        }

        return $exito;
    }

    public function actualizarConvenio(Convenio $convenio): bool {
        
        $exito = false;
        try {
            $convenioEncontrado = ConvenioDao::find($convenio->getId());
            if ($convenioEncontrado) {

                $idUsuarioSesion = Auth::id();
                DB::statement("SET @usuario_sesion = $idUsuarioSesion");

                $convenioEncontrado->nombre = $convenio->getNombre();
                $convenioEncontrado->calendario_id = $convenio->getCalendarioId();
                $convenioEncontrado->fec_ini = $convenio->getFecInicio();
                $convenioEncontrado->fec_fin = $convenio->getFecFin();
                $convenioEncontrado->descuento = $convenio->getDescuento();
                $convenioEncontrado->es_cooperativa = $convenio->esCooperativa();
                $convenioEncontrado->comentarios = $convenio->getComentarios();
                $convenioEncontrado->save();
            }

            $exito = true;

        } catch(Exception $e) {            
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
                'convenios.nombre as convenio'
            )
            ->join('formulario_inscripcion', 'formulario_inscripcion.participante_id', '=', 'participantes.id')
            ->join('convenios', 'convenios.id', '=', 'formulario_inscripcion.convenio_id')
            ->join('grupos', 'grupos.id', '=', 'formulario_inscripcion.grupo_id')
            ->join('curso_calendario', 'curso_calendario.id', '=', 'grupos.curso_calendario_id')
            ->join('cursos', 'cursos.id', '=', 'curso_calendario.curso_id')
            ->join('calendarios', 'calendarios.id', '=', 'curso_calendario.calendario_id')
            ->where('convenios.id', $convenioId)
            ->where('calendarios.id', $calendarioId)
            ->orderBy('participantes.primer_nombre')
            ->orderBy('participantes.primer_apellido')
            ->get();
            
        
            $participantes[] = ['NOMBRE', 'TIPO_DOCUMENTO', 'DOCUMENTO', 'CURSO', 'GRUPO', 'HORARIO', 'DESCUENTO' ,'VALOR_A_PAGAR', 'CONVENIO' ,'PERIODO'];
            foreach($items as $item) {
                
                $nombreCompleto = $item->primer_nombre . " " . $item->segundo_nombre . " " . $item->primer_apellido . " " . $item->segundo_apellido;

                $participantes[] = [mb_strtoupper($nombreCompleto, 'UTF-8'), 
                                    $item->tipo_documento, 
                                    $item->documento, 
                                    mb_strtoupper($item->nombre_curso, 'UTF-8'), 
                                    $item->grupo, 
                                    mb_strtoupper($item->dia."/".$item->jornada, 'UTF-8'), 
                                    $item->descuento, 
                                    $item->total_a_pagar,
                                    // '$' . number_format($item->total_a_pagar, 2, ',', '.'),
                                    mb_strtoupper($item->convenio, 'UTF-8'),                                    
                                    mb_strtoupper($item->periodo, 'UTF-8')];
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
}