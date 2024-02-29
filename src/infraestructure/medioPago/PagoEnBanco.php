<?php

namespace Src\infraestructure\medioPago;

use Carbon\Carbon;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\domain\FormularioInscripcion;
use Src\domain\FormularioInscripcionPago;
use Src\infraestructure\pdf\DataPDF;
use Src\infraestructure\pdf\SicePDF;
use Src\infraestructure\util\FormatoFecha;
use Src\view\dto\Response;

class PagoEnBanco implements IMedioPago{

    public function Pagar(FormularioInscripcion $formulario, $voucher, $valorPago): Response {

        // Plantillas
        // $path_css1      = __DIR__ . "/template/style.css";
        // $html = $this->contenidoHtml($formularioInscripcion->getId());

        // $nombreArchivo = "RECIBO_PAGO_" . strtotime(Carbon::now()) . $formularioInscripcion->getId() . ".pdf";

        // $dataPdf = new DataPDF($nombreArchivo);
        // $dataPdf->setData([
        //     'path_css1' => $path_css1,
        //     'html' => $html,
        // ]);
        

        // $exito = SicePDF::generarFormatoPago($dataPdf);
        
        // if (!$exito) {
        //     return new Response("500", "Ha ocurrido un error al generar el pdf");
        // }

        $response = new Response("201", "Se ha registrado con éxito.");
        // $response->data['nombre_archivo'] = $nombreArchivo;
        
        return $response;
    }

    private function contenidoHtml($formularioId) {

        $formularioRepository = new FormularioInscripcionDao();
        $formulario = $formularioRepository->buscarFormularioPorId($formularioId);

        $path_template  = __DIR__ . "/template/pago_en_banco.html";
        if ($formulario->getConvenioNombre() == "") {
            $path_template  = __DIR__ . "/template/pago_en_banco_sin_descuento.html";
        }

        $plantilla = file_get_contents($path_template);

        $plantilla = str_replace('{{NOMBRE_COMPLETO}}', $formulario->getParticipanteNombreCompleto(), $plantilla);
        $plantilla = str_replace('{{DOCUMENTO}}', $formulario->getParticipanteTipoYDocumento(), $plantilla);
        $plantilla = str_replace('{{NOMBRE_CURSO}}', $formulario->getGrupoNombreCurso(), $plantilla);
        // $plantilla = str_replace('{{GRUPO_ID}}', $formulario->getGrupoId(), $plantilla);
        // $plantilla = str_replace('{{DIA}}', $formulario->getGrupoDia(), $plantilla);
        // $plantilla = str_replace('{{JORNADA}}', $formulario->getGrupoJornada(), $plantilla);
        // $plantilla = str_replace('{{MODALIDAD}}', $formulario->getGrupoModalidad(), $plantilla);
        // $plantilla = str_replace('{{CALENDARIO}}', $formulario->getGrupoCalendarioNombre(), $plantilla);
        $plantilla = str_replace('{{COSTO_DEL_CURSO}}', $formulario->getGrupoCursoCosto(), $plantilla);
        $plantilla = str_replace('{{NUMERO_FORMULARIO}}', $formulario->getNumero(), $plantilla);
        

        if ($formulario->getConvenioNombre() != "") {
            $plantilla = str_replace('{{NOMBRE_CONVENIO}}', $formulario->getConvenioNombre(), $plantilla);
            $plantilla = str_replace('{{VALOR_DESCUENTO}}', $formulario->getValorDescuentoFormateado(), $plantilla);
        }
        $plantilla = str_replace('{{TOTAL_A_PAGAR}}', $formulario->getTotalAPagarFormateado(), $plantilla);
        $plantilla = str_replace('{{TELEFONO}}', $formulario->getParticipanteTelefono(), $plantilla);        
        $plantilla = str_replace('{{DIRECCION}}', $formulario->getParticipanteDireccion(), $plantilla);        
        $plantilla = str_replace('{{CORREO_ELECTRONICO}}', $formulario->getParticipanteEmail(), $plantilla);    
        $plantilla = str_replace('{{FECHA_IMPRESION}}', FormatoFecha::fechaActual01enero1970(), $plantilla);    
        $plantilla = str_replace('{{HORA_IMPRESION}}', FormatoFecha::horaActual1030AM(), $plantilla); 


        $codigo = $formulario->getNumero() . $formulario->getParticipanteDocumento() . $formulario->getParticipanteId();
        $plantilla = str_replace('{{CODIGO_BARRA}}', $this->generarCodigoDeBarra($codigo), $plantilla); 

        return $plantilla;

    }

    private function generarCodigoDeBarra($codigo) {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($codigo, $generator::TYPE_CODE_128);
        return '<img src="data:image/png;base64,' . base64_encode($barcode) . '" width="350" height="90">';
    }
}