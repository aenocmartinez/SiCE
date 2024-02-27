<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Src\domain\Convenio;
use Src\domain\repositories\ConvenioRepository;

use Sentry\Laravel\Facade as Sentry;

class ConvenioDao extends Model implements ConvenioRepository {
    
    protected $table = 'convenios';
    protected $fillable = ['nombre', 'calendario_id', 'fec_ini', 'fec_fin', 'descuento'];

    public function beneficiarios() {
        $this->belongsToMany(ParticipanteDao::class,'convenio_id', 'participante_id', 'convenio_participante');
    }

    public function listarConvenios(): array {
        $listaConvenios = array();

        try {

            $calendarioDao = new CalendarioDao();
            $convenios = ConvenioDao::all();

            $convenios = ConvenioDao::select('convenios.id', 'nombre', 'calendario_id', 'fec_ini', 'fec_fin', 'descuento',
                                        DB::raw('COUNT(convenio_participante.id) as numBeneficiados'))
                        ->leftJoin('convenio_participante', 'convenio_participante.convenio_id', '=', 'convenios.id')
                        ->groupBy('convenios.id')
                        ->get();

            foreach($convenios as $c) {
                $convenio = new Convenio();
                $convenio->setId($c->id);
                $convenio->setNombre($c->nombre);
                $convenio->setFecInicio($c->fec_ini);
                $convenio->setFecFin($c->fec_fin);
                $convenio->setDescuento($c->descuento);
                $convenio->setNumeroBeneficiados($c->numBeneficiados);
    
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
            $calendarioDao = new CalendarioDao();
            $c = ConvenioDao::select('convenios.id', 'convenios.nombre', 'convenios.calendario_id', 
                                    'convenios.fec_ini', 'convenios.fec_fin', 'convenios.descuento', 
                                    DB::raw('COUNT(formulario_inscripcion.id) as numIncripciones'),
                                    DB::raw('COUNT(convenio_participante.id) as numBeneficiados'))
                        ->leftJoin('formulario_inscripcion', 'convenios.id', '=', 'formulario_inscripcion.convenio_id')
                        ->leftJoin('convenio_participante', 'convenios.id', '=', 'convenio_participante.convenio_id')
                        ->where('convenios.id', $id)
                        ->groupBy('convenios.id', 'convenios.nombre', 'convenios.calendario_id', 'convenios.fec_ini', 'convenios.fec_fin', 'convenios.descuento')
                        ->first();

            if ($c) {
                $convenio = new Convenio();
                $convenio->setId($c->id);
                $convenio->setNombre($c->nombre);
                $convenio->setFecInicio($c->fec_ini);
                $convenio->setFecFin($c->fec_fin);
                $convenio->setDescuento($c->descuento);
                $convenio->setNumeroInscritos($c->numIncripciones);
                $convenio->setNumeroBeneficiados($c->numBeneficiados);

                // dd($c->numBeneficiados);
    
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
            ConvenioDao::create([
                'nombre' => $convenio->getNombre(),
                'calendario_id' => $convenio->getCalendarioId(), 
                'fec_ini' => $convenio->getFecInicio(), 
                'fec_fin' => $convenio->getFecFin(), 
                'descuento' => $convenio->getDescuento(),
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
                $convenioEncontrado->nombre = $convenio->getNombre();
                $convenioEncontrado->calendario_id = $convenio->getCalendarioId();
                $convenioEncontrado->fec_ini = $convenio->getFecInicio();
                $convenioEncontrado->fec_fin = $convenio->getFecFin();
                $convenioEncontrado->descuento = $convenio->getDescuento();
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
                $convenioEncontrado->delete();
            }
            $exito = true;
        } catch(Exception $e) {            
            Sentry::captureException($e);
        }

        return $exito;
    }

    public function agregarBeneficiarioAConvenio(int $convenioId, int $participanteId): bool {

        try {

            $fechaHoraActual = now()->toDateTimeString();

            DB::table('convenio_participante')->insert([
                'convenio_id' => $convenioId,
                'participante_id' => $participanteId,
                'created_at' => $fechaHoraActual,
                'updated_at' => $fechaHoraActual,
            ]);

        } catch(\Exception $e) {
            dd($e->getMessage());
            Sentry::captureException($e);
        }
        return true;
    }
}