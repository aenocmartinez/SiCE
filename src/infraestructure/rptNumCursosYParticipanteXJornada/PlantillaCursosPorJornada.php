<?php

namespace Src\infraestructure\rptNumCursosYParticipanteXJornada;

use DateTime;
use Illuminate\Support\Facades\Auth;
use Src\infraestructure\rptNumCursosYParticipanteXJornada\RegistroDTO;
use Src\view\dto\ReporteNumeroCursoYParticipantePorJornadaDto;

class PlantillaCursosPorJornada
{
    private static $total_acumulados_por_area = [];
    private static $total_acumulados = [];

    public static function procesarDatos($datos=[], $numero_periodo): array
    {   
        $html_array = [];
        $tr_tBody_Area = "";
        $tr_tBody = "";
        $area_actual = "";
        $curso_actual = "";
        $ha_cambiado_de_area = false;

        $content = file_get_contents(__DIR__ . "/template/plantilla.html"); 

        $registro = new RegistroDTO();  
        self::inicializarAcumuladosPorArea();
        self::inicializarTotalAcumulados();     
        $consolidadoPorJornadaYSexo = self::inicializarConsolidadoPorJornadaYSexo();

        $tamano = sizeof($datos);

        foreach($datos as $index => $dato) {
            
            $es_la_ultima_area = ($index + 1 == $tamano);

            if (!$ha_cambiado_de_area) 
            {
                $ha_cambiado_de_area = true;
                $tr_tBody_Area .= self::agregarTagArea($dato);
                $area_actual = $dato->getArea();
            }

            if (strlen($curso_actual) == 0) 
            {
                $curso_actual = $dato->getCurso();
                $registro->setCursoActual($dato->getCurso());
                $registro->setCursoActualTotalGrupos($dato->getTotalGrupos());
                $registro->setCursoActualTotalGeneroFemenino($dato->getTotalFemeninos());
                $registro->setCursoActualTotalGeneroMasculino($dato->getTotalMasculinos());
                $registro->setCursoActualTotalGeneroOtro($dato->getTotalOtro());
                $registro->setCursoActualTotalParticipantes($dato->getTotalParticipantes());
            }
                        
            if ($curso_actual != $dato->getCurso()) 
            {          
                $tr_tBody .= self::agregarTagRegistro($registro, $consolidadoPorJornadaYSexo);         
                $registro->setCursoActual($dato->getCurso());
                
                // Reinicia datos para ciclo de siguiente curso
                $consolidadoPorJornadaYSexo = self::inicializarConsolidadoPorJornadaYSexo();
                $curso_actual = $dato->getCurso();
                $registro->setCursoActual($dato->getCurso());
                $registro->setCursoActualTotalGrupos($dato->getTotalGrupos());
                $registro->setCursoActualTotalGeneroFemenino($dato->getTotalFemeninos());
                $registro->setCursoActualTotalGeneroMasculino($dato->getTotalMasculinos());
                $registro->setCursoActualTotalGeneroOtro($dato->getTotalOtro());
                $registro->setCursoActualTotalParticipantes($dato->getTotalParticipantes());
            }

            if ($area_actual != $dato->getArea() || $es_la_ultima_area) 
            {
                $tr_tBody = $tr_tBody_Area . $tr_tBody . self::agregarAcumuladoPorArea($area_actual);
                $html_array[] = $tr_tBody;
                $area_actual = $dato->getArea();
                $tr_tBody_Area = "";
                $tr_tBody = "";
                $ha_cambiado_de_area = false;
                self::inicializarAcumuladosPorArea();
            }

            $consolidadoPorJornadaYSexo = self::consolidadoPorJornadaYSexo($consolidadoPorJornadaYSexo, $dato);
        }

        $html_array[] = self::agregarTotalAcumulados();
        $html_string = implode("", $html_array);
        $content = str_replace("{{TR_TBODY}}", $html_string, $content);
        $content = str_replace("{{PERIODO}}", $numero_periodo, $content);
        $content = str_replace("{{PROYECTADO_POR}}", auth()->user()->name, $content);
        $content = str_replace("{{FECHA_HORA_ACTUAL}}", self::getFechaHoraActual(), $content);
        

        return [
            "content" => $content,
            "css" => __DIR__ . "/template/style.css"
        ];
    }

    private static function getFechaHoraActual(): string {
        date_default_timezone_set('America/Bogota');
        $fechaHora = new DateTime();
        $formateador = new \IntlDateFormatter(
            'es_ES',
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::SHORT,
            'America/Bogota',
            \IntlDateFormatter::GREGORIAN,
            "d 'de' MMMM 'de' yyyy h:mm a"
        );

        $formatoFecha = $formateador->format($fechaHora);

        return $formatoFecha;
    }

