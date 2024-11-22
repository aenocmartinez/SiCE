<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarCalenadario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\domain\Calendario;
use Src\infraestructure\pdf\DataPDF;
use Src\infraestructure\pdf\SicePDF;
use Src\view\dto\CalendarioDto;
use Src\view\dto\CursoCalendarioDto;
use Src\infraestructure\util\Validador;
use Src\usecase\areas\ListarAreasUseCase;
use Src\usecase\cursos\ListarCursosPorAreaUseCase;
use Src\usecase\calendarios\CrearCalendarioUseCase;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\calendarios\EliminarCalendarioUseCase;
use Src\usecase\calendarios\ActualizarCalendarioUseCase;
use Src\usecase\calendarios\BuscarCalendarioPorIdUseCase;
use Src\usecase\calendarios\AgregarCursoACalendarioUseCase;
use Src\usecase\calendarios\CerrarCalendarioUseCase;
use Src\usecase\calendarios\EstadisticasCalendarioUseCase;
use Src\usecase\calendarios\ListarCursosPorCalendarioUseCase;
use Src\usecase\calendarios\ListarParticipantesPorCalendarioUseCase;
use Src\usecase\calendarios\ReporteNumeroCursoYParticipantePorJornadaUseCase;
use Src\usecase\calendarios\RetirarCursoACalendarioUseCase;

class CalendarioController extends Controller
{
    public function index()
    {
        $casoUso = new ListarCalendariosUseCase();
        $calendarios = $casoUso->ejecutar();

        return view('calendario.index', compact('calendarios'));

    }

    public function create()
    {
        return view('calendario.create', ['calendario' => new Calendario()]);
    }

    public function store(GuardarCalenadario $request)
    {
        $data = $request->validated();
        
        $calendarioDto = new CalendarioDto();
        $calendarioDto->nombre = $data['nombre'];
        $calendarioDto->fechaInicial = $data['fec_ini'];
        $calendarioDto->fechaFinal = $data['fec_fin'];
        $calendarioDto->fechaInicioClase = $data['fec_ini_clase'];
        
        $calendarioDto->estaFormularioInscripcionAbierto = false;
        if (isset($data['esta_formulario_inscripcion_abierto'])) {
            $calendarioDto->estaFormularioInscripcionAbierto = true;
        }

        $response = (new CrearCalendarioUseCase())->ejecutar($calendarioDto);
        
        return redirect()->route('calendario.index')->with('code', $response->code)->with('status', $response->message);
    }


    public function edit($id)
    {
        if (!Validador::parametroId($id)) {
            return redirect()->route('calendario.index')->with('status','parámetro no válido');
        }

        $casoUsoBuscarCalendario = new BuscarCalendarioPorIdUseCase();
        $calendario = $casoUsoBuscarCalendario->ejecutar($id);

        if (!$calendario->existe()) {
            return redirect()->route('calendario.index')->with('status','Calendario no encontrado.');
        }

        return view('calendario.edit',['calendario' => $calendario]);
    }

    public function update(GuardarCalenadario $request, $id)
    {
        $data = $request->validated();        
        if (!Validador::parametroId($id)) {
            return redirect()->route('calendario.index')->with('status','parámetro no válido');
        }

        $calendarioDto = new CalendarioDto();
        $calendarioDto->nombre = $data['nombre'];
        $calendarioDto->fechaInicial = $data['fec_ini'];
        $calendarioDto->fechaFinal = $data['fec_fin'];
        $calendarioDto->fechaInicioClase = $data['fec_ini_clase'];
        $calendarioDto->id = $id;

        $calendarioDto->estaFormularioInscripcionAbierto = false;
        if (isset($data['esta_formulario_inscripcion_abierto'])) {
            $calendarioDto->estaFormularioInscripcionAbierto = true;
        }

        $casoUsoActualizar = new ActualizarCalendarioUseCase();
        $response = $casoUsoActualizar->ejecutar($calendarioDto);

        return redirect()->route('calendario.index')->with('code', $response->code)->with('status', $response->message);
    }


