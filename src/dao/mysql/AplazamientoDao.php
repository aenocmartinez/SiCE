<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Src\dao\mysql\ParticipanteDao;
use Src\domain\Aplazamiento;

class AplazamientoDao extends Model
{
    protected $table = 'participante_aplazamientos';

    protected $fillable = [
        'participante_id',
        'saldo_a_favor',
        'redimido',
        'caducado',
        'fecha_caducidad',
        'calendario_id',
        'formulario_id',
        'comentarios'
    ];

    public function participante()
    {
        return $this->belongsTo(ParticipanteDao::class, 'participante_id');
    }
    
    public function formulario()
    {
        return $this->belongsTo(FormularioInscripcionDao::class, 'formulario_id');
    }

    public static function buscarPorId($aplazamientoId=0): Aplazamiento
    {
        $aplazamiento = new Aplazamiento();
        $registro = self::find($aplazamientoId);

        if ($registro)
        {
            $aplazamiento->setId($registro->id);
            $aplazamiento->setRedimido($registro->redimido);
            $aplazamiento->setCaducado($registro->caducado);
            $aplazamiento->setFechaCaducidad($registro->fecha_caducidad);
            $aplazamiento->setComentarios($registro->comentarios);
        }
        return $aplazamiento;
    }

    public function redimir($aplazamientoId)
    {
        $registro = self::find($aplazamientoId);
        if ($registro)
        {
            $registro->redimido = true;
            $registro->update();
        }
    }
}
