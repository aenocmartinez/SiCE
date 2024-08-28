<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgregarAreaOrientador;
use App\Http\Requests\GuardarOrientador;

use Src\domain\Orientador;
use Src\infraestructure\util\ListaDeValor;
use Src\infraestructure\util\Validador;
use Src\usecase\areas\ListarAreasUseCase;
use Src\usecase\grupos\CancelarGrupoUseCase;
use Src\usecase\orientadores\ActualizarOrientadorUseCase;
use Src\usecase\orientadores\BuscadorOrientadorUseCase;
use Src\usecase\orientadores\BuscarOrientadorPorIdUseCase;
use Src\usecase\orientadores\CrearOrientadorUseCase;
use Src\usecase\orientadores\EliminarOrientadorUseCase;
use Src\usecase\orientadores\ListarOrientadoresPaginadoUseCase;
use Src\usecase\orientadores\ListarOrientadoresUseCase;
use Src\view\dto\OrientadorDto;

class OrientadorController extends Controller 
{
    public $listaEPS = array();
    public function __construct() {
        $this->listaEPS = ListaDeValor::eps();
        
    }

    public function index() {
        $casoUso = new ListarOrientadoresUseCase();
        $orientadores = $casoUso->ejecutar();
        return view("orientadores.index", compact('orientadores'));
    }

    public function edit($id) {

        $this->validarParametroId($id);

        $orientador = (new BuscarOrientadorPorIdUseCase())->ejecutar($id);

        if (!$orientador->existe()) {
            return redirect()->route('cursos.index')->with('code', "404")->with('status', "Orientador no encontrado");
        }

        $nivelesEstudio = explode(',', env('APP_NIVEL_ESTUDIO'));
        $listaRangoSalarial = explode(',', env('APP_RANGO_SALARIAL'));   

        return view('orientadores.edit', [
            'orientador' => $orientador, 
            'listaEps' => ListaDeValor::eps(),
            'nivelesEstudio' => $nivelesEstudio,
            'listaRangoSalarial' => $listaRangoSalarial,
            'areas' => (new ListarAreasUseCase)->ejecutar(),
        ]);
    }

    public function create() {
        $nivelesEstudio = explode(',', env('APP_NIVEL_ESTUDIO'));
        $listaRangoSalarial = explode(',', env('APP_RANGO_SALARIAL'));
        return view('orientadores.create', [
            'orientador' => new Orientador(),
            'listaEps' => ListaDeValor::eps(),
            'nivelesEstudio' => $nivelesEstudio,
            'listaRangoSalarial' => $listaRangoSalarial,
            'areas' => (new ListarAreasUseCase)->ejecutar(),
        ]);
    }

    public function store(GuardarOrientador $request) {
        $data = $request->validated();        
        $orientadorDto = $this->hydrateDto($data);
        
        $casoUso = new CrearOrientadorUseCase();
        $response = $casoUso->ejecutar($orientadorDto);

        return redirect()->route('orientadores.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function delete($id) {
        $this->validarParametroId($id);

        $casoUso = new EliminarOrientadorUseCase();
        $response = $casoUso->ejecutar($id);

        return redirect()->route('orientadores.index')->with('code', $response->code)->with('status', $response->message);
    }
    
    public function update(GuardarOrientador $request) {
        $data = $request->validated();
        $orientadorDto = $this->hydrateDto($data);
       
        $casoUso = new ActualizarOrientadorUseCase();
        $response = $casoUso->ejecutar($orientadorDto);

        return redirect()->route('orientadores.index')->with('code', $response->code)->with('status', $response->message);
    }     

    private function hydrateDto($data): OrientadorDto {

        $orientadorDto = new OrientadorDto();

        if (isset($data['id'])) {
            $orientadorDto->id = $data['id'];
        }

        $orientadorDto->nombre = $data['nombre'];
        $orientadorDto->tipoDocumento = $data['tipoDocumento'];
        $orientadorDto->documento = $data['documento'];
        $orientadorDto->fechaNacimiento = $data['fecNacimiento'];
        $orientadorDto->nivelEducativo = $data['nivelEstudio'];
        $orientadorDto->rangoSalarial = $data['rangoSalarial'];
        $orientadorDto->areas = $data['areas'];
        
        
        $orientadorDto->estado = true;
        
        $orientadorDto->emailPersonal = "";
        if (!is_null($data['emailPersonal'])) {
            $orientadorDto->emailPersonal = $data['emailPersonal'];
        }

        $orientadorDto->emailInstitucional = "";
        if (!is_null($data['emailInstitucional'])) {
            $orientadorDto->emailInstitucional = $data['emailInstitucional'];
        }

        $orientadorDto->direccion = "";
        if (!is_null($data['direccion'])) {
            $orientadorDto->direccion = $data['direccion'];
        }

        $orientadorDto->eps = "";
        if (!is_null($data['eps'])) {
            $orientadorDto->eps = $data['eps'];
        }        

        $orientadorDto->observacion = "";
        if (!is_null($data['observacion'])) {
            $orientadorDto->observacion = $data['observacion'];
        }
        
        return $orientadorDto;
    }

    private function validarParametroId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            return redirect()->route('orientadores.index')->with('code', "401")->with('status', "parámetro no válido");
        }        
    }

    public function show($id) {        
        $orientador = (new BuscarOrientadorPorIdUseCase)->ejecutar($id);
        if (!$orientador->existe()) {
            return redirect()->route('cursos.index')->with('code', "404")->with('status', "Orientador no encontrado");
        }

        return view('orientadores.moreInfo', [
            'orientador' => $orientador, 
            'dias' => ListaDeValor::diasSemana(),
            'jornadas' => ListaDeValor::jornadas(),
            'areas' => (new ListarAreasUseCase)->ejecutar(),
        ]);
    }

    public function listarPaginado($page=1) {              
        return view("orientadores.index", [
            'paginate' => (new ListarOrientadoresPaginadoUseCase)->ejecutar($page),
        ]);        
    }

    public function paginadorBuscador($page, $criterio) {
        return view("orientadores.index", [
            "paginate" => (new BuscadorOrientadorUseCase)->ejecutar($criterio, $page), 
            "criterio" => $criterio
        ]);         
    }

    public function buscador() { 
             
        $criterio = '';
        if (!is_null(request('criterio'))) {
            $criterio = request('criterio');
        } else {
            return redirect()->route('orientadores.index');
        }

        return view("orientadores.index", [
            "paginate" => (new BuscadorOrientadorUseCase)->ejecutar($criterio),
            "criterio" => $criterio,
        ]); 
    }   
    
    public function cancelar($orientadorId, $grupoId) {        
        $response = (new CancelarGrupoUseCase)->ejecutar($grupoId);
        return redirect()->route('orientadores.moreInfo', $orientadorId)->with('code', $response->code)->with('status', $response->message);        
    }    
}
