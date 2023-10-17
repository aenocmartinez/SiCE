<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarTipoSalon;
use Illuminate\Http\Request;
use Src\domain\TipoSalon;
use Src\infraestructure\util\Validador;
use Src\usecase\tipo_salones\ActualizarTipoSalonUseCase;
use Src\usecase\tipo_salones\BuscarTipoSalonPorIdUseCase;
use Src\usecase\tipo_salones\CrearTipoSalonUseCase;
use Src\usecase\tipo_salones\EliminarTipoSalonUseCase;
use Src\usecase\tipo_salones\ListarTipoSalonesUseCase;
use Src\view\dto\TipoSalonDto;

class TipoSalonController extends Controller
{
    public function index() {
        $casoUso = new ListarTipoSalonesUseCase();
        $tipo_salones = $casoUso->ejecutar();

        return view("tipo_salones.index", compact('tipo_salones'));
    }

    public function create() {
        return view("tipo_salones.create", ["tipo" => new TipoSalon()]);
    }

    public function store(GuardarTipoSalon $request)
    {
        $request->validated();

        $tipoSalonDto = $this->hydrateDto();

        $casoUso = new CrearTipoSalonUseCase();
        $response = $casoUso->ejecutar($tipoSalonDto);

        return redirect()->route('tipo-salones.index')->with('code', $response->code)->with('status', $response->message);        
    }

    public function show($id)
    {
        $esValido = Validador::parametroId($id);
        if (!$esValido)
            return redirect()->route('tipo_salones.index')->with('code', "401")->with('status', "parámetro no válido");

        $casoUso = new BuscarTipoSalonPorIdUseCase();
        $salon = $casoUso->ejecutar($id);

        return view("tipo_salones.edit", compact('tipo'));   
    }

    public function edit($id)
    {
        $esValido = Validador::parametroId($id);
        if (!$esValido)
            return redirect()->route('tipo_salones.index')->with('code', "401")->with('status', "parámetro no válido");

        $casoUso = new BuscarTipoSalonPorIdUseCase();
        $tipo = $casoUso->ejecutar($id);

        return view("tipo_salones.edit", compact('tipo'));  
    }

    public function update(GuardarTipoSalon $request)
    {
        $request->validated();
        $tipoSalonDto = $this->hydrateDto();

        $casoUso = new ActualizarTipoSalonUseCase();
        $response = $casoUso->ejecutar($tipoSalonDto);
        return redirect()->route('tipo-salones.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function destroy($id)
    {
        $esValido = Validador::parametroId($id);
        if (!$esValido) 
            return redirect()->route('tipo-salones.index')->with('code', "401")->with('status', "parámetro no válido");
        
        $casoUso = new EliminarTipoSalonUseCase();
        $response = $casoUso->ejecutar(request('id'));
        return redirect()->route('tipo-salones.index')->with('code', $response->code)->with('status', $response->message);
    }

    private function hydrateDto(): TipoSalonDto {
        $tipoSalonDto = new TipoSalonDto();
        $tipoSalonDto->id = request('id');
        $tipoSalonDto->nombre = request('nombre');
        
        return $tipoSalonDto;
    }    
}
