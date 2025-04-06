<?php

namespace Src\usecase\certificados;

use PhpOffice\PhpWord\TemplateProcessor;
use Src\view\dto\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Src\dao\mysql\GrupoDao;
use Src\dao\mysql\ParticipanteDao;
use Src\infraestructure\util\FormatoString;

class GenerarCertificadoWordUseCase
{
    public function ejecutar(int $participanteID, int $grupoID): Response
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
            // Obtener fechas
            $fechaInicio = $grupo->getCursoCalendario()->getCalendario()->getFechaInicioClaseFormateada();
            $fechaFin = $grupo->getCursoCalendario()->getCalendario()->getFechaFinalFormateada();
            $fechaCertificado = now()->format('j') . ' días del mes de ' . now()->translatedFormat('F') . ' de ' . now()->year;

            // Preparar rutas
            $uuid = Str::uuid();
            $nombreBase = "certificado_temp_{$uuid}";
            $rutaPlantilla = storage_path('app/certificados/plantillas/certificado_plantilla.docx');
            $rutaTemporal = storage_path('app/temp');

            // Crear carpeta temporal si no existe
            if (!is_dir($rutaTemporal)) {
                if (!mkdir($rutaTemporal, 0755, true) && !is_dir($rutaTemporal)) {
                    return new Response("500", "No se pudo crear el directorio temporal.");
                }
            }

            $rutaDocx = "{$rutaTemporal}/{$nombreBase}.docx";
            $rutaPdf = "{$rutaTemporal}/{$nombreBase}.pdf";

            // Procesar plantilla
            $template = new TemplateProcessor($rutaPlantilla);
            $template->setValue('NOMBRE_COMPLETO', FormatoString::convertirACapitalCase($participante->getNombreCompleto()));
            $template->setValue('DOCUMENTO', $participante->getDocumentoCompleto());
            $template->setValue('NOMBRE_CURSO', strtoupper($grupo->getNombreCurso()));
            $template->setValue('FECHA_INICIO', $fechaInicio);
            $template->setValue('FECHA_FIN', $fechaFin);
            $template->setValue('FECHA_CERTIFICADO', $fechaCertificado);
            $template->setValue('INTENSIDAD', '48'); // Valor fijo según indicaste

            $template->saveAs($rutaDocx);

            // Verificar si se debe convertir a PDF
            if (env('CERTIFICADO_CONVERTIR_PDF', false)) {
                $libreOfficePath = env('LIBREOFFICE_PATH', 'C:\\Program Files\\LibreOffice\\program\\soffice.exe'); // Aquí usamos la ruta de LibreOffice en Windows

                $comando = "\"$libreOfficePath\" --headless --convert-to pdf:writer_pdf_Export \"$rutaDocx\" --outdir \"$rutaTemporal\"";
                
                // Ejecutar el comando y capturar salida
                exec($comando, $output, $returnCode);

                // Registrar el comando y la salida para ver qué está pasando
                // Log::error('Comando ejecutado para convertir a PDF', [
                //     'comando' => $comando,
                //     'output' => $output,
                //     'returnCode' => $returnCode,
                // ]);

                // Verificar si la conversión fue exitosa
                if ($returnCode !== 0 || !file_exists($rutaPdf)) {
                    // Log::error('Error al convertir certificado a PDF', [
                    //     'comando' => $comando,
                    //     'output' => $output,
                    //     'code' => $returnCode,
                    // ]);
                    unlink($rutaDocx);
                    return new Response("500", "No se pudo generar el PDF con LibreOffice.");
                }

                // Si todo sale bien, devolver el archivo PDF
                unlink($rutaDocx); // Limpieza
                return new Response("200", "PDF generado correctamente", [
                    'path' => $rutaPdf,
                    'filename' => "certificado_{$participante->getId()}_{$grupo->getId()}.pdf"
                ]);
            }

            // En desarrollo (sin PDF)
            return new Response("200", "DOCX generado correctamente", [
                'path' => $rutaDocx,
                'filename' => "certificado_{$participante->getId()}_{$grupo->getId()}.docx"
            ]);

        } catch (\Throwable $e) {
            // Log::error('Error inesperado al generar el certificado', ['exception' => $e]);
            return new Response("500", "Error al generar certificado: " . $e->getMessage());
        }
    }
}
