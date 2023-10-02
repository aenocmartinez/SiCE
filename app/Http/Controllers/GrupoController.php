<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\cursos\ListarCursosUseCase;
use Src\usecase\grupos\ListarGruposUseCase;
use Src\usecase\orientadores\ListarOrientadoresUseCase;
use Src\usecase\salones\ListarSalonesUseCase;

class GrupoController extends Controller
{
    public function index()
    {
        $casoUsoListarGrupos = new ListarGruposUseCase();
        $grupos = $casoUsoListarGrupos->ejecutar();
        return view('grupos.index', compact('grupos'));
    }

    public function create()
    {
        return view('grupos.create', [
            'cursos' => (new ListarCursosUseCase())->ejecutar(),
            'calendarios' => (new ListarCalendariosUseCase())->ejecutar(),
            'salones' => (new ListarSalonesUseCase())->ejecutar(),
            'orientadores' => (new ListarOrientadoresUseCase())->ejecutar(),
            'dias' => ListaDeValor::diasSemana(),
            'jornadas' => ListaDeValor::jornadas()
        ]);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
