<?php

namespace App\Http\Controllers;

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
        $resp = $casoUso->ejecutar();

        return view('areas.index', [
            "areas" => $resp['data']
        ]);
    }

    public function buscarPorId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        }

        $casoUso = new BuscarAreaPorIdUseCase();
        $resp = $casoUso->ejecutar($id);
        return view('areas.edit', [
            'area' => $resp['data'],
        ]);        
    }

    public function create() {
        $data = [
            'nombre' => ''
        ];
        return view('areas.create', ['area' => $data]);
    }

    public function store() {
        request()->validate([
            'nombre' => 'required'
        ]);

        $casoUso = new CrearAreaUseCase();
        $casoUso->ejecutar(request('nombre'));
        
        return redirect()->route('areas.index');
    }

    public function delete($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        }
        $casoUso = new EliminarAreaUseCase();
        $casoUso->ejecutar($id);        
        return redirect()->route('areas.index');
    }
    
    public function update() {

        request()->validate([
            'id' => 'required',
            'nombre' => 'required|max:150'
        ]);        
        
        $areaDto = new AreaDto();        
        $areaDto->id = request('id');
        $areaDto->nombre = request('nombre');
        
        $casoUso = new ActualizarAreaUseCase();
        $casoUso->ejecutar($areaDto);
        return redirect()->route('areas.index');
        // echo json_encode($respuesta);
    }    
}
