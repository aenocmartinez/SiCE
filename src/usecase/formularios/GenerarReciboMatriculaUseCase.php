<?php

namespace Src\usecase\formularios;

use NumberFormatter;
use Src\dao\mysql\FormularioInscripcionDao;
use Src\infraestructure\pdf\DataPDF;
use Src\infraestructure\pdf\SicePDF;

class GenerarReciboMatriculaUseCase
{
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
    const COMENTARIO = 17;
    const SALON = 18;

    public function ejecutar($formularioId = 0, $calendarioId): array
    {
        $datos_recibo_pago = FormularioInscripcionDao::GenerarReciboDeMatricula($formularioId, $calendarioId);

        if (sizeof($datos_recibo_pago) === 0) {
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
        

        $path_template = __DIR__ . "/../../../src/infraestructure/reciboMatricula/template/recibo_matricula.html";
        $path_css1 = __DIR__ . "/../../../src/infraestructure/reciboMatricula/template/estilo.css";
        $css_path_public = 'file://' . realpath($path_css1);

        // Logos
        $logo_universidad = 'file://' . realpath(__DIR__ . "/../../../src/infraestructure/reciboMatricula/template/Logo-Universidad.png");
        $logo_calidad     = 'file://' . realpath(__DIR__ . "/../../../src/infraestructure/reciboMatricula/template/LogoAcreCalidadLegal.png");
        $logo_icontec     = 'file://' . realpath(__DIR__ . "/../../../src/infraestructure/reciboMatricula/template/logo-icontec.png");


        $html_template = file_get_contents($path_template);
        $html_template = str_replace('{{CSS_PATH}}', $css_path_public, $html_template);

        $formatter = new NumberFormatter('es_CO', NumberFormatter::CURRENCY);
        $cursos_matriculados = "";
        $TOTAL_FACTURA = 0;

        foreach ($datos_recibo_pago as $item) {
            $info_convenio = !is_null($item[self::CONVENIO])
                ? "<br><br><strong>Convenio:</strong> " . $item[self::CONVENIO]
                : "";

            $SUBTOTAL = $item[self::CURSO_COSTO] - $item[self::CURSO_VALOR_DESCUENTO];
            $TOTAL_FACTURA += $SUBTOTAL;

            $cursos_matriculados .= "<tr>
                <td>
                    <span class=\"course-name\">{$item[self::CURSO_NOMBRE]}</span><br>                    
                    <span class=\"course-details\">{$item[self::DIA]}/{$item[self::JORNADA]}<br>Salón: {$item[self::SALON]}$info_convenio</span>
                </td>
                <td>" . $formatter->formatCurrency($item[self::CURSO_COSTO], 'COP') . "</td>
                <td>" . $formatter->formatCurrency($item[self::CURSO_VALOR_DESCUENTO], 'COP') . "</td>
                <td>" . $formatter->formatCurrency($SUBTOTAL, 'COP') . "</td>
                <td>{$item[self::ESTADO]}</td>
                <td>{$item[self::COMENTARIO]}</td>
            </tr>";
        }

        $fecha_recibo = date('Y-m-d');

        $bloque_recibo = "
            <div class=\"bloque-recibo\">
                <div class=\"header\">
                    <table width=\"100%\" style=\"margin-bottom: 5px;\">
                        <tr>
                            <td style=\"width: 20%; text-align: left;\">
                                <img src=\"$logo_universidad\" style=\"height: 48px;\">
                            </td>
                            <td style=\"width: 60%; text-align: center;\">
                                <h1>Comprobante de Matrícula</h1>
                                <p>Universidad Colegio Mayor de Cundinamarca</p>
                                <p>NIT. 800.144.829-9</p>
                                <p>Teléfono: +57 3164718555</p>
                                <div class=\"period\">Periodo: $periodo</div>
                            </td>
                            <td style=\"width: 20%; text-align: center; vertical-align: middle;\">
                                <div style=\"display: inline-flex; justify-content: center; align-items: center; gap: 10px;\">
                                    <img src=\"$logo_calidad\" style=\"height: 42px;\">
                                    <img src=\"$logo_icontec\" style=\"height: 42px;\">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class=\"personal-info\" style=\"margin-bottom: 5px;\">
                    <table width=\"100%\" style=\"font-size: 9px; border-collapse: collapse;\">
                        <tr>
                            <td style=\"padding: 2px 4px;\"><strong>Nombre:</strong> $participante_nombre</td>
                            <td style=\"padding: 2px 4px;\"><strong>Cédula:</strong> $participante_cedula</td>
                            <td style=\"padding: 2px 4px;\"><strong>Teléfono:</strong> $participante_telefono</td>
                        </tr>
                        <tr>
                            <td style=\"padding: 2px 4px;\"><strong>Email:</strong> $participante_email</td>
                            <td style=\"padding: 2px 4px;\"><strong>Dirección:</strong> $participante_direccion</td>
                            <td style=\"padding: 2px 4px;\"></td>
                        </tr>
                    </table>
                </div>
                <p class=\"date\">Fecha de generación: $fecha_recibo</p>
                <table>
                    <thead>
                        <tr>
                            <th style=\"width: 21%;\">Curso, Día, Jornada y Convenio</th>
                            <th style=\"width: 10%;\">Costo</th>
                            <th style=\"width: 10%;\">Valor Descuento</th>
                            <th style=\"width: 10%;\">Total</th>
                            <th style=\"width: 10%;\">Estado</th>
                            <th style=\"width: 39%;\">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        $cursos_matriculados
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan=\"3\">Total</td>
                            <td colspan=\"3\">" . $formatter->formatCurrency($TOTAL_FACTURA, 'COP') . "</td>
                        </tr>
                    </tfoot>
                </table>
                <p class=\"class-start\">Inicio de clase a partir del $fecha_inicio_clase en el día y jornada de su curso matriculado</p>
            </div>
        ";

        $html_final = str_replace('{{BLOQUES_RECIBO_REPETIDOS}}', $bloque_recibo . $bloque_recibo, $html_template);

        $nombre_archivo = "RECIBO_MATRICULA_" . $formulario . ".pdf";

        $dataPdf = new DataPDF($nombre_archivo);
        $dataPdf->setData([
            'path_css1' => $path_css1,
            'html' => $html_final,
            'format' => 'Letter',
            'orientation' => 'P',
        ]);

        SicePDF::generarReciboMatricula($dataPdf);

        return [
            "exito" => true,
            "nombre_archivo" => $nombre_archivo,
        ];
    }
}
