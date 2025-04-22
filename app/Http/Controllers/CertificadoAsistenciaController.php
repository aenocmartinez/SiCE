<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF; // si usas DomPDF, recuerda tenerlo instalado
use App\Models\Participante;

class CertificadoAsistenciaController extends Controller
{
    public function formulario()
    {
        dd("LLega hasta aqui");
        return view('certificados.formulario');
    }

    public function descargar(Request $request)
    {

        // $request->validate([
        //     'documento' => 'required|string',
        // ]);

        // $participante = Participante::where('documento', $request->documento)->first();

        // if (!$participante) {
        //     return back()->withErrors(['documento' => 'Participante no encontrado']);
        // }

        // // Supón que tienes una vista `certificados.pdf_asistencia.blade.php`
        // $pdf = PDF::loadView('certificados.pdf_asistencia', [
        //     'participante' => $participante,
        // ]);

        // return $pdf->download("certificado_asistencia_{$participante->documento}.pdf");
    }
}
