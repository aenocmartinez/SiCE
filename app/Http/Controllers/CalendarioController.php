<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarCalenadario;
use Illuminate\Http\Request;
use Src\domain\Calendario;
use Src\infraestructure\util\Validador;
use Src\usecase\calendarios\ActualizarCalendarioUseCase;
use Src\usecase\calendarios\BuscarCalendarioPorIdUseCase;
use Src\usecase\calendarios\CrearCalendarioUseCase;
use Src\usecase\calendarios\EliminarCalendarioUseCase;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\view\dto\CalendarioDto;

class CalendarioController extends Controller
{
    public function index()
    {
        $casoUso = new ListarCalendariosUseCase();
        $calendarios = $casoUso->ejecutar();

        return view('calendario.index', compact('calendarios'));

    }

    public function create()
    {
        return view('calendario.create', ['calendario' => new Calendario()]);
    }

    public function store(GuardarCalenadario $request)
    {
        $data = $request->validated();        
        $calendarioDto = new CalendarioDto($data['nombre'], $data['fec_ini'], $data['fec_fin']);
        $casoUsoCrearCalendario = new CrearCalendarioUseCase();
        $response = $casoUsoCrearCalendario->ejecutar($calendarioDto);
        
        return redirect()->route('calendario.index')->with('code', $response->code)->with('status', $response->message);
    }


    public function edit($id)
    {
        if (!Validador::parametroId($id)) {
            return redirect()->route('calendario.index')->with('status','parámetro no válido');
        }

        $casoUsoBuscarCalendario = new BuscarCalendarioPorIdUseCase();
        $calendario = $casoUsoBuscarCalendario->ejecutar($id);

        if (!$calendario->existe()) {
            return redirect()->route('calendario.index')->with('status','Calendario no encontrado.');
        }

        return view('calendario.edit',['calendario' => $calendario]);
    }

    public function update(GuardarCalenadario $request, $id)
    {
        $data = $request->validated();        
        if (!Validador::parametroId($id)) {
            return redirect()->route('calendario.index')->with('status','parámetro no válido');
        }

        $calendarioDto = new CalendarioDto($data['nombre'], $data['fec_ini'], $data['fec_fin']);
        $calendarioDto->id = $id;

        $casoUsoActualizar = new ActualizarCalendarioUseCase();
        $response = $casoUsoActualizar->ejecutar($calendarioDto);

        return redirect()->route('calendario.index')->with('code', $response->code)->with('status', $response->message);
    }


    public function destroy($id)
    {
        if (!Validador::parametroId($id)) {
            return redirect()->route('calendario.index')->with('status','parámetro no válido');
        }
        
        $casoUsoEliminar = new EliminarCalendarioUseCase();
        $response = $casoUsoEliminar->ejecutar($id);

        return redirect()->route('calendario.index')->with('code', $response->code)->with('status', $response->message);
    }
}
