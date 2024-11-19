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
                    COUNT(f.participante_id) AS total_inscritos,
                    (SELECT COUNT(g1.id) 
                        FROM grupos g1
                        INNER JOIN curso_calendario cc1 ON cc1.id = g1.curso_calendario_id
                        WHERE cc1.curso_id = c.id AND cc1.calendario_id = ".$calendarioId.") AS total_grupos,
                    (SELECT COUNT(f1.participante_id) 
                        FROM formulario_inscripcion f1
                        INNER JOIN grupos g2 ON f1.grupo_id = g2.id
                        INNER JOIN curso_calendario cc2 ON g2.curso_calendario_id = cc2.id
                        WHERE cc2.curso_id = c.id AND cc2.calendario_id = ".$calendarioId." 
                            AND f1.estado = 'Pagado') AS total_participantes,
                    (SELECT COUNT(f2.participante_id)
                        FROM formulario_inscripcion f2
                        INNER JOIN participantes p2 ON f2.participante_id = p2.id
                        INNER JOIN grupos g3 ON f2.grupo_id = g3.id
                        INNER JOIN curso_calendario cc3 ON g3.curso_calendario_id = cc3.id
                        WHERE cc3.curso_id = c.id AND cc3.calendario_id = ".$calendarioId." 
                            AND p2.sexo = 'M' AND f2.estado = 'Pagado') AS total_masculino,
                    (SELECT COUNT(f3.participante_id)
                        FROM formulario_inscripcion f3
                        INNER JOIN participantes p3 ON f3.participante_id = p3.id
                        INNER JOIN grupos g4 ON f3.grupo_id = g4.id
                        INNER JOIN curso_calendario cc4 ON g4.curso_calendario_id = cc4.id
                        WHERE cc4.curso_id = c.id AND cc4.calendario_id = ".$calendarioId." 
                            AND p3.sexo = 'F' AND f3.estado = 'Pagado') AS total_femenino,
                    (SELECT COUNT(f4.participante_id)
                        FROM formulario_inscripcion f4
                        INNER JOIN participantes p4 ON f4.participante_id = p4.id
                        INNER JOIN grupos g5 ON f4.grupo_id = g5.id
                        INNER JOIN curso_calendario cc5 ON g5.curso_calendario_id = cc5.id
                        WHERE cc5.curso_id = c.id AND cc5.calendario_id = ".$calendarioId." 
                            AND p4.sexo = 'Otro' AND f4.estado = 'Pagado') AS total_otro
                FROM 
                    grupos g
                INNER JOIN curso_calendario cc ON cc.id = g.curso_calendario_id
                INNER JOIN cursos c ON c.id = cc.curso_id 
                INNER JOIN areas a ON a.id = c.area_id
                LEFT JOIN formulario_inscripcion f ON g.id = f.grupo_id
                LEFT JOIN participantes p ON f.participante_id = p.id
                WHERE 
                    cc.calendario_id = ".$calendarioId."
                    AND f.estado = 'Pagado'
                GROUP BY 
                    a.nombre, c.nombre, g.jornada, p.sexo, c.id
                ORDER BY 
                    a.nombre, c.nombre, FIELD(g.jornada, 'mañana', 'tarde', 'noche'), p.sexo
        ";
    
        return DB::select($query);
    }
    
}
