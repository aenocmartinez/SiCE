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
                    <span class=\"course-details\">{$item[self::DIA]}/{$item[self::JORNADA]}$info_convenio</span>
                </td>
                <td>" . $formatter->formatCurrency($item[self::CURSO_COSTO], 'COP') . "</td>
                <td>" . $formatter->formatCurrency($item[self::CURSO_VALOR_DESCUENTO], 'COP') . "</td>
                <td>" . $formatter->formatCurrency($SUBTOTAL, 'COP') . "</td>
                <td>{$item[self::FORMULARIO]}</td>
                <td>{$item[self::ESTADO]}</td>
                <td>{$item[self::FEC_MAX_LEGALIZACION]}</td>
            </tr>";
        }

        $fecha_recibo = date('Y-m-d');

        $bloque_recibo = "
            <div class=\"bloque-recibo\">
                <div class=\"header\">
                    <h1>Comprobante de Matrícula</h1>
                    <p>Universidad Colegio Mayor de Cundinamarca</p>
                    <p>NIT. 800.144.829-9</p>
                    <p>Teléfono: +57 3164718555</p>
                    <div class=\"period\">Periodo: $periodo</div>
                </div>
                <div class=\"personal-info\">
                    <p><strong>Nombre:</strong> $participante_nombre</p>
                    <p><strong>Cédula:</strong> $participante_cedula</p>
                    <p><strong>Teléfono:</strong> $participante_telefono</p>
                    <p><strong>Email:</strong> $participante_email</p>
                    <p><strong>Dirección:</strong> $participante_direccion</p>
                </div>
                <p class=\"date\">Fecha de generación: $fecha_recibo</p>
                <table>
                    <thead>
                        <tr>
                            <th>Curso, Día, Jornada y Convenio</th>
                            <th>Costo</th>
                            <th>Valor Descuento</th>
                            <th>Total</th>
                            <th>Número de Formulario</th>
                            <th>Estado</th>
                            <th>Fecha Máxima de Legalización</th>
                        </tr>
                    </thead>
                    <tbody>
                        $cursos_matriculados
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan=\"4\">Total</td>
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
