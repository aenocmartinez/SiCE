<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Src\infraestructure\util\Validador;

use Src\view\dto\AreaDto;

use Src\usecase\areas\CrearAreaUseCase;
use Src\usecase\areas\ListarAreasUseCase;

use Src\usecase\areas\BuscarAreaPorIdUseCase;
use Src\usecase\areas\ActualizarAreaUseCase;
use Src\usecase\areas\EliminarAreaUseCase;


class AreaController extends Controller
{
    public function index() {
        $casoUso = new ListarAreasUseCase();
        $lista = $casoUso->ejecutar();
        echo json_encode($lista);
    }

    public function buscarPorId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        }

        $casoUso = new BuscarAreaPorIdUseCase();
        $respuesta = $casoUso->ejecutar($id);
        echo json_encode($respuesta);
    }

    public function create(Request $req) {
        $nombre = $req->nombre;
        $casoUso = new CrearAreaUseCase();
        $respuesta = $casoUso->ejecutar($nombre);
        echo json_encode($respuesta);
    }

    public function delete($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        }

        $casoUso = new EliminarAreaUseCase();
        $respuesta = $casoUso->ejecutar($id);
        echo json_encode($respuesta);
    }
    
    public function update($id, $nombre) {
        $casoUso = new ActualizarAreaUseCase();
        
        $areaDto = new AreaDto();        
        $areaDto->id = $id;
        $areaDto->nombre = $nombre;

        $respuesta = $casoUso->ejecutar($areaDto);
        echo json_encode($respuesta);
    }    
}
