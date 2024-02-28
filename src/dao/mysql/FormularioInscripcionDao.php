<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Src\domain\Convenio;
use Src\domain\FormularioInscripcion;
use Src\domain\repositories\FormularioRepository;

use Sentry\Laravel\Facade as Sentry;
use Src\domain\FormularioInscripcionPago;
use Src\infraestructure\util\Paginate;

class FormularioInscripcionDao extends Model implements FormularioRepository {

    protected $table = 'formulario_inscripcion';
    protected $fillable = ['grupo_id', 
                            'participante_id', 
                            'convenio_id', 
                            'costo_curso', 
                            'voucher',
                            'numero_formulario',
                            'valor_descuento', 
                            'total_a_pagar',
                            'medio_pago',
                            'fecha_max_legalizacion'
                        ];
    
    public function grupo() {
        return $this->belongsTo(GrupoDao::class, 'grupo_id');
    }

    public function listarFormulariosPorPeriodo(int $calendarioId, $estado, $page=1): Paginate {
        
        $paginate = new Paginate($page);
        
        $formularios = [];
        try {
            $grupoDao = new GrupoDao();
            $participanteDao = new ParticipanteDao();
            $convenioDao = new ConvenioDao();
            
            $query = FormularioInscripcionDao::with('grupo')
            ->whereHas('grupo', function ($query) use ($calendarioId) {
                $query->where('calendario_id', $calendarioId);
            })
            ->when($estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->orderByDesc('participante_id')
            ->orderByDesc('id');
        
        $totalRecords = $query->count(); 
        
        $resultados = $query->skip($paginate->Offset())
            ->take($paginate->Limit())
            ->get(['id', 'grupo_id', 'participante_id', 'convenio_id', 'created_at', 
                    'estado', 'costo_curso', 'valor_descuento', 'total_a_pagar', 'medio_pago', 
                    'numero_formulario', 'fecha_max_legalizacion']);

            foreach($resultados as $resultado) {
                $formulario = new FormularioInscripcion();
                $formulario->setId($resultado->id);
                $formulario->setEstado($resultado->estado);
                $formulario->setFechaCreacion($resultado->created_at);
                $formulario->setTotalAPagar($resultado->total_a_pagar);
                $formulario->setMedioPago($resultado->medio_pago);
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
            Sentry::captureException($e);
        }
        
        $paginate->setTotalRecords($totalRecords);
        $paginate->setRecords($formularios);

        return $paginate;
    }

    public function crearInscripcion(FormularioInscripcion &$formulario): bool {        
    
        $exito = true;

        try {
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
                $nuevoFormulario->medio_pago = $formulario->getMedioPago();
                

                $nuevoFormulario->created_at =  $formulario->getFechaCreacion();
                $nuevoFormulario->updated_at =  $formulario->getFechaCreacion();
                $nuevoFormulario->fecha_max_legalizacion = $formulario->getFechaCreacion();

                $nuevoFormulario->numero_formulario = $formulario->getNumero();

                $participante->formulariosInscripcion()->save($nuevoFormulario);

                $formulario->setId($nuevoFormulario->id);
            }
        } catch(Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }

        return $exito;
    } 
    
    public function anularInscripcion($numeroFormulario): bool {
        $exito = true;
        try {
            $formulario = FormularioInscripcionDao::where('numero_formulario', $numeroFormulario)->first();
            if ($formulario) {
                $formulario->estado = 'Anulado';
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

            FormularioInscripcionPagoDao::create([
                'formulario_id' => $formularioId, 
                'valor' => $pago->getValor(), 
                'medio' => $pago->getMedio(), 
                'voucher' => $pago->getVoucher(), 
                'created_at' => $pago->getFecha(), 
                'updated_at' => $pago->getFecha()
            ]);

        } catch (\Exception $e) {            
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
            $resultado = FormularioInscripcionDao::select('id','numero_formulario','estado','total_a_pagar','created_at',
                'valor_descuento','participante_id','grupo_id','convenio_id', 'voucher')    
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

                if (!is_null($resultado->voucher)) {
                    $formulario->setVoucher($resultado->voucher);
                }
                

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

    public function buscarFormularioPorId(int $id): FormularioInscripcion {
        $formulario = new FormularioInscripcion;
        $grupoDao = new GrupoDao();
        $participanteDao = new ParticipanteDao();
        $convenioDao = new ConvenioDao();

        try {
            $resultado = FormularioInscripcionDao::select('id','numero_formulario','estado','total_a_pagar','created_at',
                'valor_descuento','participante_id','grupo_id','convenio_id', 'voucher')    
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

                if (!is_null($resultado->voucher)) {
                    $formulario->setVoucher($resultado->voucher);
                }
                

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
            DB::table('convenio_participante')
                ->where('convenio_id', $convenioId)
                ->where('participante_id', $particianteId)
                ->where('disponible', 'SI')
                ->update(['redimido' => 'SI']);

        } catch (\Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }

        return $exito;
    }
}