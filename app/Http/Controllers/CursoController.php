<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarCurso;
use Src\domain\Curso;
use Src\view\dto\CursoDto;

use Src\infraestructure\util\Validador;
use Src\usecase\areas\BuscarCursoPorNombreYAreaUseCase;
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
        $cursos = $casoUso->ejecutar();

        return view("cursos.index", compact('cursos'));
    }

    public function buscarPorId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido)
            return redirect()->route('cursos.index')->with('code', "401")->with('status', "parámetro no válido");
            
        $casoUsoBuscarCurso = new BuscarCursoPorIdUseCase();
        $curso = $casoUsoBuscarCurso->ejecutar($id);
        if (!$curso->existe())
            return redirect()->route('cursos.index')->with('code', "404")->with('status', "Curso no encontrado");
        
        $casoUso = new ListarAreasUseCase();        
        return view("cursos.edit", [
            "curso" => $curso,
            "areas" => $casoUso->ejecutar(), 
        ]);
    }

    public function create() {
        $casoUso = new ListarAreasUseCase();
        return view("cursos.create", [
            "curso" => new Curso(),
            "areas" => $casoUso->ejecutar(), 
        ]);
    }

    public function store(GuardarCurso $request) {

        $request->validated();
        $cursoDto = $this->hydrateCursoDto();

        $casoUsoCrearCurso = new CrearCursoUseCase();
        $response = $casoUsoCrearCurso->ejecutar($cursoDto);
       
        return redirect()->route('cursos.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function delete($id) {

        $esValido = Validador::parametroId($id);
        if (!$esValido)
            return redirect()->route('cursos.index')->with('code', "401")->with('status', "parámetro no válido");           

        $casoUsoEliminarCurso = new EliminarCursoUseCase();
        $response = $casoUsoEliminarCurso->ejecutar($id);

        return redirect()->route('cursos.index')->with('code', $response->code)->with('status', $response->message);
    }
    
    public function update(GuardarCurso $request) {

        $request->validated();
        $cursoDto = $this->hydrateCursoDto();

        $casoUso = new ActualizarCursoUseCase();
        $response = $casoUso->ejecutar($cursoDto);

        return redirect()->route('cursos.index')->with('code', $response->code)->with('status', $response->message);
    }        

    private function hydrateCursoDto(): CursoDto {
        $cursoDto = new CursoDto();        
        
        $cursoDto->modalidad = 'presencial';
        if (!is_null(request('modalidad'))) 
            $cursoDto->modalidad = 'virtual';
        
        $cursoDto->nombre = request('nombre');
        $cursoDto->costo = request('costo');
        $cursoDto->areaId = request('area');
        $cursoDto->id = request('id');

        return $cursoDto;
    }
}
