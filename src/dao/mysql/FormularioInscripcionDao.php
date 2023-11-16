<?php

namespace Src\dao\mysql;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Src\domain\Convenio;
use Src\domain\FormularioInscripcion;
use Src\domain\repositories\FormularioRepository;
use Src\view\dto\ConfirmarInscripcionDto;

class FormularioInscripcionDao extends Model implements FormularioRepository {

    protected $table = 'formulario_inscripcion';
    protected $fillable = ['grupo_id', 
                            'participante_id', 
                            'convenio_id', 
                            'costo_curso', 
                            'numero_formulario',
                            'valor_descuento', 
                            'total_a_pagar',
                            'medio_pago'];
    
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

            $resultados = $query->get(['id', 'grupo_id', 'participante_id', 'convenio_id', 'created_at', 'estado', 'costo_curso', 'valor_descuento', 'total_a_pagar', 'medio_pago', 'numero_formulario']);

            foreach($resultados as $resultado) {
                $formulario = new FormularioInscripcion();
                $formulario->setId($resultado->id);
                $formulario->setEstado($resultado->estado);
                $formulario->setFechaCreacion($resultado->created_at);
                $formulario->setTotalAPagar($resultado->total_a_pagar);
                $formulario->setMedioPago($resultado->medio_pago);
                $formulario->setNumero($resultado->numero_formulario);

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
        }
        
        return $formularios;
    }

    public function crearInscripcion(ConfirmarInscripcionDto $dto): bool {
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
                $nuevoFormulario->numero_formulario = strtotime(Carbon::now()) . $dto->participanteId;
                
                $participante->formulariosInscripcion()->save($nuevoFormulario);
            }
        } catch(Exception $e) {
            $exito = false;
            dd($e->getMessage());
        }

        return $exito;
    }    
}