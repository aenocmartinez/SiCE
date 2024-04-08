<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Src\usecase\convenios\ListarParticipantesConvenioUseCase;

class ExportarCSVController extends Controller
{
    public function listaParticipantesConvenio($convenioId=0) {

        $data = (new ListarParticipantesConvenioUseCase)->ejecutar($convenioId);

        $fileName = 'listado_participantes.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);        
    }
}
