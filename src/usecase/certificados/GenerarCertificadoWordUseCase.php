<?php

namespace Src\usecase\certificados;

use PhpOffice\PhpWord\TemplateProcessor;
use Src\view\dto\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Src\dao\mysql\FirmaDao;
use Src\dao\mysql\GrupoDao;
use Src\dao\mysql\ParticipanteDao;
use Src\infraestructure\util\FormatoString;
use Src\infraestructure\util\ListaDeValor;

use Src\dao\mysql\CertificadoGeneradoDao;


class GenerarCertificadoWordUseCase
{
    public function ejecutar(int $participanteID, int $grupoID, bool $solicitadoEnLinea = false): Response
    {
        $participanteDao = new ParticipanteDao();
        $grupoDao = new GrupoDao();

        $participante = $participanteDao->buscarParticipantePorId($participanteID);
        if (!$participante->existe()) {
            return new Response("404", "Participante no encontrado");
        }

        $grupo = $grupoDao->buscarGrupoPorId($grupoID);
        if (!$grupo->existe()) {
            return new Response("404", "Grupo no encontrado");
        }

        try {
            // Fechas
            $fechaInicio = $grupo->getCursoCalendario()->getCalendario()->getFechaInicioClaseFormateada();
            $fechaFin = $grupo->getCursoCalendario()->getCalendario()->getFechaFinalFormateada();

            $fechaBase = $grupo->getCursoCalendario()->getCalendario()->getFechaCertificado();
            $fecha = $solicitadoEnLinea || !$fechaBase || !strlen($fechaBase)
                ? now()
                : \Carbon\Carbon::parse($fechaBase);
            $fechaCertificado = $fecha->format('j') . ' días del mes de ' . $fecha->translatedFormat('F') . ' de ' . $fecha->year;

            // Firmas
            $firma = FirmaDao::ObtenerFirmas();
            $nombreFirmante1 = $firma->existe() ? $firma->getNombreFirmante1() : env('NOMBRE_FIRMANTE_1');
            $nombreFirmante2 = $firma->existe() ? $firma->getNombreFirmante2() : env('NOMBRE_FIRMANTE_2');
            $cargoFirmante1  = $firma->existe() ? $firma->getCargoFirmante1()  : env('CARGO_FIRMANTE_1');
            $cargoFirmante2  = $firma->existe() ? $firma->getCargoFirmante2()  : env('CARGO_FIRMANTE_2');

            // Rutas
            $uuid = Str::uuid();
            $nombreBase = "certificado_temp_{$uuid}";
            $rutaPlantilla = storage_path('app/certificados/plantillas/certificado_plantilla.docx');
            $rutaTemporal = storage_path('app/temp');

            if (!is_dir($rutaTemporal)) {
                mkdir($rutaTemporal, 0755, true);
            }

            $rutaDocx = "{$rutaTemporal}/{$nombreBase}.docx";
            $rutaPdf  = "{$rutaTemporal}/{$nombreBase}.pdf";
            $qrPath   = "{$rutaTemporal}/qr_{$uuid}.png";

            // ✅ Código QR desde API externa
            //$url = "http://cursos-extension.test/validar-certificado?codigo={$uuid}";
            
            $url = env('DOMINIO_CERTIFICADO') . "/validar-certificado?codigo={$uuid}";

            $qrApi = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($url) . "&size=200x200";

            file_put_contents($qrPath, file_get_contents($qrApi));

            if (!file_exists($qrPath)) {
                Log::error("❌ No se pudo descargar el QR desde la API externa: $qrApi");
                return new Response("500", "No se pudo generar el código QR.");
            }

            // 📄 Plantilla Word
            $template = new TemplateProcessor($rutaPlantilla);
            $template->setValue('NOMBRE_COMPLETO', FormatoString::convertirACapitalCase($participante->getNombreCompleto()));
            $template->setValue('TIPO_DOCUMENTO', ListaDeValor::obtenerNombreTipoDocumentoPorCodigo($participante->getTipoDocumento()));
            $template->setValue('DOCUMENTO', $participante->getDocumento());
            $template->setValue('NOMBRE_CURSO', mb_strtoupper($grupo->getNombreCurso(), 'UTF-8'));
            $template->setValue('FECHA_INICIO', $fechaInicio);
            $template->setValue('FECHA_FIN', $fechaFin);
            $template->setValue('FECHA_CERTIFICADO', $fechaCertificado);
            $template->setValue('INTENSIDAD', '48');
            $template->setValue('NOMBRE_FIRMANTE_1', $nombreFirmante1);
            $template->setValue('NOMBRE_FIRMANTE_2', $nombreFirmante2);
            $template->setValue('CARGO_FIRMANTE_1', $cargoFirmante1);
            $template->setValue('CARGO_FIRMANTE_2', $cargoFirmante2);
            $template->setImageValue('CODIGO_QR', [
                'path' => $qrPath,
                'width' => 70,
                'height' => 70,
                'ratio' => false
            ]);

            $template->saveAs($rutaDocx);

            // 🧾 Convertir a PDF
            //$libreOfficePath = env('LIBREOFFICE_PATH', 'C:\\Program Files\\LibreOffice\\program\\soffice.exe'); //Windows
            $libreOfficePath = env('LIBREOFFICE_PATH') ?? '/usr/bin/libreoffice';

            //$comando = "\"$libreOfficePath\" --headless --convert-to pdf:writer_pdf_Export \"$rutaDocx\" --outdir \"$rutaTemporal\""; //Windows
            $comando = "HOME=/tmp \"$libreOfficePath\" --headless --convert-to pdf:writer_pdf_Export \"$rutaDocx\" --outdir \"$rutaTemporal\"";

            exec($comando, $output, $returnCode);

            Log::info('Comando ejecutado para convertir a PDF', [
                'comando' => $comando,
                'output' => $output,
                'returnCode' => $returnCode,
            ]);

            if ($returnCode !== 0 || !file_exists($rutaPdf)) {
                unlink($rutaDocx);
                return new Response("500", "No se pudo generar el PDF con LibreOffice.");
            }

            $certificadoDao = new CertificadoGeneradoDao();
            $certificadoDao->registrar((string) $uuid, $participante->getId(), $grupo->getId());


            unlink($rutaDocx); // Limpieza DOCX
            return new Response("200", "PDF generado correctamente", [
                'path' => $rutaPdf,
                'filename' => "certificado_{$participante->getId()}_{$grupo->getId()}.pdf"
            ]);



        } catch (\Throwable $e) {
            Log::error('❌ Error al generar certificado', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return new Response("500", "Error al generar certificado: " . $e->getMessage());
        }
    }
}