    public function destroy($id)
    {
        if (!Validador::parametroId($id)) {
            return redirect()->route('calendario.index')->with('status','parámetro no válido');
        }
        
        $casoUsoEliminar = new EliminarCalendarioUseCase();
        $response = $casoUsoEliminar->ejecutar($id);

        return redirect()->route('calendario.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function cursosDelCalendario($calendarioId) {
        if (!Validador::parametroId($calendarioId)) {
            return redirect()->route('calendario.index')->with('code', 401)->with('status','parámetro no válido');
        }

        $calendario = (new BuscarCalendarioPorIdUseCase)->ejecutar($calendarioId);
        if (!$calendario->existe()) {
            return redirect()->route('calendario.index')->with('code', 500)->with('status','El calendario no existe');
        }

        if (!$calendario->esVigente()) {
            return redirect()->route('calendario.index')->with('code', 500)->with('status','No se permite realizar esta acción, el calendario ya está caducado.');
        }
        

        return view('calendario.cursos_calendario',[
            'calendario' => $calendario,
            'areas' => (new ListarAreasUseCase)->ejecutar()
        ]);
    }

    public function agregarCursoACalendario() {  
        $cursoCalendarioDto = $this->hydrateCursoCalendarioDto();        
        $response = (new AgregarCursoACalendarioUseCase())->ejecutar($cursoCalendarioDto);
        
        return redirect()->route('calendario.cursos', [
                        'id' => $cursoCalendarioDto->calendarioId,
                        'area_id' => request('area_id'),
                        ])
                        ->with('code', $response->code)
                        ->with('status', $response->message);
    }

    public function guardarTodosLosCursos(Request $request) {  

        $areaId = 0;
        $cursos = $request->input('cursos', []);

        $areaId = request('area_id');
        if (is_null(request('area_id'))) {
            return redirect()->route('calendario.index')->with('code', 500)->with('status','No se recibe un área válida.');
        }

        $calendarioVigente = Calendario::Vigente();
        if (!$calendarioVigente->existe()) {
            return redirect()->route('calendario.index')->with('code', 500)->with('status','No existe un periodo vigente.');
        }

        try {

            $ids_de_cursos_abiertos = $calendarioVigente->obtenerIDsDeCursosAbiertosPorCalendarioYArea($areaId);
            $nuevos_ids_de_cursos_abiertos = [];

            foreach ($cursos as $cursoData) {   

                if (is_null($cursoData['costo'])) {
                    continue;
                }
               
                $cursoCalendarioDto = $this->hydrateCursoCalendarioDto2($cursoData, $calendarioVigente);

                $response = (new AgregarCursoACalendarioUseCase())->ejecutar($cursoCalendarioDto);

                if ($response->code == '200') {
                    $nuevos_ids_de_cursos_abiertos[] = $cursoCalendarioDto->cursoId;
                }
            }

            $ids_para_retirar_del_calendario = array_diff($ids_de_cursos_abiertos, $nuevos_ids_de_cursos_abiertos);
            $response = (new RetirarCursoACalendarioUseCase)->ejecutar($calendarioVigente, $ids_para_retirar_del_calendario);

            $response->message = "Asignación de cursos exitosa";
            if ($response->code != 200) {
                $response->message = "Ha ocurrido un error en la apertura de los cursos.";
            }

            return redirect()->route('calendario.cursos', [
                'id' => $calendarioVigente->getId(),
                'area_id' => $areaId,
                ])
                ->with('code', $response->code)
                ->with('status', $response->message);            
    
        } catch (\Exception $e) {

            return redirect()->route('calendario.cursos', [
                'id' => $calendarioVigente->getId(),
                'area_id' => $areaId,
                ])
                ->with('code', 500)
                ->with('status', 'Error al asignar los cursos');             
        }
    }    

    public function retirarCursoACalendario($calendarioId, $cursoCalendarioId, $areaId) {
        if (!Validador::parametroId($calendarioId)) {
            return redirect()->route('calendario.index')->with('code', 401)->with('status','parámetro no válido');
        }

        if (!Validador::parametroId($cursoCalendarioId)) {
            return redirect()->route('calendario.index')->with('code', 401)->with('status','parámetro no válido');
        }   
        
        $response = (new RetirarCursoACalendarioUseCase)->ejecutar($calendarioId, $cursoCalendarioId);

        return redirect()->route('calendario.cursos', [
            'id' => $calendarioId,
            'area_id' => $areaId,
            ])
            ->with('code', $response->code)
            ->with('status', $response->message);              
    }

    public function listarCursosPorArea($calendarioId, $areaId) {

        if (!Validador::parametroId($areaId)) {
            return redirect()->route('calendario.index')->with('code', 401)->with('status','parámetro no válido');
        }        
        
        return view('calendario._cursos', [
            'cursos' => (new ListarCursosPorAreaUseCase)->ejecutar($areaId),
            'calendario_id' => $calendarioId,
            'area_id' => $areaId,
        ]);
    }

    public function listarCursosDelCalendario($calendarioId, $areaId) {
        if (!Validador::parametroId($calendarioId)) {
            return redirect()->route('calendario.index')->with('code', 401)->with('status','parámetro no válido');                    
        }

        if (!Validador::parametroId($areaId)) {
            return redirect()->route('calendario.index')->with('code', 401)->with('status','parámetro no válido');
        }        

        return view('calendario._cursos_abiertos', [
            'cursosCalendario' => (new ListarCursosPorCalendarioUseCase)->ejecutar($calendarioId, $areaId),
            'area_id' => $areaId,
        ]);
    }

    public function estadisticas($id) {
        
        $data = (new EstadisticasCalendarioUseCase)->ejecutar($id);
        
        if (!$data['existe']) {
            return redirect()->route('calendario.index')->with('code', 401)->with('status','Periodo no encontrado.');
        }

        return view('calendario.estadisticas', [
            'data' => $data,
            'calendarioId' => $id,
        ]);
    }

    public function descargarParticipantes($calendarioId) {

        $data = (new ListarParticipantesPorCalendarioUseCase)->ejecutar($calendarioId);

        $fileName = 'participantes_calendario_'.$calendarioId.'.csv';

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

    public function generarReporteNumeroCursosYParticipantesPorJornada($calendarioId) {
        
        $calendario = (new BuscarCalendarioPorIdUseCase)->ejecutar($calendarioId);
        if (!$calendario->existe()) {
            return redirect()->route('calendario.index')->with('code', 401)->with('status','Periodo no encontrado.');
        }

        $reporte = (new ReporteNumeroCursoYParticipantePorJornadaUseCase)->ejecutar($calendario->getId());

        $nombre_archivo = "CUADRO_NO_110.pdf";
        $dataPdf = new DataPDF($nombre_archivo);
        $dataPdf->setData([
            'path_css1' => $reporte["css"],
            'html' => $reporte["content"],
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

    public function cerrarPeriodo($calendarioId=0) {

        $periodo = (new BuscarCalendarioPorIdUseCase)->ejecutar($calendarioId);
        if (!$periodo->existe()) 
        {
            return redirect()->route('calendario.index')->with('code', 401)->with('status','Periodo no encontrado.');
        }

        
        (new CerrarCalendarioUseCase)->Ejecutar($periodo);

        return redirect()->route('calendario.index')->with('code', 200)->with('status', "Periodo cerrado con éxito");
    }

    protected function hydrateCursoCalendarioDto2($cursoData, Calendario $calendario) {

        $cursoCalendarioDto = new CursoCalendarioDto();    
        $cursoCalendarioDto->calendario = $calendario;
        $cursoCalendarioDto->cursoId = $cursoData['curso_id'];
        $cursoCalendarioDto->costo = Validador::convertirAEnteroDesdeMoneda($cursoData['costo']);
        $cursoCalendarioDto->modalidad = $cursoData['modalidad'];

        // $clave = request('curso_id') . request('calendario_id');
        // $modalidad = 'modalidad_' . $clave;    
        
        // $costo = 0;
        // if (!is_null(request('costo_' . $clave))) {
        //     $costo = str_replace("$", "", request('costo_' . $clave));
        //     $costo = str_replace(".", "", $costo);        
        //     $costo = str_replace(" ", "", $costo);  
        // }

        // $cupos = 0;
        // if (!is_null(request('cupos_' . $clave))) {
        //     $cupos = request('cupos_' . $clave);
        // }

        // $modalidad = 'Presencial';
        // if (!is_null(request('modalidad_' . $clave))) {
        //     $modalidad = request('modalidad_' . $clave);
        // }        

        // $cursoCalendarioDto->costo = floatval($costo);
        // $cursoCalendarioDto->cupos = (int)$cupos;
        // $cursoCalendarioDto->modalidad = $modalidad;
    
        return $cursoCalendarioDto;
    }

    // private function hydrateCursoCalendarioDto(): CursoCalendarioDto{        
    //     $cursoCalendarioDto = new CursoCalendarioDto();
    //     $cursoCalendarioDto->calendarioId = (int)request('calendario_id');
    //     $cursoCalendarioDto->cursoId = (int)request('curso_id');

    //     $clave = request('curso_id') . request('calendario_id');
    //     $modalidad = 'modalidad_' . $clave;    
        
    //     $costo = 0;
    //     if (!is_null(request('costo_' . $clave))) {
    //         $costo = str_replace("$", "", request('costo_' . $clave));
    //         $costo = str_replace(".", "", $costo);        
    //         $costo = str_replace(" ", "", $costo);  
    //     }

    //     $cupos = 0;
    //     if (!is_null(request('cupos_' . $clave))) {
    //         $cupos = request('cupos_' . $clave);
    //     }

    //     $modalidad = 'Presencial';
    //     if (!is_null(request('modalidad_' . $clave))) {
    //         $modalidad = request('modalidad_' . $clave);
    //     }        

    //     $cursoCalendarioDto->costo = floatval($costo);
    //     $cursoCalendarioDto->cupos = (int)$cupos;
    //     $cursoCalendarioDto->modalidad = $modalidad;

    //     return $cursoCalendarioDto;
    // }
}
