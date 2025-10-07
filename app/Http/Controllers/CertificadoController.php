<?php

namespace App\Http\Controllers;

use Src\usecase\certificados\GenerarCertificadoWordUseCase;

class CertificadoController extends Controller
{    
    // public function descargar($participanteID, $grupoID)
    // {
    //     $response = (new GenerarCertificadoWordUseCase)->ejecutar($participanteID, $grupoID);
    
    //     if ($response->code !== "200") {
    //         return redirect()->route('dashboard')
    //             ->with('code', $response->code)
    //             ->with('status', $response->message);
    //     }
    
    //     $path = $response->data['path'];
    //     $filename = $response->data['filename'];
    
    //     return response()->streamDownload(function () use ($path) {
    //         readfile($path);
    
    //         register_shutdown_function(function () use ($path) {
    //             if (file_exists($path)) {
    //                 @unlink($path);
    //             }
    //         });
    //     }, $filename);
    // }
    
    public function descargar($participanteID, $grupoID)
    {
        $response = (new GenerarCertificadoWordUseCase)->ejecutar($participanteID, $grupoID);

        if ($response->code !== "200") {
            return redirect()->route('dashboard')
                ->with('code', $response->code)
                ->with('status', $response->message);
        }

        $path = $response->data['path'];
        $filename = $response->data['filename'];

        return response()
            ->download($path, $filename, [
                'Content-Type'              => 'application/pdf',
                'Content-Transfer-Encoding' => 'binary',
                'Cache-Control'             => 'no-store, must-revalidate',
                'Pragma'                    => 'no-cache',
            ])
            ->deleteFileAfterSend(true);
    }
    
}
