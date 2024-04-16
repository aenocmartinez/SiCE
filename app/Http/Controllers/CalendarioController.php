<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarCalenadario;
use Src\domain\Calendario;
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
use Src\usecase\calendarios\EstadisticasCalendarioUseCase;
use Src\usecase\calendarios\ListarCursosPorCalendarioUseCase;
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
        $response = (new CrearCalendarioUseCase())->ejecutar(new CalendarioDto($data['nombre'], $data['fec_ini'], $data['fec_fin']));
        
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

        $calendarioDto = new CalendarioDto($data['nombre'], $data['fec_ini'], $data['fec_fin']);
        $calendarioDto->id = $id;

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

        return view('calendario._cursos_calendario', [
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
        ]);
    }

    private function hydrateCursoCalendarioDto(): CursoCalendarioDto{        
        $cursoCalendarioDto = new CursoCalendarioDto();
        $cursoCalendarioDto->calendarioId = (int)request('calendario_id');
        $cursoCalendarioDto->cursoId = (int)request('curso_id');

        $clave = request('curso_id') . request('calendario_id');
        $modalidad = 'modalidad_' . $clave;    
        
        $costo = 0;
        if (!is_null(request('costo_' . $clave))) {
            $costo = str_replace("$", "", request('costo_' . $clave));
            $costo = str_replace(".", "", $costo);        
            $costo = str_replace(" ", "", $costo);  
        }

        $cupos = 0;
        if (!is_null(request('cupos_' . $clave))) {
            $cupos = request('cupos_' . $clave);
        }

        $modalidad = 'Presencial';
        if (!is_null(request('modalidad_' . $clave))) {
            $modalidad = request('modalidad_' . $clave);
        }        

        $cursoCalendarioDto->costo = floatval($costo);
        $cursoCalendarioDto->cupos = (int)$cupos;
        $cursoCalendarioDto->modalidad = $modalidad;

        return $cursoCalendarioDto;
    }
}
