<?php

namespace Src\infraestructure\pdf;

use Illuminate\Support\Facades\Response;

use Exception;
use Mpdf\Mpdf;



class SicePDF {

    public static function generarFormatoPago(DataPDF $dataPDF): bool {

        $exito = true;
        try {

            $data = $dataPDF->getData();

            // $html = file_get_contents($data['path_template']);
            $html = $data['html'];

            $mpdf = new Mpdf([
                'format' => $data['format'],
                'orientation' => $data['orientation'],
            ]);

            $stylesheet1 = file_get_contents($data['path_css1']);
            // $stylesheet2 = file_get_contents($data['path_css2']);
            
            $mpdf->WriteHTML($stylesheet1, \Mpdf\HTMLParserMode::HEADER_CSS);
            // $mpdf->WriteHTML($stylesheet2, \Mpdf\HTMLParserMode::HEADER_CSS);

            date_default_timezone_set('America/Bogota');
            $html .= '
                    <br><br><br><br><br>
                    <div style="margin-left: 50px;">
                        <p style="width: 200px; border-bottom: 1px solid #000; display: inline-block; margin-bottom: 2px;">&nbsp;</p>
                        <p style="margin-top: 0;">Firma del docente</p>
                    </div>
                ';
            
            // Añadir pie de página con la fecha y hora de Colombia
            $footerText = 'Vicerrectoría de Investigación, Innovación y Extensión Subdirección de Proyección y Extensión';
            $date = date('Y-m-d H:i:s'); // Obtener la fecha y hora actuales en la zona horaria de Colombia
            $footerHTML = '<div style="font-size: 10px; font-weight: normal;">' . $footerText . ' - Página {PAGENO} - Generado el ' . $date . '</div>';
            $mpdf->SetFooter($footerHTML);           

            $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

            $nombreArchivoPDF = $dataPDF->getFileName();

            $pdfPath = storage_path() . '/' . $nombreArchivoPDF;

            $mpdf->Output($pdfPath, 'F');            

        } catch (Exception $e) {
            $exito = false;
            dd($e->getMessage());
        } 

        return $exito;
    }

    public static function generarReciboMatricula(DataPDF $dataPDF): bool {

        $exito = true;
        try {

            $data = $dataPDF->getData();
            // $html = file_get_contents($data['path_template']);
            $html = $data['html'];

            $mpdf = new Mpdf([
                'format' => $data['format'],
                'orientation' => $data['orientation'],
            ]);

            $stylesheet1 = file_get_contents($data['path_css1']);
            
            $mpdf->WriteHTML($stylesheet1, \Mpdf\HTMLParserMode::HEADER_CSS);      

            $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

            $nombreArchivoPDF = $dataPDF->getFileName();

            $pdfPath = storage_path() . '/' . $nombreArchivoPDF;

            $mpdf->Output($pdfPath, 'F');            

        } catch (Exception $e) {
            $exito = false;
            dd($e->getMessage());
        } 

        return $exito;
    }
}