<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;

class DevolucionDao extends Model
{
    protected $table = 'participante_devoluciones';

    protected $fillable = [
        'participante_id',
        'calendario_id',
        'total_devuelto',
        'origen',
        'porcentaje',
        'comentarios'
    ];

    public function participante()
    {
        return $this->belongsTo(ParticipanteDao::class, 'participante_id');
    }

    public function calendario()
    {
        return $this->belongsTo(CalendarioDao::class, 'calendario_id');
    }    
}
