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

            $mpdf = new Mpdf();

            $stylesheet1 = file_get_contents($data['path_css1']);
            // $stylesheet2 = file_get_contents($data['path_css2']);
            
            $mpdf->WriteHTML($stylesheet1, \Mpdf\HTMLParserMode::HEADER_CSS);
            // $mpdf->WriteHTML($stylesheet2, \Mpdf\HTMLParserMode::HEADER_CSS);

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