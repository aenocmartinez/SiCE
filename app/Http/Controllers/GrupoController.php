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
        $casoUsoCursos = new ListarCursosUseCase();
        $casoUsoCalendarios = new ListarCalendariosUseCase();
        $casoUsoSalones = new ListarSalonesUseCase();
        $casoUsoOrientador = new ListarOrientadoresUseCase();

        $cursos = $casoUsoCursos->ejecutar();
        $calendarios = $casoUsoCalendarios->ejecutar();
        $salones = $casoUsoSalones->ejecutar();
        
        $dias = ListaDeValor::diasSemana();
        $jornadas = ListaDeValor::jornadas();

        return view('grupos.create', [
            'cursos' => $cursos['data'],
            'calendarios' => $calendarios,
            'salones' => $salones,
            'dias' => $dias,
            'jornadas' => $jornadas
        ]);
        // $orientadores = $casoUsoOrientador->ejecutar();


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
