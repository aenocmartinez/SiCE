<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarArea;
use Src\domain\Area;
use Src\infraestructure\util\Validador;
use Src\view\dto\AreaDto;

use Src\usecase\areas\CrearAreaUseCase;
use Src\usecase\areas\ListarAreasUseCase;

use Src\usecase\areas\BuscarAreaPorIdUseCase;
use Src\usecase\areas\ActualizarAreaUseCase;
use Src\usecase\areas\EliminarAreaUseCase;
use Src\usecase\areas\ListarAreasPaginadosUseCase;

class AreaController extends Controller
{
    public function index() {
        $casoUso = new ListarAreasUseCase();
        $areas = $casoUso->ejecutar();

        return view('areas.index', compact('areas'));
    }

    public function paginar($page=1) {
        return view("areas.index", [
            'paginate' => (new ListarAreasPaginadosUseCase)->ejecutar($page)
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
        $area = $casoUso->ejecutar($id);

        if (!$area->existe()) {
            return redirect()->route('areas.index')                
                    ->with('code', "200")
                    ->with('status', "área no encontrada");            
        }

        return view('areas.edit', compact('area'));        
    }

    public function create() {
        $area = new Area();
        return view('areas.create', compact('area'));
    }

    public function store(GuardarArea $req) {
        $datos = $req->validated();
        $casoUso = new CrearAreaUseCase();
        $response = $casoUso->ejecutar($datos['nombre']);
                
        return redirect()->route('areas.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function delete($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            return redirect()->route('areas.index')
                            ->with('status', 'parámetro no válido');
        }

        $casoUso = new EliminarAreaUseCase();
        $response = $casoUso->ejecutar($id);
    
        return redirect()->route('areas.index')->with('code', $response->code)->with('status', $response->message);
    }
    
    public function update(GuardarArea $req) {

        request()->validate([
            'id' => 'required',
            'nombre' => 'required|max:150'
        ]);        
        
        $areaDto = new AreaDto(request('id'), request('nombre'));

        $casoUso = new ActualizarAreaUseCase();
        $response = $casoUso->ejecutar($areaDto);

        return redirect()->route('areas.index')->with('code', $response->code)->with('status', $response->message);
    }    
}
