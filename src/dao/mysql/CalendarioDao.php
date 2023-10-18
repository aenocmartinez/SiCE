<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Src\domain\Calendario;
use Src\domain\repositories\CalendarioRepository;

class CalendarioDao extends Model implements CalendarioRepository {

    protected $table = 'calendarios';
    protected $fillable = ['nombre', 'fec_ini', 'fec_fin'];    

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
            $e->getMessage();
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
            $e->getMessage();
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
            $e->getMessage();
        }
        return $calendario;
    }

    public function crearCalendario(Calendario $calendario): bool {
        try {
            $result = CalendarioDao::create([
                'nombre' => $calendario->getNombre(),
                'fec_ini' => $calendario->getFechaInicio(),
                'fec_fin' => $calendario->getFechaFinal()
            ]);
        } catch (\Exception $e) {
            $e->getMessage();
        }   
        return $result['id'] > 0;
    }

    public function eliminarCalendario(Calendario $calendario): bool {
        try {
            $exito = false;
            $rs = CalendarioDao::destroy($calendario->getId());
            if ($rs) {
                $exito = true;
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }   
        return $exito;
    }

    public function actualizarCalendario(Calendario $calendario): bool {
        try {
            $exito = false;
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
            $e->getMessage();
        }   
        return $exito; 
    }
}