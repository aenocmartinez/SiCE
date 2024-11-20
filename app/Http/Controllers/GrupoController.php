<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarGrupo;
use Src\domain\Calendario;
use Src\domain\Grupo;
use Src\infraestructure\pdf\DataPDF;
use Src\infraestructure\pdf\SicePDF;
use Src\infraestructure\util\ListaDeValor;
use Src\infraestructure\util\Validador;
use Src\usecase\areas\ListarOrientadoresPorCursoCalendarioUseCase;
use Src\usecase\calendarios\BuscarCalendarioPorIdUseCase;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\cursos\ListarCursosUseCase;
use Src\usecase\dashboard\ListadoDeGruposConYSinCuposDisponiblesUseCase;
use Src\usecase\grupos\ActualizarGrupoUseCase;
use Src\usecase\grupos\BuscadorGruposUseCase;
use Src\usecase\grupos\BuscarGrupoPorIdUseCase;
use Src\usecase\grupos\CrearGrupoUseCase;
use Src\usecase\grupos\EliminarGrupoUseCase;
use Src\usecase\grupos\ListarCursosPorCalendarioUseCase;
use Src\usecase\grupos\ListarGruposUseCase;
use Src\usecase\grupos\ListarParticipantesGrupoUseCase as GruposListarParticipantesGrupoUseCase;
use Src\usecase\grupos\ListarParticipantesPlanillaAsistenciaUseCase;
use Src\usecase\orientadores\ListarOrientadoresUseCase;
use Src\usecase\salones\ListarSalonesPorEstadoUseCase;
use Src\view\dto\GrupoDto;

class GrupoController extends Controller
{
    public function index($pagina=1)
    {
        $periodo = Calendario::Vigente();
        if (!is_null(request('periodo'))) {
            $periodo = (new BuscarCalendarioPorIdUseCase)->ejecutar(request('periodo'));
        }
        $listaGruposPaginados = (new ListarGruposUseCase)->ejecutar($pagina, $periodo);

        return view('grupos.index', [
                    'paginate' => $listaGruposPaginados,
                    'periodoActual' => $periodo,
                    'periodos' => (new ListarCalendariosUseCase)->ejecutar(), 
            ]);
    }

    public function create()
    {
        return view('grupos.create', [
            'cursos' => array(),
            'calendarios' => (new ListarCalendariosUseCase())->ejecutar(),
            'salones' => (new ListarSalonesPorEstadoUseCase)->ejecutar(),
            'orientadores' => array(),
            'dias' => ListaDeValor::diasSemana(),
            'jornadas' => ListaDeValor::jornadas(),
            'grupo' => new Grupo,
        ]);
    }

    public function store(GuardarGrupo $request)
    {
        $grupoDto = $this->hydrateDto($request->validated());
        $response = (new CrearGrupoUseCase)->ejecutar($grupoDto);
        return redirect()->route('grupos.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            return redirect()->route('grupos.index')->with('code', "401")->with('status', "parámetro no válido");
        }

        $grupo = (new BuscarGrupoPorIdUseCase)->ejecutar($id);
        if (!$grupo->existe()) {
            return redirect()->route('grupos.index')->with('code', "200")->with('status', "grupo no encontrado");
        }

        return view('grupos.edit', [
            'cursos' => (new ListarCursosUseCase())->ejecutar(),
            'calendarios' => (new ListarCalendariosUseCase())->ejecutar(),
            'salones' => (new ListarSalonesPorEstadoUseCase())->ejecutar(),
            'orientadores' => (new ListarOrientadoresUseCase())->ejecutar(),
            'dias' => ListaDeValor::diasSemana(),
            'jornadas' => ListaDeValor::jornadas(),
            'grupo' => $grupo,
        ]);
    }

