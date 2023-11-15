<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Src\domain\Convenio;
use Src\domain\FormularioInscripcion;
use Src\domain\repositories\FormularioRepository;

class FormularioInscripcionDao extends Model implements FormularioRepository {

    protected $table = 'formulario_inscripcion';
    protected $fillable = ['grupo_id', 
                            'participante_id', 
                            'convenio_id', 
                            'costo_curso', 
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

            $resultados = $query->get(['id', 'grupo_id', 'participante_id', 'convenio_id', 'created_at', 'estado', 'costo_curso', 'valor_descuento', 'total_a_pagar', 'medio_pago']);

            foreach($resultados as $resultado) {
                $formulario = new FormularioInscripcion();
                $formulario->setId($resultado->id);
                $formulario->setEstado($resultado->estado);
                $formulario->setFechaCreacion($resultado->created_at);
                $formulario->setTotalAPagar($resultado->total_a_pagar);
                $formulario->setMedioPago($resultado->medio_pago);

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
}