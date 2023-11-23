<?php

namespace Src\infraestructure\pdf;

use Illuminate\Support\Facades\Response;

use Exception;
use Mpdf\Mpdf;



class SicePDF {

    public static function generar(DataPDF $data): bool {

        $exito = true;
        try {

            $html = '<h1>Ejemplo de PDF con mPDF en Laravel 8</h1>';

            $mpdf = new Mpdf();

            $mpdf->WriteHTML($html);

            $nombreArchivoPDF = $data->getFileName();

            $pdfPath = storage_path() . '/' . $nombreArchivoPDF;

            $mpdf->Output($pdfPath, 'F');

            // return Response::download($pdfPath)->deleteFileAfterSend(true);

            // $pdf_content = $mpdf->Output('', 'S');

            // header('Content-Type: application/pdf');
            // header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');

            // $pdfContent = $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);

            // ob_clean();
            // flush();

            // echo $pdfContent;

            // ignore_user_abort(true);
            

        } catch (Exception $e) {
            $exito = false;
            dd($e->getMessage());
        } 

        return $exito;
    }
}