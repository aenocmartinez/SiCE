<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarSalon;
use Src\domain\Salon;
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
        $salones = $casoUso->ejecutar();

        return view("salones.index", compact('salones'));
    }

    public function buscarPorId($id) {

        $esValido = Validador::parametroId($id);
        if (!$esValido)
            return redirect()->route('cursos.index')->with('code', "401")->with('status', "parámetro no válido");

        $casoUso = new BuscarSalonPorIdUseCase();
        $salon = $casoUso->ejecutar($id);

        return view("salones.edit", compact('salon'));     
    }

    public function buscador() {
        request()->validate([
            'criterio' => 'required',
        ]);        

        $criterio = request('criterio');
        $casoUso = new BuscadorSalonesUseCase();
        $salones = $casoUso->ejecutar($criterio);

        return view("salones.index", ["salones" => $salones, "criterio" => $criterio]);        
    }

    public function create() {
        return view("salones.create", ["salon" => new Salon()]);     
    }

    public function store(GuardarSalon $request) {
        $request->validated();

        $salonDto = $this->hydrateDto();

        $casoUso = new CrearSalonUseCase();
        $response = $casoUso->ejecutar($salonDto);

        return redirect()->route('salones.index')->with('code', $response->code)->with('status', $response->message);        
    }

    public function delete($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) 
            return redirect()->route('salones.index')->with('code', "401")->with('status', "parámetro no válido");
        
        $casoUso = new EliminarSalonUseCase();
        $response = $casoUso->ejecutar(request('id'));
        return redirect()->route('salones.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function update(GuardarSalon $request) {
        $request->validated();
        $salonDto = $this->hydrateDto();

        $casoUso = new ActualizarSalonUseCase();
        $response = $casoUso->ejecutar($salonDto);
        return redirect()->route('salones.index')->with('code', $response->code)->with('status', $response->message);
    }

    private function hydrateDto(): SalonDto {
        $salonDto = new SalonDto();
        $salonDto->id = request('id');
        $salonDto->nombre = request('nombre');
        $salonDto->capacidad = request('capacidad');
        
        $salonDto->disponible = true;
        if (is_null(request('disponible'))) {
            $salonDto->disponible = false;
        }
        
        return $salonDto;
    }

}