    private static function inicializarConsolidadoPorJornadaYSexo(): array {
        $estructuraBase = [
            "curso" => "",
            "F" => [
                "total" => 0,
            ],
            "M" => [
                "total" => 0,
            ],
            "Otro" => [
                "total" => 0,
            ],
            "total_participantes" => 0,
        ];

        $datoJornada = [
            "Mañana" => $estructuraBase,
            "Tarde" => $estructuraBase,
            "Noche" => $estructuraBase,
        ];
    
        return $datoJornada;
    }

    private static function inicializarTotalAcumulados() {
        self::$total_acumulados = [
            "total_acumulado_participantes_mañama" => 0,
            "total_acumulado_participantes_hombres_mañama" => 0,
            "total_acumulado_participantes_mujeres_mañama" => 0,
            "total_acumulado_participantes_tarde" => 0,
            "total_acumulado_participantes_hombres_tarde" => 0,
            "total_acumulado_participantes_mujeres_tarde" => 0,
            "total_acumulado_participantes_noche" => 0,
            "total_acumulado_participantes_hombres_noche" => 0,
            "total_acumulado_participantes_mujeres_noche" => 0,
            "total_acumulado_hombres" => 0,
            "total_acumulado_mujeres" => 0,
            "total_acumulado_grupos" => 0,
            "total_acumulado_participantes" => 0,
        ];
    }

    private static function inicializarAcumuladosPorArea() {
        self::$total_acumulados_por_area = [
            "total_participantes_mañama" => 0,
            "total_participantes_hombres_mañama" => 0,
            "total_participantes_mujeres_mañama" => 0,
            "total_participantes_tarde" => 0,
            "total_participantes_hombres_tarde" => 0,
            "total_participantes_mujeres_tarde" => 0,
            "total_participantes_noche" => 0,
            "total_participantes_hombres_noche" => 0,
            "total_participantes_mujeres_noche" => 0,
            "total_hombres" => 0,
            "total_mujeres" => 0,
            "total_grupos" => 0,
            "total_participantes" => 0,
        ];
    }

    private static function consolidadoPorJornadaYSexo($datoJornada = [], ReporteNumeroCursoYParticipantePorJornadaDto $dato): array {
        $jornada = $dato->getJornada();
        $sexo = (string) $dato->getSexo(); 

        if ($sexo == "Otro") {
            $sexo = "M";
        }
        
        if (isset($datoJornada[$jornada]) && isset($datoJornada[$jornada][$sexo])) {
            $datoJornada[$jornada]["curso"] = $dato->getCurso(); 
            $datoJornada[$jornada][$sexo]["total"] = $dato->getTotalInscritos();
        }    

        $datoJornada[$jornada]["total_participantes"] =  $datoJornada[$jornada]["F"]["total"] + $datoJornada[$jornada]["M"]["total"];

        return $datoJornada;
    }

    private static function agregarTagRegistro(RegistroDTO $registro, $consolidadoPorJornadaYSexo = []): string
    {
        $fila = "
        <tr>
            <td class=\"nombre_curso\">".$registro->getCursoActual()."</td>
            <td>".$consolidadoPorJornadaYSexo["Mañana"]["total_participantes"]."</td>
            <td>".$consolidadoPorJornadaYSexo["Mañana"]["M"]["total"]."</td>
            <td>".$consolidadoPorJornadaYSexo["Mañana"]["F"]["total"]."</td>
            <td>".$consolidadoPorJornadaYSexo["Tarde"]["total_participantes"]."</td>
            <td>".$consolidadoPorJornadaYSexo["Tarde"]["M"]["total"]."</td>
            <td>".$consolidadoPorJornadaYSexo["Tarde"]["F"]["total"]."</td>
            <td>".$consolidadoPorJornadaYSexo["Noche"]["total_participantes"]."</td>
            <td>".$consolidadoPorJornadaYSexo["Noche"]["M"]["total"]."</td>
            <td>".$consolidadoPorJornadaYSexo["Noche"]["F"]["total"]."</td>
            <td>".((int) $registro->getCursoActualTotalGeneroMasculino() + (int) $registro->getCursoActualTotalGeneroOtro())."</td>
            <td>".$registro->getCursoActualTotalGeneroFemenino()."</td>
            <td>".$registro->getCursoActualTotalGrupos()."</td>
            <td>".$registro->getCursoActualTotalParticipantes()."</td>
        </tr>
        ";    

        self::actualizarAcumuladoPorArea($registro, $consolidadoPorJornadaYSexo);

        return $fila;
    }  

