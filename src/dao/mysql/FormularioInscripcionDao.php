<?php

namespace Src\dao\mysql;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Src\domain\Convenio;
use Src\domain\FormularioInscripcion;
use Src\domain\repositories\FormularioRepository;
use Src\infraestructure\diasFestivos\Calendario;
use Src\view\dto\ConfirmarInscripcionDto;

use Sentry\Laravel\Facade as Sentry;

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

    public function listarFormulariosPorPeriodo(int $calendarioId, $estado): array {
        $formularios = array();
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

            $resultados = $query->get(['id', 'grupo_id', 'participante_id', 'convenio_id', 'created_at', 'estado', 'costo_curso', 'valor_descuento', 'total_a_pagar', 'medio_pago', 'numero_formulario', 'fecha_max_legalizacion']);

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
        
        return $formularios;
    }

    public function crearInscripcion(ConfirmarInscripcionDto &$dto): bool {        
    
        $exito = true;

        try {
            $participante = ParticipanteDao::find($dto->participanteId);
            if ($participante) {
                $nuevoFormulario = new FormularioInscripcionDao();
                $nuevoFormulario->grupo_id = $dto->grupoId;                
                if ($dto->convenioId > 0) {
                    $nuevoFormulario->convenio_id = $dto->convenioId;
                }
                $nuevoFormulario->costo_curso = $dto->costoCurso;
                $nuevoFormulario->valor_descuento = $dto->valorDescuento;
                $nuevoFormulario->total_a_pagar = $dto->totalAPagar;
                $nuevoFormulario->medio_pago = $dto->medioPago;
                
                date_default_timezone_set('America/Bogota');

                $fechaActual = Carbon::now();
                $nuevoFormulario->created_at =  $fechaActual;
                $nuevoFormulario->updated_at =  $fechaActual;
                $nuevoFormulario->fecha_max_legalizacion = Calendario::fechaSiguienteDiaHabil($fechaActual, $dto->diasFesctivos);

                $nuevoFormulario->numero_formulario = strtotime($fechaActual) . $dto->participanteId;

                $participante->formulariosInscripcion()->save($nuevoFormulario);

                $dto->formularioId = $nuevoFormulario->id;
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

    public function pagarInscripcion($formularioId, $voucher): bool {
        $exito = true;
        try {
            $formulario = FormularioInscripcionDao::find($formularioId);
            if ($formulario) {
                $formulario->voucher = $voucher;
                $formulario->estado = 'Pagado';
                $formulario->save();
            }
        } catch (Exception $e) {
            $exito = false;
            Sentry::captureException($e);
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

    public function legalizarFormulario(int $formularioId, string $voucher): bool {
        $exito = true;
        try {

            $formulario = FormularioInscripcionDao::find($formularioId);
            if ($formulario) {
                $formulario->estado = 'Pagado';
                $formulario->voucher = $voucher;
                $formulario->save();
            }

        } catch(Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }

        return $exito;
    }
}