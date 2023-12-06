<?php

namespace Src\domain;

use Src\dao\mysql\AlarmaDao;

class Alarma {

    public static function ultimosInscritos(): array {
        return AlarmaDao::numeroUltimosInscritos();
    }

}