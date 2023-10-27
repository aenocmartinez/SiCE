<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Src\domain\Convenio;
use Src\domain\repositories\ConvenioRepository;

class ConvenioDao extends Model implements ConvenioRepository {
    
    protected $table = 'convenios';
    protected $fillable = ['nombre', 'calendario_id', 'fec_ini', 'fec_fin', 'descuento'];

    public function listarConvenios(): array {
        $listaConvenios = array();

        try {

            $calendarioDao = new CalendarioDao();
            $convenios = ConvenioDao::all();
            foreach($convenios as $c) {
                $convenio = new Convenio();
                $convenio->setId($c->id);
                $convenio->setNombre($c->nombre);
                $convenio->setFecInicio($c->fec_ini);
                $convenio->setFecFin($c->fec_fin);
                $convenio->setDescuento($c->descuento);
    
                $calendario = $calendarioDao->buscarCalendarioPorId($c->calendario_id);
                $convenio->setCalendario($calendario);
    
                array_push($listaConvenios, $convenio);
            }

        } catch (Exception $e) {
            $e->getMessage();
        }

        return $listaConvenios;
    }

    public function buscarConvenioPorId(int $id): Convenio {
        $convenio = new Convenio();
        try {
            $calendarioDao = new CalendarioDao();
            $c = ConvenioDao::find($id);
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
            $e->getMessage();
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
            $e->getMessage();
        }

        return $convenio;
    }

    public function crearConvenio(Convenio $convenio): bool {
        $exito = true;
        try {
            ConvenioDao::create([
                'nombre' => $convenio->getNombre(),
                'calendario_id' => $convenio->getCalendarioId(), 
                'fec_ini' => $convenio->getFecInicio(), 
                'fec_fin' => $convenio->getFecFin(), 
                'descuento' => $convenio->getDescuento(),
            ]);
        } catch(Exception $e) {
            $exito = false;
            $e->getMessage();
        }

        return $exito;
    }

    public function actualizarConvenio(Convenio $convenio): bool {
        $exito = true;
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

        } catch(Exception $e) {
            $exito = false;
            $e->getMessage();
        }

        return $exito;
    }

    public function eliminarConvenio(int $convenioId): bool {
        $exito = true;
        try {
            $convenioEncontrado = ConvenioDao::find($convenioId);
            if ($convenioEncontrado) {
                $convenioEncontrado->delete();
            }

        } catch(Exception $e) {
            $exito = false;
            $e->getMessage();
        }

        return $exito;
    }
}