<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Src\usecase\certificados\GenerarCertificadoWordUseCase;
use Src\usecase\certificados\ObtenerDatosParaCertificadoUseCase;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificadoController extends Controller
{
    
    public function descargar($participanteID, $grupoID)
    {
        try {
            $response = (new ObtenerDatosParaCertificadoUseCase)->ejecutar($participanteID, $grupoID);
    
            if ($response->code === "404") {
                return redirect()->route('dashboard')->with('code', $response->code)->with('status', $response->message);
            }
    
            $participante = $response->data['participante'];
            $grupo = $response->data['grupo'];
    
            $generador = new GenerarCertificadoWordUseCase();
            $certificado = $generador->ejecutar($participante, $grupo);
    
            if ($certificado->code !== "200") {
                return back()->with('error', $certificado->message);
            }
    
            $path = $certificado->data['path'];
            $filename = $certificado->data['filename'];
    
            return new StreamedResponse(function () use ($path) {
                readfile($path);
                unlink($path); // ✅ eliminar después de enviar
            }, 200, [
                'Content-Type' => mime_content_type($path),
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
    
        } catch (\Throwable $e) {
            Log::error('Error al generar certificado: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error inesperado al generar el certificado.');
        }
    }
    
}
