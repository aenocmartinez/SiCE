<?php

namespace App\Http\Controllers;


use Src\infraestructure\util\Validador;
use Src\usecase\salones\ActualizarSalonUseCase;
use Src\usecase\salones\BuscadorSalonesUseCase;
use Src\usecase\salones\BuscarSalonPorIdUseCase;
use Src\usecase\salones\CrearSalonUseCase;
use Src\usecase\salones\EliminarSalonUseCase;
use Src\usecase\salones\ListarSalonesUseCase;
use Src\view\dto\SalonDto;

class SalonController extends Controller
{
    public function index() {
        $casoUso = new ListarSalonesUseCase();
        $resp = $casoUso->ejecutar();

        return view("salones.index", [
            "salones" => $resp["data"]
        ]);
    }

    public function buscarPorId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            return ["code" => "401", "message" => "parámetro no válido"];
        }

        $casoUso = new BuscarSalonPorIdUseCase();
        $resp = $casoUso->ejecutar($id);
        $salon = $resp['data'];
        return view("salones.edit", ["salon" => [
            'id' => $salon['id'],
            'nombre' => $salon['nombre'],
            'capacidad' => $salon['capacidad'],
            'disponible' => $salon['esta_disponible'],
        ]]);     
    }

    public function buscador() {
        request()->validate([
            'criterio' => 'required',
        ]);        

        $criterio = request('criterio');
        $casoUso = new BuscadorSalonesUseCase();
        $resp = $casoUso->ejecutar($criterio);

        return view("salones.index", [
            "salones" => $resp["data"],
            "criterio" => $criterio,
        ]);        
    }

    public function create() {
        return view("salones.create", ["salon" => [
            'nombre' => '',
            'capacidad' => '',
            'disponible' => '',
        ]]);        
    }

    public function store() {
        request()->validate([
            'nombre' => 'required',
            'capacidad' => 'required|numeric',
        ]);

        $salonDto = new SalonDto();

        $salonDto->disponible = true;
        if (is_null(request('disponible'))) {
            $salonDto->disponible = false;
        }

        $salonDto->nombre = request('nombre');
        $salonDto->capacidad = request('capacidad');
        
        $casoUso = new CrearSalonUseCase();
        $resp = $casoUso->ejecutar($salonDto);

        return redirect()
                    ->route('salones.index')
                    ->with('code', $resp['code'])
                    ->with('status', $resp['message']);        
    }

    public function delete($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            return ["code" => "401", "message" => "parámetro no válido"];
        }

        $casoUso = new EliminarSalonUseCase();
        $resp = $casoUso->ejecutar($id);
        return redirect()->route('salones.index')
                ->with('code', $resp['code'])
                ->with('status', $resp['message']);
    }

    public function update() {
        request()->validate([
            'id' => 'required|numeric',
            'nombre' => 'required',
            'capacidad' => 'required|numeric',
        ]);

        $salonDto = new SalonDto();
        $salonDto->id = request('id');
        $salonDto->nombre = request('nombre');
        $salonDto->capacidad = request('capacidad');
        
        $salonDto->disponible = true;
        if (is_null(request('disponible'))) {
            $salonDto->disponible = false;
        }

        $casoUso = new ActualizarSalonUseCase();
        $resp = $casoUso->ejecutar($salonDto);
        return redirect()->route('salones.index')
                ->with('code', $resp['code'])
                ->with('status', $resp['message']);
    }

}
