<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarGrupo;

use Src\domain\Grupo;
use Src\infraestructure\util\ListaDeValor;
use Src\infraestructure\util\Validador;
use Src\usecase\areas\ListarOrientadoresPorCursoCalendarioUseCase;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\cursos\ListarCursosUseCase;
use Src\usecase\grupos\ActualizarGrupoUseCase;
use Src\usecase\grupos\BuscadorGruposUseCase;
use Src\usecase\grupos\BuscarGrupoPorIdUseCase;
use Src\usecase\grupos\CrearGrupoUseCase;
use Src\usecase\grupos\EliminarGrupoUseCase;
use Src\usecase\grupos\ListarCursosPorCalendarioUseCase;
use Src\usecase\grupos\ListarGruposUseCase;
use Src\usecase\orientadores\ListarOrientadoresUseCase;
use Src\usecase\salones\ListarSalonesPorEstadoUseCase;
use Src\view\dto\GrupoDto;

class GrupoController extends Controller
{
    public function index()
    {
        return view('grupos.index', ['grupos' => (new ListarGruposUseCase)->ejecutar()]);
    }

    public function create()
    {
        return view('grupos.create', [
            'cursos' => array(),
            'calendarios' => (new ListarCalendariosUseCase())->ejecutar(),
            'salones' => (new ListarSalonesPorEstadoUseCase)->ejecutar(),
            'orientadores' => array(),
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
            'salones' => (new ListarSalonesPorEstadoUseCase())->ejecutar(),
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
        return redirect()->route('grupos.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function listarCursosPorCalendario($calendarioId, $cursoCalendarioIdActual) {
        if (!Validador::parametroId($calendarioId)) {
            return redirect()->route('grupos.index')->with('status','parámetro no válido');
        }

        return view('grupos._cursos_por_calendario',[
            'cursos' => (new ListarCursosPorCalendarioUseCase)->ejecutar($calendarioId),
            'cursoCalendarioIdActual' => $cursoCalendarioIdActual,
        ]);
    }

    public function listarOrientadoresPorCursoCalendario($cursoCalendarioId, $orientadorIdActual) {
        if (!Validador::parametroId($cursoCalendarioId)) {
            return redirect()->route('grupos.index')->with('status','parámetro no válido');
        }

        return view('grupos._orientadores_por_curso',[
            'orientadores' => (new ListarOrientadoresPorCursoCalendarioUseCase)->ejecutar($cursoCalendarioId),
            'orientadorIdActual' => $orientadorIdActual,
        ]);        
    }

    public function buscadorGrupos(){
        $criterio = '';
        if (!is_null(request('criterio'))) {
            $criterio = request('criterio');
        }

        $grupos = (new BuscadorGruposUseCase)->ejecutar($criterio);

        return view("grupos.index", [
            "grupos" => $grupos,
            "criterio" => $criterio,
        ]);         
    }

    private function hydrateDto($data): GrupoDto {        
        $grupoDto = new GrupoDto();
        $grupoDto->dia = $data['dia'];
        $grupoDto->cursoCalendarioId = $data['curso'];
        $grupoDto->salonId = $data['salon'];
        $grupoDto->jornada = $data['jornada'];
        $grupoDto->calendarioId = $data['calendario'];
        $grupoDto->orientadorId = $data['orientador'];
        $grupoDto->cupo = $data['cupo'];

        if (isset(request()->id)) {
            $grupoDto->id = request()->id;
        }
        
        return $grupoDto;
    }
}
