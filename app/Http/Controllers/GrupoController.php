<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarGrupo;
use Src\domain\Grupo;
use Src\infraestructure\util\ListaDeValor;
use Src\infraestructure\util\Validador;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\cursos\ListarCursosUseCase;
use Src\usecase\grupos\ActualizarGrupoUseCase;
use Src\usecase\grupos\BuscarGrupoPorIdUseCase;
use Src\usecase\grupos\CrearGrupoUseCase;
use Src\usecase\grupos\EliminarGrupoUseCase;
use Src\usecase\grupos\ListarGruposUseCase;
use Src\usecase\orientadores\ListarOrientadoresUseCase;
use Src\usecase\salones\ListarSalonesUseCase;
use Src\view\dto\GrupoDto;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = (new ListarGruposUseCase)->ejecutar();
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
            'jornadas' => ListaDeValor::jornadas(),
            'grupo' => new Grupo,
        ]);
    }

    public function store(GuardarGrupo $request)
    {
        $grupoDto = $this->hydrateDto($request->validated());
        $response = (new CrearGrupoUseCase)->ejecutar($grupoDto);
        return redirect()->route('grupos.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            return redirect()->route('grupos.index')->with('code', "401")->with('status', "parámetro no válido");
        }

        $grupo = (new BuscarGrupoPorIdUseCase)->ejecutar($id);
        if (!$grupo->existe()) {
            return redirect()->route('grupos.index')->with('code', "200")->with('status', "grupo no encontrada");            
        }

        return view('grupos.edit', [
            'cursos' => (new ListarCursosUseCase())->ejecutar(),
            'calendarios' => (new ListarCalendariosUseCase())->ejecutar(),
            'salones' => (new ListarSalonesUseCase())->ejecutar(),
            'orientadores' => (new ListarOrientadoresUseCase())->ejecutar(),
            'dias' => ListaDeValor::diasSemana(),
            'jornadas' => ListaDeValor::jornadas(),
            'grupo' => $grupo,
        ]);
    }

    public function update(GuardarGrupo $request, $id)
    {
        $grupoDto = $this->hydrateDto($request->validated());
        $response = (new ActualizarGrupoUseCase)->ejecutar($grupoDto);
        return redirect()->route('grupos.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function destroy($id)
    {
        if (!Validador::parametroId($id)) {
            return redirect()->route('grupos.index')->with('status','parámetro no válido');
        }
        
        $response = (new EliminarGrupoUseCase)->ejecutar($id);        
        return redirect()->route('calendario.index')->with('code', $response->code)->with('status', $response->message);
    }

    private function hydrateDto($data): GrupoDto {        
        $grupoDto = new GrupoDto();
        $grupoDto->dia = $data['dia'];
        $grupoDto->cursoId = $data['curso'];
        $grupoDto->salonId = $data['salon'];
        $grupoDto->jornada = $data['jornada'];
        $grupoDto->calendarioId = $data['calendario'];
        $grupoDto->orientadorId = $data['orientador'];

        if (isset($data['id'])) {
            $grupoDto->id = $data['id'];
        }
        
        return $grupoDto;
    }
}
