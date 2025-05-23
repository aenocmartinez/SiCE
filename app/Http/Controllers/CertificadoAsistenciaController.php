<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF; // si usas DomPDF, recuerda tenerlo instalado
use App\Models\Participante;
use App\Support\BancoPreguntasIdentidad;
use Src\dao\mysql\CertificadoGeneradoDao;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\certificados\BuscarCertificadoPorUUIDUseCase;
use Src\usecase\certificados\GenerarCertificadoWordUseCase;
use Src\usecase\certificados\ValidarCertificadoPorCodigoUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;
use Src\usecase\participantes\BuscarParticipantePorIdUseCase;
use Src\usecase\participantes\ListarCursosRealizadosParaDescargarCertificadoUseCase;

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
    
            $respuestaUsuario = preg_replace('/\s+/', ' ', $respuestaUsuario);
            $esperada = preg_replace('/\s+/', ' ', $esperada);
    
            if ($respuestaUsuario !== $esperada) {
                session()->forget(['participante_id', 'verificacion_preguntas']);
    
                return redirect()
                    ->route('certificado.asistencia.formulario')
                    ->withErrors([
                        'error' => 'No se aprobó la validación de identidad. Por favor, inténtelo nuevamente desde el inicio.',
                    ]);
            }
        }
    
        // ✅ Si pasa la verificación
        $response = (new ListarCursosRealizadosParaDescargarCertificadoUseCase)
            ->ejecutar($participante->getId());
    
        if ($response->code !== "200") {
            return redirect()
                ->route('certificado.asistencia.formulario')
                ->withErrors(['error' => 'No fue posible obtener los cursos realizados.']);
        }
    
        $participante = $response->data;
    
        return view('certificados.cursos_participados', compact('participante'));
    }

    public function descargarCertificadoPublico($participanteID, $grupoID)
    {
        $sessionID = session('participante_id');
        if (!$sessionID || (int)$participanteID !== (int)$sessionID) {
            return redirect()->route('certificado.asistencia.formulario')
                ->withErrors(['error' => 'Acceso no autorizado al certificado.']);
        }

        $participante = (new BuscarParticipantePorIdUseCase)->ejecutar($participanteID);
        if (!$participante->existe()) {
            return redirect()->route('certificado.asistencia.formulario')
                ->withErrors(['error' => 'Participante no encontrado.']);
        }
    
        $cursoValido = collect($participante->cursosParticipados())->first(function ($curso) use ($grupoID) {
            return $curso->grupo_id == $grupoID && $curso->aprobado;
        });
    
        if (!$cursoValido) {
            return redirect()->route('certificado.asistencia.cursos')
                ->withErrors(['error' => 'No tiene permitido descargar el certificado para este curso.']);
        }

        $response = (new GenerarCertificadoWordUseCase)->ejecutar($participanteID, $grupoID, false);
    
        if ($response->code !== "200") {
            return redirect()->route('certificado.asistencia.cursos')
                ->withErrors(['error' => $response->message]);
        }
    
        $path = $response->data['path'];
        $filename = $response->data['filename'];
    
        return response()->streamDownload(function () use ($path) {
            readfile($path);
            register_shutdown_function(function () use ($path) {
                if (file_exists($path)) {
                    @unlink($path);
                }
            });
        }, $filename);
    }

    public function validarPorCodigo(Request $request)
    {
        $uuid = $request->query('codigo');

        $useCase = new ValidarCertificadoPorCodigoUseCase(new CertificadoGeneradoDao());
        $registro = $useCase->ejecutar($uuid);

        if (!$registro) {
            return view('certificados.no_valido');
        }

        return view('certificados.valido', ['registro' => $registro]);
    }

    public function descargarDesdeQR(string $uuid)
    {
        $certificado = (new BuscarCertificadoPorUUIDUseCase)->ejecutar($uuid);

        if (!$certificado) {
            abort(404, 'Certificado no encontrado.');
        }

        // Generar certificado nuevamente desde el UUID
        $response = (new GenerarCertificadoWordUseCase)->ejecutar(
            $certificado['participante_id'],
            $certificado['grupo_id'],
            false
        );

        if ($response->code !== "200") {
            abort(500, $response->message);
        }

        $path = $response->data['path'];
        $filename = $response->data['filename'];

        return response()->streamDownload(function () use ($path) {
            readfile($path);
            register_shutdown_function(function () use ($path) {
                if (file_exists($path)) {
                    @unlink($path);
                }
            });
        }, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
 
}
