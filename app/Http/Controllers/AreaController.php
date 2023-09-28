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
            return redirect()->route('areas.index')                
                    ->with('code', "401")
                    ->with('status', "parámetro no válido");
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
        $rs = $casoUso->ejecutar(request('nombre'));
        
        return redirect()->route('areas.index')                
                        ->with('code', $rs['code'])
                        ->with('status', $rs['message']);
    }

    public function delete($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            return back()->with('status', 'parámetro no válido');
        }

        $casoUso = new EliminarAreaUseCase();
        $rs = $casoUso->ejecutar($id);
    
        return redirect()->route('areas.index')
                ->with('code', $rs['code'])
                ->with('status', $rs['message']);
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
        $rs = $casoUso->ejecutar($areaDto);

        return redirect()->route('areas.index')                
            ->with('code', $rs['code'])
            ->with('status', $rs['message']);
    }    
}