    private static function actualizarTotalAcumulados()
    {   
        self::$total_acumulados["total_acumulado_participantes_mañama"] += self::$total_acumulados_por_area["total_participantes_mañama"];
        self::$total_acumulados["total_acumulado_participantes_hombres_mañama"] += self::$total_acumulados_por_area["total_participantes_hombres_mañama"];
        self::$total_acumulados["total_acumulado_participantes_mujeres_mañama"] += self::$total_acumulados_por_area["total_participantes_mujeres_mañama"];
        self::$total_acumulados["total_acumulado_participantes_tarde"] += self::$total_acumulados_por_area["total_participantes_tarde"];
        self::$total_acumulados["total_acumulado_participantes_hombres_tarde"] += self::$total_acumulados_por_area["total_participantes_hombres_tarde"];
        self::$total_acumulados["total_acumulado_participantes_mujeres_tarde"] += self::$total_acumulados_por_area["total_participantes_mujeres_tarde"];
        self::$total_acumulados["total_acumulado_participantes_noche"] += self::$total_acumulados_por_area["total_participantes_noche"];
        self::$total_acumulados["total_acumulado_participantes_hombres_noche"] += self::$total_acumulados_por_area["total_participantes_hombres_noche"];
        self::$total_acumulados["total_acumulado_participantes_mujeres_noche"] += self::$total_acumulados_por_area["total_participantes_mujeres_noche"];
        self::$total_acumulados["total_acumulado_hombres"] += self::$total_acumulados_por_area["total_hombres"];
        self::$total_acumulados["total_acumulado_mujeres"] += self::$total_acumulados_por_area["total_mujeres"];
        self::$total_acumulados["total_acumulado_grupos"] += self::$total_acumulados_por_area["total_grupos"];
        self::$total_acumulados["total_acumulado_participantes"] += self::$total_acumulados_por_area["total_participantes"];
    } 

    private static function actualizarAcumuladoPorArea(RegistroDTO $registro, $consolidadoPorJornadaYSexo = [])
    {
        self::$total_acumulados_por_area["total_participantes_mañama"] += $consolidadoPorJornadaYSexo["Mañana"]["total_participantes"];
        self::$total_acumulados_por_area["total_participantes_hombres_mañama"] += $consolidadoPorJornadaYSexo["Mañana"]["M"]["total"];
        self::$total_acumulados_por_area["total_participantes_mujeres_mañama"] += $consolidadoPorJornadaYSexo["Mañana"]["F"]["total"];
        self::$total_acumulados_por_area["total_participantes_tarde"] += $consolidadoPorJornadaYSexo["Tarde"]["total_participantes"];
        self::$total_acumulados_por_area["total_participantes_hombres_tarde"] += $consolidadoPorJornadaYSexo["Tarde"]["M"]["total"];
        self::$total_acumulados_por_area["total_participantes_mujeres_tarde"] += $consolidadoPorJornadaYSexo["Tarde"]["F"]["total"];
        self::$total_acumulados_por_area["total_participantes_noche"] += $consolidadoPorJornadaYSexo["Noche"]["total_participantes"];
        self::$total_acumulados_por_area["total_participantes_hombres_noche"] += $consolidadoPorJornadaYSexo["Noche"]["M"]["total"];
        self::$total_acumulados_por_area["total_participantes_mujeres_noche"] += $consolidadoPorJornadaYSexo["Noche"]["F"]["total"];
        self::$total_acumulados_por_area["total_hombres"] += $registro->getCursoActualTotalGeneroMasculino();
        self::$total_acumulados_por_area["total_mujeres"] += $registro->getCursoActualTotalGeneroFemenino();
        self::$total_acumulados_por_area["total_grupos"] += $registro->getCursoActualTotalGrupos();
        self::$total_acumulados_por_area["total_participantes"] += $registro->getCursoActualTotalParticipantes();
    }  
    
    private static function agregarAcumuladoPorArea(string $nombre_area): string
    {    
        $fila = "
        <tr>
            <td class=\"nombre_curso bold-text\">Total ".$nombre_area."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_participantes_mañama"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_participantes_hombres_mañama"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_participantes_mujeres_mañama"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_participantes_tarde"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_participantes_hombres_tarde"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_participantes_mujeres_tarde"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_participantes_noche"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_participantes_hombres_noche"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_participantes_mujeres_noche"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_hombres"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_mujeres"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_grupos"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados_por_area["total_participantes"]."</td>
        </tr>
        ";    

        self::actualizarTotalAcumulados();

        return $fila;
    }
    
    private static function agregarTotalAcumulados(): string
    {    

        $fila = "
        <tr>
            <td class=\"nombre_curso bold-text\">Total participantes</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_participantes_mañama"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_participantes_hombres_mañama"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_participantes_mujeres_mañama"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_participantes_tarde"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_participantes_hombres_tarde"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_participantes_mujeres_tarde"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_participantes_noche"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_participantes_hombres_noche"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_participantes_mujeres_noche"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_hombres"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_mujeres"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_grupos"]."</td>
            <td class=\"bold-text\">".self::$total_acumulados["total_acumulado_participantes"]."</td>
        </tr>
        ";    

        return $fila;
    }    
    
    private static function agregarTagArea(ReporteNumeroCursoYParticipantePorJornadaDto $dato): string {
        return "<tr><td colspan=\"14\" class=\"bold-text\">".$dato->getArea()."</td></tr>";
    }
}