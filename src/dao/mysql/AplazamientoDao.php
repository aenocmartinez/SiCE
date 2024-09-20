<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Src\dao\mysql\ParticipanteDao;

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
        'comentarios'
    ];

    public function participante()
    {
        return $this->belongsTo(ParticipanteDao::class, 'participante_id');
    }
    
}
