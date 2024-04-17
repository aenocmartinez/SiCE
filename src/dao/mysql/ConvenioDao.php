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
    protected $fillable = ['nombre', 'calendario_id', 'fec_ini', 'fec_fin', 'descuento', 'es_cooperativa', 'es_ucmc_actual'];

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
                        ->groupBy('convenios.id')
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
                    'c.total_a_pagar',
                    DB::raw('(select count(*) from formulario_inscripcion where convenio_id = c.id and estado <> \'Anulado\') as numeroInscritos'),
                    DB::raw('(select count(*) from convenio_participante where convenio_id = c.id) as numeroBeneficiados')
                )
                ->where('c.id', $id)
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
            
                $calendarioDao = new CalendarioDao();
                $calendario = $calendarioDao->buscarCalendarioPorId($c->calendario_id);
                $convenio->setCalendario($calendario);
            }

            
        } catch (Exception $e) {
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
            ]);
            
            $exito = true;

        } catch(Exception $e) {   
            dd($e->getMessage());         
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
                'calendarios.nombre as periodo'
            )
            ->join('convenio_participante', 'participantes.documento', '=', 'convenio_participante.cedula')
            ->join('formulario_inscripcion', function ($join) {
                $join->on('formulario_inscripcion.convenio_id', '=', 'convenio_participante.convenio_id')
                    ->on('formulario_inscripcion.participante_id', '=', 'participantes.id');
            })
            ->join('grupos', 'grupos.id', '=', 'formulario_inscripcion.grupo_id')
            ->join('curso_calendario', 'curso_calendario.id', '=', 'grupos.curso_calendario_id')
            ->join('cursos', 'cursos.id', '=', 'curso_calendario.curso_id')
            ->join('calendarios', 'calendarios.id', '=', 'curso_calendario.calendario_id')
            ->where('convenio_participante.convenio_id', $convenioId)
            ->where('calendarios.id', $calendarioId)
            ->orderBy('participantes.primer_nombre')
            ->orderBy('participantes.primer_apellido')
            ->get();

            
            $participantes[] = ['NOMBRE', 'TIPO_DOCUMENTO', 'DOCUMENTO', 'CURSO', 'PERIODO'];
            foreach($items as $item) {
                
                $nombreCompleto = $item->primer_nombre . " " . $item->segundo_nombre . " " . $item->primer_apellido . " " . $item->segundo_apellido;

                $participantes[] = [$nombreCompleto, $item->tipo_documento, $item->documento, $item->nombre_curso, $item->periodo];
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