<?php

namespace App\Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Src\dao\mysql\ParticipanteDao;

class AplazamientoDao extends Model
{
    protected $table = 'participante_aplazamientos';


    protected $fillable = [
        'participante_id',
        'saldo_a_favor',
        'redimido',
        'fecha_caducidad',
    ];

    public function participante()
    {
        return $this->belongsTo(ParticipanteDao::class, 'participante_id');
    }
}
