<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarCurso;
use Src\domain\Curso;
use Src\view\dto\CursoDto;

use Src\infraestructure\util\Validador;
use Src\usecase\areas\ListarAreasUseCase;
use Src\usecase\cursos\ActualizarCursoUseCase;
use Src\usecase\cursos\BuscarCursoPorIdUseCase;
use Src\usecase\cursos\CrearCursoUseCase;
use Src\usecase\cursos\EliminarCursoUseCase;
use Src\usecase\cursos\ListarCursosPaginadosUseCase;
use Src\usecase\cursos\ListarCursosUseCase;

class CursoController extends Controller
{
    public function index() {

        $casoUso = new ListarCursosUseCase();
        $cursos = $casoUso->ejecutar();

        return view("cursos.index", compact('cursos'));
    }

    public function paginar($page) {
        return view("cursos.index", [
            'paginate' => (new ListarCursosPaginadosUseCase)->ejecutar($page)
        ]);
    }

    public function buscarPorId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido)
            return redirect()->route('cursos.index', 1)->with('code', "401")->with('status', "parámetro no válido");
            
        $casoUsoBuscarCurso = new BuscarCursoPorIdUseCase();
        $curso = $casoUsoBuscarCurso->ejecutar($id);
        if (!$curso->existe())
            return redirect()->route('cursos.index', 1)->with('code', "404")->with('status', "Curso no encontrado");
        
        $tipoCursos = explode(',', env('APP_TIPO_CURSOS'));
        
        return view("cursos.edit", [
            "curso" => $curso,
            "areas" => (new ListarAreasUseCase)->ejecutar(), 
            "tipoCursos" => $tipoCursos,
        ]);
    }

    public function create() {
        $tipoCursos = explode(',', env('APP_TIPO_CURSOS'));
        return view("cursos.create", [
            "curso" => new Curso(),
            "areas" => (new ListarAreasUseCase)->ejecutar(), 
            "tipoCursos" => $tipoCursos,
        ]);
    }

    public function store(GuardarCurso $request) {

        $request->validated();
        $cursoDto = $this->hydrateCursoDto();

        $casoUsoCrearCurso = new CrearCursoUseCase();
        $response = $casoUsoCrearCurso->ejecutar($cursoDto);
       
        return redirect()->route('cursos.index', 1)->with('code', $response->code)->with('status', $response->message);
    }

    public function delete($id) {

        $esValido = Validador::parametroId($id);
        if (!$esValido)
            return redirect()->route('cursos.index', 1)->with('code', "401")->with('status', "parámetro no válido");           

        $casoUsoEliminarCurso = new EliminarCursoUseCase();
        $response = $casoUsoEliminarCurso->ejecutar($id);

        return redirect()->route('cursos.index', 1)->with('code', $response->code)->with('status', $response->message);
    }
    
    public function update(GuardarCurso $request) {

        $request->validated();
        $cursoDto = $this->hydrateCursoDto();

        $casoUso = new ActualizarCursoUseCase();
        $response = $casoUso->ejecutar($cursoDto);

        return redirect()->route('cursos.index', 1)->with('code', $response->code)->with('status', $response->message);
    }        

    private function hydrateCursoDto(): CursoDto {
        $cursoDto = new CursoDto();                    
        $cursoDto->nombre = request('nombre');
        $cursoDto->areaId = request('area');
        $cursoDto->tipoCurso = request('tipoCurso');
        $cursoDto->id = request('id');

        return $cursoDto;
    }
}
