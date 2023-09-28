<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Src\view\dto\CursoDto;

use Src\infraestructure\util\Validador;
use Src\usecase\areas\ListarAreasUseCase;
use Src\usecase\cursos\ActualizarCursoUseCase;
use Src\usecase\cursos\BuscarCursoPorIdUseCase;
use Src\usecase\cursos\CrearCursoUseCase;
use Src\usecase\cursos\EliminarCursoUseCase;
use Src\usecase\cursos\ListarCursosUseCase;

class CursoController extends Controller
{
    public function index() {
        $casoUso = new ListarCursosUseCase();
        $resp = $casoUso->ejecutar();
        
        return view("cursos.index", [
            "cursos" => $resp["data"]
        ]);
    }

    public function buscarPorId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            return redirect()->route('cursos.index')                
                    ->with('code', "401")
                    ->with('status', "parámetro no válido");            
        }
            
        $casoUso = new BuscarCursoPorIdUseCase();
        $curso = $casoUso->ejecutar($id);
        $curso = $curso['data'];
        
        $listarAreas = new ListarAreasUseCase();
        $areas = $listarAreas->ejecutar();
        

        return view("cursos.edit", ["curso" => [
            'id' => $curso['id'],
            'areas' => $areas['data'],
            'nombre' => $curso['nombre'],
            'costo' => $curso['costo'],
            'modalidad' => $curso['modalidad'],
            'areaId' => $curso['area']['id']
        ]]);                  
    }

    public function create() {
        $listarAreas = new ListarAreasUseCase();
        $rs = $listarAreas->ejecutar();
        $areas = $rs['data'];

        return view("cursos.create", ["curso" => [
            'areas' => $areas,
            'nombre' => '',
            'costo' => '',
            'modalidad' => '',
        ]]);
    }

    public function store() {

        request()->validate([
            'nombre' => 'required',
            'costo' => 'required',
            'area' => 'required',
        ]);

        $cursoDto = new CursoDto();
        
        $cursoDto->modalidad = 'presencial';
        if (!is_null(request('modalidad'))) {
            $cursoDto->modalidad = 'virtual';
        }
        
        $cursoDto->nombre = request('nombre');
        $cursoDto->costo = request('costo');
        $cursoDto->areaId = request('area');

        $casoUso = new CrearCursoUseCase();
        $resp = $casoUso->ejecutar($cursoDto);   

        return redirect()
                    ->route('cursos.index')
                    ->with('code', $resp['code'])
                    ->with('status', $resp['message']);
    }

    public function delete($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        }

        $casoUso = new EliminarCursoUseCase();
        $rs = $casoUso->ejecutar($id);
        return redirect()->route('cursos.index')
                ->with('code', $rs['code'])
                ->with('status', $rs['message']);
    }
    
    public function update() {

        request()->validate([
            'id' => 'required',
            'nombre' => 'required',
            'costo' => 'required',
            'area' => 'required',
        ]);

        $cursoDto = new CursoDto();
        $cursoDto->modalidad = 'presencial';
        if (!is_null(request('modalidad'))) {
            $cursoDto->modalidad = 'virtual';
        }
        
        $cursoDto->id = request('id');
        $cursoDto->nombre = request('nombre');
        $cursoDto->costo = request('costo');
        $cursoDto->areaId = request('area');

        $casoUso = new ActualizarCursoUseCase();     
        $rs = $casoUso->ejecutar($cursoDto);
        return redirect()->route('cursos.index')
                ->with('code', $rs['code'])
                ->with('status', $rs['message']);
    }        
}
