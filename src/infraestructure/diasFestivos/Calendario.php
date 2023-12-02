<?php

namespace Src\infraestructure\diasFestivos;

use DateTime;
use GuzzleHttp\Client;

class Calendario {

    public static function obtenerDiasFestivo($anio) {
        $client = new Client();
        $response = $client->get("https://date.nager.at/api/v2/publicholidays/$anio/co");
        $holidaysData = json_decode($response->getBody(), true);

        $holidays = array_map(function ($holiday) {
            return $holiday['date'];
        }, $holidaysData);

        return implode(",",$holidays);
    }

    public static function fechaSiguienteDiaHabil($startDate, $holidays) {
        $date = new DateTime($startDate);
        $numberOfDays = env('APP_NUMERO_DIAS_HABILES_INSCRIPCION');
        $weekendDays = [6, 7];

        $count = 0;
        while ($count < $numberOfDays) {

            $date->modify('+1 day');
            if (!in_array($date->format('Y-m-d'), $holidays) && !in_array($date->format('N'), $weekendDays)) {
                $count++;
            }
        }
    
        return $date->format('Y-m-d');
    }
}