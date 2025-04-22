<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF; // si usas DomPDF, recuerda tenerlo instalado
use App\Models\Participante;
use App\Support\BancoPreguntasIdentidad;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;
use Src\usecase\participantes\BuscarParticipantePorIdUseCase;

class CertificadoAsistenciaController extends Controller
{
    public function formulario()
    {
        $tiposDocumento = ListaDeValor::tipoDocumentos();
        return view('certificados.formulario', compact('tiposDocumento'));
    }  


    public function verificar(Request $request)
    {
        $request->validate([
            'tipo_documento' => 'required',
            'documento' => 'required',
            'g-recaptcha-response' => 'required',
        ]);
    
        //  Validar reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = env('RECAPTCHA_SECRET_KEY_V2');
        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    
        $response = file_get_contents($verifyUrl . '?secret=' . $secretKey . '&response=' . $recaptchaResponse);
        $responseKeys = json_decode($response, true);
    
        if (!$responseKeys["success"]) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Verificación de reCAPTCHA fallida. Inténtelo de nuevo.'
            ])->withInput();
        }

        $buscarParticipante = (new BuscarParticipantePorDocumentoUseCase);
        $participante = $buscarParticipante->ejecutar($request->tipo_documento, $request->documento);

        if (!$participante->existe()) {
            return back()->withErrors(['documento' => 'No se encontró un registro con esos datos.']);
        }

        $preguntas = BancoPreguntasIdentidad::generarAleatorias(2);
        foreach ($preguntas as &$pregunta) {
            $pregunta['texto'] = BancoPreguntasIdentidad::personalizarTexto($pregunta, $participante);
        }

        session([
            'participante_id' => $participante->getId(),
            'verificacion_preguntas' => $preguntas,
        ]);

        return view('certificados.verificacion', compact('preguntas'));
    }

    public function descargar(Request $request)
    {
        $participante = (new BuscarParticipantePorIdUseCase)->ejecutar(session('participante_id'));
        $preguntas = session('verificacion_preguntas');
    
        if (!$participante->existe() || !$preguntas) {
            return redirect()->route('certificado.asistencia.formulario')
                ->withErrors(['error' => 'Sesión inválida. Por favor inicie nuevamente.']);
        }
    
        foreach ($preguntas as $index => $pregunta) {
            $respuestaUsuario = strtolower(trim($request->input("respuesta_{$index}")));
            $esperada = strtolower(trim(BancoPreguntasIdentidad::respuestaEsperada($pregunta, $participante)));
    
            // Normaliza espacios múltiples
            $respuestaUsuario = preg_replace('/\s+/', ' ', $respuestaUsuario);
            $esperada = preg_replace('/\s+/', ' ', $esperada);
    
            if ($respuestaUsuario !== $esperada) {
                // Limpiar sesión (opcional)
                session()->forget(['participante_id', 'verificacion_preguntas']);
    
                return redirect()
                    ->route('certificado.asistencia.formulario')
                    ->withErrors([
                        'error' => 'No se aprobó la validación de identidad. Por favor, inténtelo nuevamente desde el inicio.',
                    ]);
            }
        }
    
        // ✅ Si pasa la verificación
        dd("Pasó la verificación y comenzará la descarga");
    }
    
}