    public function update(GuardarGrupo $request, $id)
    {
        $grupoDto = $this->hydrateDto($request->validated());        
        $response = (new ActualizarGrupoUseCase)->ejecutar($grupoDto);
        return redirect()->route('grupos.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function destroy($id)
    {
        if (!Validador::parametroId($id)) {
            return redirect()->route('grupos.index')->with('status','parámetro no válido');
        }
        
        $response = (new EliminarGrupoUseCase)->ejecutar($id);        
        return redirect()->route('grupos.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function listarCursosPorCalendario($calendarioId, $cursoCalendarioIdActual) {
        if (!Validador::parametroId($calendarioId)) {
            return redirect()->route('grupos.index')->with('status','parámetro no válido');
        }

        return view('grupos._cursos_por_calendario',[
            'cursos' => (new ListarCursosPorCalendarioUseCase)->ejecutar($calendarioId),
            'cursoCalendarioIdActual' => $cursoCalendarioIdActual,
        ]);
    }

    public function listarOrientadoresPorCursoCalendario($cursoCalendarioId, $orientadorIdActual) {
        if (!Validador::parametroId($cursoCalendarioId)) {
            return redirect()->route('grupos.index')->with('status','parámetro no válido');
        }

        return view('grupos._orientadores_por_curso',[
            'orientadores' => (new ListarOrientadoresPorCursoCalendarioUseCase)->ejecutar($cursoCalendarioId),
            'orientadorIdActual' => $orientadorIdActual,
        ]);        
    }

    public function listarCursosAbiertosOCerrados($tipo) {
                
        $index = 'con_cupos';
        $title = 'Cursos abiertos';
        if ($tipo == 'cerrado') {
            $index = 'sin_cupos';
            $title = 'Cursos cerrados';
        }

        if ($tipo == 'cancelados') {
            $index = 'cancelados';
            $title = 'cancelados';
        }
        
        $cursos = (new ListadoDeGruposConYSinCuposDisponiblesUseCase)->ejecutar();        
        return view('grupos.cursos_por_estado', [
            'title' => $title,
            'cursos' => $cursos[$index]
        ]);
        
    }

    public function buscadorGrupos(){        
        $criterio = '';
        if (!is_null(request('criterio'))) {
            $criterio = request('criterio');
        }      
        
        $periodo = Calendario::Vigente();
        if( !is_null(request('periodo')))
        {
            $periodo = (new BuscarCalendarioPorIdUseCase)->ejecutar(request('periodo'));
        }

        return view("grupos.index", [
            "paginate" => (new BuscadorGruposUseCase)->ejecutar($criterio, $periodo),
            "criterio" => $criterio,
            'periodoActual' => $periodo,
            'periodos' => (new ListarCalendariosUseCase)->ejecutar(),             
        ]);         
    }

    public function buscadorGruposPaginados($criterio, $page = 1) {   
        if (strlen($criterio) == 0) {
            return redirect()->route('grupos.index');
        }
    
        $periodo = Calendario::Vigente();
        if (!is_null(request('periodo'))) {
            $periodo = (new BuscarCalendarioPorIdUseCase)->ejecutar(request('periodo'));
        }
    
        return view("grupos.index", [
            "paginate" => (new BuscadorGruposUseCase)->ejecutar($criterio, $periodo, $page),
            "criterio" => $criterio,
            'periodoActual' => $periodo,
            'periodos' => (new ListarCalendariosUseCase)->ejecutar(),
        ]);         
    }
    

    public function masInformacion($id) {
        $grupo = (new BuscarGrupoPorIdUseCase)->ejecutar($id);
        if (!$grupo->existe()) {
            return redirect()->route('grupos.index')->with('code', "200")->with('status', "grupo no encontrado");
        }
        
        return view('grupos.mas_informacion', ['grupo' => $grupo]);
    }

    public function descargarListadoParticipantes($grupoId=0) {    
        
        $data = (new GruposListarParticipantesGrupoUseCase)->ejecutar($grupoId);

        $fileName = 'listado_participantes.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);        
    }

    public function descargarPlanillaAsistencia($grupoId=0) {    

        $datos = (new ListarParticipantesPlanillaAsistenciaUseCase)->ejecutar($grupoId);        
        if (sizeof($datos) == 1) {
            return redirect()->route('grupos.index')->with('code', "500")->with('status', "No tiene participantes inscritos");
        }            
        
        $curso = $datos[1][0];
        $orientador = $datos[1][1];
        $horario = $datos[1][3] . ", " . $datos[1][4];
        $periodo = $datos[1][11];
        $salon = $datos[1][13];
        $area = $datos[1][14];
        $numero_participantes = sizeof($datos) - 1;
        $participantes = "";

        foreach($datos as $index => $participante) {
            
            if ($index == 0) {
                continue;
            }     
            $participantes .= "<tr>
                <td class=\"student-name\">".$participante[5]."<br>".$participante[6]."</td>
                <td class=\"day-cell\"></td><td class=\"day-cell\"></td><td class=\"day-cell\"></td><td class=\"day-cell\"></td>
                <td class=\"day-cell\"></td><td class=\"day-cell\"></td><td class=\"day-cell\"></td><td class=\"day-cell\"></td>
                <td class=\"day-cell\"></td><td class=\"day-cell\"></td><td class=\"day-cell\"></td><td class=\"day-cell\"></td>
                <td class=\"day-cell\"></td><td class=\"day-cell\"></td><td class=\"day-cell\"></td><td class=\"day-cell\"></td>
                <td class=\"certification-cell\"></td>
                <td class=\"certification-cell\"></td>
                <td class=\"certification-cell\">".$participante[12]."</td>                
            </tr>";
        }

        $path_css1 = __DIR__ . "/../../../src/infraestructure/listaAsistencia/template/style.css"; 
        $path_template  = __DIR__ . "/../../../src/infraestructure/listaAsistencia/template/plantilla_asistencia.html";

        $html = file_get_contents($path_template);
        $html = str_replace('{{CURSO}}', $curso, $html);
        $html = str_replace('{{AREA}}', $area, $html);
        $html = str_replace('{{GRUPO}}', "G".$grupoId, $html);
        $html = str_replace('{{HORARIO}}', $horario, $html);
        $html = str_replace('{{ORIENTADOR}}', $orientador, $html);
        $html = str_replace('{{PERIODO}}', $periodo, $html);
        $html = str_replace('{{NUMERO_PARTICIPANTES}}', $numero_participantes, $html);        
        $html = str_replace('{{SALON}}', $salon, $html);        
        $html = str_replace('{{PARTICIPANTES}}', $participantes, $html);
        

        $nombre_archivo = "LISTA_ASISTENCIA_G" . $grupoId . ".pdf";

        $dataPdf = new DataPDF($nombre_archivo);
        $dataPdf->setData([
            'path_css1' => $path_css1,
            'html' => $html,
            'format' => 'Letter',
            'orientation' => 'L',
        ]);

        SicePDF::generarFormatoPago($dataPdf);
                
        $ruta_archivo = storage_path() . '/' . $nombre_archivo;
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nombre_archivo . '"',
        ];
        
        return response()->download($ruta_archivo, $nombre_archivo, $headers)->deleteFileAfterSend(true);    
    }

    public function descargarReporteEstadoDeLegalizaciónDeParticipantes($grupoId=0) {    

        $datos = (new GruposListarParticipantesGrupoUseCase)->ejecutar($grupoId);
        if (sizeof($datos) == 1) {
            return redirect()->route('grupos.index')->with('code', "500")->with('status', "No tiene participantes inscritos");
        }    
        
        $curso = $datos[1][0];
        $salon = $datos[1][13];
        $orientador = $datos[1][1];
        $horario = $datos[1][3] . ", " . $datos[1][4];
        $periodo = $datos[1][11];        
        $numero_participantes = sizeof($datos) - 1;        
        $participantes = "";

        foreach($datos as $index => $participante) {
            
            if ($index == 0) {
                continue;
            }            
            
            $participantes .= "<tr>
                                <td>".$participante[5]."</td>
                                <td>".$participante[6]."</td>
                                <td>".$participante[7]."</td>
                                <td>".$participante[8]."</td>
                                <td>".$participante[10]."</td>
                                <td>".$participante[12]."</td>
                            </tr>";
        }

        $path_css1 = __DIR__ . "/../../../src/infraestructure/legalizacionParticipantes/template/style.css"; 
        $path_template  = __DIR__ . "/../../../src/infraestructure/legalizacionParticipantes/template/plantilla_legalizacion.html";

        $html = file_get_contents($path_template);
        $html = str_replace('{{CURSO}}', $curso, $html);
        $html = str_replace('{{GRUPO}}', "G".$grupoId, $html);
        $html = str_replace('{{SALON}}', $salon , $html);
        $html = str_replace('{{HORARIO}}', $horario, $html);
        $html = str_replace('{{ORIENTADOR}}', $orientador, $html);
        $html = str_replace('{{PERIODO}}', $periodo, $html);
        $html = str_replace('{{NUMERO_PARTICIPANTES}}', $numero_participantes, $html);        
        $html = str_replace('{{PARTICIPANTES}}', $participantes, $html);
        

        $nombre_archivo = "ESTADO_LEGALIZACION_DE_PARTICIPANTES_G" . $grupoId . ".pdf";

        $dataPdf = new DataPDF($nombre_archivo);
        $dataPdf->setData([
            'path_css1' => $path_css1,
            'html' => $html,
            'format' => 'Letter',
            'orientation' => 'L',
        ]);

        SicePDF::generarPDFEstadoLegalizacionParticipantes($dataPdf);
                
        $ruta_archivo = storage_path() . '/' . $nombre_archivo;
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nombre_archivo . '"',
        ];
        
        return response()->download($ruta_archivo, $nombre_archivo, $headers)->deleteFileAfterSend(true);    
    }

    private function hydrateDto($data): GrupoDto {            
        $grupoDto = new GrupoDto();
        $grupoDto->dia = $data['dia'];
        $grupoDto->cursoCalendarioId = $data['curso'];
        $grupoDto->salonId = $data['salon'];
        $grupoDto->jornada = $data['jornada'];
        $grupoDto->calendarioId = $data['calendario'];
        $grupoDto->orientadorId = $data['orientador'];
        $grupoDto->observaciones = $data['observaciones'];
        $grupoDto->cupo = $data['cupo'];
        
        $grupoDto->bloqueado = true;
        if (is_null(request()->bloqueado)) {
            $grupoDto->bloqueado = false;
        }
        
        $grupoDto->cerradoParaInscripcion = true;
        if (is_null(request()->cerradoParaInscripcion)) {
            $grupoDto->cerradoParaInscripcion = false;
        }
        
        $grupoDto->habilitadoParaPreInscripcion = true;
        if (is_null(request()->habilitadoParaPreInscripcion)) {
            $grupoDto->habilitadoParaPreInscripcion = false;
        }        

        if (isset(request()->id)) {
            $grupoDto->id = request()->id;
        }
        
        return $grupoDto;
    }

}
