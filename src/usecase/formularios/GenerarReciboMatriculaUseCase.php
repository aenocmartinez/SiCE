<?php

namespace Src\usecase\formularios;

use NumberFormatter;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\infraestructure\pdf\DataPDF;
use Src\infraestructure\pdf\SicePDF;

class GenerarReciboMatriculaUseCase {

    const CURSO_NOMBRE = 8;
    const CURSO_COSTO = 9;
    const CURSO_VALOR_DESCUENTO = 10;
    const CURSO_TOTAL_PAGAR = 11;
    const FORMULARIO = 0;
    const ESTADO = 2; 
    const FEC_MAX_LEGALIZACION = 12; 
    const DIA = 14;
    const JORNADA = 13;
    const CONVENIO = 15;

    public function ejecutar($formularioId=0): array {
        
        $datos_recibo_pago = FormularioInscripcionDao::GenerarReciboDeMatricula($formularioId);
        
        if (sizeof($datos_recibo_pago) == 0) {
            return [
                "exito" => false,
                "nombre_archivo" => "",
            ];            
        }

        $formulario = $datos_recibo_pago[0][0];
        $periodo = $datos_recibo_pago[0][1];
        $participante_nombre = $datos_recibo_pago[0][3];
        $participante_cedula = $datos_recibo_pago[0][4];
        $participante_telefono = $datos_recibo_pago[0][5];
        $participante_email = $datos_recibo_pago[0][6];
        $participante_direccion = $datos_recibo_pago[0][7];
        $fecha_inicio_clase = $datos_recibo_pago[0][16];

        $path_css1 = __DIR__ . "/../../../src/infraestructure/reciboMatricula/template/estilo.css"; 
        $path_template  = __DIR__ . "/../../../src/infraestructure/reciboMatricula/template/recibo_matricula.html";

        $html = file_get_contents($path_template);
        
        $html = str_replace('{{PERIODO}}', $periodo, $html);        
        $html = str_replace('{{PARTICIPANTE_NOMBRE}}', $participante_nombre, $html);
        $html = str_replace('{{PARTICIPANTE_CEDULA}}', $participante_cedula, $html);
        $html = str_replace('{{PARTICIPANTE_TELEFONO}}', $participante_telefono, $html);        
        $html = str_replace('{{PARTICIPANTE_EMAIL}}', $participante_email, $html);
        $html = str_replace('{{PARTICIPANTE_DIRECCION}}', $participante_direccion, $html);
        $html = str_replace('{{FECHA_INICIO_CLASE}}', $fecha_inicio_clase, $html);

        date_default_timezone_set('America/Bogota');
        $html = str_replace('{{FECHA_RECIBO}}', date('Y-m-d'), $html);
    
        $TOTAL_FACTURA = 0;
        
        $cursos_matriculados = "";        
        $formatter = new NumberFormatter('es_CO', NumberFormatter::CURRENCY);        
        foreach($datos_recibo_pago as $item) {   
            
            $info_convenio = "";
            if (!is_null($item[self::CONVENIO])) {
                $info_convenio = "<br><br><strong>Convenio:</strong> ".$item[self::CONVENIO];
            }

            $SUBTOTAL = ($item[self::CURSO_COSTO] - $item[self::CURSO_VALOR_DESCUENTO]);
            // $TOTAL_FACTURA += $item[self::CURSO_TOTAL_PAGAR];
            $TOTAL_FACTURA += $SUBTOTAL;
            $cursos_matriculados .= "<tr>
                <td>
                    <span class=\"course-name\">".$item[self::CURSO_NOMBRE]."</span><br>
                    <span class=\"course-details\">".$item[self::DIA]."/".$item[self::JORNADA].$info_convenio."</span>
                </td>
                <td>". $formatter->formatCurrency($item[self::CURSO_COSTO], 'COP') ."</td>
                <td>". $formatter->formatCurrency($item[self::CURSO_VALOR_DESCUENTO], 'COP') ."</td>
                <td>". $formatter->formatCurrency($SUBTOTAL , 'COP') ."</td>
                <td>". $item[self::FORMULARIO] ."</td>
                <td>". $item[self::ESTADO]."</td>
                <td>". $item[self::FEC_MAX_LEGALIZACION]."</td>
            </tr>";
        }

        $html = str_replace('{{CURSOS_MATRICULADOS}}', $cursos_matriculados, $html);
        $html = str_replace('{{TOTAL_PAGO_MATRICULA}}', $formatter->formatCurrency($TOTAL_FACTURA, 'COP'), $html);
        
        
        $nombre_archivo = "RECIBO_MATRICULA_" . $formulario . ".pdf";
        $dataPdf = new DataPDF($nombre_archivo);
        $dataPdf->setData([
            'path_css1' => $path_css1,
            'html' => $html,
            'format' => 'Letter',
            'orientation' => 'P',
        ]);

        SicePDF::generarReciboMatricula($dataPdf);        

        return [
            "exito" => true,
            "nombre_archivo" => $nombre_archivo
        ];
    }
}