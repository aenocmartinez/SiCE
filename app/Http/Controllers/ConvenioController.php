<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarConvenio;
use Illuminate\Http\Request;
use Src\domain\Convenio;
use Src\infraestructure\util\Validador;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\convenios\ActualizarConvenioUseCase;
use Src\usecase\convenios\BuscarConvenioPorIdUseCase;
use Src\usecase\convenios\CrearConvenioUseCase;
use Src\usecase\convenios\ListarConveniosUseCase;
use Src\view\dto\ConvenioDto;

class ConvenioController extends Controller
{

    public function index()
    {
        return view('convenios.index', [
            'convenios' => (new ListarConveniosUseCase)->ejecutar(),
        ]);        
    }

    public function create()
    {        
        return view('convenios.create', [
            'convenio' => new Convenio(),
            'calendarios' => (new ListarCalendariosUseCase)->ejecutar(),
        ]);
    }

    public function store(GuardarConvenio $req)
    {
        $convenioDto = $this->hydrateDto($req->validated());
        $response = (new CrearConvenioUseCase)->ejecutar($convenioDto);                
        return redirect()->route('convenios.index')->with('code', $response->code)->with('status', $response->message);        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $esValido = Validador::parametroId($id);
        if (!$esValido) 
            return redirect()->route('convenios.index')->with('code', "401")->with('status', "parámetro no válido");
        
        $convenio = (new BuscarConvenioPorIdUseCase)->ejecutar($id);
        if (!$convenio->existe())
            return redirect()->route('convenios.index')->with('code', "200")->with('status', "Convenio no encontrado");

        return view('convenios.edit', [
            'convenio'=> $convenio,
            'calendarios' => (new ListarCalendariosUseCase)->ejecutar(),
        ]);     
    }

    public function update(GuardarConvenio $req)
    {
        $convenioDto = $this->hydrateDto($req->validated());    
        $response = (new ActualizarConvenioUseCase)->ejecutar($convenioDto);
        return redirect()->route('convenios.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function destroy($id)
    {
        //
    }

    public function hydrateDto($req): ConvenioDto {
        $convenioDto = new ConvenioDto();

        $convenioDto->calendarioId = $req['calendario'];
        $convenioDto->nombre = $req['nombre'];
        $convenioDto->fechaInicial = $req['fec_ini'];
        $convenioDto->fechaFinal = $req['fec_fin'];
        $convenioDto->descuento = $req['descuento'];   
        if (isset(request()->id)) {
            $convenioDto->id = request()->id;
        }             
        return $convenioDto;
    }
}
