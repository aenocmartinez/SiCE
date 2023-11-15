<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuscarInscripciones;
use Illuminate\Http\Request;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\formularios\BuscarFormulariosUseCase;

class FormularioInscripcionController extends Controller
{
    public function index() {
        
        $periodos = (new ListarCalendariosUseCase)->ejecutar();
        $idPeriodo = 0;
        if (sizeof($periodos) > 0) {
            $idPeriodo = $periodos[0]->getId();
        }

        return view('formularios.index', [
            'periodos' => (new ListarCalendariosUseCase)->ejecutar(),
            'estadoFormulario' => (ListaDeValor::estadosFormularioInscripcion()),
            'formularios' => (new BuscarFormulariosUseCase)->ejecutar($idPeriodo, ''),
        ]);
    }

    public function filtrarInscripciones(BuscarInscripciones $req) {

        $filtro = $req->validated();

        return view('formularios.index', [
            'periodos' => (new ListarCalendariosUseCase)->ejecutar(),
            'estadoFormulario' => (ListaDeValor::estadosFormularioInscripcion()),
            'periodo' => $filtro['periodo'],
            'estado' => $filtro['estado'],
            'formularios' => (new BuscarFormulariosUseCase)->ejecutar($filtro['periodo'], $filtro['estado']),
        ]);
        
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        //
    }

    public function show($id) {
        //
    }

    public function edit($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($id) {
        //
    }
}
