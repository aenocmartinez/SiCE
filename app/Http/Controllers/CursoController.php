<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Src\view\dto\CursoDto;

use Src\infraestructure\util\Validador;
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
        
        if ($resp['code'] != "200") {

        }

        return view("cursos", [
            "cursos" => $resp["data"]
        ]);
    }

    public function buscarPorId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) 
            return ["code" => "401", "message" => "parámetro no válido"];
        
        $casoUso = new BuscarCursoPorIdUseCase();
        $resp = $casoUso->ejecutar($id);
        echo json_encode($resp);
    }

    public function create($nombre, $modalidad, $costo, $area) {
        $cursoDto = new CursoDto();
        $cursoDto->nombre = $nombre;
        $cursoDto->modalidad = $modalidad;
        $cursoDto->costo = $costo;
        $cursoDto->areaId = $area;

        $casoUso = new CrearCursoUseCase();
        $resp = $casoUso->ejecutar($cursoDto);   

        echo json_encode($resp);
    }

    public function delete($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        }

        $casoUso = new EliminarCursoUseCase();
        $respuesta = $casoUso->ejecutar($id);
        echo json_encode($respuesta);
    }
    
    public function update($id, $nombre, $modalidad, $costo, $area) {
        $cursoDto = new CursoDto();
        $cursoDto->id = $id;
        $cursoDto->nombre = $nombre;
        $cursoDto->modalidad = $modalidad;
        $cursoDto->costo = $costo;
        $cursoDto->areaId = $area;

        $casoUso = new ActualizarCursoUseCase();     
        $respuesta = $casoUso->ejecutar($cursoDto);
        echo json_encode($respuesta);
    }        
}
