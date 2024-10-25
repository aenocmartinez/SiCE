<?php 

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Src\usecase\calendarios\BuscarCalendarioPorIdUseCase;
use Src\view\dto\ReporteNumeroCursoYParticipantePorJornadaDto;

class ReporteNumeroCursoYParticipantePorJornadaDao extends Model {

    /**
     * Transforma datos del repositorio a un array de ReporteNumeroCursoYParticipantePorJornadaDto
     *
     * @param $calendarioId
     * @return array
     */
     public function obtenerReportePorJornada(int $calendarioId): array {
        $reporte = [];

        $datos = $this->obtenerDatosParaReportePorJornada($calendarioId);
        foreach($datos as $dato)
        {
            $registro = new ReporteNumeroCursoYParticipantePorJornadaDto();
            $registro->setArea($dato->area);
            $registro->setCurso($dato->curso);
            $registro->setJornada($dato->jornada);
            $registro->setSexo($dato->sexo);
            $registro->setTotalInscritos($dato->total_inscritos);
            $registro->setTotalGrupos($dato->total_grupos);
            $registro->setTotalParticipantes($dato->total_participantes);
            $registro->setTotalMasculinos($dato->total_masculino);
            $registro->setTotalFemeninos($dato->total_femenino);
            $registro->setTotalOtro($dato->total_otro);

            $reporte[] = $registro;
        }    
        
        return $reporte;
     }

    /**
     * Obtener reporte de número de cursos y participantes por jornada.
     *
     * @param $calendarioId
     * @return array
     */
    private function obtenerDatosParaReportePorJornada($calendarioId): array
    {
        $query = "
            SELECT 
                a.nombre AS area,
                c.nombre AS curso,
                g.jornada,
                p.sexo,
                COUNT(DISTINCT f.participante_id) AS total_inscritos,
                (SELECT COUNT(DISTINCT g1.id) 
                    FROM grupos g1
                    INNER JOIN curso_calendario cc1 ON cc1.id = g1.curso_calendario_id
                    WHERE cc1.curso_id = c.id AND cc1.calendario_id = ".$calendarioId.") AS total_grupos,
                (SELECT COUNT(DISTINCT f1.participante_id) 
                    FROM formulario_inscripcion f1
                    LEFT JOIN convenios c1 ON f1.convenio_id = c1.id
                    INNER JOIN grupos g2 ON f1.grupo_id = g2.id
                    INNER JOIN curso_calendario cc2 ON g2.curso_calendario_id = cc2.id
                    WHERE cc2.curso_id = c.id AND cc2.calendario_id = ".$calendarioId." 
                        AND (f1.convenio_id IS NULL AND f1.estado = 'Pagado' 
                                OR c1.es_cooperativa = 1 
                                OR (f1.estado = 'Pagado' AND f1.convenio_id IS NOT NULL))) AS total_participantes,
                (SELECT COUNT(DISTINCT f2.participante_id)
                    FROM formulario_inscripcion f2
                    LEFT JOIN convenios c2 ON f2.convenio_id = c2.id
                    INNER JOIN participantes p2 ON f2.participante_id = p2.id
                    INNER JOIN grupos g3 ON f2.grupo_id = g3.id
                    INNER JOIN curso_calendario cc3 ON g3.curso_calendario_id = cc3.id
                    WHERE cc3.curso_id = c.id AND cc3.calendario_id = ".$calendarioId." 
                        AND p2.sexo = 'M' 
                        AND (f2.convenio_id IS NULL AND f2.estado = 'Pagado' 
                                OR c2.es_cooperativa = 1 
                                OR (f2.estado = 'Pagado' AND f2.convenio_id IS NOT NULL))) AS total_masculino,
                (SELECT COUNT(DISTINCT f3.participante_id)
                    FROM formulario_inscripcion f3
                    LEFT JOIN convenios c3 ON f3.convenio_id = c3.id
                    INNER JOIN participantes p3 ON f3.participante_id = p3.id
                    INNER JOIN grupos g4 ON f3.grupo_id = g4.id
                    INNER JOIN curso_calendario cc4 ON g4.curso_calendario_id = cc4.id
                    WHERE cc4.curso_id = c.id AND cc4.calendario_id = ".$calendarioId." 
                        AND p3.sexo = 'F' 
                        AND (f3.convenio_id IS NULL AND f3.estado = 'Pagado' 
                                OR c3.es_cooperativa = 1 
                                OR (f3.estado = 'Pagado' AND f3.convenio_id IS NOT NULL))) AS total_femenino,
                (SELECT COUNT(DISTINCT f4.participante_id)
                    FROM formulario_inscripcion f4
                    LEFT JOIN convenios c4 ON f4.convenio_id = c4.id
                    INNER JOIN participantes p4 ON f4.participante_id = p4.id
                    INNER JOIN grupos g5 ON f4.grupo_id = g5.id
                    INNER JOIN curso_calendario cc5 ON g5.curso_calendario_id = cc5.id
                    WHERE cc5.curso_id = c.id AND cc5.calendario_id = ".$calendarioId." 
                        AND p4.sexo = 'Otro' 
                        AND (f4.convenio_id IS NULL AND f4.estado = 'Pagado' 
                                OR c4.es_cooperativa = 1 
                                OR (f4.estado = 'Pagado' AND f4.convenio_id IS NOT NULL))) AS total_otro
            FROM 
                grupos g
            INNER JOIN curso_calendario cc ON cc.id = g.curso_calendario_id
            INNER JOIN cursos c ON c.id = cc.curso_id 
            INNER JOIN areas a ON a.id = c.area_id
            LEFT JOIN formulario_inscripcion f ON g.id = f.grupo_id
            LEFT JOIN participantes p ON f.participante_id = p.id
            LEFT JOIN convenios co ON f.convenio_id = co.id
            WHERE 
                cc.calendario_id = ".$calendarioId."
                AND (f.convenio_id IS NULL AND f.estado = 'Pagado' 
                    OR co.es_cooperativa = 1 
                    OR (f.estado = 'Pagado' AND f.convenio_id IS NOT NULL))
                AND f.estado NOT IN ('Aplazado', 'Devuelto', 'Anulado')
            GROUP BY 
                a.nombre, c.nombre, g.jornada, p.sexo, c.id
            ORDER BY 
                a.nombre, c.nombre, FIELD(g.jornada, 'mañana', 'tarde', 'noche'), p.sexo
        ";
    
        return DB::select($query);
        
    }
}
